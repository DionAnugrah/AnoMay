@extends('layouts.penjual')

@section('title', 'Beranda - Penjual')

@section('content')

@if (session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl text-sm font-medium mb-4 flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif
@if (session('error') || session('message'))
    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl text-sm font-medium mb-4">
        {{ session('error') ?? session('message') }}
    </div>
@endif

{{-- Ringkasan Hari Ini --}}
@if($laporanHariIni->isNotEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
    <h3 class="font-poppins font-bold text-anomay-dark mb-3 text-sm">📊 Penjualan Hari Ini</h3>

    {{-- Grafik Donut --}}
    <div class="flex items-center gap-4 mb-4">
        <div class="relative w-28 h-28 flex-shrink-0">
            <canvas id="chartDonut"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="font-poppins font-bold text-lg text-anomay-dark leading-none">{{ $laporanHariIni->sum('qty_sold') }}</span>
                <span class="text-[10px] text-gray-400">terjual</span>
            </div>
        </div>
        <div class="flex-1 space-y-2">
            @foreach($laporanHariIni as $lap)
            @php $produk = $lap->stockAllocation?->product ?? null; @endphp
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: {{ ['#ff7f11','#2563eb','#16a34a','#7c3aed','#dc2626'][$loop->index % 5] }}"></span>
                    <span class="text-xs text-gray-600 truncate max-w-[100px]">{{ $produk->name ?? '-' }}</span>
                </div>
                <span class="text-xs font-bold text-anomay-dark">{{ $lap->qty_sold }} pcs</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Total Setoran --}}
    <div class="bg-green-50 rounded-xl p-3 flex justify-between items-center">
        <span class="text-xs font-semibold text-gray-500">Total Setoran Hari Ini</span>
        <span class="font-poppins font-bold text-green-600">Rp {{ number_format($laporanHariIni->sum('total_deposit'), 0, ',', '.') }}</span>
    </div>
</div>
@endif

{{-- GPS --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-poppins font-bold text-anomay-dark text-sm">📍 Bagikan Lokasi</h3>
        <span id="gps-pill" class="text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-500">Nonaktif</span>
    </div>
    <button id="btn-keliling" onclick="toggleKeliling()"
        class="w-full py-3 rounded-xl font-poppins font-bold shadow-sm transition text-white bg-green-500 hover:bg-green-600 text-sm">
        🛵 Mulai Keliling
    </button>
    <div id="gps-info" class="hidden mt-3 grid grid-cols-2 gap-2">
        <div class="bg-gray-50 rounded-lg p-2.5">
            <p class="text-[10px] text-gray-400 mb-0.5">Latitude</p>
            <p id="gps-lat" class="text-xs font-semibold text-anomay-dark font-mono">—</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-2.5">
            <p class="text-[10px] text-gray-400 mb-0.5">Longitude</p>
            <p id="gps-lng" class="text-xs font-semibold text-anomay-dark font-mono">—</p>
        </div>
    </div>
    <p id="gps-log" class="mt-2 text-xs text-gray-400 text-center">Tekan tombol untuk mulai berbagi lokasi.</p>
</div>

{{-- Laporan Per Produk --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-gray-100">
        <h3 class="font-poppins font-bold text-anomay-dark text-sm">📝 Lapor Penjualan Hari Ini</h3>
        <p class="text-xs text-gray-400 mt-0.5">Laporkan tiap produk yang kamu bawa</p>
    </div>

    @if($stokHariIni->isEmpty())
        <div class="p-6 text-center">
            <p class="text-sm text-gray-400">Admin belum membagikan stok untuk hari ini.</p>
        </div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach($stokHariIni as $alokasi)
            @php $sudah = in_array($alokasi->id, $sudahDilaporkan); @endphp
            <div class="p-4 {{ $sudah ? 'bg-green-50/50' : '' }}">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="font-poppins font-bold text-anomay-dark text-sm">{{ $alokasi->product->name }}</p>
                        <p class="text-xs text-gray-400">Stok dibawa: <strong>{{ $alokasi->qty_given }} pcs</strong> · Rp {{ number_format($alokasi->product->price, 0, ',', '.') }}/pcs</p>
                    </div>
                    @if($sudah)
                        <span class="text-xs bg-green-100 text-green-700 font-bold px-2.5 py-1 rounded-full flex-shrink-0">✓ Dilaporkan</span>
                    @endif
                </div>

                @if(!$sudah)
                <form action="/penjual/lapor-jualan" method="POST" class="flex gap-2 items-end">
                    @csrf
                    <input type="hidden" name="stock_allocation_id" value="{{ $alokasi->id }}">
                    <div class="flex-1">
                        <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide block mb-1">Jumlah Terjual</label>
                        <input type="number" name="qty_sold" min="0" max="{{ $alokasi->qty_given }}"
                            placeholder="0 – {{ $alokasi->qty_given }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-anomay-orange outline-none bg-gray-50"
                            required>
                    </div>
                    <button type="submit"
                        class="bg-anomay-orange text-white px-4 py-2.5 rounded-lg font-poppins font-semibold text-sm shadow-sm hover:bg-orange-600 transition flex-shrink-0">
                        Kirim
                    </button>
                </form>
                @else
                {{-- Tampilkan hasil laporan --}}
                @php
                    $lap = $laporanHariIni->firstWhere('stock_allocation_id', $alokasi->id);
                @endphp
                @if($lap)
                <div class="grid grid-cols-3 gap-2 mt-1">
                    <div class="bg-white rounded-lg p-2 text-center border border-gray-100">
                        <p class="text-[10px] text-gray-400">Terjual</p>
                        <p class="font-bold text-anomay-dark text-sm">{{ $lap->qty_sold }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-2 text-center border border-gray-100">
                        <p class="text-[10px] text-gray-400">Sisa</p>
                        <p class="font-bold text-red-500 text-sm">{{ $lap->qty_returned }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-2 text-center border border-gray-100">
                        <p class="text-[10px] text-gray-400">Setoran</p>
                        <p class="font-bold text-green-600 text-xs">Rp {{ number_format($lap->total_deposit, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endif
                @endif
            </div>
            @endforeach
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
// ── Grafik Donut ──────────────────────────────────────────────
@if($laporanHariIni->isNotEmpty())
const donutCtx = document.getElementById('chartDonut');
if (donutCtx) {
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($laporanHariIni->map(fn($l) => $l->stockAllocation?->product?->name ?? '-')->values()) !!},
            datasets: [{
                data: {!! json_encode($laporanHariIni->pluck('qty_sold')->values()) !!},
                backgroundColor: ['#ff7f11','#2563eb','#16a34a','#7c3aed','#dc2626'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            cutout: '70%',
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => ` ${c.label}: ${c.raw} pcs` } } },
        }
    });
}
@endif

// ── GPS ───────────────────────────────────────────────────────
let aktif = false, watchId = null, intervalId = null, lastPos = null, sendCount = 0;
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function toggleKeliling() { aktif ? stopKeliling() : mulaiKeliling(); }

function mulaiKeliling() {
    if (!navigator.geolocation) { setLog('GPS tidak didukung.', true); return; }
    aktif = true; updateUI(); setLog('Mendapatkan posisi...');
    watchId = navigator.geolocation.watchPosition(onPos, onPosErr, { enableHighAccuracy: true, maximumAge: 10000, timeout: 15000 });
    intervalId = setInterval(() => { if (lastPos) kirim(lastPos); }, 15000);
}

function stopKeliling() {
    aktif = false;
    if (watchId !== null) navigator.geolocation.clearWatch(watchId);
    if (intervalId) clearInterval(intervalId);
    watchId = intervalId = lastPos = null;
    updateUI(); setLog('Lokasi berhenti dikirim.');
    fetch('/penjual/lokasi/stop', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } }).catch(() => {});
}

function onPos(pos) {
    lastPos = pos;
    document.getElementById('gps-info').classList.remove('hidden');
    document.getElementById('gps-lat').textContent = pos.coords.latitude.toFixed(5);
    document.getElementById('gps-lng').textContent = pos.coords.longitude.toFixed(5);
    setLog('GPS aktif'); kirim(pos);
}

function onPosErr(e) { setLog('GPS error: ' + e.message, true); }

async function kirim(pos) {
    try {
        await fetch('/penjual/lokasi', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: JSON.stringify({ latitude: pos.coords.latitude, longitude: pos.coords.longitude }) });
        sendCount++;
        setLog('Terkirim ' + new Date().toLocaleTimeString('id-ID') + ' (' + sendCount + '×)');
    } catch(e) { setLog('Gagal kirim.', true); }
}

function updateUI() {
    const btn = document.getElementById('btn-keliling');
    if (aktif) {
        btn.textContent = '⏹ Berhenti Keliling';
        btn.classList.replace('bg-green-500', 'bg-red-500');
        btn.classList.replace('hover:bg-green-600', 'hover:bg-red-600');
        setPill('Aktif', 'bg-green-100 text-green-700');
    } else {
        btn.textContent = '🛵 Mulai Keliling';
        btn.classList.replace('bg-red-500', 'bg-green-500');
        btn.classList.replace('hover:bg-red-600', 'hover:bg-green-600');
        setPill('Nonaktif', 'bg-gray-100 text-gray-500');
        document.getElementById('gps-info').classList.add('hidden');
    }
}

function setPill(t, c) { const p = document.getElementById('gps-pill'); p.textContent = t; p.className = 'text-xs font-semibold px-3 py-1 rounded-full ' + c; }
function setLog(m, e = false) { const el = document.getElementById('gps-log'); el.textContent = m; el.className = 'mt-2 text-xs text-center ' + (e ? 'text-red-400' : 'text-gray-400'); }
</script>
@endpush
