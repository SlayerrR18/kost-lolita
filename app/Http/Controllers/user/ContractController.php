<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contract = Order::where('user_id', auth()->id())
                        ->where('status', 'confirmed')
                        ->latest()
                        ->first();

        return view('user.contract.index', compact('contract'));
    }
}
