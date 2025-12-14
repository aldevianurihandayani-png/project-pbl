<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Ubah berbagai alias role ke bentuk baku (canonical).
     * Baku: admin, mahasiswa, dosen_pembimbing, dosen_penguji, jaminan_mutu, koor_pbl
     */
    private function normalizeRole(string $role = null): ?string
    {
        if ($role === null) return null;

        $r = strtolower(trim($role));

        $map = [
            // admin
            'admin'           => 'admin',
            'admins'          => 'admin',

            // koordinator PBL
            'koor_pbl'        => 'koor_pbl',
            'koordinator'     => 'koor_pbl',
            'koordinator_pbl' => 'koor_pbl',

            // jaminan mutu
            'jaminanmutu'     => 'jaminan_mutu',
            'jaminan_mutu'    => 'jaminan_mutu',

            // dosen
            'dosen_pembimbing'=> 'dosen_pembimbing',
            'dosenpembimbing' => 'dosen_pembimbing',

            'dosen_penguji'   => 'dosen_penguji',
            'dosenpenguji'    => 'dosen_penguji',

            // mahasiswa
            'mhs'             => 'mahasiswa',
            'mahasiswa'       => 'mahasiswa',
        ];

        return $map[$r] ?? $r;
    }

    public function authenticate(Request $request)
    {
        // Role dari form sekarang OPTIONAL (boleh ada, boleh tidak)
        $inputRole = $this->normalizeRole($request->input('role'));
        $request->merge(['role' => $inputRole]);

        // Validasi (role optional)
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'role'     => ['nullable', Rule::in([
                'admin','mahasiswa','dosen_pembimbing','dosen_penguji','jaminan_mutu','koor_pbl'
            ])],
        ]);

        // Coba login (email+password)
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']], true)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        $request->session()->regenerate();

        // Role user dari DB (source of truth)
        $user = Auth::user();
        $userRoleCanonical = $this->normalizeRole($user->role);

        // =========================================================
        // ✅ TAMBAHAN: BLOKIR LOGIN JIKA AKUN MASIH PENDING
        //    - admin tetap boleh masuk
        //    - user lain kalau pending → ditolak
        // =========================================================
        $userStatus = $user->status ?? null; // aman walau kolom status belum ada
        if ($userRoleCanonical !== 'admin' && $userStatus === 'pending') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Akun masih menunggu persetujuan admin.'])
                ->withInput();
        }
        // =========================================================
        // ✅ END TAMBAHAN
        // =========================================================

        // ✅ Jika user memilih role di form, kita cek kecocokan
        // ✅ Tapi kalau role tidak dikirim (admin masuk tanpa pilih role), skip cek ini
        if (!empty($data['role']) && $userRoleCanonical !== $data['role']) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['role' => 'Role tidak sesuai dengan akun.'])
                ->withInput();
        }

        // Redirect sesuai role DB
        $routeMap = [
            'admin'            => 'admins.dashboard',
            'dosen_pembimbing' => 'dosen.dashboard',
            'dosen_penguji'    => 'dosenpenguji.dashboard',
            'jaminan_mutu'     => 'jaminanmutu.dashboard',
            'koor_pbl'         => 'koordinator.dashboard',
            'mahasiswa'        => 'mahasiswa.dashboard',
        ];

        $redirectRoute = $routeMap[$userRoleCanonical] ?? 'home';

        return redirect()->route($redirectRoute)->with('success', 'Login berhasil');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
