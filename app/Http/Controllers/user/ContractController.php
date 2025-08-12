<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Halaman kontrak
    public function index()
    {
        $contract = Order::with('kost')
            ->where('user_id', auth()->id())
            ->where('status', 'confirmed')
            ->latest('tanggal_keluar')
            ->first();

        return view('user.contract.index', compact('contract'));
    }

    // Ajukan perpanjangan
  // app/Http/Controllers/User/ContractController.php (extend)
    public function extend(Request $request)
    {
        $validated = $request->validate([
            'duration'         => 'required|integer|min:1|max:12',
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $contract = Order::with('kost')
            ->where('user_id', auth()->id())
            ->where('status', 'confirmed')
            ->latest('tanggal_keluar')
            ->firstOrFail();

        $start = $contract->tanggal_keluar->copy()->addDay();
        $end   = $start->copy()->addMonthsNoOverflow((int)$validated['duration']);

        $bukti = $request->file('bukti_pembayaran')->store('payments','public');

        $order = Order::create([
            'user_id'          => auth()->id(),
            'kost_id'          => $contract->kost_id,
            'name'             => $contract->name,
            'email'            => $contract->email,
            'phone'            => $contract->phone,
            'alamat'           => $contract->alamat,
            'duration'         => (int)$validated['duration'],
            'tanggal_masuk'    => $start,
            'tanggal_keluar'   => $end,
            'status'           => 'pending',
            'bukti_pembayaran' => $bukti,
            'type'             => 'extension',
            'parent_order_id'  => $contract->id,
        ]);

        return back()->with('success','Permohonan perpanjangan dikirim.');
    }

}

