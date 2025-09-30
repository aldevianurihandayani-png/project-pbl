<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Tampilkan form login
    public function index()
    {
        return view('login'); // resources/views/login.blade.php
    }

    // Proses login
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // arahkan ke dashboard
            return redirect()->intended('/dashboard');
        }

        // Kalau gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        // hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // kembali ke halaman login
        return redirect()->route('login');
    }
}
