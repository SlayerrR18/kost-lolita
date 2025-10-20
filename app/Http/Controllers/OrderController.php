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
        try {
            // Log request data
            Log::info('Order request data:', $request->all());

            $validated = $request->validate([
                'kost_id'          => 'required|exists:kosts,id',
                'phone'            => 'required|string|max:20',
                'alamat'           => 'required|string|max:500',
                'duration'         => 'required|integer|min:1|max:12',
                'tanggal_masuk'    => 'required|date|after_or_equal:today',
                'ktp_image'        => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            // Log validated data
            Log::info('Validated data:', $validated);

            $user = Auth::user();
            $kost = Kost::findOrFail($validated['kost_id']);

            if (!$kost->isAvailable()) {
                return back()->with('error', 'Maaf, kamar ini baru saja dipesan orang lain.');
            }

            DB::beginTransaction();

            try {
                // Handle KTP image upload
                if ($request->hasFile('ktp_image')) {
                    $file = $request->file('ktp_image');
                    $fileName = time() . '_ktp_' . Str::slug($user->name) . '.' . $file->getClientOriginalExtension();
                    $ktpPath = $file->storeAs('ktp', $fileName, 'public');
                    Log::info('KTP file stored at: ' . $ktpPath);
                }

                // Handle payment proof upload
                if ($request->hasFile('bukti_pembayaran')) {
                    $file = $request->file('bukti_pembayaran');
                    $fileName = time() . '_bukti_' . Str::slug($user->name) . '.' . $file->getClientOriginalExtension();
                    $buktiPath = $file->storeAs('bukti-pembayaran', $fileName, 'public');
                    Log::info('Payment proof stored at: ' . $buktiPath);
                }

                $tanggal_masuk = Carbon::parse($validated['tanggal_masuk']);
                $tanggal_keluar = $tanggal_masuk->copy()->addMonthsNoOverflow((int)$validated['duration']);

                // Create order
                $orderData = [
                    'user_id'          => $user->id,
                    'kost_id'          => $kost->id,
                    'name'             => $user->name,
                    'email'            => $user->email,
                    'phone'            => $validated['phone'],
                    'alamat'           => $validated['alamat'],
                    'duration'         => (int)$validated['duration'],
                    'tanggal_masuk'    => $tanggal_masuk,
                    'tanggal_keluar'   => $tanggal_keluar,
                    'ktp_image'        => $ktpPath ?? null,
                    'bukti_pembayaran' => $buktiPath ?? null,
                    'status'           => 'pending',
                    'type'             => 'new',
                ];

                // Log order data before creation
                Log::info('Creating order with data:', $orderData);

                $order = Order::create($orderData);

                // Update user profile
                $user->update([
                    'phone' => $validated['phone'],
                    'alamat' => $validated['alamat']
                ]);

                DB::commit();

                return redirect()
                    ->route('order.confirmation', $order)
                    ->with('success', 'Pesanan Anda telah diterima dan sedang menunggu konfirmasi admin.');

            } catch (\Exception $e) {
                DB::rollBack();

                // Delete uploaded files if exists
                if (isset($ktpPath)) Storage::disk('public')->delete($ktpPath);
                if (isset($buktiPath)) Storage::disk('public')->delete($buktiPath);

                throw $e; // Re-throw to be caught by outer catch
            }

        } catch (\Throwable $e) {
            Log::error('Order creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
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
