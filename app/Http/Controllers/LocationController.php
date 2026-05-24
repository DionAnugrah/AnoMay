<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Penjual kirim koordinat GPS (is_tracking = true).
     * POST /penjual/location  |  POST /penjual/lokasi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $userId = auth()->user()->id;

        // Tandai semua lokasi lama milik user ini sebagai tidak aktif
        Location::where('user_id', $userId)->update(['is_tracking' => false]);

        // Simpan titik baru dengan is_tracking = true
        Location::create([
            'user_id'     => $userId,
            'latitude'    => $validated['latitude'],
            'longitude'   => $validated['longitude'],
            'is_tracking' => true,
        ]);

        return response()->json(['message' => 'Lokasi berhasil disimpan.']);
    }

    /**
     * Penjual berhenti keliling — tandai semua lokasinya is_tracking = false.
     * POST /penjual/location/stop
     */
    public function stop()
    {
        Location::where('user_id', auth()->user()->id)
            ->update(['is_tracking' => false]);

        return response()->json(['message' => 'Tracking dihentikan.']);
    }

    /**
     * Boss/Admin ambil lokasi terakhir tiap penjual.
     * Status: aktif = is_tracking true & update < 15 menit
     *         idle  = is_tracking false tapi update < 2 jam
     *         offline = sisanya
     */
    public function latest()
    {
        $latest = Location::selectRaw('MAX(id) as id')->groupBy('user_id');

        $locations = Location::with('user:id,name')
            ->joinSub($latest, 'latest', fn($j) => $j->on('locations.id', '=', 'latest.id'))
            ->get(['locations.*']);

        $data = $locations->map(function ($l) {
            $recentEnough = $l->created_at->gt(now()->subMinutes(15));

            // Aktif = penjual masih tracking DAN lokasi terakhir < 15 menit
            if ($l->is_tracking && $recentEnough) {
                $status = 'aktif';
            } elseif ($l->created_at->gt(now()->subHours(2))) {
                $status = 'idle';
            } else {
                $status = 'offline';
            }

            $laporan = \App\Models\DailyReport::where('user_id', $l->user_id)
                ->whereDate('date', today())->get();

            return [
                'id'         => $l->user_id,
                'nama'       => $l->user->name,
                'lat'        => (float) $l->latitude,
                'lng'        => (float) $l->longitude,
                'status'     => $status,
                'update'     => $l->created_at->diffForHumans(),
                'jual'       => 'Rp ' . number_format($laporan->sum('total_deposit'), 0, ',', '.'),
                'qty'        => $laporan->sum('qty_sold') . ' pcs',
                // untuk test.html
                'user_id'    => $l->user_id,
                'name'       => $l->user->name,
                'latitude'   => (float) $l->latitude,
                'longitude'  => (float) $l->longitude,
                'is_active'  => $status === 'aktif',
                'updated_at' => $l->created_at->diffForHumans(),
            ];
        });

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    /**
     * Jalur perjalanan penjual hari ini.
     * GET /boss/locations/{userId}/trail
     */
    public function trail(int $userId)
    {
        $points = Location::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->orderBy('id')
            ->get(['latitude', 'longitude', 'created_at', 'is_tracking']);

        return response()->json([
            'data' => $points->map(fn($p) => [
                'latitude'    => (float) $p->latitude,
                'longitude'   => (float) $p->longitude,
                'time'        => $p->created_at->format('H:i:s'),
                'is_tracking' => (bool) $p->is_tracking,
            ]),
        ]);
    }
}
