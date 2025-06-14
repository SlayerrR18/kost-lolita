<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Cek apakah pengguna yang login adalah admin
        $admin = User::where('email', $request->email)->where('role', 'admin')->first();

        // Jika admin valid, login
        if ($admin && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('admin.dashboard');
        }

        // Cek apakah pengguna yang login adalah user biasa
        $user = User::where('email', $request->email)->where('role', 'user')->first();

        if ($user && Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('user.dashboard'); // Redirect ke dashboard user
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
