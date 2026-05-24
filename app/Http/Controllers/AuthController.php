<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login penjual/admin/boss via username & password.
     * POST /login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 1. JIKA GAGAL: Lempar kembali ke halaman login membawa pesan error
        if (! Auth::attempt($credentials, $request->has('remember'))) {
            return back()->with('error', 'Username atau password salah.');
        }

        // Regenerate session agar aman dari session fixation
        $request->session()->regenerate();

        $user = Auth::user();

        // 2. JIKA BERHASIL: Arahkan (redirect) ke halaman dashboard masing-masing role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'penjual') {
            return redirect('/penjual/dashboard');
        } elseif ($user->role === 'boss') {
            return redirect('/boss/dashboard');
        }

        return redirect('/');
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
        return redirect('/login');
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