<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function stockAllocation() {
        return $this->belongsTo(StockAllocation::class);
    }
}