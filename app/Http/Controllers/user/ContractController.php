<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Halaman kontrak
    public function index()
    {
        $contract = Order::where('user_id', auth()->id())
                       ->with('kost')
                       ->latest()
                       ->first();

        if ($contract) {
            // Debug KTP image path
            \Log::info('KTP Image Debug:', [
                'raw_path' => $contract->ktp_image,
                'storage_path' => storage_path('app/public/' . $contract->ktp_image),
                'exists' => Storage::disk('public')->exists($contract->ktp_image),
                'url' => Storage::url($contract->ktp_image)
            ]);

            $totalDays = $contract->tanggal_masuk->diffInDays($contract->tanggal_keluar);
            $remainingDays = now()->diffInDays($contract->tanggal_keluar, false);
            $progress = round(($totalDays - max(0, $remainingDays)) / $totalDays * 100);

            // Determine contract phase
            $phase = 'active';
            if (now()->lt($contract->tanggal_masuk)) {
                $phase = 'pre';
            } elseif (now()->gt($contract->tanggal_keluar)) {
                $phase = 'post';
            }

            // Define status class based on contract status
            $statusClass = match ($contract->status) {
                'active' => 'status-active text-success bg-success-subtle',
                'pending' => 'status-pending text-warning bg-warning-subtle',
                'expired' => 'status-expired text-danger bg-danger-subtle',
                default => 'text-secondary bg-secondary-subtle'
            };

            return view('user.contract.index', compact(
                'contract',
                'totalDays',
                'remainingDays',
                'progress',
                'phase',
                'statusClass'
            ));
        }

        return view('user.contract.index', ['contract' => null]);
    }

    // Ajukan perpanjangan
   public function extend(Request $request)
    {
        if ($request->expectsJson() || $request->ajax()) {
            $validator = Validator::make($request->all(), [
                'duration'         => 'required|integer|min:1|max:12',
                'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $contract = Order::with('kost')
                ->where('user_id', auth()->id())
                ->where('status', 'confirmed')
                ->latest('tanggal_keluar')
                ->firstOrFail();

            $start = $contract->tanggal_keluar->copy()->addDay();
            $end   = $start->copy()->addMonthsNoOverflow((int)$request->input('duration'));

            $bukti = $request->file('bukti_pembayaran')->store('payments','public');

            $order = Order::create([
                'user_id'          => auth()->id(),
                'kost_id'          => $contract->kost_id,
                'name'             => $contract->name,
                'email'            => $contract->email,
                'phone'            => $contract->phone,
                'alamat'           => $contract->alamat,
                'duration'         => (int)$request->input('duration'),
                'tanggal_masuk'    => $start,
                'tanggal_keluar'   => $end,
                'status'           => 'pending',
                'bukti_pembayaran' => $bukti,
                'type'             => 'extension',
                'parent_order_id'  => $contract->id,
            ]);

            return response()->json([
                'message'  => 'Mohon ditunggu, perpanjangan kontrak kamu sedang diproses.',
                'order_id' => $order->id,
                'start'    => $start->toDateString(),
                'end'      => $end->toDateString(),
                'status'   => $order->status,
            ], 201);
        }

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

