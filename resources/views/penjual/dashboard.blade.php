@extends('layouts.penjual')

@section('title', 'Beranda - Penjual')

@section('content')
    
    @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm font-medium mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg text-sm font-medium mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- KATALOG --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-anomay-beige p-3 border-b border-gray-200">
            <h3 class="font-poppins font-bold text-anomay-dark">Katalog Item di Panci</h3>
        </div>
        <div class="p-4">
            <ul class="space-y-3">
                @foreach ($products as $item)
                <li class="flex justify-between items-center border-b border-gray-100 pb-2 last:border-0">
                    <span class="text-anomay-dark font-medium">{{ $item->name }}</span>
                    <span class="font-bold text-anomay-dark bg-gray-100 px-3 py-1 rounded-md">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- GPS / MULAI KELILING --}}
    <div class="bg-white rounded-lg shadow-md p-5 border-t-4 border-green-400 mt-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-poppins font-bold text-anomay-dark">Bagikan Lokasi</h3>
            <span id="gps-pill" class="text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-500">Nonaktif</span>
        </div>

        <button id="btn-keliling"
            onclick="toggleKeliling()"
            class="w-full py-3 rounded-lg font-poppins font-bold shadow-md transition text-white bg-green-500 hover:bg-green-600">
            Mulai Keliling
        </button>

        <div id="gps-info" class="hidden mt-4 grid grid-cols-2 gap-3">
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-xs text-gray-400 mb-1">Latitude</p>
                <p id="gps-lat" class="text-sm font-semibold text-anomay-dark font-mono">—</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-xs text-gray-400 mb-1">Longitude</p>
                <p id="gps-lng" class="text-sm font-semibold text-anomay-dark font-mono">—</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-xs text-gray-400 mb-1">Akurasi</p>
                <p id="gps-akurasi" class="text-sm font-semibold text-anomay-dark">—</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-xs text-gray-400 mb-1">Terkirim</p>
                <p id="gps-count" class="text-sm font-semibold text-anomay-dark">0×</p>
            </div>
        </div>

        <p id="gps-log" class="mt-3 text-xs text-gray-400 text-center">Tekan tombol untuk mulai berbagi lokasi ke boss.</p>
    </div>

    {{-- LAPORAN PENJUALAN --}}
    <div class="bg-white rounded-lg shadow-md p-5 border-t-4 border-anomay-sage mt-4">
        <h3 class="font-poppins font-bold text-anomay-dark mb-4">Lapor Penjualan Harian</h3>
        <form action="/penjual/lapor-jualan" method="POST">
            @csrf
            
            @php
                $alokasiSaya = \App\Models\StockAllocation::where('user_id', auth()->id())->where('date', date('Y-m-d'))->first();
            @endphp
            
            @if($alokasiSaya)
                <input type="hidden" name="stock_allocation_id" value="{{ $alokasiSaya->id }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-anomay-dark mb-1">Berapa Siomay yang laku terjual hari ini?</label>
                        <input type="number" name="qty_sold" min="0" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-anomay-sage outline-none bg-gray-50" placeholder="Masukkan jumlah terjual..." required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-anomay-sage text-anomay-dark py-3 mt-6 rounded-lg font-poppins font-bold shadow-md hover:opacity-90 transition">Kirim Setoran</button>
            @else
                <p class="text-sm text-red-500 font-medium">Admin belum membagikan stok panci untuk Anda hari ini.</p>
            @endif
        </form>
    </div>

@endsection

@push('scripts')
<script>
    let aktif      = false;
    let watchId    = null;
    let intervalId = null;
    let lastPos    = null;
    let sendCount  = 0;

    const CSRF     = document.querySelector('meta[name="csrf-token"]').content;
    const ENDPOINT = '/penjual/lokasi';
    const INTERVAL_MS = 15000;

    function toggleKeliling() {
        aktif ? stopKeliling() : mulaiKeliling();
    }

    function mulaiKeliling() {
        if (!navigator.geolocation) {
            setLog('Browser ini tidak mendukung GPS.', true);
            return;
        }
        aktif = true;
        updateUI();
        setLog('Mendapatkan posisi GPS...');

        watchId = navigator.geolocation.watchPosition(
            onPosSuccess,
            onPosError,
            { enableHighAccuracy: true, maximumAge: 10000, timeout: 15000 }
        );

        intervalId = setInterval(() => {
            if (lastPos) kirimKeLaravel(lastPos);
        }, INTERVAL_MS);
    }

    function stopKeliling() {
        aktif = false;
        if (watchId !== null) navigator.geolocation.clearWatch(watchId);
        if (intervalId)       clearInterval(intervalId);
        watchId = intervalId = lastPos = null;
        updateUI();
        setLog('Berbagi lokasi dihentikan.');
    }

    function onPosSuccess(position) {
        lastPos = position;
        const { latitude, longitude, accuracy } = position.coords;

        document.getElementById('gps-info').classList.remove('hidden');
        document.getElementById('gps-lat').textContent     = latitude.toFixed(5);
        document.getElementById('gps-lng').textContent     = longitude.toFixed(5);
        document.getElementById('gps-akurasi').textContent = Math.round(accuracy) + ' m';

        setLog('GPS aktif — lokasi terdeteksi.');
        kirimKeLaravel(position);
    }

    function onPosError(err) {
        setLog('Gagal ambil GPS: ' + err.message, true);
        setPill('Error', 'bg-red-100 text-red-600');
    }

    async function kirimKeLaravel(position) {
        try {
            await fetch(ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    latitude:  position.coords.latitude,
                    longitude: position.coords.longitude,
                }),
            });
            sendCount++;
            document.getElementById('gps-count').textContent = sendCount + '×';
            setLog('Terkirim pukul ' + new Date().toLocaleTimeString('id-ID'));
        } catch (e) {
            setLog('Gagal kirim ke server.', true);
        }
    }

    function updateUI() {
        const btn = document.getElementById('btn-keliling');
        if (aktif) {
            btn.textContent = 'Berhenti Keliling';
            btn.className   = btn.className.replace('bg-green-500 hover:bg-green-600', 'bg-red-500 hover:bg-red-600');
            setPill('Aktif', 'bg-green-100 text-green-700');
        } else {
            btn.textContent = 'Mulai Keliling';
            btn.className   = btn.className.replace('bg-red-500 hover:bg-red-600', 'bg-green-500 hover:bg-green-600');
            setPill('Nonaktif', 'bg-gray-100 text-gray-500');
            document.getElementById('gps-info').classList.add('hidden');
        }
    }

    function setPill(teks, kelas) {
        const pill = document.getElementById('gps-pill');
        pill.textContent = teks;
        pill.className   = 'text-xs font-semibold px-3 py-1 rounded-full ' + kelas;
    }

    function setLog(msg, err = false) {
        const el = document.getElementById('gps-log');
        el.textContent  = msg;
        el.className    = 'mt-3 text-xs text-center ' + (err ? 'text-red-400' : 'text-gray-400');
    }
</script>
@endpush