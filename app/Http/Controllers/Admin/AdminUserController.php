<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;   // ✅ tambahkan ini

class AdminUserController extends Controller
{
    public function index()
    {
        // ✔ pakai kolom id
        $users = User::orderBy('id', 'ASC')->get();

        return view('admins.users.index', compact('users'));
    }

    public function create()
    {
        $roles = [
            'admin'            => 'Admin',
            'mahasiswa'        => 'Mahasiswa',
            'dosen_pembimbing' => 'Dosen Pembimbing',
            'dosen_penguji'    => 'Dosen Penguji',
            'koordinator'      => 'Koordinator',
            'jaminan_mutu'     => 'Jaminan Mutu',
        ];

        return view('admins.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'nullable|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nama'     => $validated['nama'] ?? null,
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admins.users.index')
            ->with('success', 'Akun berhasil dibuat');
    }

    // Pakai route model binding
    public function show(User $user)
    {
        return view('admins.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = [
            'admin'            => 'Admin',
            'mahasiswa'        => 'Mahasiswa',
            'dosen_pembimbing' => 'Dosen Pembimbing',
            'dosen_penguji'    => 'Dosen Penguji',
            'koordinator'      => 'Koordinator',
            'jaminan_mutu'     => 'Jaminan Mutu',
        ];

        return view('admins.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama'     => 'nullable|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id . ',id',
            'role'     => 'required',
            'password' => 'nullable|min:6',
        ]);

        $user->nama  = $validated['nama'] ?? null;
        $user->email = $validated['email'];
        $user->role  = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('admins.users.index')
            ->with('success', 'Akun berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        // ✅ pakai facade Auth, bukan helper yg bikin intelephense bingung
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang login.');
        }

        $user->delete();

        return redirect()
            ->route('admins.users.index')
            ->with('success', 'Akun berhasil dihapus');
    }
}
