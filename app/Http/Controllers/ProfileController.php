<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil user yang sedang login.
     */
    public function show()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Mengupdate data profil user.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // ✅ Validasi input
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // ✅ Update nama & email
        $user->nama  = $request->nama;
        $user->email = $request->email;

        // ✅ Upload & ganti foto bila ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru ke storage/app/public/profile_photos
            $path = $request->file('foto')->store('profile_photos', 'public');
            $user->foto = $path;
        }

        // ✅ Ganti password bila diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // ✅ Simpan semua perubahan
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
