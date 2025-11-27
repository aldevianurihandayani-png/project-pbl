<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],
            'password' => ['required','confirmed','min:8'],
            'role'     => ['required', Rule::in(['mahasiswa','dosen_pembimbing','dosen_penguji','koordinator_pbl','jaminan_mutu','admin'])],
            'nim'      => ['nullable','string','max:30'],
            'prodi'    => ['nullable','string','max:100'],
        ]);

        $user = User::create([
            'nama'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],

        ]);

        $user->sendEmailVerificationNotification();
        auth()->login($user);

        return redirect()->route('verification.notice')
            ->with('message', 'Silakan cek email Anda untuk verifikasi akun.');
    }
}
