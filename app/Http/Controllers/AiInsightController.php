<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Services\AiInsightService;
use Illuminate\Http\Request;

class AiInsightController extends Controller
{
    public function __construct(protected AiInsightService $ai) {}

    // =========================================================
    // ANALISA HARIAN
    // =========================================================

    /**
     * Boss/Admin minta analisa AI untuk penjualan hari tertentu.
     */

    public function index()
    {
        return view('boss.ai.ai-insight');
    }

    public function harianInsight(Request $request)
    {
        $request->validate([
            'date' => 'sometimes|date',
        ]);

        $date  = $request->input('date', now()->toDateString());
        $rekap = $this->buildRekapHarian($date);

        if ($rekap['total_qty_sold'] == 0) {
            return response()->json([
                'message' => 'Belum ada data jualan untuk tanggal ini.',
                'date'    => $date,
            ], 404);
        }

        $prompt  = $this->ai->buildPromptHarian($rekap);
        $analisa = $this->ai->analisa($prompt);

        return response()->json([
            'date'       => $date,
            'rekap'      => $rekap,
            'insight_ai' => $analisa,
        ]);
    }

    // =========================================================
    // ANALISA BULANAN
    // =========================================================

    /**
     * Boss/Admin minta analisa AI untuk penjualan bulan tertentu.
     */
    public function bulananInsight(Request $request)
    {
        $request->validate([
            'bulan' => 'sometimes|integer|min:1|max:12',
            'tahun' => 'sometimes|integer|min:2000',
        ]);

        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $rekap = $this->buildRekapBulanan($bulan, $tahun);

        if ($rekap['total_qty_sold'] == 0) {
            return response()->json([
                'message' => 'Belum ada data jualan untuk periode ini.',
                'periode' => "{$bulan}/{$tahun}",
            ], 404);
        }

        $prompt  = $this->ai->buildPromptBulanan($rekap);
        $analisa = $this->ai->analisa($prompt);

        return response()->json([
            'periode'    => "{$bulan}/{$tahun}",
            'rekap'      => $rekap,
            'insight_ai' => $analisa,
        ]);
    }

    // =========================================================
    // PRIVATE: Builder Rekap Data
    // =========================================================

    /**
     * Kumpulkan & susun data harian dari tabel daily_reports.
     */
    private function buildRekapHarian(string $date): array
    {
        $reports = DailyReport::with('user')
                    ->where('date', $date)
                    ->where('status', 'accepted') // hanya yang sudah dikonfirmasi admin
                    ->get();

        $perPenjual = $reports->map(fn($r) => [
            'nama'          => $r->user->name,
            'qty_sold'      => $r->qty_sold,
            'total_deposit' => number_format($r->total_deposit, 0, ',', '.'),
        ])->toArray();

        return [
            'date'           => $date,
            'total_qty_sold' => $reports->sum('qty_sold'),
            'total_deposit'  => number_format($reports->sum('total_deposit'), 0, ',', '.'),
            'per_penjual'    => $perPenjual,
        ];
    }

    /**
     * Kumpulkan & susun data bulanan dari tabel daily_reports.
     */
    private function buildRekapBulanan(int $bulan, int $tahun): array
    {
        $reports = DailyReport::whereMonth('date', $bulan)
                    ->whereYear('date', $tahun)
                    ->where('status', 'accepted')
                    ->get();

        $hariAktif  = $reports->groupBy('date')->count();
        $totalSold  = $reports->sum('qty_sold');
        $rataRata   = $hariAktif > 0 ? round($totalSold / $hariAktif) : 0;

        return [
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'total_qty_sold' => $totalSold,
            'total_deposit'  => number_format($reports->sum('total_deposit'), 0, ',', '.'),
            'hari_aktif'     => $hariAktif,
            'rata_rata'      => $rataRata,
        ];
    }
}