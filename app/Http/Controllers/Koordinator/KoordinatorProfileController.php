<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KoordinatorProfileController extends Controller
{
    public function show()
    {
        $u = Auth::user();
        $user = $u; // alias supaya view bisa pakai $user
        return view('koordinator.profile.index', compact('u', 'user'));
    }

    public function edit()
{
    $u = Auth::user();
    $user = $u;
    return view('koordinator.profile.profile-edit', compact('u','user'));
}


    public function update(Request $request)
    {
        /** @var User $u */
        $u = Auth::user();

        $request->validate([
            'nama'     => 'nullable|string|max:255',
            'email'    => 'required|email|max:255',

            // sesuai DB kamu ada kolom nim
            'nim'      => 'nullable|string|max:30',

            'prodi'    => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $u->nama  = $request->nama ?? $u->nama;
        $u->email = $request->email;

        // simpan nim kalau ada inputnya
        $u->nim   = $request->nim ?? $u->nim;

        $u->prodi = $request->prodi ?? $u->prodi;

        if ($request->filled('password')) {
            $u->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // kalau foto lama tersimpan sebagai path string di kolom foto
            if ($u->foto && Storage::disk('public')->exists($u->foto)) {
                Storage::disk('public')->delete($u->foto);
            }

            // simpan path file ke kolom foto
            $u->foto = $request->file('foto')->store('foto-koordinator', 'public');
        }

        $u->save();

        return redirect()
            ->route('koordinator.profile')
            ->with('success', 'Profil koordinator berhasil diperbarui.');
    }
}
