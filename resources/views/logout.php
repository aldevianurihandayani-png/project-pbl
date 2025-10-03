<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();                                // keluar dari sesi user
        $request->session()->invalidate();            // hapus session
        $request->session()->regenerateToken();       // regenerate CSRF token

        return redirect('/'); // arahkan ke halaman utama (home)
    }
}
