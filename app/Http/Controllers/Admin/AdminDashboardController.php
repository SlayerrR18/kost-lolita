<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use App\Models\Order;
use App\Models\Transaction;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalrooms = Room::count();
        $availablerooms = Room::where('status', 'available')->count();
        // user yang sudah memiliki kamar aktif
        $activeusers = User::whereHas('orders', function ($query) {
            $query->where('status', 'approved');
        })->count();
        return view('admin.dashboard', compact('totalrooms', 'availablerooms', 'activeusers'));
    }
}
