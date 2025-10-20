<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterSuccessMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],   // ✅ ganti 'nama' ke 'name'
            'email'    => ['required','email','unique:users,email'],
            'role'     => ['required', Rule::in([
                'dosen_pembimbing','admin','mahasiswa','jaminan_mutu','koor_pbl','dosen_penguji',
            ])],
            'password' => ['required','min:6','confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],      // ✅ konsisten dengan nama kolom tabel
            'email'    => $data['email'],
            'role'     => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        try {
            Mail::to($user->email)->queue(new RegisterSuccessMail($user->name));
        } catch (\Throwable $e) {
            \Log::error('Gagal kirim email register: '.$e->getMessage());
        }

        Auth::login($user);
        $request->session()->regenerate();

        // Redirect sesuai role
        if ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Registrasi berhasil. Email konfirmasi telah dikirim.');
        }

        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil. Email konfirmasi telah dikirim.');
    }
}
