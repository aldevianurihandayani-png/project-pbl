<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Arahkan user ke halaman login Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google setelah user memilih akun.
     */
    public function callback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')
                ->stateless()      // boleh dihapus kalau tidak perlu stateless
                ->user();
        } catch (\Exception $e) {
            // dd($e->getMessage()); // bisa dipakai debug kalau error
            return redirect('/login')->with('error', 'Gagal login dengan Google.');
        }

        $email = $googleUser->getEmail();

        // 1. BATASI HANYA EMAIL POLITALA
        if (!preg_match('/@(mhs\.)?politala\.ac\.id$/', $email)) {
            return redirect('/login')
                ->with('error', 'Harap gunakan akun Politala (@mhs.politala.ac.id).');
        }

        // 2. CARI USER BERDASARKAN EMAIL
        $user = User::where('email', $email)->first();

        // 3. KALAU BELUM ADA -> BUAT BARU
        if (!$user) {
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $email,
                'password'          => bcrypt(Str::random(16)), // random, karena SSO
                'email_verified_at' => now(),                    // anggap sudah terverifikasi
                'role'              => 'mahasiswa',              // default boleh kamu ganti
            ]);
        } else {
            // 4. KALAU SUDAH ADA TAPI BELUM VERIFIED, ANGAP VERIFIED
            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
                $user->save();
            }
        }

        // 5. LOGIN-KAN USER
        Auth::login($user);

        // 6. ARAHKAN KE DASHBOARD SESUAI ROLE
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admins.dashboard');

            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');

            case 'dosen_pembimbing':
                return redirect()->route('dosen.dashboard');

            case 'dosen_penguji':
                return redirect()->route('dosenpenguji.dashboard');

            case 'koordinator':
                return redirect()->route('koordinator.dashboard');

            case 'jaminan_mutu':
                return redirect()->route('jaminanmutu.dashboard');

            default:
                return redirect()->route('home');
        }
    }
}
