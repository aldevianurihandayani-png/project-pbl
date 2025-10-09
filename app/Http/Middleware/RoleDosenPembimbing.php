<?php

namespace App\Http\Middleware;

use Closure; // <- WAJIB
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <- disarankan pakai facade

class RoleDosenPembimbing
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'dosen_pembimbing') {
            return $next($request);
        }

        abort(403, 'Akses hanya untuk Dosen Pembimbing.');
    }
}
