<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
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
            'role'     => 'required|in:admin,mahasiswa,dosen_pembimbing,dosen_penguji,koordinator,jaminan_mutu',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nama'           => $validated['nama'] ?? null,
            'email'          => $validated['email'],
            'role'           => $validated['role'],
            'password'       => Hash::make($validated['password']),

            // akun yang dibuat langsung oleh admin dianggap sudah aktif
            'status'         => 'active',
            'requested_role' => null,
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
            'role'     => 'required|in:admin,mahasiswa,dosen_pembimbing,dosen_penguji,koordinator,jaminan_mutu',
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
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang login.');
        }

        $user->delete();

        return redirect()
            ->route('admins.users.index')
            ->with('success', 'Akun berhasil dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE / REJECT USER (PENDAFTARAN PENDING)
    |--------------------------------------------------------------------------
    */

    public function approve(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,mahasiswa,dosen_pembimbing,dosen_penguji,koordinator,jaminan_mutu',
        ]);

        // role final dari admin
        $user->role           = $request->role;
        $user->status         = 'active';
        $user->requested_role = null;   // opsional: reset karena sudah diputuskan
        $user->save();

        // TODO: bisa tambahkan notifikasi / email ke user di sini

        return back()->with('success', 'Akun disetujui dan role sudah ditetapkan.');
    }

    public function reject(User $user)
    {
        $user->status         = 'rejected';
        // opsional: kosongkan role juga
        // $user->role           = null;
        $user->save();

        // TODO: bisa tambahkan notifikasi / email penolakan di sini

        return back()->with('success', 'Akun ditolak.');
    }
}
