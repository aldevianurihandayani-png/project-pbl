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
        // Normalisasi role dari request (jaga-jaga jika datang sebagai alias)
        $inputRole = $this->normalizeRole($request->input('role'));
        $request->merge(['role' => $inputRole]);

        // Validasi
        $data = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
            'role'     => ['required', Rule::in([
                'admin','mahasiswa','dosen_pembimbing','dosen_penguji','jaminan_mutu','koor_pbl'
            ])],
        ]);

        // Coba login
        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']], true)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        // Login sukses → kunci session
        $request->session()->regenerate();

        // Ambil role user di DB dan normalisasi agar legacy tetap cocok
        $user = Auth::user();
        $userRoleCanonical = $this->normalizeRole($user->role);

        // Cek kecocokan role yang dipilih di form vs role akun
        if ($userRoleCanonical !== $data['role']) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['role' => 'Role tidak sesuai dengan akun.'])
                ->withInput();
        }

        // Peta role → route dashboard (pakai nama route yang saat ini ada di project-mu)
        $routeMap = [
            'admin'            => 'admins.dashboard',        // kamu saat ini pakai "admins.dashboard"
            'dosen_pembimbing' => 'dosen.dashboard',
            'dosen_penguji'    => 'dosenpenguji.dashboard',
            'jaminan_mutu'     => 'jaminanmutu.dashboard',
            'koor_pbl'         => 'koordinator.dashboard',   // kamu namai route "koordinator"
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
