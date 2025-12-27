<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function create(Room $room)
    {
        $user = Auth::user();

        // 1. CEGAH BOOKING GANDA
        if ($user->hasActiveContract()) {
            return redirect()->route('user.contract.index')
                ->with('error', 'Anda sudah memiliki kamar aktif. Silakan kelola kontrak Anda di sini.');
        }

        return view('user.orders.create', [
            'room' => $room,
            'user' => $user,
        ]);
    }

    public function store(Request $request, Room $room)
    {
        $user = Auth::user();

        if ($user->hasActiveContract()) {
            return redirect()->route('user.contract.index')
                ->with('error', 'Gagal memproses. Anda sudah memiliki kontrak aktif.');
        }
        
        $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:30',
            'address'        => 'required|string',
            'id_number'      => 'required|string|max:50',
            'id_photo'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'rent_duration'  => 'required|integer|min:1',
            'start_date'     => 'required|date|after_or_equal:today',
            'transfer_proof' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $idPhotoPath       = $request->file('id_photo')->store('id_cards', 'public');
        $transferProofPath = $request->file('transfer_proof')->store('transfers', 'public');

        $order = Order::create([
            'user_id'            => $user->id,
            'room_id'            => $room->id,
            'email'              => $user->email,
            'full_name'          => $request->full_name,
            'phone'              => $request->phone,
            'address'            => $request->address,
            'id_number'          => $request->id_number,
            'id_photo_path'      => $idPhotoPath,
            'rent_duration'      => $request->rent_duration,
            'start_date'         => $request->start_date,
            'transfer_proof_path'=> $transferProofPath,
            'status'             => 'pending',
        ]);

        return redirect()
            ->route('user.orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat. Silakan menunggu konfirmasi dari admin.');
    }

    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with('room')
            ->latest()
            ->get();

        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('user.orders.show', compact('order'));
    }
}
