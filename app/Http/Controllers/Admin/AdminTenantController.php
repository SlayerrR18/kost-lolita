<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;

class AdminTenantController extends Controller
{
    // Menampilkan daftar penghuni kost
    public function index()
    {
        // Ambil semua user dengan role 'tenant' dan eager-load current room + orders
        $tenants = User::where('role', 'tenant')
            ->with(['room', 'orders'])
            ->get();
        return view('admin.tenants.index', compact('tenants'));
    }

    // Menampilkan halaman edit penghuni kost
    public function edit(User $user)
    {
        return view('admin.tenants.edit', compact('user'));
    }

     public function show(User $user)
    {
        // Ambil riwayat pesanan penghuni (jika ada)
        $orders = Order::where('user_id', $user->id)->get();

        // Mengambil informasi terkait penghuni, misalnya pesanan yang belum dikonfirmasi
        return view('admin.tenants.show', compact('user', 'orders'));
    }

    // Mengupdate informasi penghuni kost (email, password)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Update email
        $user->email = $request->email;

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('admin.tenants.index')->with('success', 'Penghuni berhasil diperbarui.');
    }

        public function destroy(User $user)
    {
        // Periksa apakah penghuni yang akan dihapus adalah admin atau bukan
        if ($user->role === 'admin') {
            return redirect()->route('admin.tenants.index')->with('error', 'Tidak dapat menghapus akun admin.');
        }

        // Cari semua order yang approved milik user ini
        $approvedOrders = Order::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();

        // Update status kamar kembali ke 'available' untuk setiap order approved
        foreach ($approvedOrders as $order) {
            // Periksa apakah ada order approved lain untuk kamar yang sama
            $hasOtherApprovedOrders = Order::where('room_id', $order->room_id)
                ->where('status', 'approved')
                ->where('id', '!=', $order->id)
                ->where('user_id', '!=', $user->id)
                ->exists();

            // Jika tidak ada order approved lain, ubah status kamar ke 'available'
            if (!$hasOtherApprovedOrders) {
                $order->room()->update([
                    'status' => 'available',
                ]);
            }
        }

        // Hapus penghuni beserta data terkait
        $user->delete();

        // Redirect kembali ke daftar penghuni dengan pesan sukses
        return redirect()->route('admin.tenants.index')->with('success', 'Akun penghuni berhasil dihapus dan kamar dibebaskan.');
    }
}
