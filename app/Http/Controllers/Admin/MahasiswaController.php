<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = User::where('role', 'mahasiswa')->latest()->paginate(15);
        return view('admins.mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        return view('admins.mahasiswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        return redirect()->route('admins.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(User $mahasiswa)
    {
        return view('admins.mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, User $mahasiswa)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.','. $mahasiswa->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $mahasiswa->update($data);

        return redirect()->route('admins.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(User $mahasiswa)
    {
        // Pastikan tidak menghapus diri sendiri jika admin juga seorang user
        if ($mahasiswa->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $mahasiswa->delete();

        return redirect()->route('admins.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}