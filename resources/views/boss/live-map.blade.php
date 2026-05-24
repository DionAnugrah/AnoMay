@extends('layouts.boss')

@section('title', 'Peta Penjual')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

<style>
    .peta-wrap * { box-sizing: border-box; }
    .peta-wrap { font-family: 'Inter', sans-serif; padding: 1.5rem; }

    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 12px;
    }
    .page-title {
        font-family: 'Poppins', sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: #262626;
    }
    .page-title span { color: #ff7f11; }

    .topbar-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

    .live-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 600;
        color: #065f46;
        background: #ecfdf5;
        border: 0.5px solid #a7f3d0;
        border-radius: 20px;
        padding: 5px 12px;
        letter-spacing: 0.03em;
    }
    .live-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #10b981;
        animation: blink 1.4s infinite;
    }
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

    .refresh-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        background: transparent;
        cursor: pointer;
        color: #6b7280;
        font-family: 'Inter', sans-serif;
        transition: all 0.15s;
    }
    .refresh-btn:hover { background: #f9fafb; }
    .refresh-btn.loading { opacity: 0.6; pointer-events: none; }
    .refresh-icon { transition: transform 0.4s; }
    .refresh-btn.loading .refresh-icon { animation: spin 0.8s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .stat-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 12px;
        margin-bottom: 1.5rem;
    }
    .stat-card {
        background: #f9fafb;
        border-radius: 12px;
        padding: 0.9rem 1.1rem;
        border: 0.5px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .stat-icon {
        width: 34px; height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-orange { background: #fff4e8; color: #ff7f11; }
    .icon-green  { background: #ecfdf5; color: #059669; }
    .icon-gray   { background: #f3f4f6; color: #6b7280; }
    .icon-blue   { background: #eff6ff; color: #2563eb; }

    .stat-label {
        font-size: 10px;
        color: #9ca3af;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }
    .stat-value {
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: #262626;
        line-height: 1.2;
    }

    .map-layout {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 16px;
        align-items: start;
    }

    .map-card {
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }
    .map-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem 0.75rem;
        border-bottom: 0.5px solid #f3f4f6;
    }
    .map-card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #262626;
        margin: 0;
    }
    .map-card-sub { font-size: 11px; color: #9ca3af; margin: 2px 0 0; }

    .map-legend { display: flex; gap: 14px; flex-wrap: wrap; }
    .map-legend-item { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #6b7280; }
    .legend-circle { width: 10px; height: 10px; border-radius: 50%; }
    .lc-aktif   { background: #10b981; }
    .lc-idle    { background: #f59e0b; }
    .lc-offline { background: #d1d5db; }

    #peta-leaflet {
        display: block;
        width: 100%;
        height: 460px;
        min-height: 460px;
    }

    .sidebar-card {
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }
    .sidebar-header {
        padding: 1rem 1.25rem 0.75rem;
        border-bottom: 0.5px solid #f3f4f6;
    }
    .sidebar-title {
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #262626;
        margin: 0 0 2px;
    }
    .sidebar-sub { font-size: 11px; color: #9ca3af; }

    .sidebar-search {
        padding: 0.75rem 1rem;
        border-bottom: 0.5px solid #f3f4f6;
    }
    .search-input {
        width: 100%;
        font-size: 12px;
        font-family: 'Inter', sans-serif;
        padding: 7px 12px 7px 32px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #f9fafb url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E") no-repeat 10px center;
        color: #374151;
        outline: none;
        transition: border-color 0.15s;
    }
    .search-input:focus { border-color: #ff7f11; background-color: #fff; }

    .penjual-list {
        max-height: 390px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #e5e7eb transparent;
    }
    .penjual-list::-webkit-scrollbar { width: 4px; }
    .penjual-list::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

    .penjual-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 1.25rem;
        border-bottom: 0.5px solid #f9fafb;
        cursor: pointer;
        transition: background 0.12s;
    }
    .penjual-item:last-child { border-bottom: none; }
    .penjual-item:hover { background: #fafafa; }
    .penjual-item.selected { background: #fff4e8; }

    .avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .penjual-info { flex: 1; min-width: 0; }
    .penjual-nama {
        font-size: 12px;
        font-weight: 600;
        color: #262626;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .penjual-meta { font-size: 10px; color: #9ca3af; margin-top: 1px; }

    .status-badge {
        font-size: 10px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        flex-shrink: 0;
    }
    .badge-aktif   { background: #ecfdf5; color: #065f46; }
    .badge-idle    { background: #fffbeb; color: #92400e; }
    .badge-offline { background: #f3f4f6; color: #6b7280; }

    .penjual-detail {
        border-top: 0.5px solid #f3f4f6;
        padding: 1rem 1.25rem;
        display: none;
    }
    .penjual-detail.show { display: block; }
    .detail-title {
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 600;
        color: #262626;
        margin-bottom: 8px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: #6b7280;
        padding: 4px 0;
        border-bottom: 0.5px solid #f9fafb;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-row strong { color: #374151; font-weight: 500; }

    .focus-btn {
        width: 100%;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        padding: 8px;
        border-radius: 8px;
        border: none;
        background: #ff7f11;
        color: #fff;
        cursor: pointer;
        transition: opacity 0.15s;
    }
    .focus-btn:hover { opacity: 0.88; }

    .empty-state {
        padding: 2rem 1rem;
        text-align: center;
        font-size: 12px;
        color: #9ca3af;
    }

    .last-update { font-size: 11px; color: #d1d5db; text-align: right; margin-top: 0.75rem; }

    @media (max-width: 900px) {
        .map-layout { grid-template-columns: 1fr; }
        .penjual-list { max-height: 260px; }
        #peta-leaflet { height: 320px; min-height: 320px; }
    }
    @media (max-width: 480px) {
        .peta-wrap { padding: 1rem; }
        .stat-row { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="peta-wrap">

    <div class="top-bar">
        <div class="page-title">Peta <span>Penjual</span></div>
        <div class="topbar-right">
            <div class="live-pill">
                <span class="live-dot"></span>
                LIVE
            </div>
            <button class="refresh-btn" id="btn-refresh" onclick="muatSemuaLokasi()">
                <svg class="refresh-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 4 23 10 17 10"/>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                </svg>
                Refresh Lokasi
            </button>
        </div>
    </div>

    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon icon-green">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
            <div>
                <div class="stat-label">Aktif Keliling</div>
                <div class="stat-value" id="s-aktif">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-orange">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <div class="stat-label">Idle / Berhenti</div>
                <div class="stat-value" id="s-idle">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-gray">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="1" y1="1" x2="23" y2="23"/><path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55M5 12.55a10.94 10.94 0 0 1 5.17-2.39M10.71 5.05A16 16 0 0 1 22.56 9M1.42 9a15.91 15.91 0 0 1 4.7-2.88M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01"/></svg>
            </div>
            <div>
                <div class="stat-label">Offline</div>
                <div class="stat-value" id="s-offline">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
                <div class="stat-label">Total Penjual</div>
                <div class="stat-value" id="s-total">—</div>
            </div>
        </div>
    </div>

    <div class="map-layout">

        <div class="map-card">
            <div class="map-card-header">
                <div>
                    <p class="map-card-title">Lokasi Real-time Penjual</p>
                    <p class="map-card-sub">Titik diperbarui otomatis setiap 30 detik</p>
                </div>
                <div class="map-legend">
                    <span class="map-legend-item"><span class="legend-circle lc-aktif"></span> Aktif</span>
                    <span class="map-legend-item"><span class="legend-circle lc-idle"></span> Idle</span>
                    <span class="map-legend-item"><span class="legend-circle lc-offline"></span> Offline</span>
                </div>
            </div>
            <div id="peta-leaflet"></div>
        </div>

        <div class="sidebar-card">
            <div class="sidebar-header">
                <p class="sidebar-title">Daftar Penjual</p>
                <p class="sidebar-sub">Klik untuk fokus di peta</p>
            </div>
            <div class="sidebar-search">
                <input class="search-input" type="text" id="input-cari"
                       placeholder="Cari nama penjual..."
                       oninput="filterPenjual(this.value)">
            </div>
            <div class="penjual-list" id="daftar-penjual">
                <div class="empty-state">Memuat data...</div>
            </div>
            <div class="penjual-detail" id="panel-detail">
                <div class="detail-title" id="detail-nama">—</div>
                <div class="detail-row"><span>Status</span><strong id="detail-status">—</strong></div>
                <div class="detail-row"><span>Terakhir update</span><strong id="detail-update">—</strong></div>
                <div class="detail-row"><span>Koordinat</span><strong id="detail-koordinat">—</strong></div>
                <div class="detail-row"><span>Penjualan hari ini</span><strong id="detail-jual">—</strong></div>
                <div class="detail-row"><span>Qty terjual</span><strong id="detail-qty">—</strong></div>
                <button class="focus-btn" onclick="fokusKePeta(selectedId)">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Fokus di Peta
                </button>
            </div>
        </div>

    </div>

    <div class="last-update">Terakhir diperbarui: <span id="tgl-update">—</span></div>

</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, markerLayer = {};
    let dataPenjual = [];
    let selectedId = null;

    const STATUS_COLOR = { aktif: '#10b981', idle: '#f59e0b', offline: '#9ca3af' };

    // ── Fetch dari BE ─────────────────────────────────────────
    async function fetchLokasi() {
        const res = await fetch('/boss/penjual/lokasi', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        const json = await res.json();
        if (json.status !== 'success') throw new Error('Gagal ambil data');
        return json.data;
    }

    // ── Map Init ──────────────────────────────────────────────
    function initMap() {
        map = L.map('peta-leaflet', {
            center: [-0.8917, 119.8707],
            zoom: 13,
            zoomControl: false
        });
        L.control.zoom({ position: 'bottomright' }).addTo(map);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(map);
        window.addEventListener('resize', () => map.invalidateSize());
    }

    // ── Buat Ikon Marker ──────────────────────────────────────
    function buatIkon(status, nama) {
        const warna   = STATUS_COLOR[status] || '#9ca3af';
        const inisial = nama.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
        const svg = `
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" viewBox="0 0 40 50">
                <circle cx="20" cy="20" r="18" fill="${warna}" stroke="#fff" stroke-width="2.5"/>
                <text x="20" y="25" text-anchor="middle" font-family="Poppins,sans-serif"
                    font-size="11" font-weight="700" fill="#fff">${inisial}</text>
                <polygon points="14,35 26,35 20,48" fill="${warna}"/>
            </svg>`;
        return L.divIcon({ html: svg, className: '', iconSize: [40, 50], iconAnchor: [20, 48], popupAnchor: [0, -44] });
    }

    // ── Render Marker ─────────────────────────────────────────
    function renderMarker(p) {
        if (markerLayer[p.id]) map.removeLayer(markerLayer[p.id]);
        const marker = L.marker([p.lat, p.lng], { icon: buatIkon(p.status, p.nama) });
        marker.bindPopup(`
            <div style="font-family:'Inter',sans-serif; min-width:160px;">
                <div style="font-family:'Poppins',sans-serif; font-weight:700; font-size:13px; color:#262626; margin-bottom:4px;">${p.nama}</div>
                <div style="font-size:11px; color:#6b7280; margin-bottom:6px;">Update: ${p.update}</div>
                <div style="display:flex; justify-content:space-between; font-size:11px; padding:3px 0; border-top:0.5px solid #f3f4f6;">
                    <span style="color:#9ca3af">Hari ini</span><strong style="color:#374151">${p.jual}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:11px; padding:3px 0; border-top:0.5px solid #f3f4f6;">
                    <span style="color:#9ca3af">Qty</span><strong style="color:#374151">${p.qty}</strong>
                </div>
            </div>
        `, { maxWidth: 200 });
        marker.on('click', () => pilihPenjual(p.id));
        marker.addTo(map);
        markerLayer[p.id] = marker;
    }

    // ── Render Sidebar List ───────────────────────────────────
    function renderSidebar(data) {
        const container = document.getElementById('daftar-penjual');
        if (!data.length) {
            container.innerHTML = '<div class="empty-state">Tidak ada penjual ditemukan.</div>';
            return;
        }
        container.innerHTML = data.map(p => {
            const inisial  = p.nama.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
            const avatarBg = STATUS_COLOR[p.status] || '#9ca3af';
            const badgeTeks = p.status === 'aktif' ? 'Aktif' : p.status === 'idle' ? 'Idle' : 'Offline';
            return `
                <div class="penjual-item ${selectedId === p.id ? 'selected' : ''}" id="item-${p.id}" onclick="pilihPenjual(${p.id})">
                    <div class="avatar" style="background:${avatarBg}">${inisial}</div>
                    <div class="penjual-info">
                        <div class="penjual-nama">${p.nama}</div>
                        <div class="penjual-meta">${p.update}</div>
                    </div>
                    <span class="status-badge badge-${p.status}">${badgeTeks}</span>
                </div>`;
        }).join('');
    }

    // ── Render Stat Cards ─────────────────────────────────────
    function renderStat(data) {
        document.getElementById('s-aktif').textContent   = data.filter(p => p.status === 'aktif').length;
        document.getElementById('s-idle').textContent    = data.filter(p => p.status === 'idle').length;
        document.getElementById('s-offline').textContent = data.filter(p => p.status === 'offline').length;
        document.getElementById('s-total').textContent   = data.length;
    }

    // ── Pilih Penjual (sidebar + detail panel) ────────────────
    function pilihPenjual(id) {
        selectedId = id;
        const p = dataPenjual.find(x => x.id === id);
        if (!p) return;

        document.querySelectorAll('.penjual-item').forEach(el => el.classList.remove('selected'));
        const item = document.getElementById('item-' + id);
        if (item) { item.classList.add('selected'); item.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }

        const badgeTeks = p.status === 'aktif' ? 'Aktif' : p.status === 'idle' ? 'Idle' : 'Offline';
        document.getElementById('detail-nama').textContent      = p.nama;
        document.getElementById('detail-status').textContent    = badgeTeks;
        document.getElementById('detail-update').textContent    = p.update;
        document.getElementById('detail-koordinat').textContent = `${p.lat.toFixed(5)}, ${p.lng.toFixed(5)}`;
        document.getElementById('detail-jual').textContent      = p.jual;
        document.getElementById('detail-qty').textContent       = p.qty;
        document.getElementById('panel-detail').classList.add('show');
    }

    // ── Fokus ke Marker di Peta ───────────────────────────────
    function fokusKePeta(id) {
        const p = dataPenjual.find(x => x.id === id);
        if (!p) return;
        map.flyTo([p.lat, p.lng], 16, { animate: true, duration: 0.8 });
        if (markerLayer[id]) markerLayer[id].openPopup();
    }

    // ── Filter Cari ───────────────────────────────────────────
    function filterPenjual(kata) {
        renderSidebar(dataPenjual.filter(p => p.nama.toLowerCase().includes(kata.toLowerCase())));
    }

    // ── Load Semua Lokasi ─────────────────────────────────────
    async function muatSemuaLokasi() {
        const btn = document.getElementById('btn-refresh');
        btn.classList.add('loading');
        try {
            const data = await fetchLokasi();
            dataPenjual = data;
            renderStat(data);
            renderSidebar(data);
            data.forEach(p => renderMarker(p));

            // Auto-fit peta ke semua marker kalau ada data
            if (data.length > 0) {
                const bounds = data.map(p => [p.lat, p.lng]);
                map.fitBounds(bounds, { padding: [40, 40], maxZoom: 15 });
            }

            document.getElementById('tgl-update').textContent = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
        } catch (err) {
            console.error('Gagal memuat lokasi:', err);
            document.getElementById('daftar-penjual').innerHTML =
                '<div class="empty-state">Gagal memuat data. Coba refresh.</div>';
        }
        btn.classList.remove('loading');
    }

    // ── Init ──────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        initMap();
        setTimeout(function () {
            map.invalidateSize();
            muatSemuaLokasi();
        }, 150);
        // Auto refresh setiap 30 detik
        setInterval(muatSemuaLokasi, 30000);
    });
</script>
@endpush

@endsection