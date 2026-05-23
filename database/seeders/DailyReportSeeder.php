<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyReportSeeder extends Seeder
{
    public function run(): void
    {
        // Mengambil user pertama yang memiliki role penjual
        $penjual = User::where('role', 'penjual')->first();

        if (!$penjual) {
            $this->command->warn("Pastikan sudah ada data user penjual di database kamu!");
            return;
        }

        // Membuat simulasi jualan selama 3 hari ke belakang
        for ($i = 0; $i < 3; $i++) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');

            // Anggap saja ada 2 laporan setoran jualan per hari
            for ($j = 1; $j <= 2; $j++) {
                $qtySold = rand(30, 50); // Jumlah porsi somay yang laku
                $qtyReturned = rand(5, 15); // Sisa somay yang dibawa pulang
                $totalDeposit = $qtySold * 2000; // Contoh harga per biji Rp 2.000

                DB::table('daily_reports')->insert([
                    'user_id'       => $penjual->id,
                    'date'          => $tanggal,
                    'qty_sold'      => $qtySold,
                    'qty_returned'  => $qtyReturned,
                    'total_deposit' => $totalDeposit,
                    'status'        => $i == 0 ? 'pending' : 'accepted', // Hari ini pending, kemarin otomatis accepted
                    'created_at'    => Carbon::now()->subDays($i),
                    'updated_at'    => Carbon::now()->subDays($i),
                ]);
            }
        }
    }
}