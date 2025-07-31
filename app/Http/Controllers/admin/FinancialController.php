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
use Illuminate\Validation\ValidationException;

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

    public function income()
    {
        $transactions = Financial::with('kost')
            ->where('status', 'Pemasukan')
            ->latest('tanggal_transaksi')
            ->get();

        $totalIncome = $transactions->sum('total');
        $kosts = Kost::all();

        return view('admin.financial.income', compact('transactions', 'totalIncome', 'kosts'));
    }

    public function expense()
    {
        $transactions = Financial::with('kost')
            ->where('status', 'Pengeluaran')
            ->latest('tanggal_transaksi')
            ->get();

        $totalExpense = $transactions->sum('total');
        $kosts = Kost::all();

        return view('admin.financial.expense', compact('transactions', 'totalExpense', 'kosts'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kost_id' => 'required|exists:kosts,id',
                'nama_transaksi' => 'required|string|max:255',
                'tanggal_transaksi' => 'required|date|before_or_equal:today',
                'total' => 'required|numeric|min:0',
                'status' => 'required|in:Pemasukan,Pengeluaran',
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'keterangan' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = time() . '_' . Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('bukti-pembayaran', $fileName, 'public');
                $validated['bukti_pembayaran'] = $path;
            }

            // Add additional fields
            $validated['created_by'] = auth()->id();
            $validated['updated_by'] = auth()->id();

            $transaction = Financial::create($validated);

            // Update kost status if needed
            if ($validated['status'] === 'Pemasukan') {
                $kost = Kost::find($validated['kost_id']);
                if ($kost) {
                    $kost->last_payment_date = $validated['tanggal_transaksi'];
                    $kost->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditambahkan',
                'data' => $transaction->load('kost')
            ]);

        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Transaction creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
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

    public function getFinancialSummary()
    {
        try {
            $currentMonth = now()->format('m');
            $currentYear = now()->format('Y');

            $summary = [
                'total_income' => Financial::where('status', 'Pemasukan')
                    ->whereMonth('tanggal_transaksi', $currentMonth)
                    ->whereYear('tanggal_transaksi', $currentYear)
                    ->sum('total'),

                'total_expense' => Financial::where('status', 'Pengeluaran')
                    ->whereMonth('tanggal_transaksi', $currentMonth)
                    ->whereYear('tanggal_transaksi', $currentYear)
                    ->sum('total'),

                'latest_transactions' => Financial::with('kost')
                    ->latest('tanggal_transaksi')
                    ->take(5)
                    ->get(),

                'monthly_stats' => Financial::selectRaw('
                    YEAR(tanggal_transaksi) as year,
                    MONTH(tanggal_transaksi) as month,
                    status,
                    SUM(total) as total
                ')
                ->whereYear('tanggal_transaksi', $currentYear)
                ->groupBy('year', 'month', 'status')
                ->get()
            ];

            $summary['profit'] = $summary['total_income'] - $summary['total_expense'];

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to get financial summary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil ringkasan keuangan'
            ], 500);
        }
    }
}
