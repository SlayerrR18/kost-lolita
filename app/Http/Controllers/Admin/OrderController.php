<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Income;
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

        // Jika order disetujui (approved) → update room status menjadi 'occupied' dan buat record pemasukan
        if ($request->status === 'approved') {
            $order->room()->update([
                'status' => 'occupied',
            ]);

            // Buat record pemasukan otomatis
            $roomPrice = $order->room->price ?? 0;
            Income::create([
                'source' => 'Sewa Kamar ' . $order->room->room_number,
                'description' => 'Penyewa: ' . $order->full_name . ' | Durasi: ' . $order->rent_duration . ' bulan',
                'amount' => $roomPrice,
                'date' => now()->toDateString(),
                'category' => 'room_rent',
                'payment_method' => 'transfer',
                'reference' => 'ORD-' . $order->id,
                'order_id' => $order->id,
            ]);

            // Jika ini adalah perpanjangan dengan pergantian kamar, coba bebaskan kamar lama (parent)
            if ($order->type === 'extension_change' && $order->parent_order_id) {
                $parent = Order::find($order->parent_order_id);
                if ($parent && $parent->room_id) {
                    $hasOtherApproved = Order::where('room_id', $parent->room_id)
                        ->where('status', 'approved')
                        ->where('id', '!=', $parent->id)
                        ->exists();

                    if (!$hasOtherApproved) {
                        $parent->room()->update(['status' => 'available']);
                    }
                }
                // Tandai parent order sebagai selesai/gantikan agar tidak lagi dianggap kontrak aktif
                if ($parent) {
                    $parent->update(['status' => 'completed']);
                }
            }
        }

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
