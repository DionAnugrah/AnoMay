<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SawService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BossDashboardController extends Controller
{
    /**
     * Perhitungan ringkasan penjualan umum (/boss/sales)
     */
    protected $sawService;

    // Inject SawService ke dalam Controller
    public function __construct(SawService $sawService)
    {
        $this->sawService = $sawService;
    }

    public function index()
    {
        // Hitung peringkat penjual berdasarkan algoritma SAW
        $sawRankings = $this->sawService->calculateRanking();

        // Kirim data peringkat ke view dashboard boss
        return view('boss.dashboard', compact('sawRankings'));
    }

    public function salesSummary()
    {
        // Total omset akumulatif dari laporan yang sudah disetujui (accepted)
        $totalOmsetGlobal = DB::table('daily_reports')->where('status', 'accepted')->sum('total_deposit');

        // Total omset khusus hari ini
        $omsetHariIni = DB::table('daily_reports')->whereDate('date', Carbon::today())->sum('total_deposit');

        // Total produk somay yang berhasil terjual secara kumulatif
        $totalSomayTerjual = DB::table('daily_reports')->sum('qty_sold');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total_omset_global'   => (int) $totalOmsetGlobal,
                'omset_hari_ini'       => (int) $omsetHariIni,
                'total_somay_terjual'  => (int) $totalSomayTerjual,
            ]
        ]);
    }

    /**
     * Perhitungan performa penjualan per nama penjual (/boss/performance)
     */
    public function performanceSummary()
    {
        $performa = DB::table('daily_reports')
            ->join('users', 'daily_reports.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('SUM(daily_reports.qty_sold) as total_porsi_terjual'),
                DB::raw('SUM(daily_reports.total_deposit) as total_setoran')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_setoran', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $performa
        ]);
    }

    /**
     * Perhitungan keuntungan bersih bulanan (/boss/profit)
     */
    public function profitSummary()
    {
        // Menghitung omset kotor bulanan berjalan
        $omsetBulanIni = DB::table('daily_reports')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->where('status', 'accepted')
            ->sum('total_deposit');

        // Simulasi hitung keuntungan bersih (misal keuntungan bersih adalah 40% dari omset setoran)
        $keuntunganBersihEstimasi = $omsetBulanIni * 0.4;

        return response()->json([
            'status' => 'success',
            'data'   => [
                'bulan_berjalan'    => Carbon::now()->translatedFormat('F Y'),
                'omset_kotor'       => (int) $omsetBulanIni,
                'estimasi_profit'   => (int) $keuntunganBersihEstimasi,
            ]
        ]);
    }
}