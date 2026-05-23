<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Auth Routes (publik, tidak perlu login)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login'])->name('login');

/*
|--------------------------------------------------------------------------
| Routes yang butuh login
|--------------------------------------------------------------------------
*/
Route::middleware('auth.json')->group(function () {

    // Logout & info user sendiri (semua role boleh)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');

    /*
    |----------------------------------------------------------------------
    | ADMIN
    | Kelola user, sistem, produk, melihat semua data
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Placeholder — isi dengan controller yang sesuai nanti
        Route::get('/dashboard', fn () => response()->json(['area' => 'Admin Dashboard']))->name('dashboard');
        Route::get('/users',     fn () => response()->json(['area' => 'Kelola User']))->name('users.index');
        Route::get('/products',  fn () => response()->json(['area' => 'Kelola Produk']))->name('products.index');
    });

    /*
    |----------------------------------------------------------------------
    | BOSS
    | Melihat penjualan, performa penjual, laporan, rekomendasi bonus
    |----------------------------------------------------------------------
    */
    Route::middleware('role:boss')->prefix('boss')->name('boss.')->group(function () {
        Route::get('/dashboard',    fn () => response()->json(['area' => 'Boss Dashboard']))->name('dashboard');
        Route::get('/sales',        fn () => response()->json(['area' => 'Data Penjualan']))->name('sales');
        Route::get('/performance',  fn () => response()->json(['area' => 'Performa Penjual']))->name('performance');
        Route::get('/profit',       fn () => response()->json(['area' => 'Laporan Keuntungan']))->name('profit');
        Route::get('/bonus',        fn () => response()->json(['area' => 'Rekomendasi Bonus']))->name('bonus');
    });

    /*
    |----------------------------------------------------------------------
    | PENJUAL
    | Input transaksi, update lokasi, stok sendiri, penghasilan harian
    |----------------------------------------------------------------------
    */
    Route::middleware('role:penjual')->prefix('penjual')->name('penjual.')->group(function () {
        Route::get('/dashboard',    fn () => response()->json(['area' => 'Penjual Dashboard']))->name('dashboard');
        Route::get('/stock',        fn () => response()->json(['area' => 'Stok Saya']))->name('stock');
        Route::get('/income',       fn () => response()->json(['area' => 'Penghasilan Harian']))->name('income');
        Route::post('/transaction', fn () => response()->json(['area' => 'Input Transaksi']))->name('transaction.store');
        Route::post('/location',    fn () => response()->json(['area' => 'Update Lokasi']))->name('location.update');
    });

    /*
    |----------------------------------------------------------------------
    | ADMIN bisa akses semua area di atas juga
    | Tambahkan 'admin' ke middleware role kalau perlu cross-access
    |----------------------------------------------------------------------
    */
});