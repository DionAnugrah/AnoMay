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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Penjual
            $table->decimal('latitude', 10, 8); // Titik koordinat Y
            $table->decimal('longitude', 11, 8); // Titik koordinat X
            $table->timestamps(); // create_at dipakai untuk tahu jam berapa lokasi ini direkam
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
