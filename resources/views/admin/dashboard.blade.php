@extends('layouts.admin')

@section('title', 'Dashboard Admin - AnoMay')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-poppins text-2xl font-bold text-anomay-dark">Selamat datang, {{ Auth::user()->name }} 👋</h2>
            <p class="text-sm text-gray-500 mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <a href="/admin/stock-allocations"
           class="bg-anomay-orange text-white px-4 py-2.5 rounded-xl font-poppins font-semibold text-sm shadow-sm hover:bg-orange-600 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Bagi Stok Pagi
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-anomay-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Penjual</p>
                <p class="font-poppins text-2xl font-bold text-anomay-dark">{{ $totalPenjual }}</p>
                <p class="text-xs text-gray-400">{{ $penjualAktif }} aktif hari ini</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Katalog Produk</p>
                <p class="font-poppins text-2xl font-bold text-anomay-dark">{{ $totalProduk }}</p>
                <p class="text-xs text-gray-400">{{ $stokHariIni }} alokasi hari ini</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Antrean Validasi</p>
                <p class="font-poppins text-2xl font-bold text-anomay-dark">{{ $laporanPending }}</p>
                <p class="text-xs text-yellow-500 font-medium">{{ $laporanPending > 0 ? 'Perlu ditindaklanjuti' : 'Semua beres ✓' }}</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-anomay-orange to-orange-400 rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-white/70 uppercase tracking-wide">Setoran Hari Ini</p>
                <p class="font-poppins text-xl font-bold text-white">Rp {{ number_format($setoranHariIni, 0, ',', '.') }}</p>
                <p class="text-xs text-white/70">Sudah divalidasi</p>
            </div>
        </div>

    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Alokasi Stok Hari Ini --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div>
                    <h3 class="font-poppins font-semibold text-anomay-dark">Alokasi Stok Hari Ini</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ now()->translatedFormat('d F Y') }}</p>
                </div>
                <a href="/admin/stock-allocations" class="text-xs text-anomay-orange font-semibold hover:underline">Lihat semua →</a>
            </div>
            @if($alokasiHariIni->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <p class="text-sm font-medium">Belum ada alokasi stok hari ini</p>
                    <a href="/admin/stock-allocations" class="mt-3 text-xs bg-anomay-orange text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 transition">Bagi Stok Sekarang</a>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($alokasiHariIni as $alokasi)
                    <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-anomay-orange/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-anomay-orange">{{ strtoupper(substr($alokasi->user->name ?? '?', 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-anomay-dark">{{ $alokasi->user->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $alokasi->product->name ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-anomay-orange">{{ $alokasi->qty_given }} pcs</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick Actions + Laporan Terbaru --}}
        <div class="flex flex-col gap-5">

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-poppins font-semibold text-anomay-dark mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="/admin/users/create" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center group-hover:bg-purple-100 transition">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Tambah Penjual</span>
                    </a>
                    <a href="/admin/products/create" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Tambah Produk</span>
                    </a>
                    <a href="/admin/stock-allocations" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center group-hover:bg-orange-100 transition">
                            <svg class="w-4 h-4 text-anomay-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Bagi Stok Pagi</span>
                    </a>
                    <a href="/admin/laporan" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center group-hover:bg-green-100 transition">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Validasi Laporan</span>
                        @if($laporanPending > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $laporanPending }}</span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Laporan Terbaru --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex-1">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="font-poppins font-semibold text-anomay-dark">Laporan Terbaru</h3>
                    <a href="/admin/laporan" class="text-xs text-anomay-orange font-semibold hover:underline">Semua →</a>
                </div>
                @if($laporanTerbaru->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">Belum ada laporan.</p>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($laporanTerbaru as $laporan)
                        <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition">
                            <div>
                                <p class="text-sm font-semibold text-anomay-dark">{{ $laporan->user->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($laporan->date)->translatedFormat('d M') }} · {{ $laporan->qty_sold }} pcs</p>
                            </div>
                            @if($laporan->status === 'pending')
                                <span class="text-xs bg-yellow-100 text-yellow-700 font-semibold px-2.5 py-1 rounded-full">Pending</span>
                            @else
                                <span class="text-xs bg-green-100 text-green-700 font-semibold px-2.5 py-1 rounded-full">✓ Valid</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Grafik Penjualan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-5">

        {{-- Grafik per Produk --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-poppins font-semibold text-anomay-dark mb-1">Penjualan per Produk</h3>
            <p class="text-xs text-gray-400 mb-4">Total semua waktu (laporan tervalidasi)</p>
            @if($grafikProduk->isEmpty())
                <p class="text-sm text-gray-400 text-center py-8">Belum ada data.</p>
            @else
                <div style="position:relative;height:220px">
                    <canvas id="chartProduk"></canvas>
                </div>
            @endif
        </div>

        {{-- Grafik per Penjual Hari Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-poppins font-semibold text-anomay-dark mb-1">Penjualan per Penjual Hari Ini</h3>
            <p class="text-xs text-gray-400 mb-4">{{ now()->translatedFormat('d F Y') }}</p>
            @if($grafikPenjual->isEmpty())
                <p class="text-sm text-gray-400 text-center py-8">Belum ada laporan hari ini.</p>
            @else
                <div style="position:relative;height:220px">
                    <canvas id="chartPenjual"></canvas>
                </div>
            @endif
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const COLORS = ['#ff7f11','#2563eb','#16a34a','#7c3aed','#dc2626','#0891b2','#d97706','#059669'];

@if($grafikProduk->isNotEmpty())
new Chart(document.getElementById('chartProduk'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($grafikProduk->keys()->values()) !!},
        datasets: [{ data: {!! json_encode($grafikProduk->values()) !!}, backgroundColor: COLORS, borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position: 'right', labels: { font: { size: 11 }, boxWidth: 12 } },
            tooltip: { callbacks: { label: c => ` ${c.label}: ${c.raw} pcs` } }
        }
    }
});
@endif

@if($grafikPenjual->isNotEmpty())
new Chart(document.getElementById('chartPenjual'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($grafikPenjual->pluck('nama')) !!},
        datasets: [{
            label: 'Terjual (pcs)',
            data: {!! json_encode($grafikPenjual->pluck('terjual')) !!},
            backgroundColor: COLORS,
            borderRadius: 8, borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => ` ${c.raw} pcs` } } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#9ca3af' } },
            y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { size: 10 }, color: '#9ca3af' } }
        }
    }
});
@endif
</script>
@endpush
