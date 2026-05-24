<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Penjual kirim koordinat GPS.
     * POST /penjual/location
     * Body: { "latitude": -6.2088, "longitude": 106.8456 }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        Location::create([
            'user_id'   => auth()->user()->id,
            'latitude'  => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json(['message' => 'Lokasi berhasil disimpan.']);
    }

    /**
     * Boss/Admin ambil lokasi terakhir tiap penjual.
     * GET /boss/locations  atau  GET /admin/locations
     */
    public function latest()
    {
        // Subquery: ambil id lokasi terbaru per user
        $latest = Location::selectRaw('MAX(id) as id')
            ->groupBy('user_id');

        $locations = Location::with('user:id,name,username')
            ->joinSub($latest, 'latest', fn($j) => $j->on('locations.id', '=', 'latest.id'))
            ->get(['locations.*']);

        return response()->json([
            'data' => $locations->map(fn($l) => [
                'user_id'    => $l->user_id,
                'name'       => $l->user->name,
                'latitude'   => (float) $l->latitude,
                'longitude'  => (float) $l->longitude,
                'updated_at' => $l->created_at->diffForHumans(),
                // aktif = lokasi terakhir dikirim dalam 10 menit terakhir
                'is_active'  => $l->created_at->gt(now()->subMinutes(10)),
            ]),
        ]);
    }

    /**
     * Boss/Admin ambil semua riwayat lokasi satu penjual hari ini (untuk jalur).
     * GET /boss/locations/{userId}/trail
     */
    public function trail(int $userId)
    {
        $points = Location::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->orderBy('id')
            ->get(['latitude', 'longitude', 'created_at']);

        return response()->json([
            'data' => $points->map(fn($p) => [
                'latitude'  => (float) $p->latitude,
                'longitude' => (float) $p->longitude,
                'time'      => $p->created_at->format('H:i'),
            ]),
        ]);
    }
}
