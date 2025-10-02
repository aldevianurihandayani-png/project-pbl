<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // tampilkan form (opsional, jika pakai view terpisah)
    public function showRegister()
    {
        return view('register');
    }

    // proses simpan register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:100'],
            'nim_nip'  => ['required','string','max:50','unique:users,nim_nip'],
            'prodi'    => ['nullable','string','max:100'],
            'email'    => ['required','email','max:150','unique:users,email'],
            'role'     => ['required','in:mahasiswa,dosen,koordinator,admin'], // atau sesuaikan
            'password' => ['required','min:6','confirmed'], // butuh field password_confirmation
        ]);

        // berkat casts 'hashed', password otomatis di-bcrypt
        $user = User::create($data);

        // opsional: auto login setelah daftar
        // auth()->login($user);

        return redirect()->route('home')->with('success','Registrasi berhasil. Silakan login.');
    }
}
