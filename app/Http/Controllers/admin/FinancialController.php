<?php

namespace App\Http\Controllers\Admin;

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
                'kost_id'           => 'required|exists:kosts,id',
                'nama_transaksi'    => 'required|string|max:255',
                'tanggal_transaksi' => 'required|date|before_or_equal:today',
                'total'             => 'required|numeric|min:0',
                'status'            => 'required|in:Pemasukan,Pengeluaran',
                'bukti_pembayaran'  => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            DB::beginTransaction();

            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $nameOnly = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = time() . '_bukti_' . Str::slug($nameOnly) . '.' . $file->getClientOriginalExtension();
                // Perbaiki path penyimpanan
                $validated['bukti_pembayaran'] = $file->storeAs('bukti-pembayaran', $fileName, 'public');

                // Log untuk debugging
                \Log::info('File uploaded:', [
                    'original_name' => $file->getClientOriginalName(),
                    'stored_path' => $validated['bukti_pembayaran'],
                    'full_url' => Storage::disk('public')->url($validated['bukti_pembayaran']),
                    'exists' => Storage::disk('public')->exists($validated['bukti_pembayaran'])
                ]);
            }

            $transaction = Financial::create($validated);

            if ($validated['status'] === 'Pemasukan') {
                if ($kost = \App\Models\Kost::find($validated['kost_id'])) {
                    $kost->last_payment_date = $validated['tanggal_transaksi'];
                    $kost->save();
                }
            }

            DB::commit();

            return $request->expectsJson()
                ? response()->json(['success'=>true,'message'=>'Transaksi berhasil ditambahkan','data'=>$transaction->load('kost')])
                : back()->with('success','Transaksi berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return $request->expectsJson()
                ? response()->json(['success'=>false,'message'=>'Validasi gagal','errors'=>$e->errors()],422)
                : back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Transaction creation failed: '.$e->getMessage());
            return $request->expectsJson()
                ? response()->json(['success'=>false,'message'=>$e->getMessage()],500)
                : back()->with('error','Terjadi kesalahan: '.$e->getMessage())->withInput();
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
            'kost_id'           => $kost->id,
            'nama_transaksi'    => 'Pembayaran Kost - '.$user->name,
            'tanggal_transaksi' => now(),
            'total'             => $kost->harga,
            'status'            => 'Pemasukan',
            'bukti_pembayaran'  => null,
        ]);
    }


    public static function getTotalIncome()
    {
        return Financial::where('status', 'Pemasukan')
                       ->sum('total');
    }

   public function confirmOrder(Order $order)
{
    abort_if($order->status !== 'pending', 400, 'Order bukan pending.');

    \DB::beginTransaction();
    try {
        // Cegah overlap
        if ($this->hasOverlap($order)) {
            throw new \RuntimeException('Periode bertabrakan dengan kontrak lain.');
        }

        $isExtension   = $order->is_extension;
        $createdNew    = false;
        $plainPassword = null;

        // Ambil / buat user
        $user = $order->user ?: User::where('email',$order->email)->first();
        if (!$user) {
            // hanya order BARU yang berpotensi bikin user
            $plainPassword = \Illuminate\Support\Str::random(10);
            $user = User::create([
                'name'     => $order->name,
                'email'    => $order->email,
                'password' => \Illuminate\Support\Facades\Hash::make($plainPassword),
                'role'     => 'user',
            ]);
            $createdNew = true;
        }

        // Tautkan order ke user + sinkron profil
        $order->user_id = $user->id;
        $order->status  = 'confirmed';
        $order->confirmed_at = now();
        $order->save();

        // update profil user (phone, address, kost)
        $user->fill([
            'phone'   => $order->phone,
            'address' => $order->alamat,
            'kost_id' => $order->kost_id,
        ])->save();

        // Kamar: hanya order BARU yang mengubah penghuni/status
        if (!$isExtension) {
            optional($order->kost)->update([
                'status'   => 'Terisi',
                'penghuni' => $user->id,
                // opsional: simpan tanggal terakhir kontrak aktif
                'last_checkin'  => $order->tanggal_masuk,
                'last_checkout' => $order->tanggal_keluar,
            ]);
        }

        // Keuangan
        Financial::create([
            'kost_id'           => $order->kost_id,
            'nama_transaksi'    => ($isExtension ? 'Perpanjangan Kamar ' : 'Pembayaran Kamar ') . ($order->kost->nomor_kamar ?? ''),
            'tanggal_transaksi' => now(),
            'total'             => (int)optional($order->kost)->harga * (int)$order->duration,
            'status'            => 'Pemasukan',
            'bukti_pembayaran'  => $order->bukti_pembayaran,
            'keterangan'        => $isExtension ? 'Konfirmasi perpanjangan' : 'Konfirmasi order baru',
            'created_by'        => auth()->id(),
            'updated_by'        => auth()->id(),
        ]);

        \DB::commit();

        // WhatsApp (di luar transaksi)
        $msg = $isExtension
            ? "✅ *Perpanjangan Dikonfirmasi*\n\nHalo {$order->name}, perpanjangan kamar Anda sudah dikonfirmasi.\n\n🏠 Kamar: {$order->kost->nomor_kamar}\n📅 {$order->tanggal_masuk->format('d/m/Y')} — {$order->tanggal_keluar->format('d/m/Y')}\n⏱️ Durasi: {$order->duration} bulan"
            : "✅ *Konfirmasi Pesanan Kost Lolita*\n\nHalo {$order->name}, pesanan kamar Anda telah dikonfirmasi.\n\n🏠 Kamar: {$order->kost->nomor_kamar}\n📅 {$order->tanggal_masuk->format('d/m/Y')} — {$order->tanggal_keluar->format('d/m/Y')}\n⏱️ Durasi: {$order->duration} bulan" . (
                $createdNew && $plainPassword
                ? "\n\n*Akun Anda:*\n📧 {$user->email}\n🔑 {$plainPassword}\nHarap ganti password setelah login."
                : ''
              );

        $this->trySendWhatsApp($order->phone, $msg);

        return response()->json([
            'success' => true,
            'message' => $isExtension ? 'Perpanjangan dikonfirmasi.' : 'Order dikonfirmasi.',
            'data'    => [
                'order_id'       => $order->id,
                'type'           => $isExtension ? 'extension' : 'new',
                'name'           => $user->name,
                'email'          => $user->email,
                'duration'       => (int)$order->duration,
                'room_number'    => $order->kost->nomor_kamar ?? null,
                'tanggal_masuk'  => $order->tanggal_masuk->toDateString(),
                'tanggal_keluar' => $order->tanggal_keluar->toDateString(),
            ],
        ]);
    } catch (\Throwable $e) {
        \DB::rollBack();
        \Log::error('Confirmation failed: '.$e->getMessage());
        return response()->json(['success'=>false,'message'=>'Gagal mengonfirmasi: '.$e->getMessage()],500);
    }
}


    public function pendingOrders()
    {
        $pendingOrders = Order::with(['kost','user','parent'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.financial.pending-orders', compact('pendingOrders'));
    }


    public function rejectOrder(Order $order)
    {
        abort_if($order->status !== 'pending', 400, 'Order bukan pending.');

        DB::beginTransaction();
        try {
            $order->update(['status' => 'rejected']);

            $msg = "❌ *Pesanan Ditolak*\n\n"
                . "Halo {$order->name}, pesanan kamar Anda tidak dapat kami konfirmasi.\n"
                . "Silakan hubungi admin untuk informasi lebih lanjut.";
            $this->trySendWhatsApp($order->phone, $msg);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pesanan berhasil ditolak']);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Order rejection failed: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menolak pesanan: '.$e->getMessage()], 500);
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
    protected function hasOverlap(Order $order): bool
    {
        return Order::where('kost_id', $order->kost_id)
            ->where('status', 'confirmed')
            ->where('id', '!=', $order->id)
            ->where(function ($q) use ($order) {
                $q->whereBetween('tanggal_masuk',  [$order->tanggal_masuk, $order->tanggal_keluar])
                ->orWhereBetween('tanggal_keluar',[$order->tanggal_masuk, $order->tanggal_keluar])
                ->orWhere(function ($q2) use ($order) {
                    $q2->where('tanggal_masuk', '<=', $order->tanggal_masuk)
                        ->where('tanggal_keluar', '>=', $order->tanggal_keluar);
                });
            })
            ->exists();
    }

    /** Kirim WA tapi jangan bikin transaksi gagal kalau error */
    protected function trySendWhatsApp(string $to, string $message): bool
    {
        try {
            return $this->whatsappService->sendMessage($to, $message);
        } catch (\Throwable $e) {
            \Log::warning('WhatsApp failed: '.$e->getMessage());
            return false;
        }
    }
}
