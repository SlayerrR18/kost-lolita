<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Tampilkan formulir pembuatan pesanan untuk kamar (kost) yang dipilih.
     *
     * @param  \App\Models\Kost  $kost
     * @return \Illuminate\View\View
     */
    public function create(Kost $kost)
    {
        // Pastikan kamar masih tersedia sebelum menampilkan form
        if (!$kost->isAvailable()) {
            return redirect()->route('kamar')->with('error', 'Maaf, kamar yang Anda pilih sudah tidak tersedia.');
        }

        // Ambil data user yang sedang login untuk mengisi form secara otomatis
        $user = Auth::user();

        return view('order.create', compact('kost', 'user'));
    }

    /**
     * Simpan pesanan baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kost_id'          => 'required|exists:kosts,id',
            'phone'            => 'required|string|max:20',
            'alamat'           => 'required|string|max:500',
            'duration'         => 'required|integer|min:1|max:12',
            'tanggal_masuk'    => 'required|date|after_or_equal:today',
            'ktp_image'        => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Dapatkan user yang sedang login
        $user = Auth::user();
        $kost = Kost::findOrFail($validated['kost_id']);

        // Double check ketersediaan kamar sebelum transaksi
        if (!$kost->isAvailable()) {
            return back()->with('error', 'Maaf, kamar ini baru saja dipesan orang lain. Silakan pilih kamar lain.')->withInput();
        }

        DB::beginTransaction();

        $ktpPath = null;
        $buktiPath = null;

        try {
            // Handle upload KTP
            if ($request->hasFile('ktp_image')) {
                $file = $request->file('ktp_image');
                $fileName = time() . '_ktp_' . Str::slug($user->name) . '.' . $file->getClientOriginalExtension();
                $ktpPath = $file->storeAs('ktp', $fileName, 'public');
            }

            // Handle upload bukti pembayaran
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = time() . '_bukti_' . Str::slug($user->name) . '.' . $file->getClientOriginalExtension();
                $buktiPath = $file->storeAs('bukti-pembayaran', $fileName, 'public');
            }

            // Hitung tanggal keluar
            $tanggal_masuk = Carbon::parse($validated['tanggal_masuk']);
            $tanggal_keluar = $tanggal_masuk->copy()->addMonthsNoOverflow((int)$validated['duration']);

            // Buat order baru, tanpa membuat user baru
            $order = Order::create([
                'user_id'          => $user->id, // Gunakan ID user yang login
                'kost_id'          => $kost->id,
                'name'             => $user->name, // Ambil nama dari user yang login
                'email'            => $user->email, // Ambil email dari user yang login
                'phone'            => $validated['phone'],
                'alamat'           => $validated['alamat'],
                'duration'         => (int)$validated['duration'],
                'tanggal_masuk'    => $tanggal_masuk,
                'tanggal_keluar'   => $tanggal_keluar,
                'ktp_image'        => $ktpPath,
                'bukti_pembayaran' => $buktiPath,
                'status'           => 'pending', // Status awal selalu pending
                'type'             => 'new',
            ]);

            // Update profile user jika no. telp atau alamat di form berbeda
            $user->phone = $validated['phone'];
            $user->address = $validated['alamat'];
            $user->save();

            DB::commit();

            return redirect()->route('order.confirmation', $order)->with('success', 'Pesanan Anda telah diterima dan sedang menunggu konfirmasi admin.');

        } catch (\Throwable $e) {
            DB::rollBack();

            // Jika terjadi error, hapus file yang sudah ter-upload
            if ($ktpPath && Storage::disk('public')->exists($ktpPath)) {
                Storage::disk('public')->delete($ktpPath);
            }
            if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }

            Log::error('Order creation failed: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Tampilkan halaman konfirmasi setelah user submit order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function confirmation(Order $order)
    {
        // Security check: Pastikan user hanya bisa melihat halaman konfirmasi miliknya sendiri.
        if ($order->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        return view('order.confirmation', compact('order'));
    }
}
