<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create(Kost $kost)
    {
        // Check if room is available
        if (!$kost->isAvailable()) {
            return redirect()->back()->with('error', 'Kamar tidak tersedia');
        }

        return view('order.create', compact('kost'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'kost_id' => 'required|exists:kosts,id',
            'alamat' => 'required|string',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'ktp_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'duration' => 'required|integer|min:1|max:12',
            'tanggal_masuk' => 'required|date|after_or_equal:today'
        ]);

        try {
            DB::beginTransaction();

            $order = new Order();
            $order->fill($validated);

            // Hitung tanggal keluar berdasarkan durasi
            $tanggalMasuk = \Carbon\Carbon::parse($validated['tanggal_masuk']);
            $order->tanggal_keluar = $tanggalMasuk->copy()->addMonths($validated['duration']);

            if ($request->hasFile('bukti_pembayaran')) {
                $order->bukti_pembayaran = $request->file('bukti_pembayaran')->store('payments', 'public');
            }

            if ($request->hasFile('ktp_image')) {
                $order->ktp_image = $request->file('ktp_image')->store('ktp', 'public');
            }

            $order->status = 'pending';
            $order->save();

            DB::commit();

            // Redirect to confirmation page instead of back
            return redirect()->route('order.confirmation', $order)
                ->with('success', 'Pesanan berhasil dikirim');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function confirm(Order $order)
    {
        try {
            DB::beginTransaction();

            // Generate password
            $password = Str::random(8);

            // Create user account
            $user = User::create([
                'name' => $order->name,
                'email' => $order->email,
                'password' => Hash::make($password),
                'role' => 'user',
                'phone' => $order->phone
            ]);

            // Update kost status
            $order->kost->update([
                'status' => 'Terisi',
                'penghuni' => $user->id
            ]);

            // Create financial record
            Financial::create([
                'kost_id' => $order->kost_id,
                'nama_transaksi' => 'Pembayaran Sewa Kamar ' . $order->kost->nomor_kamar,
                'tanggal_transaksi' => now(),
                'total' => $order->kost->harga,
                'status' => 'Pemasukan',
                'bukti_transaksi' => $order->bukti_pembayaran,
                'keterangan' => 'Pembayaran dari ' . $order->name
            ]);

            // Update order status
            $order->update([
                'status' => 'confirmed',
                'user_id' => $user->id
            ]);

            DB::commit();

            // Send email with credentials
            // Mail::to($user->email)->send(new OrderConfirmed($order, $user, $password));

            return response()->json([
                'success' => true,
                'message' => 'Pesanan dikonfirmasi dan akun user telah dibuat',
                'data' => [
                    'user_email' => $user->email,
                    'password' => $password
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Order $order)
    {
        try {
            $order->update(['status' => 'rejected']);
            // Mail::to($order->email)->send(new OrderRejected($order));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function confirmation(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('welcome')
                ->with('error', 'Pesanan tidak valid atau sudah diproses');
        }

        return view('order.confirmation', compact('order'));
    }
}
