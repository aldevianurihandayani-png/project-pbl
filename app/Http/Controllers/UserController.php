<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterSuccessMail;
use App\Mail\LogbookSuccessMail;
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

    public function store(Request $request)
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

        $user = User::create([
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'password' => bcrypt($data['password']),
        ]);

       try {
    Mail::to($user->email)->queue(new RegisterSuccessMail($user->nama));

} catch (\Throwable $e) {
    \Log::error('Gagal kirim email register/logbook: '.$e->getMessage());
}

return redirect()->route('home')->with('success','Registrasi berhasil. Email konfirmasi telah dikirim.');


}
}