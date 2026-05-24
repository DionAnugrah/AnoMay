<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LiveMapController extends Controller
{
    /**
     * Tampilkan halaman peta live.
     */
    public function index()
    {
        return view('boss.peta-penjual');
    }

    /**
     * Ambil lokasi terbaru semua penjual beserta status dan data penjualan hari ini.
     * GET /boss/penjual/lokasi
     */
    public function lokasi()
    {
        $today = Carbon::today();

        // Ambil lokasi terbaru per penjual (1 baris per user_id)
        $lokasi = Location::with('user')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                      ->from('locations')
                      ->groupBy('user_id');
            })
            ->get();

        // Ambil penjualan hari ini per user
        $jualHariIni = DailyReport::whereDate('date', $today)
            ->where('status', 'accepted')
            ->select('user_id', DB::raw('SUM(total_deposit) as total_jual'), DB::raw('SUM(qty_sold) as total_qty'))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $hasil = $lokasi->map(function ($loc) use ($jualHariIni, $today) {
            $menit      = Carbon::parse($loc->updated_at)->diffInMinutes(now());
            $jual       = $jualHariIni->get($loc->user_id);

            // Tentukan status berdasarkan kapan terakhir kirim lokasi
            if ($menit <= 10) {
                $status = 'aktif';
            } elseif ($menit <= 60) {
                $status = 'idle';
            } else {
                $status = 'offline';
            }

            // Format waktu update
            if ($menit < 1) {
                $updateLabel = 'Baru saja';
            } elseif ($menit < 60) {
                $updateLabel = $menit . ' menit lalu';
            } else {
                $jam = floor($menit / 60);
                $updateLabel = $jam . ' jam lalu';
            }

            return [
                'id'     => $loc->user_id,
                'nama'   => $loc->user->name,
                'status' => $status,
                'lat'    => (float) $loc->latitude,
                'lng'    => (float) $loc->longitude,
                'update' => $updateLabel,
                'jual'   => $jual
                    ? 'Rp ' . number_format($jual->total_jual, 0, ',', '.')
                    : 'Belum ada',
                'qty'    => $jual
                    ? $jual->total_qty . ' pcs'
                    : '0 pcs',
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $hasil->values(),
        ]);
    }

    /**
     * Penjual kirim titik GPS dari HP.
     * POST /penjual/lokasi
     * (dipanggil dari JS penjual, bukan boss)
     */
    public function simpanLokasi(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        Location::create([
            'user_id'   => auth()->id(),
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['status' => 'success']);
    }
}