<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffLoginController extends Controller
{
    public function showLoginForm()
    {
        // Clear any existing sessions before showing login
        if (Auth::check()) {
            Auth::logout();
        }
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Clear any existing sessions before login
        if (Auth::check()) {
            Auth::logout();
        }
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (Auth::guard('staff')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('staff.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        
        // Also logout from web guard
        if (Auth::check()) {
            Auth::logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear all session data
        $request->session()->flush();
        
        return redirect('/');
    }
}