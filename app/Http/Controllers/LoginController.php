<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login'); 
    }

    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
            'role'     => ['required', Rule::in([
                'mahasiswa','dosen_pembimbing','dosen_penguji','koor_pbl','jaminan_mutu','admin'
            ])],
        ]);

        // Coba login pakai email & password
        if (!Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']], true)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        // Cek kecocokan role
        $user = Auth::user();
        if ($user->role !== $data['role']) {
            Auth::logout();
            return back()->withErrors(['role' => 'Role tidak sesuai dengan akun.'])->withInput();
        }

        // Sukses
        $request->session()->regenerate();

        if ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard')->with('success', 'Login berhasil');
        }

        return redirect()->route('home')->with('success','Login berhasil');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}