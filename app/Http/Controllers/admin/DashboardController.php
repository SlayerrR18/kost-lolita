<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kost;
use App\Models\Financial;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {

        $total_kamar_kosong = Kost::where('status', 'Kosong')->count();
        $total_penghuni = Kost::where('status', 'Terisi')->count();
        $total_pengeluaran = Financial::where('status', 'Pengeluaran')->sum('total');
        $total_pemasukan_kotor = Financial::where('status', 'Pemasukan')->sum('total');


        $total_pemasukan = $total_pemasukan_kotor - $total_pengeluaran;

        return view('admin.dashboard', compact(
            'total_kamar_kosong',
            'total_penghuni',
            'total_pengeluaran',
            'total_pemasukan',
            'total_pemasukan_kotor'
        ));
    }
}
