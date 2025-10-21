<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Pakai seperti:
     *   ->middleware('role:admin')
     *   ->middleware('role:mahasiswa,admin')  // OR logic
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // jika tidak diberikan roles, lewati saja (anggap pass-through)
        if (empty($roles)) {
            return $next($request);
        }

        // izinkan jika role user ada di daftar roles
        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Akses ditolak.');
    }
}
