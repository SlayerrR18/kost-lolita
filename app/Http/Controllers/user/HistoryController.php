<?php
namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Ambil transaksi berdasarkan kost_id user
        $transactions = Financial::with('kost')
            ->where('kost_id', $user->kost_id)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        return view('user.history.index', compact('transactions'));
    }
}
