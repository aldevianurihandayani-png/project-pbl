<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    // REGISTER → AUTO LOGIN → REDIRECT PER-ROLE
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:100'],
            'email'    => ['required','email','max:150','unique:users,email'],
            'password' => ['required','min:6','confirmed'],
            'role'     => ['nullable','in:admin,dosen_pembimbing,mahasiswa'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'] ?? 'mahasiswa',
        ]);

        Auth::login($user);

        return redirect()->route(match ($user->role) {
            'admin'            => 'admins.dashboard',
            'dosen_pembimbing' => 'dosen.dashboard',
            default            => 'mahasiswa.dashboard',
        })->with('success','Registrasi berhasil. Selamat datang!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $cred = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($cred, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $role = auth()->user()->role;
            return redirect()->route(match ($role) {
                'admin'            => 'admins.dashboard',
                'dosen_pembimbing' => 'dosen.dashboard',
                default            => 'mahasiswa.dashboard',
            });
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}