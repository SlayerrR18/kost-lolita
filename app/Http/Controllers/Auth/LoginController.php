<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     * This is a fallback, the main logic is in the authenticated() method.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     * This method is called after a successful login attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Admin redirect
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Regular user - allow them to browse and order rooms
        if ($user->role === 'user') {
            // If user trying to access dashboard without approved order
            if ($request->is('user/dashboard*') && !$user->hasApprovedOrder()) {
                Auth::logout();
                return redirect()->route('home')
                    ->with('warning', 'Anda belum memiliki pesanan yang disetujui.');
            }

            // Otherwise, let them browse and order
            return redirect()->intended(route('home'));
        }

        // Fallback redirect
        return redirect($this->redirectTo);
    }
}

