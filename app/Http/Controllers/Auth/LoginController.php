<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        $app_company = session('app_company') == null ? env('APP_COMPANY') : session('app_company');
        return view('auth.login')
            ->with('app_company', $app_company);
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard.index')->with('toast_success', 'Welcome back, ' . Auth::user()->username . '!');
        }

        return back()->with('error', 'Username/Password is incorrect.');
    }
    
    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
