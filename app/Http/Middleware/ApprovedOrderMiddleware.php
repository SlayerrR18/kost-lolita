<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class ApprovedOrderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Hanya user dengan minimal satu order yang approved/confirmed bisa akses dashboard user.
     * User yang belum punya order yang approved → redirect ke landing dengan pesan
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Cek apakah user punya order dengan status 'approved'
        $hasApprovedOrder = Order::where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();

        if (!$hasApprovedOrder) {
            return redirect()
                ->route('landing')
                ->with('error', 'Silakan buat pesanan kamar terlebih dahulu dan tunggu persetujuan admin.');
        }

        return $next($request);
    }
}
