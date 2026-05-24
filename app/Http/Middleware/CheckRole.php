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
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect('/login');
        }

        $user = Auth::user();

        if (! in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message'   => 'Forbidden.',
                    'your_role' => $user->role,
                    'required'  => $roles,
                ], 403);
            }
            return response()->view('errors.403', [
                'yourRole' => $user->role,
                'required' => $roles,
            ], 403);
        }

        return $next($request);
    }
}