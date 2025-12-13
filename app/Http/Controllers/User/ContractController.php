<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil kontrak terakhir
        $latestContract = Order::with('room')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->orderByDesc('start_date')
            ->first();

        // 2. Ambil kontrak pertama (untuk total hari)
        $firstContract = Order::where('user_id', $user->id)
            ->where('status', 'approved')
            ->orderBy('start_date', 'asc')
            ->first();

        $totalDays = 0;
        $remainingDays = 0;

        if ($latestContract) {
            $endDate = $latestContract->start_date->copy()->addMonths($latestContract->rent_duration);
            $remainingDays = now()->diffInDays($endDate, false);

            if ($firstContract) {
                $totalDays = $firstContract->start_date->diffInDays($endDate);
            }
            $latestContract->end_date = $endDate;
        }

        // 3. Cek Pending Extension
        $pendingExtension = Order::with('room')
            ->where('user_id', $user->id)
            ->whereNotNull('parent_order_id')
            ->latest()
            ->first();

        if ($pendingExtension && $pendingExtension->status == 'approved') {
            if ($latestContract && $pendingExtension->id == $latestContract->id) {
                $pendingExtension = null;
            } else {
                $pendingExtension->end_date = $pendingExtension->start_date->copy()->addMonths($pendingExtension->rent_duration);
            }
        }

        $isExtensionPending = false;
        if ($pendingExtension && $pendingExtension->status == 'pending') {
            $isExtensionPending = true;
        }

        // 4. Ambil History
        $history = Order::with('room')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            // Kecualikan kontrak yang sedang aktif agar tidak duplikat
            ->when($latestContract, function($q) use ($latestContract) {
                return $q->where('id', '!=', $latestContract->id);
            })
            ->orderByDesc('start_date')
            ->get();

        return view('user.contract.index', compact( // Pastikan path view benar (user.contracts.index atau user.contract.index)
            'user',
            'latestContract',
            'firstContract',
            'totalDays',
            'remainingDays',
            'pendingExtension',
            'isExtensionPending',
            'history'
        ));
    }

    // ... (method availableRooms, extend, updateInfo tetap sama) ...
    public function availableRooms()
    {
        $availableRooms = Room::where('status', 'available')
            ->orderBy('room_number')
            ->get(['id', 'room_number', 'price']);

        return response()->json($availableRooms);
    }

    public function extend(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'duration'            => 'required|integer|min:1|max:12',
            'bukti_pembayaran'    => 'required|image|mimes:jpg,jpeg,png|max:5120', // Max 5MB
            'request_change_room' => 'nullable',
            'new_kost_id'         => 'nullable|required_if:request_change_room,1|exists:rooms,id',
            'current_kost_id'     => 'required|exists:rooms,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $userId = Auth::id();


            $contract = Order::where('user_id', $userId)
                ->where('status', 'approved')
                ->orderByDesc('start_date')
                ->firstOrFail();


            $oldEndDate = $contract->start_date->copy()->addMonths($contract->rent_duration);
            $newStartDate = $oldEndDate->copy()->addDay();


            $proofPath = $request->file('bukti_pembayaran')->store('payment_proofs', 'public');


            $roomId = $request->current_kost_id;
            $type = 'extension';

            if ($request->request_change_room == '1' && $request->new_kost_id) {
                $roomId = $request->new_kost_id;
                $type = 'extension_change';
            }

            // Buat Order Baru
            Order::create([
                'user_id'             => $userId,
                'room_id'             => $roomId,
                'parent_order_id'     => $contract->id, // Relasi ke order lama

                'type'                => $type,



                'full_name'           => $contract->full_name,
                'email'               => $contract->email,
                'phone'               => $contract->phone,
                'address'             => $contract->address,
                'id_number'           => $contract->id_number,
                'id_photo_path'       => $contract->id_photo_path,
                // 'emergency_phone'     => $contract->emergency_phone ?? '-',


                'rent_duration'       => $request->duration,
                'start_date'          => $newStartDate,
                'transfer_proof_path' => $proofPath,
                'status'              => 'pending',
            ]);

            DB::commit();
            return redirect()->route('user.contract.index')->with('success', 'Permohonan perpanjangan berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateInfo(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'relation' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
        ]);

        try {
            $user = Auth::user();
            $user->emergency_contact = $data;
            $user->save();

            return redirect()->back()->with('success', 'Kontak darurat berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

}
