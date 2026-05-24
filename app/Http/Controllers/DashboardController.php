<?php

namespace App\Http\Controllers;
use App\Models\DailyReport;
use App\Models\StockAllocation;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // =========================================================
    // ADMIN
    // =========================================================

    public function adminDashboard()
    {
        $today = today()->toDateString();

        $totalPenjual   = User::where('role', 'penjual')->count();
        $totalProduk    = Product::count();
        $laporanPending = DailyReport::whereDate('date', $today)->where('status', 'pending')->count();
        $setoranHariIni = DailyReport::whereDate('date', $today)->where('status', 'accepted')->sum('total_deposit');
        $stokHariIni    = StockAllocation::whereDate('date', $today)->count();
        $penjualAktif   = StockAllocation::whereDate('date', $today)->distinct('user_id')->count('user_id');

        // Laporan terbaru (5 terakhir)
        $laporanTerbaru = DailyReport::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Alokasi stok hari ini per penjual
        $alokasiHariIni = StockAllocation::with(['user:id,name', 'product:id,name'])
            ->whereDate('date', $today)
            ->get();

        // Grafik: penjualan per produk (semua waktu)
        $grafikProduk = DailyReport::with('stockAllocation.product:id,name')
            ->where('status', 'accepted')
            ->get()
            ->groupBy(fn($r) => $r->stockAllocation?->product?->name ?? 'Lainnya')
            ->map(fn($g) => $g->sum('qty_sold'))
            ->sortDesc();

        // Grafik: penjualan per penjual hari ini
        $grafikPenjual = DailyReport::with('user:id,name')
            ->whereDate('date', $today)
            ->get()
            ->groupBy('user_id')
            ->map(fn($g) => [
                'nama'    => $g->first()->user->name ?? '-',
                'terjual' => $g->sum('qty_sold'),
                'setoran' => $g->sum('total_deposit'),
            ])
            ->values();

        return view('admin.dashboard', compact(
            'totalPenjual', 'totalProduk', 'laporanPending', 'setoranHariIni',
            'stokHariIni', 'penjualAktif', 'laporanTerbaru', 'alokasiHariIni',
            'grafikProduk', 'grafikPenjual'
        ));
    }

    // =========================================================
    // BOSS
    // =========================================================

    public function bossDashboard()
    {
        return view('boss.dashboard');
    }

    public function sales(Request $request)
    {
        // Total omset global semua waktu
        $totalOmsetGlobal = DailyReport::where('status', 'accepted')->sum('total_deposit');
        $omsetHariIni     = DailyReport::whereDate('date', today())->where('status', 'accepted')->sum('total_deposit');
        $somayTerjual     = DailyReport::where('status', 'accepted')->sum('qty_sold');

        // Grafik per produk (semua waktu)
        $perProduk = DailyReport::with('stockAllocation.product:id,name')
            ->where('status', 'accepted')
            ->get()
            ->groupBy(fn($r) => $r->stockAllocation?->product?->name ?? 'Lainnya')
            ->map(fn($g) => $g->sum('qty_sold'))
            ->sortDesc();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total_omset_global'  => $totalOmsetGlobal,
                'omset_hari_ini'      => $omsetHariIni,
                'total_somay_terjual' => $somayTerjual,
                'per_produk'          => $perProduk,
            ],
        ]);
    }

    public function performance(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $data = DailyReport::with('user:id,name')
            ->where('status', 'accepted')
            ->get()->groupBy('user_id')
            ->map(function ($reports) {
                $hariAktif = $reports->groupBy('date')->count();
                return [
                    'name'               => $reports->first()->user->name,
                    'hari_aktif'         => $hariAktif,
                    'total_terjual'      => $reports->sum('qty_sold'),
                    'total_porsi_terjual'=> $reports->sum('qty_sold'),
                    'total_setoran'      => $reports->sum('total_deposit'),
                    'rata_per_hari'      => $hariAktif > 0 ? round($reports->sum('qty_sold') / $hariAktif) : 0,
                ];
            })
            ->sortByDesc('total_setoran')
            ->values();

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function profit(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $omsetKotor = DailyReport::whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->where('status', 'accepted')
            ->sum('total_deposit');

        $namaBulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)
            ->translatedFormat('F Y');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'omset_kotor'      => $omsetKotor,
                'estimasi_profit'  => (int) ($omsetKotor * 0.4),
                'bulan_berjalan'   => $namaBulan,
            ],
        ]);
    }

    public function bonus(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $data = DailyReport::with('user:id,name')
            ->whereMonth('date', $bulan)->whereYear('date', $tahun)
            ->where('status', 'accepted')
            ->get()->groupBy('user_id')
            ->map(function ($reports) {
                $hariAktif  = $reports->groupBy('date')->count();
                $totalJual  = $reports->sum('qty_sold');
                $rataHari   = $hariAktif > 0 ? $totalJual / $hariAktif : 0;
                $layak      = $rataHari >= 80;
                return [
                    'penjual'       => $reports->first()->user->name,
                    'hari_aktif'    => $hariAktif,
                    'total_terjual' => $totalJual,
                    'total_setoran' => $reports->sum('total_deposit'),
                    'rata_per_hari' => round($rataHari),
                    'layak_bonus'   => $layak,
                    'rekomendasi'   => $layak ? '✅ Layak bonus' : '❌ Belum memenuhi target',
                ];
            })->values();

        return response()->json(['bulan' => $bulan, 'tahun' => $tahun, 'data' => $data]);
    }

    // =========================================================
    // PENJUAL
    // =========================================================

    public function penjualDashboard()
    {
        $userId = auth()->user()->id;
        $today  = today()->toDateString();

        $products       = Product::all();
        $stokHariIni    = StockAllocation::where('user_id', $userId)
                            ->whereDate('date', $today)
                            ->with('product:id,name,price')
                            ->get();

        // Tandai alokasi mana yang sudah dilaporkan
        $sudahDilaporkan = DailyReport::where('user_id', $userId)
                            ->whereDate('date', $today)
                            ->pluck('stock_allocation_id')
                            ->toArray();

        $laporanHariIni = DailyReport::where('user_id', $userId)
                            ->whereDate('date', $today)
                            ->with('stockAllocation.product:id,name')
                            ->get();

        return view('penjual.dashboard', compact(
            'products', 'stokHariIni', 'laporanHariIni', 'sudahDilaporkan'
        ));
    }

    public function income(Request $request)
    {
        $userId = auth()->user()->id;
        $bulan  = $request->input('bulan', now()->month);
        $tahun  = $request->input('tahun', now()->year);

        $data = DailyReport::where('user_id', $userId)
            ->whereMonth('date', $bulan)->whereYear('date', $tahun)
            ->orderBy('date')
            ->get(['date', 'qty_sold', 'qty_returned', 'total_deposit', 'status']);

        return response()->json([
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'total_setoran' => $data->sum('total_deposit'),
            'total_terjual' => $data->sum('qty_sold'),
            'data'          => $data,
        ]);
    }
}
