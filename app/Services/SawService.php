<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SawService
{
    public function calculateRanking()
    {
        // 1. Ambil akumulasi data laporan jualan dengan nama kolom fisik hasil check listing
        $reports = DB::table('daily_reports')
            ->join('users', 'daily_reports.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name as penjual_name',
                DB::raw('SUM(qty_sold) as total_sales'),       // Kolom fisik asli: qty_sold
                DB::raw('SUM(qty_returned) as remaining_stock'), // Kolom fisik asli: qty_returned
                DB::raw('SUM(total_deposit) as deposit_amount')   // Kolom fisik asli: total_deposit
            )
            ->groupBy('users.id', 'users.name')
            ->get();

        if ($reports->isEmpty()) {
            return [];
        }

        // 2. Cari nilai Max dan Min untuk normalisasi
        $maxSales = $reports->max('total_sales') ?: 1;
        $minStock = $reports->min('remaining_stock') ?: 1; 
        $maxDeposit = $reports->max('deposit_amount') ?: 1;

        // Tentukan bobot kriteria (Total akumulasi bobot harus 1.0)
        $weightSales = 0.5;
        $weightStock = 0.3;
        $weightDeposit = 0.2;

        $rankingResult = [];

        // 3. Proses Normalisasi & Hitung Nilai Preferensi (V)
        foreach ($reports as $report) {
            // Normalisasi Benefit (Nilai Aktual / Nilai Maksimal)
            $r_sales = $report->total_sales / $maxSales;
            $r_deposit = $report->deposit_amount / $maxDeposit;

            // Normalisasi Cost (Nilai Minimal / Nilai Aktual)
            $r_stock = $report->remaining_stock > 0 ? ($minStock / $report->remaining_stock) : 1;

            // Hitung nilai akhir preferensi SAW (V)
            $v_score = ($r_sales * $weightSales) + ($r_stock * $weightStock) + ($r_deposit * $weightDeposit);

            $rankingResult[] = [
                'user_id' => $report->user_id,
                'name' => $report->penjual_name,
                'detail_aktual' => [
                    'sales' => $report->total_sales,
                    'stock' => $report->remaining_stock,
                    'deposit' => $report->deposit_amount,
                ],
                'skor' => round($v_score, 4)
            ];
        }

        // 4. Urutkan hasil dari skor preferensi tertinggi ke terendah
        usort($rankingResult, function ($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        return $rankingResult;
    }
}