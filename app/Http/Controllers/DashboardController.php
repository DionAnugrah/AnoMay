<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // Menghitung statistik operasional harian
        $totalPenjual = \App\Models\User::where('role', 'penjual')->count();
        $totalProduk  = \App\Models\Product::count();
        $laporanPending = \App\Models\DailyReport::where('status', 'pending')->count();
        $setoranHariIni = \App\Models\DailyReport::whereDate('date', date('Y-m-d'))
                            ->where('status', 'accepted')
                            ->sum('total_deposit');

        return view('admin.dashboard', compact(
            'totalPenjual', 
            'totalProduk', 
            'laporanPending', 
            'setoranHariIni'
        ));
    }

    public function sellerDashboard()
    {
        // Mengambil alokasi stok panci khusus untuk penjual yang login hari ini
        $alokasiPanci = \App\Models\StockAllocation::with('product')
                            ->where('user_id', auth()->user()->id)
                            ->whereDate('date', date('Y-m-d'))
                            ->get();

        $products = \App\Models\Product::all();
        return view('penjual.dashboard', compact('alokasiPanci', 'products'));
    }
}