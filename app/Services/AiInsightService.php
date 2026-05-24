<?php
// app/Services/AiInsightService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiInsightService
{
    /**
     * Kirim prompt ke Gemini dan ambil balasannya.
     */
        public function analisa(string $prompt): string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='
            . env('GEMINI_API_KEY'),
            [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]
        );

        if ($response->failed()) {
            return 'Error ' . $response->status() . ': ' . $response->body();
        }

        return $response->json('candidates.0.content.parts.0.text')
            ?? 'AI tidak menghasilkan respons.';
    }

    /**
     * Bangun prompt harian dari data rekap.
     */
    public function buildPromptHarian(array $rekap): string
    {
        $detail = '';
        foreach ($rekap['per_penjual'] as $item) {
            $detail .= "- {$item['nama']}: terjual {$item['qty_sold']} pcs,"
                     . " setoran Rp {$item['total_deposit']}\n";
        }

        return "
        Kamu adalah analis bisnis untuk usaha somay keliling.
        Berikut rekap penjualan hari ini ({$rekap['date']}):

        Total seluruh penjual  : {$rekap['total_qty_sold']} pcs
        Total setoran hari ini : Rp {$rekap['total_deposit']}

        Detail per penjual:
        {$detail}

        Balas HANYA dengan JSON valid berikut, tanpa teks lain, tanpa markdown, tanpa backtick:
        {
            \"analisa\": \"ringkasan performa hari ini 2-3 kalimat\",
            \"prediksi\": \"prediksi jualan besok berdasarkan tren\",
            \"peringatan\": [\"peringatan 1\", \"peringatan 2\"],
            \"saran\": [\"saran 1\", \"saran 2\", \"saran 3\"]
        }
        ";
    }

    /**
     * Bangun prompt bulanan dari data rekap.
     */
    public function buildPromptBulanan(array $rekap): string
    {
        return "
        Kamu adalah analis bisnis untuk usaha somay keliling.
        Berikut rekap penjualan bulan {$rekap['bulan']}/{$rekap['tahun']}:

        Total terjual  : {$rekap['total_qty_sold']} pcs
        Total setoran  : Rp {$rekap['total_deposit']}
        Hari aktif     : {$rekap['hari_aktif']} hari
        Rata-rata/hari : {$rekap['rata_rata']} pcs

        Balas HANYA dengan JSON valid berikut, tanpa teks lain, tanpa markdown, tanpa backtick:
        {
            \"analisa\": \"analisa performa bulan ini 2-3 kalimat\",
            \"prediksi\": \"tren penjualan naik atau turun dan alasannya\",
            \"peringatan\": [\"peringatan 1\", \"peringatan 2\"],
            \"saran\": [\"saran 1\", \"saran 2\", \"saran 3\"]
        }
        ";
    }
}