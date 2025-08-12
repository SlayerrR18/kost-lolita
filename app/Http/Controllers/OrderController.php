<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use App\Models\Kost;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    public function create(Kost $kost)
    {
        // Check if room is available
        if (!$kost->isAvailable()) {
            return redirect()->back()->with('error', 'Kamar tidak tersedia');
        }

        return view('order.create', compact('kost'));
    }

    // app/Http/Controllers/OrderController.php (potongan penting di store)
    public function store(Request $request)
    {
        try {
            $rules = [
                'kost_id'          => 'required|exists:kosts,id',
                'name'             => 'required|string|max:255',
                'email'            => 'required|email',        // <- tidak langsung unique disini
                'phone'            => 'required|string',
                'alamat'           => 'required|string',
                'duration'         => 'required|integer|min:1|max:12',
                'tanggal_masuk'    => 'required|date|after_or_equal:today',
                'ktp_image'        => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ];

            // Validasi awal
            $validated = $request->validate($rules);

            // Validasi email unique khusus untuk order BARU:
            // user lama seharusnya pakai menu Perpanjangan, bukan order baru dari halaman publik
            if (\App\Models\User::where('email',$validated['email'])->exists()) {
                return back()
                    ->with('error','Email sudah terdaftar. Jika Anda penghuni lama, gunakan menu Perpanjangan pada halaman User.')
                    ->withInput();
            }

            \DB::beginTransaction();

            $ktpPath = $request->file('ktp_image')?->store('ktp','public');
            $buktiPath = $request->file('bukti_pembayaran')?->store('bukti-pembayaran','public');

            $tanggal_masuk  = \Carbon\Carbon::parse($validated['tanggal_masuk']);
            $tanggal_keluar = $tanggal_masuk->copy()->addMonthsNoOverflow((int)$validated['duration']);

            $order = \App\Models\Order::create([
                'kost_id'          => $validated['kost_id'],
                'name'             => $validated['name'],
                'email'            => $validated['email'],
                'phone'            => $validated['phone'],
                'alamat'           => $validated['alamat'],
                'duration'         => (int)$validated['duration'],
                'tanggal_masuk'    => $tanggal_masuk,
                'tanggal_keluar'   => $tanggal_keluar,
                'ktp_image'        => $ktpPath,
                'bukti_pembayaran' => $buktiPath,
                'status'           => 'pending',
                'type'             => 'new',     
            ]);

            \DB::commit();

            return redirect()->route('order.confirmation',$order)
                ->with('success','Pesanan berhasil dikirim');
        } catch (\Throwable $e) {
            \DB::rollBack();
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage())->withInput();
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

            $order->update([
                'status' => 'confirmed',
                'user_id' => $user->id
            ]);

            DB::commit();

            $message = "Halo {$order->name},\n\n"
                    . "Pesanan kamar kost Anda telah dikonfirmasi.\n"
                    . "Berikut adalah kredensial akun Anda:\n\n"
                    . "Email: {$user->email}\n"
                    . "Password: {$password}\n\n"
                    . "Silakan login menggunakan kredensial di atas.\n"
                    . "Untuk keamanan, harap segera ganti password Anda setelah login.\n\n"
                    . "Terima kasih!";

            $this->whatsapp->sendMessage($order->phone, $message);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan dikonfirmasi dan kredensial telah dikirim via WhatsApp',
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
