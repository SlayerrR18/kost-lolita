<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use App\Models\Kost;
use App\Models\Order;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FinancialController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    // Metode index, income, expense, store, dan destroy tidak perlu diubah
    // karena tidak terkait langsung dengan alur konfirmasi order.
    // ... (metode-metode tersebut ada di sini) ...
    public function index()
    {
        $totalIncome = Financial::where('status', 'Pemasukan')->sum('total');
        $totalExpense = Financial::where('status', 'Pengeluaran')->sum('total');
        $balance = $totalIncome - $totalExpense;
        $transactions = Financial::with('kost')->orderBy('tanggal_transaksi', 'desc')->get();
        return view('admin.financial.index', compact('transactions', 'totalIncome', 'totalExpense', 'balance'));
    }

    public function income()
    {
        $incomes = Financial::where('status', 'Pemasukan')->with('kost')->orderBy('tanggal_transaksi', 'desc')->get();
        return view('admin.financial.income', compact('incomes'));
    }

    public function expense()
    {
        $expenses = Financial::where('status', 'Pengeluaran')->with('kost')->orderBy('tanggal_transaksi', 'desc')->get();
        return view('admin.financial.expense', compact('expenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_transaksi'    => 'required|string|max:255',
            'tanggal_transaksi' => 'required|date',
            'total'             => 'required|numeric',
            'status'            => 'required|in:Pemasukan,Pengeluaran',
            'keterangan'        => 'nullable|string',
        ]);

        Financial::create($request->all());
        return back()->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function destroy(Financial $financial)
    {
        $financial->delete();
        return back()->with('success', 'Transaksi berhasil dihapus.');
    }


    /**
     * Menampilkan daftar pesanan yang masih pending.
     */
    public function pendingOrders()
    {
        $pendingOrders = Order::where('status', 'pending')->with(['user', 'kost'])->latest()->get();
        return view('admin.financial.pending-orders', compact('pendingOrders'));
    }

    /**
     * Konfirmasi pesanan yang pending.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmOrder(Order $order)
    {
        // Pastikan order yang akan dikonfirmasi statusnya 'pending'
        abort_if($order->status !== 'pending', 400, 'Order ini tidak dapat dikonfirmasi.');

        DB::beginTransaction();
        try {
            // Ambil data user yang sudah ada dari relasi order
            $user = $order->user;
            if (!$user) {
                throw new \Exception('Data pengguna tidak ditemukan untuk pesanan ini.');
            }

            // Ambil data kamar
            $kost = $order->kost;
            if (!$kost) {
                throw new \Exception('Data kamar tidak ditemukan untuk pesanan ini.');
            }

            // 1. Update status order menjadi 'confirmed'
            $order->status = 'confirmed';
            $order->confirmed_at = now();
            $order->save();

            // 2. Update status kamar menjadi 'Terisi'
            $kost->status = 'Terisi';
            $kost->penghuni = $user->id; // Set penghuni dengan ID user yang sudah ada
            $kost->save();

            // 3. Update data user (kost_id) untuk menandakan dia menempati kamar ini
            $user->kost_id = $kost->id;
            $user->save();

            // 4. Catat transaksi ke dalam tabel financial
            Financial::create([
                'kost_id'           => $kost->id,
                'nama_transaksi'    => 'Pembayaran Kamar ' . $kost->nomor_kamar . ' oleh ' . $user->name,
                'tanggal_transaksi' => now(),
                'total'             => $kost->harga * $order->duration,
                'status'            => 'Pemasukan',
                'bukti_pembayaran'  => $order->bukti_pembayaran,
                'keterangan'        => 'Konfirmasi pesanan baru ID: ' . $order->id,
            ]);

            DB::commit();

            // 5. Kirim notifikasi WhatsApp (tanpa password)
            // Convert dates to Carbon instances if they're strings
            $tanggal_masuk = $order->tanggal_masuk instanceof Carbon
                ? $order->tanggal_masuk
                : Carbon::parse($order->tanggal_masuk);

            $tanggal_keluar = $order->tanggal_keluar instanceof Carbon
                ? $order->tanggal_keluar
                : Carbon::parse($order->tanggal_keluar);

            // Update message with proper date formatting
            $message = "✅ *Pemesanan Dikonfirmasi*\n\n"
                     . "Halo {$user->name},\n"
                     . "Pesanan Anda untuk kamar *{$kost->nomor_kamar}* telah berhasil kami konfirmasi.\n\n"
                     . "Detail Periode Sewa:\n"
                     . "Check-in: {$tanggal_masuk->format('d M Y')}\n"
                     . "Check-out: {$tanggal_keluar->format('d M Y')}\n\n"
                     . "Terima kasih telah memilih Kost Lolita.";

            $this->trySendWhatsApp($user->phone, $message);

            return response()->json(['success' => true, 'message' => 'Pesanan berhasil dikonfirmasi.']);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Order confirmation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Tolak pesanan yang pending.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectOrder(Request $request, Order $order)
    {
        $request->validate(['reason' => 'required|string|max:255']);
        abort_if($order->status !== 'pending', 400, 'Order ini tidak dapat ditolak.');

        // Cukup update status order, tidak perlu menghapus user
        $order->status = 'rejected';
        $order->rejection_reason = $request->input('reason');
        $order->save();

        // Kirim notifikasi penolakan ke user
        if ($order->user && $order->user->phone) {
             $message = "❌ *Pemesanan Ditolak*\n\n"
                      . "Halo {$order->user->name},\n"
                      . "Mohon maaf, pesanan Anda untuk kamar *{$order->kost->nomor_kamar}* kami tolak.\n\n"
                      . "Alasan: {$order->rejection_reason}\n\n"
                      . "Silakan hubungi kami jika ada pertanyaan lebih lanjut.";
             $this->trySendWhatsApp($order->user->phone, $message);
        }

        return back()->with('success', 'Pesanan berhasil ditolak.');
    }

    /**
     * Helper untuk mengirim WhatsApp dan menangani error.
     */
    private function trySendWhatsApp($recipient, $message)
    {
        try {
            $this->whatsAppService->sendMessage($recipient, $message);
        } catch (\Exception $e) {
            Log::error('WhatsApp send failed: ' . $e->getMessage());
            // Tidak menghentikan proses utama jika hanya pengiriman WA yang gagal
        }
    }
}
