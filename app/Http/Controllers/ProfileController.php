<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil user yang sedang login.
     */
    public function show()
    {
        $user = Auth::user();

        // sesuaikan dengan view yang kamu pakai
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
            'nidn'     => 'nullable|string|max:50',
            'prodi'    => 'nullable|string|max:255',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // ✅ Update data dasar
        $user->nama  = $request->nama;
        $user->email = $request->email;
        $user->nidn  = $request->nidn;
        $user->prodi = $request->prodi;

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
