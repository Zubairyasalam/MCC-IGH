<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Admin Login
    public function showAdminLogin()
    {
        return view('admin.login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (in_array($user->role, ['admin', 'superadmin'])) {
                $request->session()->regenerate();
                session(['admin_logged_in' => true]);
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
            return back()->with('error', 'You do not have administrative access rights.');
        }
        return back()->with('error', 'Invalid admin credentials');
    }

    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('admin_logged_in');
        return redirect()->route('admin.login');
    }

    // SuperAdmin Login
    public function showSuperAdminLogin()
    {
        return view('superadmin.login');
    }

    public function superAdminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'superadmin') {
                $request->session()->regenerate();
                session(['superadmin_logged_in' => true]);
                return redirect()->route('superadmin.dashboard');
            }
            Auth::logout();
            return back()->with('error', 'You do not have superadmin privileges.');
        }
        return back()->with('error', 'Invalid superadmin credentials');
    }

    public function superAdminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('superadmin_logged_in');
        return redirect()->route('superadmin.login');
    }

    // Guest Login & Registration
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'role' => 'guest',
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully! Please login to continue.');
    }
}
