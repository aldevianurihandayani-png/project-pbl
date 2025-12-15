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
        // VALIDASI DATA REGISTER
        $validated = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],

            // Role yang diminta user (sekarang termasuk admin)
            'role'     => [
                'required',
                Rule::in([
                    'mahasiswa',
                    'dosen_pembimbing',
                    'dosen_penguji',
                    'koordinator_pbl',
                    'jaminan_mutu',
                    'admin',
                ]),
            ],

            'nim'       => ['nullable', 'string', 'max:30'],
            'prodi'     => ['nullable', 'string', 'max:100'],

            // untuk admin (wajib kalau role=admin, nanti dicek manual)
            'admin_key' => ['nullable', 'string'],
        ]);

        // 🔐 Jika pilih admin, wajib masukkan kode admin yang benar
        if ($validated['role'] === 'admin') {
            $expectedKey = config('app.admin_register_key'); // dari config/app.php -> env('ADMIN_REGISTER_KEY')

            if (empty($validated['admin_key']) || $validated['admin_key'] !== $expectedKey) {
                return back()
                    ->withErrors(['admin_key' => 'Kode admin tidak valid.'])
                    ->withInput();
            }
        }

        // Tentukan role & status
        $role = $validated['role'];
        $status = ($role === 'admin') ? 'active' : 'pending';

        // SIMPAN USER
        $user = User::create([
            'nama'     => $validated['nama'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),

            'nim'      => $validated['nim'] ?? null,
            'prodi'    => $validated['prodi'] ?? null,

            // ✅ Admin langsung jadi admin & active
            // ✅ Selain admin: role null dan requested_role disimpan
            'role'           => ($role === 'admin') ? 'admin' : null,
            'requested_role' => ($role === 'admin') ? null : $role,
            'status'         => $status,
        ]);

        // KIRIM EMAIL VERIFIKASI
        $user->sendEmailVerificationNotification();

        // ✅ ARAHKAN KE HALAMAN "CEK EMAIL" (verification.notice)
        return redirect()->route('verification.notice')
            ->with('success', 'Pendaftaran berhasil! Silakan cek email untuk verifikasi.');
    }
}
