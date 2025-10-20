<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasApprovedOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'user') {
            // Only check for dashboard access
            if ($request->is('user/dashboard*') && !Auth::user()->hasApprovedOrder()) {
                Auth::logout();
                return redirect()->route('home')
                    ->with('warning', 'Anda belum memiliki pesanan yang disetujui.');
            }
        }

        return $next($request);
    }
}
