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

        // Ambil semua order milik user, urutkan dari yang terbaru
        $transactions = Order::with('room')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        // Hitung ringkasan
        $totalPengeluaran = Order::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get()
            ->sum(function($order) {
                // Asumsi harga kamar dikali durasi (karena di tabel order tidak ada kolom total_price snapshot)
                return optional($order->room)->price * $order->rent_duration;
            });

        $transaksiTerakhir = $transactions->first();

        return view('user.finance.index', compact('transactions', 'totalPengeluaran', 'transaksiTerakhir'));
    }
}
