<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Hanya user yang sudah login yang bisa mengakses dashboard
        $this->middleware('role:admin');  // Pastikan hanya admin yang bisa mengakses dashboard
    }

    public function index()
    {
        return view('admin.dashboard');  // Halaman dashboard admin
    }
}
