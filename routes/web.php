<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LaporJualanController;
use App\Http\Controllers\AiInsightController;
use App\Http\Controllers\StockAllocationController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BossDashboardController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));
Route::get('/login',  fn () => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

/*
|--------------------------------------------------------------------------
| Auth Required
|--------------------------------------------------------------------------
*/
Route::middleware('auth.json')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me',      [AuthController::class, 'me'])->name('me');

    /*
    |----------------------------------------------------------------------
    | ADMIN
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Users
        Route::get('/users',                [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',         [UserController::class, 'create'])->name('users.create');
        Route::post('/users',               [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}',         [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit',    [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',         [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',      [UserController::class, 'destroy'])->name('users.destroy');

        // Products
        Route::get('/products',                  [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create',           [ProductController::class, 'create'])->name('products.create');
        Route::post('/products',                 [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}',        [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit',   [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}',        [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}',     [ProductController::class, 'destroy'])->name('products.destroy');

        // Stock Allocations
        Route::get('/stock-allocations',                      [StockAllocationController::class, 'index'])->name('stock.index');
        Route::post('/stock-allocations',                     [StockAllocationController::class, 'store'])->name('stock.store');
        Route::put('/stock-allocations/{stockAllocation}',    [StockAllocationController::class, 'update'])->name('stock.update');
        Route::delete('/stock-allocations/{stockAllocation}', [StockAllocationController::class, 'destroy'])->name('stock.destroy');

        // Laporan
        Route::get('/laporan',                   [LaporJualanController::class, 'indexAdmin'])->name('laporan.index');
        Route::get('/laporan/{report}',          [LaporJualanController::class, 'showAdmin'])->name('laporan.show');
        Route::put('/laporan/{report}',          [LaporJualanController::class, 'update'])->name('laporan.update');
        Route::patch('/laporan/{report}/status', [LaporJualanController::class, 'updateStatus'])->name('laporan.status');
        Route::delete('/laporan/{report}',       [LaporJualanController::class, 'destroy'])->name('laporan.destroy');

        // AI
        Route::get('/ai/insight/harian',  [AiInsightController::class, 'harianInsight'])->name('ai.harian');
        Route::get('/ai/insight/bulanan', [AiInsightController::class, 'bulananInsight'])->name('ai.bulanan');

        // GPS
        Route::get('/locations',                [LocationController::class, 'latest'])->name('locations');
        Route::get('/locations/{userId}/trail', [LocationController::class, 'trail'])->name('locations.trail');
    });

    /*
    |----------------------------------------------------------------------
    | BOSS
    |----------------------------------------------------------------------
    */
    Route::middleware('role:boss')->prefix('boss')->name('boss.')->group(function () {

        Route::get('/dashboard',   [BossDashboardController::class, 'index'])->name('dashboard');
        Route::get('/live-map',    fn () => view('boss.live-map'))->name('live-map');
        Route::get('/penjual/lokasi', [LocationController::class, 'latest'])->name('penjual.lokasi');
        Route::get('/sales',       [BossDashboardController::class, 'salesSummary'])->name('sales');
        Route::get('/performance', [BossDashboardController::class, 'performanceSummary'])->name('performance');
        Route::get('/profit',      [BossDashboardController::class, 'profitSummary'])->name('profit');
        Route::get('/bonus',       [DashboardController::class, 'bonus'])->name('bonus');
        Route::get('/omset-bulanan', [BossDashboardController::class, 'omsetBulanan'])->name('omset.bulanan');

        // AI
        Route::get('/ai/insight',         fn () => view('boss.ai.ai-insight'))->name('ai.insight');
        Route::get('/ai/insight/harian',  [AiInsightController::class, 'harianInsight'])->name('ai.harian');
        Route::get('/ai/insight/bulanan', [AiInsightController::class, 'bulananInsight'])->name('ai.bulanan');
    });
    /*
    |----------------------------------------------------------------------
    | PENJUAL
    |----------------------------------------------------------------------
    */
    Route::middleware('role:penjual')->prefix('penjual')->name('penjual.')->group(function () {

        Route::get('/dashboard',  [DashboardController::class, 'penjualDashboard'])->name('dashboard');
        Route::get('/income',     [DashboardController::class, 'income'])->name('income');

        // GPS — support dua endpoint (location & lokasi untuk kompatibilitas view)
        Route::post('/location',      [LocationController::class, 'store'])->name('location.store');
        Route::post('/lokasi',        [LocationController::class, 'store'])->name('lokasi.store');
        Route::post('/location/stop', [LocationController::class, 'stop'])->name('location.stop');
        Route::post('/lokasi/stop',   [LocationController::class, 'stop'])->name('lokasi.stop');

        // Laporan
        Route::post('/lapor-jualan',  [LaporJualanController::class, 'store'])->name('laporan.store');
        Route::get('/riwayat-jualan', [LaporJualanController::class, 'riwayat'])->name('laporan.riwayat');

        // Stok
        Route::get('/my-stock', [StockAllocationController::class, 'myStock'])->name('stock.mine');
    });
});