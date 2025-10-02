<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],
            'role'     => ['required','in:Dosen,Admin,Mahasiswa,Jaminan Mutu'], // sesuaikan enum di DB
            'password' => ['required','min:6','confirmed'],
        ]);

        // simpan user
        $user = User::create($data);

        return redirect()->route('home')->with('success','Registrasi berhasil');
    }
}
