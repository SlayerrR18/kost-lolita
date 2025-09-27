<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
         $userId = Auth::id();

    // Ambil kontrak terakhir yang terkonfirmasi
    $latestContract = Order::with('kost')
        ->where('user_id', $userId)
        ->where('status', 'confirmed')
        ->latest('tanggal_masuk')
        ->first();

    // Ambil kontrak pertama yang terkonfirmasi
    $firstContract = Order::with('kost')
        ->where('user_id', $userId)
        ->where('status', 'confirmed')
        ->oldest('tanggal_masuk')
        ->first();

    $totalDays = 0;
    $remainingDays = 0;

    if ($latestContract && $firstContract) {
        $totalDays = $firstContract->tanggal_masuk->diffInDays($latestContract->tanggal_keluar);
        $remainingDays = now()->diffInDays($latestContract->tanggal_keluar, false);
    }

    // [UBAH INI] Cek permohonan perpanjangan
    // Tambahkan 'confirmed' di sini juga
    $pendingExtension = Order::with('kost')
        ->where('user_id', $userId)
        ->whereNotNull('parent_order_id')
        ->whereIn('status', ['pending', 'rejected', 'confirmed']) // Tambahkan 'confirmed'
        ->latest()
        ->first();

    // Logika agar notifikasi 'confirmed' tidak muncul selamanya
    if ($pendingExtension && $pendingExtension->status == 'confirmed') {
        if ($latestContract && $pendingExtension->id != $latestContract->id) {
            $pendingExtension = null;
        }
    }


    return view('user.contract.index', compact(
        'latestContract',
        'firstContract',
        'totalDays',
        'remainingDays',
        'pendingExtension' // Kirimkan variabel ini
    ));
    }

    public function availableRooms()
    {
        // Mengambil kamar yang statusnya 'Kosong'
        $availableRooms = Kost::where('status', 'Kosong')
            ->orderBy('nomor_kamar')
            ->get(['id', 'nomor_kamar', 'harga']);
        return response()->json($availableRooms);
    }


    public function extend(Request $request)
    {
        if ($request->expectsJson() || $request->ajax()) {
            try {
                $rules = [
                    'duration'              => 'required|integer|min:1|max:12',
                    'bukti_pembayaran'      => 'required|image|mimes:jpg,jpeg,png|max:10240',
                    'request_change_room'   => 'nullable|string', // Checkbox value '1' or null
                    'new_kost_id'           => 'nullable|required_if:request_change_room,1|exists:kosts,id',
                    'current_kost_id'       => 'required|exists:kosts,id',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json(['message' => 'Validasi gagal', 'errors'  => $validator->errors()], 422);
                }

                $validated = $validator->validated();
                $isRoomChange = ($validated['request_change_room'] ?? null) == '1';

                DB::beginTransaction();

                $contract = Order::with('kost')
                    ->where('user_id', auth()->id())
                    ->where('status', 'confirmed')
                    ->latest('tanggal_masuk')
                    ->firstOrFail();

                // Cek apakah user sudah punya permohonan pending (Double submission check)
                $existingPending = Order::where('user_id', auth()->id())
                                        ->whereIn('status', ['pending'])
                                        ->latest()
                                        ->first();
                if ($existingPending) {
                    throw new \Exception('Anda sudah memiliki permohonan perpanjangan yang sedang diproses.');
                }


                $start = $contract->tanggal_keluar->copy()->addDay();
                $end   = $start->copy()->addMonthsNoOverflow((int)$validated['duration']);
                $bukti = $request->file('bukti_pembayaran')->store('payments', 'public');

                $targetKostId = $validated['current_kost_id'];
                if ($isRoomChange && $validated['new_kost_id']) {
                    $targetKostId = $validated['new_kost_id'];
                }

                // Salin semua data penting dari kontrak lama
                $dataToCopy = $contract->only([
                    'name', 'email', 'phone', 'alamat', 'ktp_number', 'emergency_phone', 'ktp_image'
                ]);

                $order = Order::create(array_merge($dataToCopy, [
                    'user_id'           => auth()->id(),
                    'kost_id'           => $targetKostId, // Menggunakan kost_id baru/lama
                    'duration'          => (int)$validated['duration'],
                    'tanggal_masuk'     => $start,
                    'tanggal_keluar'    => $end,
                    'status'            => 'pending',
                    'bukti_pembayaran'  => $bukti,
                    'type'              => $isRoomChange ? 'extension_change' : 'extension',
                    'parent_order_id'   => $contract->id,
                ]));

                DB::commit();

                return response()->json([
                    'message'  => $isRoomChange ? 'Permohonan perpanjangan dan pindah kamar sedang diproses.' : 'Permohonan perpanjangan kontrak sedang diproses.',
                    'order_id' => $order->id,
                    'start'    => $start->toDateString(),
                    'end'      => $end->toDateString(),
                    'status'   => $order->status,
                ], 201);
            } catch (\Exception $e) {
                DB::rollback();
                \Log::error('Contract extension failed:', ['error' => $e->getMessage(), 'user_id' => auth()->id()]);

                $message = $e->getMessage();
                if (Str::contains($message, 'firstOrFail')) {
                    $message = 'Anda tidak memiliki kontrak aktif yang dapat diperpanjang.';
                }

                return response()->json(['message' => $message], 500);
            }
        }
        return redirect()->route('user.contract.index')->with('error', 'Metode tidak didukung.');
    }

    public function updateInfo(Request $request)
    {
        $validated = $request->validate([
            'ktp_number' => 'required|string|max:20',
            'emergency_phone' => 'required|string|max:15',
        ]);

        try {
            // Perbarui kontrak terbaru
            $contract = Order::where('user_id', Auth::id())
                ->where('status', 'confirmed')
                ->latest('tanggal_masuk')
                ->firstOrFail();

            $contract->update($validated);

            return response()->json(['message' => 'Informasi berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui informasi: ' . $e->getMessage()], 500);
        }
    }
}
