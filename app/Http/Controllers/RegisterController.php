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


            // 👇 Sekarang bukan role final, tetapi role yang diminta user
            'role'     => [
                'required', 
                Rule::in([
                    'mahasiswa',
                    'dosen_pembimbing',
                    'dosen_penguji',
                    'koordinator_pbl',
                    'jaminan_mutu',
                    // 'admin' → biasanya TIDAK boleh user request admin! 
                ])
            ],


            'rule'     => ['required', Rule::in(['mahasiswa','dosen_pembimbing','dosen_penguji','koor_pbl','jaminan_mutu','admin'])],
            'nim'      => ['nullable','string','max:30'],
            'prodi'    => ['nullable','string','max:100'],
        ]);

        // SIMPAN USER SEBAGAI PENDING
        $user = User::create([
            'nama'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),

            // Data tambahan
            'nim'            => $validated['nim'] ?? null,
            'prodi'          => $validated['prodi'] ?? null,

            // ⚠️ BUKAN role final
            'role'           => null,                     // nanti admin tentukan
            'requested_role' => $validated['role'],       // role yang diminta user

            // Status akun baru
            'status'         => 'pending',                // pending | active | rejected
        ]);

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        // Tidak perlu login user karena statusnya masih pending
        // Jika ingin user tidak bisa masuk sebelum approved, abaikan login:
        // auth()->login($user);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Silakan cek email untuk verifikasi, dan tunggu persetujuan admin.');
    }
}
