<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        if ($type === 'admin') {
            if (!Auth::guard('web')->check()) {
                return redirect()->route('login');
            }
            
            // If staff is logged in, logout staff
            if (Auth::guard('staff')->check()) {
                Auth::guard('staff')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }
        
        if ($type === 'staff') {
            if (!Auth::guard('staff')->check()) {
                return redirect()->route('staff.login');
            }
            
            // If admin is logged in, logout admin
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }
        
        return $next($request);
    }
}