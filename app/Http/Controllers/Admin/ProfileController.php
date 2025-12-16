<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('admins.profile.index', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('admins.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'foto' => ['nullable', 'image', 'max:10240'], // Max 10MB
            'nidn' => ['nullable', 'string', 'max:255'],
            'prodi' => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'nidn' => $request->nidn,
            'prodi' => $request->prodi,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('profile-photos', 'public');
        }

        $user->update($data);

        return redirect()->route('admins.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
