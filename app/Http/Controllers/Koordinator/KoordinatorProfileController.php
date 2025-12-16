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
        $u = Auth::user(); // instance User (Eloquent)
        return view('mahasiswa.profile', compact('u'));
    }

    public function edit()
    {
        $u = Auth::user();
        return view('mahasiswa.profile-edit', compact('u'));
    }

    public function update(Request $request)
    {
        /** @var User $u */
        $u = Auth::user();

        $request->validate([
            'nama'     => 'nullable|string|max:255',
            'email'    => 'required|email|max:255',
            'nim'      => 'nullable|string|max:30',
            'prodi'    => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $u->nama  = $request->nama;
        $u->email = $request->email;
        $u->nim   = $request->nim;
        $u->prodi = $request->prodi;

        if ($request->filled('password')) {
            $u->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($u->foto && Storage::disk('public')->exists($u->foto)) {
                Storage::disk('public')->delete($u->foto);
            }

            $u->foto = $request->file('foto')->store('foto-mahasiswa', 'public');
        }

        $u->save();

        return redirect('mahasiswa/profile')
            ->with('success', 'Profil mahasiswa berhasil diperbarui.');
    }
}
