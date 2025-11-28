<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('user.dashboard', compact('user'));
    }
}
