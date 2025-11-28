<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // list semua pesanan (bisa difilter status)
    public function index(Request $request)
    {
        $status = $request->query('status'); // optional ?status=pending

        $query = Order::with(['user', 'room'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->get();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    // detail pesanan
    public function show(Order $order)
    {
        $order->load(['user', 'room']);
        return view('admin.orders.show', compact('order'));
    }

    // update status pesanan (terima / tolak)
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'     => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string',
        ]);

        $order->update([
            'status'     => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        // Jika order disetujui (approved) → update room status menjadi 'occupied'
        if ($request->status === 'approved') {
            $order->room()->update([
                'status' => 'occupied',
            ]);
        }

        // Jika order ditolak (rejected) → pastikan room kembali ke 'available' jika tidak ada order approved lain
        if ($request->status === 'rejected') {
            $hasOtherApprovedOrders = Order::where('room_id', $order->room_id)
                ->where('status', 'approved')
                ->where('id', '!=', $order->id)
                ->exists();

            if (!$hasOtherApprovedOrders) {
                $order->room()->update([
                    'status' => 'available',
                ]);
            }
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
