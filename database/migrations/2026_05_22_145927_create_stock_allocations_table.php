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
        Schema::create('stock_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Penjual
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // ID Produk
            $table->date('date'); // Tanggal bawa stok
            $table->integer('qty_given'); // Jumlah somay yang dibawa pagi hari
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_allocations');
    }
};
