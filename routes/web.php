<?php

use Illuminate\Support\Facades\Route;

// 1. Rute untuk Halaman Login
Route::get('/login', function () {
    return view('login');
});

// 2 & 3. Rute untuk Halaman Admin (Master Data & Alokasi)
Route::get('/admin', function () {
    // Pastikan nama file view Anda sesuai, misal: admin.blade.php di dalam folder layouts/ atau root views/
    // Jika file ada di folder layouts, gunakan: return view('layouts.admin');
    return view('layouts.admin'); 
});

// 4. Rute untuk Halaman Penjual (HP)
Route::get('/penjual', function () {
    // Jika file ada di folder layouts, gunakan: return view('layouts.seller');
    return view('layouts.seller');
});
