<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        // Clear any staff session before showing admin login
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }
        
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $userType = $request->input('user_type', 'admin');
        
        if ($userType === 'staff') {
            return redirect()->route('staff.login.submit')->withInput($request->only('email', 'password'));
        }
        
        // Clear any staff session before admin login
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }
        
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        // Also clear staff session
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirect to login page instead of home page
        return redirect()->route('login');
    }
}