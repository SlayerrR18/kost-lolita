<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Halaman kontrak
    public function index()
    {
        $contract = Order::with('kost')
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->latest()
            ->first();

        $totalDays = $contract ? $contract->tanggal_masuk->diffInDays($contract->tanggal_keluar) : 0;
        $remainingDays = $contract ? now()->diffInDays($contract->tanggal_keluar, false) : 0;

        return view('user.contract.index', compact('contract', 'totalDays', 'remainingDays'));
    }

    // Ajukan perpanjangan
    public function extend(Request $request)
    {
        if ($request->expectsJson() || $request->ajax()) {
            try {
                $validator = Validator::make($request->all(), [
                    'duration'         => 'required|integer|min:1|max:12',
                    'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:10240',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Validasi gagal',
                        'errors'  => $validator->errors(),
                    ], 422);
                }

                DB::beginTransaction();

                // Ambil kontrak aktif
                $contract = Order::with('kost')
                    ->where('user_id', auth()->id())
                    ->where('status', 'confirmed')
                    ->latest('tanggal_keluar')
                    ->firstOrFail();

                $start = $contract->tanggal_keluar->copy()->addDay();
                $end   = $start->copy()->addMonthsNoOverflow((int)$request->input('duration'));

                // Upload bukti pembayaran baru
                if (!$request->hasFile('bukti_pembayaran')) {
                    throw new \Exception('Bukti pembayaran wajib diunggah');
                }

                $bukti = $request->file('bukti_pembayaran')->store('payments', 'public');

                // Buat order perpanjangan dengan data lengkap
                $order = Order::create([
                    'user_id'          => auth()->id(),
                    'kost_id'          => $contract->kost_id,
                    'name'             => $contract->name,
                    'email'            => $contract->email,
                    'phone'            => $contract->phone,
                    'alamat'           => $contract->alamat,
                    'duration'         => (int)$request->input('duration'),
                    'tanggal_masuk'    => $start,
                    'tanggal_keluar'   => $end,
                    'status'           => 'pending',
                    'bukti_pembayaran' => $bukti,
                    'type'             => 'extension',
                    'parent_order_id'  => $contract->id,
                    // Pastikan ktp_image disalin dengan benar
                    'ktp_image'        => $contract->getRawOriginal('ktp_image') // Ambil nilai asli dari database
                ]);

                DB::commit();

                return response()->json([
                    'message'  => 'Mohon ditunggu, perpanjangan kontrak kamu sedang diproses.',
                    'order_id' => $order->id,
                    'start'    => $start->toDateString(),
                    'end'      => $end->toDateString(),
                    'status'   => $order->status,
                ], 201);

            } catch (\Exception $e) {
                DB::rollback();

                // Log error untuk debugging
                \Log::error('Contract extension failed:', [
                    'error' => $e->getMessage(),
                    'user_id' => auth()->id()
                ]);

                return response()->json([
                    'message' => 'Terjadi kesalahan saat memproses perpanjangan',
                    'errors' => ['system' => [$e->getMessage()]]
                ], 500);
            }
        }

        $validated = $request->validate([
            'duration'         => 'required|integer|min:1|max:12',
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $contract = Order::with('kost')
            ->where('user_id', auth()->id())
            ->where('status', 'confirmed')
            ->latest('tanggal_keluar')
            ->firstOrFail();

        $start = $contract->tanggal_keluar->copy()->addDay();
        $end   = $start->copy()->addMonthsNoOverflow((int)$validated['duration']);

        $bukti = $request->file('bukti_pembayaran')->store('payments','public');

        $order = Order::create([
            'user_id'          => auth()->id(),
            'kost_id'          => $contract->kost_id,
            'name'             => $contract->name,
            'email'            => $contract->email,
            'phone'            => $contract->phone,
            'alamat'           => $contract->alamat,
            'duration'         => (int)$validated['duration'],
            'tanggal_masuk'    => $start,
            'tanggal_keluar'   => $end,
            'status'           => 'pending',
            'bukti_pembayaran' => $bukti,
            'type'             => 'extension',
            'parent_order_id'  => $contract->id,
            'ktp_image'        => $contract->ktp_image,
        ]);

        return redirect()->route('user.contract')->with('success', 'Perpanjangan kontrak berhasil diajukan, tunggu konfirmasi selanjutnya.');
    }

    public function updateInfo(Request $request)
    {
        $validated = $request->validate([
            'ktp_number' => 'required|string|max:20',
            'emergency_phone' => 'required|string|max:15',
        ]);

        try {
            $contract = Order::where('user_id', Auth::id())
                ->where('status', 'confirmed')
                ->latest()
                ->firstOrFail();

            $contract->update($validated);

            return response()->json([
                'message' => 'Informasi berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui informasi: ' . $e->getMessage()
            ], 500);
        }
    }
}

