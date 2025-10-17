<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // TERIMA "name" ATAU fallback dari "nama" (jaga-jaga form lama)
        $request->merge([
            'name' => $request->input('name') ?? $request->input('nama'),
        ]);

        // Validasi
        $data = $request->validate([
            'name'                  => ['required','string','max:100'],
            'email'                 => ['required','email','max:150','unique:users,email'],
            'password'              => ['required','min:6','confirmed'],
            'role'                  => ['required','in:admins,dosen_pembimbing,dosen_penguji,koordinator,jaminan_mutu,mahasiswa'],
            // nim/prodi opsional (belum disimpan ke tabel users)
            'nim'                   => ['nullable','string','max:50'],
            'prodi'                 => ['nullable','string','max:100'],
        ]);

        // Simpan user (hanya kolom yang memang ada di tabel users)
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'] ?? 'mahasiswa',
        ]);

        // Auto login
        Auth::login($user);

        // Redirect per-role
        $route = match ($user->role) {
            'admins'            => 'admins.dashboard',
            'dosen_pembimbing'  => 'dosen.dashboard',
            'dosen_penguji'     => 'dosenpenguji.dashboard',
            'koordinator'       => 'koordinator.dashboard',
            'jaminan_mutu'      => 'jaminanmutu.dashboard',
            'mahasiswa'         =>  'mahasiswa.dashboard',
            default             => 'mhs.dashboard',
        };

        return redirect()->route($route)->with('success','Registrasi berhasil. Selamat datang!');
    }
}
