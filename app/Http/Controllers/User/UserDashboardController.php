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

        $activeOrder = $user->orders()
            ->with('room')
            ->where('status', 'approved')
            ->latest('start_date')
            ->first();

        $daysLeft = 0;
        $leaseEndDate = null;


        if ($activeOrder) {
            $startDate = Carbon::parse($activeOrder->start_date)->startOfDay();
            $leaseEndDate = $startDate->copy()->addMonths($activeOrder->rent_duration);

            $now = now()->startOfDay();


            if ($leaseEndDate->gte($now)) {
                $daysLeft = (int) $now->diffInDays($leaseEndDate, false);
            } else {
                $activeOrder = null;
            }
        }


        $totalSpent = $user->orders()->where('status', 'approved')->get()->sum(function($order) {
            return $order->room->price * $order->rent_duration;
        });

        $totalOrders = $user->orders()->count();

        $pendingOrders = $user->orders()->where('status', 'pending')->count();

        $recentOrders = $user->orders()
            ->with('room')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'activeOrder',
            'leaseEndDate',
            'daysLeft',
            'totalSpent',
            'totalOrders',
            'pendingOrders',
            'recentOrders'
        ));
    }
}
