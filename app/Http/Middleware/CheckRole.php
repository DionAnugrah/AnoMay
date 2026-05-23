<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Cek apakah user yang login punya role yang diizinkan.
     *
     * Cara pakai di route: middleware('role:admin')
     *                  atau middleware('role:admin,boss')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Belum login sama sekali
        if (! Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated. Silakan login terlebih dahulu.',
            ], 401);
        }

        $user = Auth::user();

        // Role tidak cocok dengan yang diizinkan
        if (! in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Forbidden. Anda tidak punya akses ke halaman ini.',
                'your_role' => $user->role,
                'required'  => $roles,
            ], 403);
        }

        return $next($request);
    }
}