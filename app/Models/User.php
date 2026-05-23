<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Override field login dari 'email' ke 'username'.
     * getAuthIdentifierName tetap 'id' agar session menyimpan ID integer,
     * bukan string username.
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    // -------------------------------------------------------------------------
    // Relasi
    // -------------------------------------------------------------------------

    public function stockAllocations()
    {
        return $this->hasMany(StockAllocation::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    // -------------------------------------------------------------------------
    // Helper role — opsional, berguna di controller/blade nanti
    // -------------------------------------------------------------------------

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBoss(): bool
    {
        return $this->role === 'boss';
    }

    public function isPenjual(): bool
    {
        return $this->role === 'penjual';
    }
}