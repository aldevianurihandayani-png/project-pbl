<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register'); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','confirmed','min:8'],
        ]);

        // Simpan user baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Kirim link verifikasi ke email
        $user->sendEmailVerificationNotification();

        // Login otomatis (opsional)
        auth()->login($user);

        // Redirect ke halaman notice verifikasi
        return redirect()->route('verification.notice')
                         ->with('message', 'Silakan cek email Anda untuk verifikasi akun.');
    }
}
