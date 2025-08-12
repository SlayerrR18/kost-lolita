<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Ambil kontrak aktif terbaru user (yang sudah dikonfirmasi)
        $contract = Order::with('kost')
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->latest('tanggal_keluar')
            ->first();

        $today = now()->timezone('Asia/Jakarta')->startOfDay();

        $remainingDays = null;
        $shouldExtend  = false;

        if ($contract) {
            $end  = $contract->tanggal_keluar->copy()->endOfDay();
            $diff = $today->diffInDays($end, false); // bisa negatif bila lewat
            $remainingDays = max(0, $diff);
            $shouldExtend  = $diff <= 30; // munculkan tombol/alert perpanjang
        }

        return view('user.dashboard', [
            'contract'      => $contract,
            'remainingDays' => $remainingDays,
            'shouldExtend'  => $shouldExtend,
        ]);
    }
}

