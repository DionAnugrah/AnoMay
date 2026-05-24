<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\DailyReport;
use App\Models\StockAllocation;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // =========================================================
    // ADMIN
    // =========================================================

    /**
     * GET /admin/dashboard
     * Ringkasan: total penjual, produk, laporan hari ini, setoran pending.
     */
    public function adminDashboard()
    {
        $today = today()->toDateString();

        return response()->json([
            'total_penjual'     => User::where('role', 'penjual')->count(),
            'total_produk'      => Product::count(),
            'laporan_hari_ini'  => DailyReport::whereDate('date', $today)->count(),
            'pending_hari_ini'  => DailyReport::whereDate('date', $today)->where('status', 'pending')->count(),
            'setoran_hari_ini'  => DailyReport::whereDate('date', $today)->where('status', 'accepted')->sum('total_deposit'),
        ]);
    }

    // =========================================================
    // BOSS
    // =========================================================

    /**
     * GET /boss/dashboard
     */
    public function bossDashboard()
    {
        $today = today()->toDateString();

        return response()->json([
            'setoran_hari_ini'   => DailyReport::whereDate('date', $today)->where('status', 'accepted')->sum('total_deposit'),
            'terjual_hari_ini'   => DailyReport::whereDate('date', $today)->where('status', 'accepted')->sum('qty_sold'),
            'penjual_aktif'      => DailyReport::whereDate('date', $today)->distinct('user_id')->count('user_id'),
            'laporan_pending'    => DailyReport::where('status', 'pending')->count(),
        ]);
    }

    /**
     * GET /boss/sales?date=&bulan=&tahun=
     * Rekap penjualan per penjual.
     */
    public function sales(Request $request)
    {
        $query = DailyReport::with('user:id,name')
            ->where('status', 'accepted');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('date', $request->bulan)->whereYear('date', $request->tahun);
        } else {
            $query->whereDate('date', today());
        }

        $data = $query->get()->groupBy('user_id')->map(function ($reports) {
            $user = $reports->first()->user;
            return [
                'penjual'       => $user->name,
                'total_terjual' => $reports->sum('qty_sold'),
                'total_setoran' => $reports->sum('total_deposit'),
                'hari_lapor'    => $reports->count(),
            ];
        })->values();

        return response()->json(['data' => $data]);
    }

    /**
     * GET /boss/performance?bulan=&tahun=
     * Performa tiap penjual dalam sebulan.
     */
    public function performance(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $data = DailyReport::with('user:id,name')
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->where('status', 'accepted')
            ->get()
            ->groupBy('user_id')
            ->map(function ($reports) use ($bulan, $tahun) {
                $user = $reports->first()->user;
                $totalHari = now()->setMonth($bulan)->setYear($tahun)->daysInMonth;
                $hariAktif = $reports->groupBy('date')->count();
                return [
                    'penjual'        => $user->name,
                    'hari_aktif'     => $hariAktif,
                    'total_hari'     => $totalHari,
                    'kehadiran_pct'  => round($hariAktif / $totalHari * 100) . '%',
                    'total_terjual'  => $reports->sum('qty_sold'),
                    'total_setoran'  => $reports->sum('total_deposit'),
                    'rata_per_hari'  => $hariAktif > 0 ? round($reports->sum('qty_sold') / $hariAktif) : 0,
                ];
            })->values();

        return response()->json(['bulan' => $bulan, 'tahun' => $tahun, 'data' => $data]);
    }

    /**
     * GET /boss/profit?bulan=&tahun=
     * Total keuntungan (setoran diterima).
     */
    public function profit(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $perHari = DailyReport::whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->where('status', 'accepted')
            ->selectRaw('date, SUM(qty_sold) as qty, SUM(total_deposit) as setoran')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'total_setoran'  => $perHari->sum('setoran'),
            'total_terjual'  => $perHari->sum('qty'),
            'per_hari'       => $perHari,
        ]);
    }

    /**
     * GET /boss/bonus?bulan=&tahun=
     * Rekomendasi bonus berdasarkan performa.
     */
    public function bonus(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $data = DailyReport::with('user:id,name')
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->where('status', 'accepted')
            ->get()
            ->groupBy('user_id')
            ->map(function ($reports) {
                $user        = $reports->first()->user;
                $totalJual   = $reports->sum('qty_sold');
                $totalSetor  = $reports->sum('total_deposit');
                $hariAktif   = $reports->groupBy('date')->count();

                // Kriteria bonus sederhana: terjual > 80/hari rata-rata
                $rataHari    = $hariAktif > 0 ? $totalJual / $hariAktif : 0;
                $layakBonus  = $rataHari >= 80;

                return [
                    'penjual'       => $user->name,
                    'hari_aktif'    => $hariAktif,
                    'total_terjual' => $totalJual,
                    'total_setoran' => $totalSetor,
                    'rata_per_hari' => round($rataHari),
                    'layak_bonus'   => $layakBonus,
                    'rekomendasi'   => $layakBonus ? '✅ Layak bonus' : '❌ Belum memenuhi target',
                ];
            })->values();

        return response()->json(['bulan' => $bulan, 'tahun' => $tahun, 'data' => $data]);
    }

    // =========================================================
    // PENJUAL
    // =========================================================

    /**
     * GET /penjual/dashboard
     * Ringkasan penjual yang login.
     */
    public function penjualDashboard()
    {
        $userId = auth()->user()->id;
        $today  = today()->toDateString();

        $stokHariIni = StockAllocation::where('user_id', $userId)
            ->whereDate('date', $today)
            ->with('product:id,name')
            ->get();

        $laporanHariIni = DailyReport::where('user_id', $userId)
            ->whereDate('date', $today)
            ->get();

        return response()->json([
            'stok_hari_ini'     => $stokHariIni,
            'sudah_lapor'       => $laporanHariIni->isNotEmpty(),
            'total_setoran'     => $laporanHariIni->sum('total_deposit'),
            'total_terjual'     => $laporanHariIni->sum('qty_sold'),
        ]);
    }

    /**
     * GET /penjual/income?bulan=&tahun=
     * Riwayat penghasilan (setoran) penjual per bulan.
     */
    public function income(Request $request)
    {
        $userId = auth()->user()->id;
        $bulan  = $request->input('bulan', now()->month);
        $tahun  = $request->input('tahun', now()->year);

        $data = DailyReport::where('user_id', $userId)
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->orderBy('date')
            ->get(['date', 'qty_sold', 'qty_returned', 'total_deposit', 'status']);

        return response()->json([
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'total_setoran'  => $data->sum('total_deposit'),
            'total_terjual'  => $data->sum('qty_sold'),
            'data'           => $data,
        ]);
    }
}
=======
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // Menghitung statistik operasional harian
        $totalPenjual = \App\Models\User::where('role', 'penjual')->count();
        $totalProduk  = \App\Models\Product::count();
        $laporanPending = \App\Models\DailyReport::where('status', 'pending')->count();
        $setoranHariIni = \App\Models\DailyReport::whereDate('date', date('Y-m-d'))
                            ->where('status', 'accepted')
                            ->sum('total_deposit');

        return view('admin.dashboard', compact(
            'totalPenjual', 
            'totalProduk', 
            'laporanPending', 
            'setoranHariIni'
        ));
    }

    public function sellerDashboard()
    {
        // Mengambil alokasi stok panci khusus untuk penjual yang login hari ini
        $alokasiPanci = \App\Models\StockAllocation::with('product')
                            ->where('user_id', auth()->user()->id)
                            ->whereDate('date', date('Y-m-d'))
                            ->get();

        $products = \App\Models\Product::all();
        return view('penjual.dashboard', compact('alokasiPanci', 'products'));
    }
}
>>>>>>> 011c46ae0d607f6ae292dbcb3e7eccdde0345937
