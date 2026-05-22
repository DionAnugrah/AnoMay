<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Penjual
            $table->date('date'); // Tanggal jualan
            $table->integer('qty_sold'); // Jumlah laku (diisi penjual)
            $table->integer('qty_returned'); // Sisa somay (Otomatis: stok pagi - laku)
            $table->integer('total_deposit'); // Uang setor (Otomatis: laku x harga)
            $table->enum('status', ['pending', 'accepted'])->default('pending'); // Kalau admin udah terima uang, ubah ke 'accepted'
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
