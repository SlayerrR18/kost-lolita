<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil semua transaksi
        $transactions = Order::where('user_id', $user->id)
            ->with('room')
            ->latest()
            ->paginate(10);

        // Ambil transaksi terakhir untuk status card
        $transaksiTerakhir = Order::where('user_id', $user->id)->latest()->first();

        // Hitung total pengeluaran (HANYA yang statusnya approved/finished)
        $totalPengeluaran = Order::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'finished']) // Filter status valid
            ->get()
            ->sum(function($order) {
                return ($order->room->price ?? 0) * $order->rent_duration;
            });

        return view('user.finance.index', compact('transactions', 'transaksiTerakhir', 'totalPengeluaran'));
    }

    // METHOD BARU UNTUK DETAIL KEUANGAN (INVOICE)
    public function show(Order $order)
    {
        // Pastikan user hanya bisa melihat datanya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.finance.show', compact('order'));
    }
}