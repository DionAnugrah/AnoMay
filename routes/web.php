<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LaporJualanController;
use App\Http\Controllers\AiInsightController;
use App\Http\Controllers\StockAllocationController;
use App\Http\Controllers\BossDashboardController;

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

        Route::get('/dashboard', fn () => response()->json(['area' => 'Admin Dashboard']))->name('dashboard');

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
    });

    /*
    |----------------------------------------------------------------------
    | BOSS — Melihat penjualan, performa, laporan, bonus
    |----------------------------------------------------------------------
    */
    Route::middleware('role:boss')->prefix('boss')->name('boss.')->group(function () {
        Route::get('/dashboard',   fn () => response()->json(['area' => 'Boss Dashboard']))->name('dashboard');
        Route::get('/sales',       [BossDashboardController::class, 'salesSummary'])->name('sales');
        Route::get('/performance', [BossDashboardController::class, 'performanceSummary'])->name('performance');
        Route::get('/profit',      [BossDashboardController::class, 'profitSummary'])->name('profit');
        Route::get('/bonus',       fn () => response()->json(['area' => 'Rekomendasi Bonus']))->name('bonus');
        Route::get('/ai/insight/harian',  [AiInsightController::class, 'harianInsight'])->name('ai.harian');
        Route::get('/ai/insight/bulanan', [AiInsightController::class, 'bulananInsight'])->name('ai.bulanan');
    });

    /*
    |----------------------------------------------------------------------
    | PENJUAL — Transaksi, lokasi, stok, penghasilan
    |----------------------------------------------------------------------
    */
    Route::middleware('role:penjual')->prefix('penjual')->name('penjual.')->group(function () {
        Route::get('/dashboard',    fn () => response()->json(['area' => 'Penjual Dashboard']))->name('dashboard');
        Route::get('/stock',        fn () => response()->json(['area' => 'Stok Saya']))->name('stock');
        Route::get('/income',       fn () => response()->json(['area' => 'Penghasilan Harian']))->name('income');
        Route::post('/transaction', fn () => response()->json(['area' => 'Input Transaksi']))->name('transaction.store');
        Route::post('/location',    fn () => response()->json(['area' => 'Update Lokasi']))->name('location.update');
        Route::post('/lapor-jualan',   [LaporJualanController::class, 'store'])->name('laporan.store');
        Route::get('/riwayat-jualan',  [LaporJualanController::class, 'riwayat'])->name('laporan.riwayat');
        Route::get('/my-stock',        [StockAllocationController::class, 'myStock'])->name('stock.mine');
    });
});