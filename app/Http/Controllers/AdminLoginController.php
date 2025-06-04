<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');  // Ganti dengan view login admin Anda
    }

    public function login(Request $request)
    {
        // Validasi inputan
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Cek apakah admin dengan email ini ada di database
        $admin = User::where('email', $request->email)->where('role', 'admin')->first();

        if ($admin && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Jika login berhasil, alihkan ke dashboard admin
            return redirect()->route('admin.dashboard'); // Ganti dengan route dashboard admin
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }
}
