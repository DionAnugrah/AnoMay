<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SawService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BossDashboardController extends Controller
{
    protected $sawService;

    public function __construct(SawService $sawService)
    {
        $this->sawService = $sawService;
    }

    public function index()
    {
        $sawRankings = $this->sawService->calculateRanking();
        return view('boss.dashboard', compact('sawRankings'));
    }

    public function salesSummary()
    {
        $totalOmsetGlobal = DB::table('daily_reports')->where('status', 'accepted')->sum('total_deposit');
        $omsetHariIni = DB::table('daily_reports')->whereDate('date', Carbon::today())->sum('total_deposit');
        $totalSomayTerjual = DB::table('daily_reports')->sum('qty_sold');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total_omset_global'  => (int) $totalOmsetGlobal,
                'omset_hari_ini'      => (int) $omsetHariIni,
                'total_somay_terjual' => (int) $totalSomayTerjual,
            ]
        ]);
    }

    public function performanceSummary()
    {
        $performa = DB::table('daily_reports')
            ->join('users', 'daily_reports.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('SUM(daily_reports.qty_sold) as total_porsi_terjual'),
                DB::raw('SUM(daily_reports.total_deposit) as total_setoran'),
                DB::raw('SUM(CASE WHEN DATE(daily_reports.date) = CURDATE() THEN daily_reports.qty_sold ELSE 0 END) as total_terjual')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_setoran', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $performa]);
    }

    public function profitSummary()
    {
        $omsetBulanIni = DB::table('daily_reports')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->where('status', 'accepted')
            ->sum('total_deposit');

        $keuntunganBersihEstimasi = $omsetBulanIni * 0.4;

        return response()->json([
            'status' => 'success',
            'data'   => [
                'bulan_berjalan'  => Carbon::now()->translatedFormat('F Y'),
                'omset_kotor'     => (int) $omsetBulanIni,
                'estimasi_profit' => (int) $keuntunganBersihEstimasi,
            ]
        ]);
    }

    public function omsetBulanan()
    {
        $data = DB::table('daily_reports')
            ->where('status', 'accepted')
            ->selectRaw('MONTH(date) as bulan, YEAR(date) as tahun, SUM(total_deposit) as total')
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->orderByRaw('YEAR(date), MONTH(date)')
            ->get();

        return response()->json(['status' => 'success', 'data' => $data]);
    }
}