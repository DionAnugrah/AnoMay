<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAllocation extends Model
{
    protected $guarded = ['id'];

    // Relasi balik ke Penjual dan Produk
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}