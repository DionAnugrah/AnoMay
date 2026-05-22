<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relasi ke tabel lain
    public function stockAllocations() {
        return $this->hasMany(StockAllocation::class);
    }
    
    public function dailyReports() {
        return $this->hasMany(DailyReport::class);
    }
    
    public function locations() {
        return $this->hasMany(Location::class);
    }
}