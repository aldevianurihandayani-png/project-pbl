<?php

namespace App\Http\Controllers\JaminanMutu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class JaminanMutuProfileController extends Controller
{
    public function show()
    {
        return view('jaminanmutu.profile');
    }

    public function edit()
    {
        return view('jaminanmutu.profile-edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama'     => 'nullable|string|max:255',
            'email'    => 'required|email|max:255',
            'prodi'    => 'nullable|string|max:255',
            'password' => 'nullable|min:6',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // data biasa
        $data = [
            'nama'  => $validated['nama'] ?? $user->nama,
            'email' => $validated['email'],
            'prodi' => $validated['prodi'] ?? $user->prodi,
        ];

        // password opsional
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        // upload foto (pakai kolom: foto)
        if ($request->hasFile('foto')) {
            // hapus foto lama kalau ada
            if (!empty($user->foto) && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $path = $request->file('foto')->store('profiles', 'public');
            $data['foto'] = $path;
        }

        $user->update($data);

        return redirect()
            ->route('jaminanmutu.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
