<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bikin Akun Admin
        User::create([
            'name' => 'Adnan Admin Anomay',
            'username' => 'adnan',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        // 2. Bikin Akun Boss
        User::create([
            'name' => 'Reynald Boss Anomay',
            'username' => 'reynald',
            'password' => Hash::make('12345678'),
            'role' => 'boss'
        ]);

        // 3. Bikin Akun Penjual (Biar UI/UX bisa dites)
        User::create([
            'name' => 'Mas Dika',
            'username' => 'dika',
            'password' => Hash::make('12345678'),
            'role' => 'penjual'
        ]);

        // 4. Bikin Harga Somay Awal
        Product::create([
            'name' => 'Somay kecil',
            'price' => 1000
        ]);
    }
}