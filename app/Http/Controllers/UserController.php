<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],
            'role'     => ['required', Rule::in([
                'dosen_pembimbing',
                'admin',
                'mahasiswa',
                'jaminan_mutu',
                'koor_pbl',
                'dosen_penguji',
            ])],
            'password' => ['required','min:6','confirmed'],
        ]);

        // Simpan hanya kolom yang memang ada di tabel
        $user = User::create([
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'password' => $data['password'], // auto-hash, lihat model
        ]);

        return redirect()->route('home')->with('success','Registrasi berhasil');
    }
}
