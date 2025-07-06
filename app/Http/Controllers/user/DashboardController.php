<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
    }

    public function index()
    {

        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->first();
        $remainingContract = 0;
        if ($order) {
            $remainingContract = $order->tanggal_keluar->diffInDays(now());
        }
        if ($remainingContract < 0) {
            $remainingContract = 0;
        }
        return view('user.dashboard', compact('user', 'remainingContract'));
    }
}
