<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LaporJualanController;
use App\Http\Controllers\AiInsightController;

/*
|--------------------------------------------------------------------------
| 1. RUTE TAMPILAN UI (FRONTEND)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('login');
})->name('login.view');

Route::get('/admin', function () {
    return view('layouts.admin'); 
})->name('admin.view');

Route::get('/penjual', function () {
    return view('layouts.seller');
})->name('penjual.view');


/*
|--------------------------------------------------------------------------
| 2. RUTE PROSES & AUTENTIKASI (BACKEND)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth.json')->group(function () {

    // Global Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');

    /*
    |----------------------------------------------------------------------
    | A. PANEL ADMIN
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn () => response()->json(['area' => 'Admin Dashboard']))->name('dashboard');

        // Manajemen Pengguna
        Route::get('/users',           [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}',    [UserController::class, 'show'])->name('users.show');
        Route::post('/users',          [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',    [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Manajemen Produk
        Route::get('/products',           [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::post('/products',          [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Manajemen Laporan
        Route::get('/laporan',                  [LaporJualanController::class, 'indexAdmin'])->name('laporan.index');
        Route::get('/laporan/{report}',         [LaporJualanController::class, 'showAdmin'])->name('laporan.show');
        Route::put('/laporan/{report}',         [LaporJualanController::class, 'update'])->name('laporan.update');
        Route::patch('/laporan/{report}/status', [LaporJualanController::class, 'updateStatus'])->name('laporan.status');
        Route::delete('/laporan/{report}',      [LaporJualanController::class, 'destroy'])->name('laporan.destroy');

        // AI Insight
        Route::get('/ai/insight/harian',  [AiInsightController::class, 'harianInsight'])->name('ai.harian');
        Route::get('/ai/insight/bulanan', [AiInsightController::class, 'bulananInsight'])->name('ai.bulanan');
    });

    /*
    |----------------------------------------------------------------------
    | B. PANEL BOSS
    |----------------------------------------------------------------------
    */
    Route::middleware('role:boss')->prefix('boss')->name('boss.')->group(function () {
        Route::get('/dashboard',   fn () => response()->json(['area' => 'Boss Dashboard']))->name('dashboard');
        Route::get('/sales',       fn () => response()->json(['area' => 'Data Penjualan']))->name('sales');
        Route::get('/performance', fn () => response()->json(['area' => 'Performa Penjual']))->name('performance');
        Route::get('/profit',      fn () => response()->json(['area' => 'Laporan Keuntungan']))->name('profit');
        Route::get('/bonus',       fn () => response()->json(['area' => 'Rekomendasi Bonus']))->name('bonus');
        
        // AI Insight
        Route::get('/ai/insight/harian',  [AiInsightController::class, 'harianInsight'])->name('ai.harian');
        Route::get('/ai/insight/bulanan', [AiInsightController::class, 'bulananInsight'])->name('ai.bulanan');
    });

    /*
    |----------------------------------------------------------------------
    | C. PANEL PENJUAL
    |----------------------------------------------------------------------
    */
    Route::middleware('role:penjual')->prefix('penjual')->name('penjual.')->group(function () {
        Route::get('/dashboard',        fn () => response()->json(['area' => 'Penjual Dashboard']))->name('dashboard');
        Route::get('/stock',            fn () => response()->json(['area' => 'Stok Saya']))->name('stock');
        Route::get('/income',           fn () => response()->json(['area' => 'Penghasilan Harian']))->name('income');
        Route::post('/transaction',     fn () => response()->json(['area' => 'Input Transaksi']))->name('transaction.store');
        Route::post('/location',        fn () => response()->json(['area' => 'Update Lokasi']))->name('location.update');
        
        // Laporan Jualan
        Route::post('/lapor-jualan',    [LaporJualanController::class, 'store'])->name('laporan.store');
        Route::get('/riwayat-jualan',   [LaporJualanController::class, 'riwayat'])->name('laporan.riwayat');
    });
});