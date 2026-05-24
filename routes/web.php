<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LaporJualanController;
use App\Http\Controllers\AiInsightController;
use App\Http\Controllers\StockAllocationController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Auth Routes (publik)
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/test.html'));
Route::post('/login', [AuthController::class, 'login'])->name('login');

/*
|--------------------------------------------------------------------------
| Routes yang butuh login
|--------------------------------------------------------------------------
*/
Route::middleware('auth.json')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');

    /*
    |----------------------------------------------------------------------
    | ADMIN — Kelola user, produk, melihat semua data
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // CRUD Penjual
        Route::get('/users',           [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}',    [UserController::class, 'show'])->name('users.show');
        Route::post('/users',          [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',    [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // CRUD Produk
        Route::get('/products',              [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}',    [ProductController::class, 'show'])->name('products.show');
        Route::post('/products',             [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}',    [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Alokasi Stok Pagi
        Route::get('/stock-allocations',                    [StockAllocationController::class, 'index'])->name('stock.index');
        Route::post('/stock-allocations',                   [StockAllocationController::class, 'store'])->name('stock.store');
        Route::put('/stock-allocations/{stockAllocation}',  [StockAllocationController::class, 'update'])->name('stock.update');
        Route::delete('/stock-allocations/{stockAllocation}', [StockAllocationController::class, 'destroy'])->name('stock.destroy');

        //Edit dan Hapus Laporan Penjualan
        Route::get('/laporan',                      [LaporJualanController::class, 'indexAdmin'])->name('laporan.index');
        Route::get('/laporan/{report}',             [LaporJualanController::class, 'showAdmin'])->name('laporan.show');
        Route::put('/laporan/{report}',             [LaporJualanController::class, 'update'])->name('laporan.update');
        Route::patch('/laporan/{report}/status',    [LaporJualanController::class, 'updateStatus'])->name('laporan.status');
        Route::delete('/laporan/{report}',          [LaporJualanController::class, 'destroy'])->name('laporan.destroy');

        //Laporan Analisis AI
        Route::get('/ai/insight/harian',  [AiInsightController::class, 'harianInsight'])->name('ai.harian');
        Route::get('/ai/insight/bulanan', [AiInsightController::class, 'bulananInsight'])->name('ai.bulanan');
        Route::get('/locations',                    [LocationController::class, 'latest'])->name('locations');
        Route::get('/locations/{userId}/trail',     [LocationController::class, 'trail'])->name('locations.trail');
    });

    /*
    |----------------------------------------------------------------------
    | BOSS — Melihat penjualan, performa, laporan, bonus
    |----------------------------------------------------------------------
    */
    Route::middleware('role:boss')->prefix('boss')->name('boss.')->group(function () {
        Route::get('/dashboard',   [DashboardController::class, 'bossDashboard'])->name('dashboard');
        Route::get('/sales',       [DashboardController::class, 'sales'])->name('sales');
        Route::get('/performance', [DashboardController::class, 'performance'])->name('performance');
        Route::get('/profit',      [DashboardController::class, 'profit'])->name('profit');
        Route::get('/bonus',       [DashboardController::class, 'bonus'])->name('bonus');
        Route::get('/ai/insight/harian',  [AiInsightController::class, 'harianInsight'])->name('ai.harian');
        Route::get('/ai/insight/bulanan', [AiInsightController::class, 'bulananInsight'])->name('ai.bulanan');
        Route::get('/locations',                    [LocationController::class, 'latest'])->name('locations');
        Route::get('/locations/{userId}/trail',     [LocationController::class, 'trail'])->name('locations.trail');
    });

    /*
    |----------------------------------------------------------------------
    | PENJUAL — Transaksi, lokasi, stok, penghasilan
    |----------------------------------------------------------------------
    */
    Route::middleware('role:penjual')->prefix('penjual')->name('penjual.')->group(function () {
        Route::get('/dashboard',    [DashboardController::class, 'penjualDashboard'])->name('dashboard');
        Route::get('/income',       [DashboardController::class, 'income'])->name('income');
        Route::post('/transaction', fn () => response()->json(['area' => 'Input Transaksi']))->name('transaction.store');
        Route::post('/location',    [LocationController::class, 'store'])->name('location.store');
        Route::post('/lapor-jualan',   [LaporJualanController::class, 'store'])->name('laporan.store');
        Route::get('/riwayat-jualan',  [LaporJualanController::class, 'riwayat'])->name('laporan.riwayat');
        Route::get('/my-stock',        [StockAllocationController::class, 'myStock'])->name('stock.mine');
    });
});