<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with('user')->latest()->paginate(15);
        return view('admins.mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        return view('admins.mahasiswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim'      => ['required', 'string', 'max:15', 'unique:mahasiswas,nim'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'angkatan' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'no_hp'    => ['nullable', 'string', 'max:15'],
        ]);

        // buat user (tabel users)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa',
            'nim'      => $request->nim,
        ]);

        // buat mahasiswa (tabel mahasiswas)
        Mahasiswa::create([
            'nim'      => $request->nim,
            'nama'     => $request->name,
            'angkatan' => $request->angkatan,
            'no_hp'    => $request->no_hp,
            'user_id'  => $user->id ?? null,
        ]);

        return redirect()
            ->route('admins.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user');
        return view('admins.mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        // VALIDASI INPUT EDIT
        $request->validate([
            'nim'      => ['required', 'string', 'max:15', 'unique:mahasiswas,nim,' . $mahasiswa->nim . ',nim'],
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $mahasiswa->user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'angkatan' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'no_hp'    => ['nullable', 'string', 'max:15'],
        ]);

        // === UPDATE DATA USER TERKAIT (tabel users) ===
        if ($mahasiswa->user) {
            $userData = [
                'name'  => $request->nama,   // nama yang tampil di akun
                'email' => $request->email,
                'nim'   => $request->nim,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $mahasiswa->user->update($userData);
        }

        // === UPDATE DATA MAHASISWA (tabel mahasiswas) ===
        $mahasiswaData = [
            'nim'      => $request->nim,
            'nama'     => $request->nama,
            'angkatan' => $request->angkatan,
            'no_hp'    => $request->no_hp,
        ];

        $mahasiswa->update($mahasiswaData);

        return redirect()
            ->route('admins.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

   public function destroy(Mahasiswa $mahasiswa)
{
    // Cegah admin menghapus akun dirinya sendiri
    // bandingkan user_id di tabel mahasiswas dengan id user yang login
    if ($mahasiswa->user_id === auth()->id()) {
        return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    // Kalau ada relasi user, hapus juga user-nya
    if ($mahasiswa->user) {
        $mahasiswa->user->delete();
    }

    // Hapus data mahasiswa
    $mahasiswa->delete();

    return redirect()
        ->route('admins.mahasiswa.index')
        ->with('success', 'Mahasiswa berhasil dihapus.');
}
}
