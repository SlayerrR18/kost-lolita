<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Ambil kontrak aktif terbaru user (untuk tanggal keluar dan sisa hari)
        $latestContract = Order::with('kost')
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->latest('tanggal_masuk')
            ->first();

        // Ambil kontrak pertama user (untuk tanggal masuk awal)
        $firstContract = Order::with('kost')
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->oldest('tanggal_masuk')
            ->first();

        $remainingDays = null;
        $shouldExtend  = false;

        if ($latestContract) {
            $today = now()->startOfDay();
            $end   = $latestContract->tanggal_keluar->copy()->endOfDay();
            $diff  = $today->diffInDays($end, false);

            $remainingDays = max(0, $diff);
            $shouldExtend  = $diff <= 30 && $diff > 0; // Tampilkan tombol/alert jika kontrak akan berakhir dalam 30 hari (bukan sudah berakhir)
        }

        return view('user.dashboard', [
            'latestContract' => $latestContract,
            'firstContract'  => $firstContract,
            'remainingDays'  => $remainingDays,
            'shouldExtend'   => $shouldExtend,
        ]);
    }
}
