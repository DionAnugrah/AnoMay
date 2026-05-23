<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login penjual/admin/boss via username & password.
     * POST /login
     * Body: { "username": "...", "password": "..." }
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, remember: false)) {
            return response()->json([
                'message' => 'Username atau password salah.',
            ], 401);
        }

        // Regenerate session agar aman dari session fixation
        $request->session()->regenerate();

        $user = Auth::user();

        return response()->json([
            'message' => 'Login berhasil.',
            'user' => [
                'id'   => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
        ]);
    }

    /**
     * Logout user yang sedang login.
     * POST /logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Ambil data user yang sedang login.
     * GET /me
     */
    public function me()
    {
        $user = Auth::user();

        return response()->json([
            'user' => [
                'id'   => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
        ]);
    }
}