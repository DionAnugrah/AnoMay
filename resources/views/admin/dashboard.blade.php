@extends('layouts.admin')
@section('title', 'Dashboard Admin - AnoMay')
@section('content')
<div class="dash">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="page-title">Selamat datang, {{ Auth::user()->name }} 👋</h2>
            <p class="page-sub">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <a href="/admin/stock-allocations"
           class="bg-anomay-orange text-white px-4 py-2.5 rounded-xl font-poppins font-semibold text-sm shadow-sm hover:bg-orange-600 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Bagi Stok Pagi
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(255,127,17,0.1)">
                <svg class="w-6 h-6 text-anomay-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="stat-label">Total Penjual</p>
                <p class="stat-value">{{ $totalPenjual }}</p>
                <p class="stat-sub">{{ $penjualAktif }} aktif hari ini</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="stat-label">Katalog Produk</p>
                <p class="stat-value">{{ $totalProduk }}</p>
                <p class="stat-sub">{{ $stokHariIni }} alokasi hari ini</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <p class="stat-label">Antrean Validasi</p>
                <p class="stat-value">{{ $laporanPending }}</p>
                <p class="stat-sub" style="color:{{ $laporanPending > 0 ? '#eab308' : 'var(--text-muted)' }}">
                    {{ $laporanPending > 0 ? 'Perlu ditindaklanjuti' : 'Semua beres ✓' }}
                </p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="panel lg:col-span-2">
            <div class="panel-header">
                <div>
                    <p class="panel-title">Alokasi Stok Hari Ini</p>
                    <p class="panel-sub">{{ now()->translatedFormat('d F Y') }}</p>
                </div>
                <a href="/admin/stock-allocations" class="text-xs text-anomay-orange font-semibold hover:underline">Lihat semua →</a>
            </div>
            @if($alokasiHariIni->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 mb-3 mx-auto" style="color:var(--border)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <p class="font-medium">Belum ada alokasi stok hari ini</p>
                    <a href="/admin/stock-allocations" class="mt-3 inline-block text-xs bg-anomay-orange text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 transition">Bagi Stok Sekarang</a>
                </div>
            @else
                @foreach($alokasiHariIni as $alokasi)
                <div class="panel-row">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(255,127,17,0.12)">
                            <span class="text-xs font-bold text-anomay-orange">{{ strtoupper(substr($alokasi->user->name ?? '?', 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="row-name">{{ $alokasi->user->name ?? '-' }}</p>
                            <p class="row-sub">{{ $alokasi->product->name ?? '-' }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-anomay-orange">{{ $alokasi->qty_given }} pcs</span>
                </div>
                @endforeach
            @endif
        </div>

        <div class="flex flex-col gap-5">
            <div class="panel p-5">
                <p class="panel-title mb-4">Aksi Cepat</p>
                <div class="space-y-1">
                    <a href="/admin/users/create" class="qa-item">
                        <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg></div>
                        <span class="qa-label">Tambah Penjual</span>
                    </a>
                    <a href="/admin/products/create" class="qa-item">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
                        <span class="qa-label">Tambah Produk</span>
                    </a>
                    <a href="/admin/stock-allocations" class="qa-item">
                        <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-anomay-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                        <span class="qa-label">Bagi Stok Pagi</span>
                    </a>
                    <a href="/admin/laporan" class="qa-item">
                        <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                        <span class="qa-label">Validasi Laporan</span>
                        @if($laporanPending > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $laporanPending }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="panel flex-1">
                <div class="panel-header">
                    <p class="panel-title">Laporan Terbaru</p>
                    <a href="/admin/laporan" class="text-xs text-anomay-orange font-semibold hover:underline">Semua →</a>
                </div>
                @if($laporanTerbaru->isEmpty())
                    <div class="empty-state">Belum ada laporan.</div>
                @else
                    @foreach($laporanTerbaru as $laporan)
                    <div class="panel-row">
                        <div>
                            <p class="row-name">{{ $laporan->user->name ?? '-' }}</p>
                            <p class="row-sub">{{ \Carbon\Carbon::parse($laporan->date)->translatedFormat('d M') }} · {{ $laporan->qty_sold }} pcs</p>
                        </div>
                        @if($laporan->status === 'pending')
                            <span class="badge-pending">Pending</span>
                        @else
                            <span class="badge-valid">✓ Valid</span>
                        @endif
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-5">
        <div class="panel p-5">
            <p class="panel-title">Penjualan per Produk</p>
            <p class="panel-sub mb-4">Total semua waktu (laporan tervalidasi)</p>
            @if($grafikProduk->isEmpty())
                <div class="empty-state">Belum ada data.</div>
            @else
                <div style="position:relative;height:220px"><canvas id="chartProduk"></canvas></div>
            @endif
        </div>
        <div class="panel p-5">
            <p class="panel-title">Penjualan per Penjual Hari Ini</p>
            <p class="panel-sub mb-4">{{ now()->translatedFormat('d F Y') }}</p>
            @if($grafikPenjual->isEmpty())
                <div class="empty-state">Belum ada laporan hari ini.</div>
            @else
                <div style="position:relative;height:220px"><canvas id="chartPenjual"></canvas></div>
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
    data: { labels: {!! json_encode($grafikProduk->keys()->values()) !!}, datasets: [{ data: {!! json_encode($grafikProduk->values()) !!}, backgroundColor: COLORS, borderWidth: 2, borderColor: '#fff' }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', labels: { font: { size: 11 }, boxWidth: 12 } }, tooltip: { callbacks: { label: c => ` ${c.label}: ${c.raw} pcs` } } } }
});
@endif
@if($grafikPenjual->isNotEmpty())
new Chart(document.getElementById('chartPenjual'), {
    type: 'bar',
    data: { labels: {!! json_encode($grafikPenjual->pluck('nama')) !!}, datasets: [{ label: 'Terjual (pcs)', data: {!! json_encode($grafikPenjual->pluck('terjual')) !!}, backgroundColor: COLORS, borderRadius: 8, borderSkipped: false }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => ` ${c.raw} pcs` } } }, scales: { x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#9ca3af' } }, y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { size: 10 }, color: '#9ca3af' } } } }
});
@endif
</script>
@endpush