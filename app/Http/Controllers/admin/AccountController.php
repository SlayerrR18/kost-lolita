<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kost;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    // Tampilkan semua user user
    public function index()
    {
        $users = User::with(['kost', 'orders' => function($query) {
            $query->where('status', 'confirmed')->latest();
        }])->where('role', 'user')->get();

        return view('admin.account.index', compact('users'));
    }

    // Form tambah akun user
    public function create()
    {
        $kosts = Kost::where('status', 'Kosong')->get();
        return view('admin.account.create', compact('kosts'));
    }

    // Simpan akun user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6',
            'kost_id'         => 'required|exists:kosts,id',
            'tanggal_masuk'   => 'required|date',
            'tanggal_keluar'  => 'required|date|after:tanggal_masuk',
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'user',
            ]);

            // Create order record
            Order::create([
                'user_id' => $user->id,
                'kost_id' => $validated['kost_id'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'status' => 'confirmed',
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'tanggal_keluar' => $validated['tanggal_keluar'],
                // Calculate duration in months
                'duration' => \Carbon\Carbon::parse($validated['tanggal_masuk'])
                    ->diffInMonths(\Carbon\Carbon::parse($validated['tanggal_keluar']))
            ]);

            // Update kost status
            $kost = Kost::find($validated['kost_id']);
            $kost->update([
                'status' => 'Terisi',
                'penghuni' => $user->id
            ]);

            DB::commit();

            return redirect()
                ->route('admin.account.index')
                ->with('success', 'Akun penghuni berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Form edit akun user
    public function edit(User $user)
    {
        $kosts = Kost::where(function($query) use ($user) {
            $query->where('status', 'Kosong')
                  ->orWhere('penghuni', $user->id);
        })->get();

        // Get latest confirmed order
        $order = $user->orders()
            ->where('status', 'confirmed')
            ->latest()
            ->first();

        return view('admin.account.edit', compact('user', 'kosts', 'order'));
    }

    // Update akun user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string',
            'address' => 'required|string',
            'kost_id' => 'required|exists:kosts,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'required|date|after:tanggal_masuk',
            'password' => 'nullable|string|min:6',
        ]);

        try {
            DB::beginTransaction();

            // Update user data
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ];

            // Only update password if provided
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            // Update or create order
            $order = $user->orders()
                ->where('status', 'confirmed')
                ->latest()
                ->first();

            if ($order) {
                $duration = \Carbon\Carbon::parse($validated['tanggal_masuk'])
                    ->diffInMonths(\Carbon\Carbon::parse($validated['tanggal_keluar']));

                $order->update([
                    'kost_id' => $validated['kost_id'],
                    'tanggal_masuk' => $validated['tanggal_masuk'],
                    'tanggal_keluar' => $validated['tanggal_keluar'],
                    'duration' => $duration
                ]);
            }

            // Handle kost room changes
            if ($user->kost_id != $validated['kost_id']) {
                // Update old room status
                if ($user->kost) {
                    $user->kost->update([
                        'status' => 'Kosong',
                        'penghuni' => null
                    ]);
                }

                // Update new room status
                Kost::find($validated['kost_id'])->update([
                    'status' => 'Terisi',
                    'penghuni' => $user->id
                ]);

                // Update user's kost_id
                $user->update(['kost_id' => $validated['kost_id']]);
            }

            DB::commit();

            return redirect()
                ->route('admin.account.index')
                ->with('success', 'Data penghuni berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Hapus akun user
    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            // Get kost room before deleting user
            $kost = $user->kost;

            // Delete user (this will trigger the boot method in User model)
            $user->delete();

            // Update kost status if exists
            if ($kost) {
                $kost->update([
                    'status' => 'Kosong',
                    'penghuni' => null
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.account.index')
                ->with('success', 'Akun berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
