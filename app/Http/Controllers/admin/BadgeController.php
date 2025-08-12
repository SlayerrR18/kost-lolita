<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Order;

class BadgeController extends Controller
{
    public function index()
    {
        $adminId = auth()->id();

        return response()->json([
            'success'        => true,
            'pending_orders' => Order::where('status','pending')->count(),
            'unread_chat'    => Message::where('admin_id', $adminId)->where('is_read', false)->count(),
        ]);
    }
}
