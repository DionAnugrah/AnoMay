<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan semua penjual.
     * GET /admin/users
     */
    public function index()
    {
        $users = User::where('role', 'penjual')
            ->select('id', 'name', 'username', 'role', 'created_at')
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $users,
        ]);
    }

    /**
     * Tampilkan detail satu penjual.
     * GET /admin/users/{id}
     */
    public function show(User $user)
    {
        return response()->json([
            'data' => $user->only('id', 'name', 'username', 'role', 'created_at'),
        ]);
    }

    /**
     * Tambah penjual baru.
     * POST /admin/users
     * Body: { "name": "...", "username": "...", "password": "...", "role": "penjual" }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', Rule::in(['admin', 'boss', 'penjual'])],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return response()->json([
            'message' => 'User berhasil ditambahkan.',
            'data'    => $user->only('id', 'name', 'username', 'role', 'created_at'),
        ], 201);
    }

    /**
     * Edit data penjual.
     * PUT /admin/users/{id}
     * Body: { "name": "...", "username": "...", "password": "..." } — semua opsional
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['sometimes', 'string', 'max:255'],
            'username' => ['sometimes', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => ['sometimes', 'string', 'min:8'],
            'role'     => ['sometimes', Rule::in(['admin', 'boss', 'penjual'])],
        ]);

        // Hash password baru kalau diisi
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User berhasil diupdate.',
            'data'    => $user->fresh()->only('id', 'name', 'username', 'role', 'created_at'),
        ]);
    }

    /**
     * Hapus penjual.
     * DELETE /admin/users/{id}
     */
    public function destroy(User $user)
    {
        // Cegah admin hapus dirinya sendiri
        if ($user->id === auth()->user()->id) {
            return response()->json([
                'message' => 'Tidak bisa menghapus akun sendiri.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus.',
        ]);
    }
}