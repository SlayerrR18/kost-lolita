<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Cek Order Aktif (Kamar yang sedang dihuni)
        // Logika: Cari order 'approved' yang masa sewanya belum habis hari ini
        $activeOrder = Order::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereRaw("DATE_ADD(start_date, INTERVAL rent_duration MONTH) >= ?", [now()->toDateString()])
            ->latest('start_date')
            ->first();

        // 2. Hitung Sisa Hari, Progress Bar, dan Tanggal Berakhir
        $daysLeft = 0;
        $leaseProgress = 0;
        $leaseEndDate = null; // Inisialisasi variabel agar tidak error di view
        
        if ($activeOrder) {
            // Menggunakan accessor getEndDateAttribute dari Model Order
            $leaseEndDate = $activeOrder->end_date; 
            
            if ($leaseEndDate) {
                // Hitung sisa hari
                $daysLeft = (int) now()->diffInDays($leaseEndDate, false);
                
                // Hitung persentase durasi sewa yang sudah berjalan (untuk progress bar)
                $totalDays = $activeOrder->start_date->diffInDays($leaseEndDate);
                $daysPassed = $activeOrder->start_date->diffInDays(now());
                
                // Hindari pembagian dengan nol
                $leaseProgress = $totalDays > 0 ? ($daysPassed / $totalDays) * 100 : 0;
            }
        }

        // 3. Ambil Transaksi Terakhir (Penting untuk Status Card di Dashboard)
        $transaksiTerakhir = Order::where('user_id', $user->id)
            ->latest()
            ->first();

        // 4. Ambil 5 transaksi terakhir untuk Widget Riwayat
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // 5. Hitung Total Pengeluaran (Hanya yang status Approved)
        // Disimpan ke variabel $totalPengeluaran sesuai kebutuhan View
        $totalPengeluaran = Order::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get()
            ->sum(function($order) {
                // Gunakan optional() untuk menghindari error jika data room terhapus
                return (optional($order->room)->price ?? 0) * $order->rent_duration;
            });

        // 6. Hitung Jumlah Pesanan Pending (Untuk Notifikasi/Badge)
        $pendingOrders = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Kirim semua variabel ke View
        return view('user.dashboard', compact(
            'user', 
            'activeOrder', 
            'daysLeft', 
            'leaseProgress', 
            'leaseEndDate',      // Variabel baru
            'recentOrders', 
            'transaksiTerakhir', // Variabel baru (Perbaikan Error)
            'totalPengeluaran',  // Variabel baru (Perbaikan Error)
            'pendingOrders'      // Variabel baru
        ));
    }
}