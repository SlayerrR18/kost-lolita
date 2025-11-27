<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Transaction;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Ambil data kontrak pengguna yang aktif
        $contract = Contract::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        // Riwayat pembayaran
        $payments = Transaction::where('contract_id', $contract->id)->get();

        return view('user.dashboard', compact('contract', 'payments'));
    }
}
