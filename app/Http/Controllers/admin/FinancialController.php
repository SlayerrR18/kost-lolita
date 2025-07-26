<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Financial;
use App\Models\Kost;
use App\Services\WhatsAppService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function index()
    {
        $transactions = Financial::with('kost')
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();
        $kosts = Kost::all();

        // Debugging
        foreach($transactions as $transaction) {
            if($transaction->bukti_pembayaran) {
                \Log::info('Bukti pembayaran path:', [
                    'path' => $transaction->bukti_pembayaran,
                    'full_url' => asset('storage/' . $transaction->bukti_pembayaran),
                    'exists' => Storage::disk('public')->exists($transaction->bukti_pembayaran)
                ]);
            }
        }

        return view('admin.financial.index', compact('transactions', 'kosts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kost_id' => 'required|exists:kosts,id',
            'nama_transaksi' => 'required|string',
            'tanggal_transaksi' => 'required|date',
            'total' => 'required|numeric',
            'status' => 'required|in:Pemasukan,Pengeluaran',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        try {
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                // Ubah cara penyimpanan file
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('bukti-pembayaran', $fileName, 'public');
                $validated['bukti_pembayaran'] = $path;
            }

            Financial::create($validated);

            return redirect()
                ->route('admin.financial.index')
                ->with('success', 'Transaksi berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Financial $financial)
    {
        try {
            // Delete old image if exists
            if ($financial->bukti_pembayaran) {
                Storage::disk('public')->delete($financial->bukti_pembayaran);
            }

            $financial->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public static function recordIncome($user, $kost)
    {
        return Financial::create([
            'kost_id' => $kost->id,
            'nama_transaksi' => 'Pembayaran Kost - ' . $user->name,
            'tanggal_transaksi' => now(),
            'total' => $kost->harga,
            'status' => 'Pemasukan',
            'bukti_transaksi' => null
        ]);
    }

    public static function getTotalIncome()
    {
        return Financial::where('status', 'Pemasukan')
                       ->sum('total');
    }

    public function confirmOrder(Order $order)
    {
        try {
            DB::beginTransaction();

            // Generate password yang aman
            $password = Str::random(8);

            // Create user account
            $user = User::create([
                'name' => $order->name,
                'email' => $order->email,
                'password' => Hash::make($password),
                'role' => 'user',
                'phone' => $order->phone,
                'address' => $order->alamat,
                'kost_id' => $order->kost_id
            ]);

            // Prepare WhatsApp message
            $message = "✅ *Konfirmasi Pesanan Kost Lolita*\n\n"
                    . "Halo {$order->name},\n"
                    . "Pesanan kamar kost Anda telah dikonfirmasi.\n\n"
                    . "*Detail Kamar:*\n"
                    . "🏠 Nomor Kamar: {$order->kost->nomor_kamar}\n"
                    . "📅 Tanggal Masuk: " . $order->tanggal_masuk->format('d/m/Y') . "\n"
                    . "📅 Tanggal Keluar: " . $order->tanggal_keluar->format('d/m/Y') . "\n"
                    . "⏱️ Durasi: {$order->duration} bulan\n\n"
                    . "*Kredensial Akun:*\n"
                    . "📧 Email: {$user->email}\n"
                    . "🔑 Password: {$password}\n\n"
                    . "Silakan login menggunakan kredensial di atas.\n"
                    . "Untuk keamanan, harap segera ganti password Anda setelah login.\n\n"
                    . "Terima kasih telah memilih Kost Lolita! 🙏";

            // Send WhatsApp message
            $messageSent = $this->whatsappService->sendMessage($order->phone, $message);

            // Create financial record
            Financial::create([
                'kost_id' => $order->kost_id,
                'nama_transaksi' => 'Pembayaran Kamar ' . $order->kost->nomor_kamar,
                'tanggal_transaksi' => now(),
                'total' => $order->kost->harga * $order->duration,
                'status' => 'Pemasukan',
                'bukti_pembayaran' => $order->bukti_pembayaran
            ]);

            // Update order status
            $order->update([
                'status' => 'confirmed',
                'user_id' => $user->id
            ]);

            // Update kost status
            $order->kost->update([
                'status' => 'Terisi',
                'penghuni' => $user->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikonfirmasi',
                'whatsapp_sent' => $messageSent,
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'room_number' => $order->kost->nomor_kamar,
                    'duration' => $order->duration,
                    // Format dates properly
                    'tanggal_masuk' => $order->tanggal_masuk->format('Y-m-d'),
                    'tanggal_keluar' => $order->tanggal_keluar->format('Y-m-d')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::channel('whatsapp')->error('Confirmation failed', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pendingOrders()
    {
        $pendingOrders = Order::where('status', 'pending')
            ->with('kost')
            ->latest()
            ->get();

        return view('admin.financial.pending-orders', compact('pendingOrders'));
    }

    public function rejectOrder(Order $order)
    {
        try {
            DB::beginTransaction();

            // Update order status
            $order->update(['status' => 'rejected']);

            // Send WhatsApp notification
            try {
                $message = "Halo {$order->name},\n\n"
                        . "Mohon maaf, pesanan kamar kost Anda tidak dapat kami konfirmasi.\n"
                        . "Pembayaran Anda akan kami kembalikan sesuai dengan prosedur yang berlaku.\n\n"
                        . "Jika ada pertanyaan, silakan hubungi admin kami.\n\n"
                        . "Terima kasih.";

                app(WhatsAppService::class)->sendMessage($order->phone, $message);
            } catch (\Exception $e) {
                \Log::error('WhatsApp notification failed: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil ditolak'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Order rejection failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
