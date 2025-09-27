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
        $userId = Auth::id();

        // Ambil kontrak aktif terbaru user
        $latestContract = Order::with('kost')
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->latest('tanggal_masuk')
            ->first();

        // Ambil kontrak pertama user
        $firstContract = Order::with('kost')
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->oldest('tanggal_masuk')
            ->first();

        // [UBAH INI] Ambil permohonan perpanjangan terakhir
        // Tambahkan 'confirmed' ke dalam pencarian status
        $pendingExtension = Order::with('kost')
            ->where('user_id', $userId)
            ->whereNotNull('parent_order_id')
            ->whereIn('status', ['pending', 'rejected', 'confirmed']) // Tambahkan 'confirmed'
            ->latest()
            ->first();

        // Logika agar notifikasi 'confirmed' tidak muncul selamanya
        // Kita anggap notifikasi 'diterima' hanya relevan jika kontraknya adalah kontrak terbaru.
        if ($pendingExtension && $pendingExtension->status == 'confirmed') {
            // Jika ID perpanjangan yang diterima tidak sama dengan ID kontrak aktif terbaru,
            // berarti sudah ada perpanjangan lain setelahnya, jadi jangan tampilkan notifikasi.
            if ($latestContract && $pendingExtension->id != $latestContract->id) {
                $pendingExtension = null;
            }
        }


        // Variabel yang sudah ada
        $remainingDays = null;
        $shouldExtend  = false;
        if ($latestContract) {
            $diff = now()->startOfDay()->diffInDays($latestContract->tanggal_keluar->copy()->endOfDay(), false);
            $remainingDays = max(0, $diff);
            $shouldExtend  = $diff <= 30 && $diff >= 0;
        }

        return view('user.dashboard', [
            'latestContract' => $latestContract,
            'firstContract'  => $firstContract,
            'remainingDays'  => $remainingDays,
            'shouldExtend'   => $shouldExtend,
            'pendingExtension' => $pendingExtension,
        ]);
    }
}
