<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LaporJualanController;
use App\Http\Controllers\AiInsightController;
use App\Http\Controllers\StockAllocationController;
use App\Http\Controllers\BossDashboardController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| 1. RUTE OTOMATIS & LOGIN VIEW
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

/*
|--------------------------------------------------------------------------
| 2. RUTE PROSES & PANEL INTEGRASI (FRONTEND + BACKEND)
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
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Manajemen Pengguna (CRUD Penjual)
        Route::get('/users',           [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/users/{user}',    [UserController::class, 'show'])->name('users.show');
        Route::post('/users',          [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',    [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Manajemen Produk (CRUD Produk)
        Route::get('/products',           [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::post('/products',          [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Manajemen Laporan Penjualan
        Route::get('/laporan',                  [LaporJualanController::class, 'indexAdmin'])->name('laporan.index');
        Route::get('/laporan/{report}',         [LaporJualanController::class, 'showAdmin'])->name('laporan.show');
        Route::put('/laporan/{report}',         [LaporJualanController::class, 'update'])->name('laporan.update');
        Route::patch('/laporan/{report}/status', [LaporJualanController::class, 'updateStatus'])->name('laporan.status');
        Route::delete('/laporan/{report}',      [LaporJualanController::class, 'destroy'])->name('laporan.destroy');

        // Alokasi Stok Pagi
        Route::get('/stock-allocations',                      [StockAllocationController::class, 'index'])->name('stock.index');
        Route::post('/stock-allocations',                     [StockAllocationController::class, 'store'])->name('stock.store');
        Route::put('/stock-allocations/{stockAllocation}',    [StockAllocationController::class, 'update'])->name('stock.update');
        Route::delete('/stock-allocations/{stockAllocation}', [StockAllocationController::class, 'destroy'])->name('stock.destroy');

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
        Route::get('/sales',       [BossDashboardController::class, 'salesSummary'])->name('sales');
        Route::get('/performance', [BossDashboardController::class, 'performanceSummary'])->name('performance');
        Route::get('/profit',      [BossDashboardController::class, 'profitSummary'])->name('profit');
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
        Route::get('/dashboard', [DashboardController::class, 'sellerDashboard'])->name('dashboard');
        Route::get('/stock',        fn () => response()->json(['area' => 'Stok Saya']))->name('stock');
        Route::get('/income',       fn () => response()->json(['area' => 'Penghasilan Harian']))->name('income');
        Route::post('/transaction', fn () => response()->json(['area' => 'Input Transaksi']))->name('transaction.store');
        Route::post('/location',    fn () => response()->json(['area' => 'Update Lokasi']))->name('location.update');
        
        // Laporan Jualan & Alokasi Stok Milik Sendiri
        Route::post('/lapor-jualan',  [LaporJualanController::class, 'store'])->name('laporan.store');
        Route::get('/riwayat-jualan', [LaporJualanController::class, 'riwayat'])->name('laporan.riwayat');
        Route::get('/my-stock',       [StockAllocationController::class, 'myStock'])->name('stock.mine');
    });

    Route::post('/logout', function(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});