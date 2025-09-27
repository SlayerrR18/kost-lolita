<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Halaman kontrak
    public function index()
    {
        $latestContract = Order::with('kost')
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->latest('tanggal_masuk')
            ->first();

        $firstContract = Order::with('kost')
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->oldest('tanggal_masuk')
            ->first();

        $totalDays = 0;
        $remainingDays = 0;

        if ($latestContract) {
            $totalDays = $firstContract->tanggal_masuk->diffInDays($latestContract->tanggal_keluar);
            $remainingDays = now()->diffInDays($latestContract->tanggal_keluar, false);
        }

        return view('user.contract.index', compact('latestContract', 'firstContract', 'totalDays', 'remainingDays'));
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

                $contract = Order::with('kost')
                    ->where('user_id', auth()->id())
                    ->where('status', 'confirmed')
                    ->latest('tanggal_masuk')
                    ->firstOrFail();

                $start = $contract->tanggal_keluar->copy()->addDay();
                $end   = $start->copy()->addMonthsNoOverflow((int)$request->input('duration'));

                if (!$request->hasFile('bukti_pembayaran')) {
                    throw new \Exception('Bukti pembayaran wajib diunggah');
                }

                $bukti = $request->file('bukti_pembayaran')->store('payments', 'public');

                $dataToCopy = $contract->only([
                    'kost_id', 'name', 'email', 'phone', 'alamat',
                    'ktp_number', 'emergency_phone', 'ktp_image'
                ]);

                $order = Order::create(array_merge($dataToCopy, [
                    'user_id'          => auth()->id(),
                    'duration'         => (int)$request->input('duration'),
                    'tanggal_masuk'    => $start,
                    'tanggal_keluar'   => $end,
                    'status'           => 'pending',
                    'bukti_pembayaran' => $bukti,
                    'type'             => 'extension',
                    'parent_order_id'  => $contract->id,
                ]));

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

                \Log::error('Contract extension failed:', [
                    'error'   => $e->getMessage(),
                    'user_id' => auth()->id()
                ]);

                return response()->json([
                    'message' => 'Terjadi kesalahan saat memproses perpanjangan',
                    'errors'  => ['system' => [$e->getMessage()]]
                ], 500);
            }
        }

        // Non-AJAX fallback, juga harus disalin
        $validated = $request->validate([
            'duration'         => 'required|integer|min:1|max:12',
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $contract = Order::with('kost')
            ->where('user_id', auth()->id())
            ->where('status', 'confirmed')
            ->latest('tanggal_masuk')
            ->firstOrFail();

        $start = $contract->tanggal_keluar->copy()->addDay();
        $end   = $start->copy()->addMonthsNoOverflow((int)$validated['duration']);

        $bukti = $request->file('bukti_pembayaran')->store('payments','public');

        $dataToCopy = $contract->only([
            'kost_id', 'name', 'email', 'phone', 'alamat',
            'ktp_number', 'emergency_phone', 'ktp_image'
        ]);

        $order = Order::create(array_merge($dataToCopy, [
            'user_id'          => auth()->id(),
            'duration'         => (int)$validated['duration'],
            'tanggal_masuk'    => $start,
            'tanggal_keluar'   => $end,
            'status'           => 'pending',
            'bukti_pembayaran' => $bukti,
            'type'             => 'extension',
            'parent_order_id'  => $contract->id,
        ]));

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
                ->latest('tanggal_masuk')
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
