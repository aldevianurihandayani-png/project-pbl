<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('koordinator.profile.index', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'photo_pos_x' => ['nullable', 'integer', 'between:0,100'],
            'photo_pos_y' => ['nullable', 'integer', 'between:0,100'],
        ]);

        // =====================
        // Update data dasar
        // =====================
        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        // =====================
        // Update password (jika diisi)
        // =====================
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // =====================
        // Update foto profil
        // =====================
        if ($request->hasFile('profile_photo')) {

            // hapus foto lama (jika ada)
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('profile_photo')
                ->store('profile-photos', 'public');

            $user->profile_photo_path = $path;
        }

        // =====================
        // Simpan posisi foto
        // =====================
        if (isset($validated['photo_pos_x'])) {
            $user->photo_pos_x = $validated['photo_pos_x'];
        }

        if (isset($validated['photo_pos_y'])) {
            $user->photo_pos_y = $validated['photo_pos_y'];
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
