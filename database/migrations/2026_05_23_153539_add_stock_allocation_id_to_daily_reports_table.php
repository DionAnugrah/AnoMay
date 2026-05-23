<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            // Tiap baris laporan sekarang terikat ke satu alokasi stok (per produk)
            $table->foreignId('stock_allocation_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('stock_allocations')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropForeign(['stock_allocation_id']);
            $table->dropColumn('stock_allocation_id');
        });
    }
};
