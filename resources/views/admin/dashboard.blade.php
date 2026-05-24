@extends('layouts.admin')

@section('title', 'Dashboard Admin - AnoMay')

@section('content')
    <h2 class="font-poppins text-2xl font-bold text-anomay-dark mb-6">Ringkasan Operasional Hari Ini</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4 border-l-4 border-l-anomay-dark">
            <div class="p-3 bg-gray-50 rounded-lg text-gray-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Penjual</p>
                <h3 class="text-2xl font-bold text-anomay-dark">{{ $totalPenjual }} Aktif</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4 border-l-4 border-l-anomay-orange">
            <div class="p-3 bg-orange-50 rounded-lg text-anomay-orange">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Katalog Item</p>
                <h3 class="text-2xl font-bold text-anomay-dark">{{ $totalProduk }} Jenis</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4 border-l-4 border-l-yellow-400">
            <div class="p-3 bg-yellow-50 rounded-lg text-yellow-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Antrean Validasi</p>
                <h3 class="text-2xl font-bold text-anomay-dark">{{ $laporanPending }} Laporan</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4 border-l-4 border-l-green-500">
            <div class="p-3 bg-green-50 rounded-lg text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Setoran Valid</p>
                <h3 class="text-2xl font-bold text-green-600">Rp {{ number_format($setoranHariIni, 0, ',', '.') }}</h3>
            </div>
        </div>

    </div>
@endsection