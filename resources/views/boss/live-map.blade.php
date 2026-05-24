@extends('layouts.boss')
@section('title', 'Peta Penjual')

@section('content')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .peta-wrap * { box-sizing:border-box; }
    .peta-wrap { font-family:'Inter',sans-serif; padding:1.5rem; background:var(--bg-page); min-height:100vh; transition:background 0.2s; }

    .top-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:12px; }
    .page-title { font-family:'Poppins',sans-serif; font-size:20px; font-weight:700; color:var(--text-main); transition:color 0.2s; }
    .page-title span { color:#ff7f11; }
    .topbar-right { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }

    .live-pill { display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:600; color:#065f46; background:#ecfdf5; border:0.5px solid #a7f3d0; border-radius:20px; padding:5px 12px; letter-spacing:0.03em; }
    .live-dot  { width:7px; height:7px; border-radius:50%; background:#10b981; animation:blink 1.4s infinite; }
    @keyframes blink { 0%,100% { opacity:1; } 50% { opacity:0.3; } }

    .refresh-btn { display:inline-flex; align-items:center; gap:6px; font-size:12px; font-weight:500; padding:6px 14px; border-radius:8px; border:1.5px solid var(--border); background:transparent; cursor:pointer; color:var(--text-muted); font-family:'Inter',sans-serif; transition:all 0.15s; }
    .refresh-btn:hover { background:var(--bg-card2); }
    .refresh-btn.loading { opacity:0.6; pointer-events:none; }
    .refresh-icon { transition:transform 0.4s; }
    .refresh-btn.loading .refresh-icon { animation:spin 0.8s linear infinite; }
    @keyframes spin { to { transform:rotate(360deg); } }

    .stat-row  { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:12px; margin-bottom:1.5rem; }
    .stat-card { background:var(--bg-card); border-radius:12px; padding:0.9rem 1.1rem; border:0.5px solid var(--border); display:flex; align-items:center; gap:12px; transition:background 0.2s,border-color 0.2s; }
    .stat-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .icon-orange { background:rgba(255,127,17,0.1); color:#ff7f11; }
    .icon-green  { background:#ecfdf5; color:#059669; }
    .icon-gray   { background:var(--bg-card2); color:var(--text-muted); }
    .icon-blue   { background:#eff6ff; color:#2563eb; }
    html.dark .icon-green { background:#052e16; }
    html.dark .icon-blue  { background:#0c2a4a; }
    .stat-label { font-size:10px; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:2px; transition:color 0.2s; }
    .stat-value { font-family:'Poppins',sans-serif; font-size:18px; font-weight:700; color:var(--text-main); line-height:1.2; transition:color 0.2s; }

    .map-layout { display:grid; grid-template-columns:1fr 280px; gap:16px; align-items:start; }

    .map-card { background:var(--bg-card); border:0.5px solid var(--border); border-radius:12px; overflow:hidden; transition:background 0.2s,border-color 0.2s; }
    .map-card-header { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem 0.75rem; border-bottom:0.5px solid var(--border-soft); }
    .map-card-title  { font-family:'Poppins',sans-serif; font-size:13px; font-weight:600; color:var(--text-main); margin:0; transition:color 0.2s; }
    .map-card-sub    { font-size:11px; color:var(--text-muted); margin:2px 0 0; transition:color 0.2s; }

    .map-legend      { display:flex; gap:14px; flex-wrap:wrap; }
    .map-legend-item { display:flex; align-items:center; gap:5px; font-size:11px; color:var(--text-muted); }
    .legend-circle   { width:10px; height:10px; border-radius:50%; }
    .lc-aktif   { background:#10b981; }
    .lc-idle    { background:#f59e0b; }
    .lc-offline { background:#d1d5db; }

    #peta-leaflet { display:block; width:100%; height:460px; min-height:460px; }

    .sidebar-card   { background:var(--bg-card); border:0.5px solid var(--border); border-radius:12px; overflow:hidden; transition:background 0.2s,border-color 0.2s; }
    .sidebar-header { padding:1rem 1.25rem 0.75rem; border-bottom:0.5px solid var(--border-soft); }
    .sidebar-title  { font-family:'Poppins',sans-serif; font-size:13px; font-weight:600; color:var(--text-main); margin:0 0 2px; transition:color 0.2s; }
    .sidebar-sub    { font-size:11px; color:var(--text-muted); transition:color 0.2s; }

    .sidebar-search { padding:0.75rem 1rem; border-bottom:0.5px solid var(--border-soft); }
    .search-input { width:100%; font-size:12px; font-family:'Inter',sans-serif; padding:7px 12px 7px 32px; border-radius:8px; border:1px solid var(--border); background:var(--bg-card2) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E") no-repeat 10px center; color:var(--text-sub); outline:none; transition:border-color 0.15s,background 0.2s; }
    .search-input:focus { border-color:#ff7f11; background-color:var(--bg-card); }

    .penjual-list { max-height:390px; overflow-y:auto; scrollbar-width:thin; scrollbar-color:var(--border) transparent; }
    .penjual-list::-webkit-scrollbar { width:4px; }
    .penjual-list::-webkit-scrollbar-thumb { background:var(--border); border-radius:4px; }

    .penjual-item { display:flex; align-items:center; gap:10px; padding:10px 1.25rem; border-bottom:0.5px solid var(--border-soft); cursor:pointer; transition:background 0.12s; }
    .penjual-item:last-child { border-bottom:none; }
    .penjual-item:hover    { background:var(--bg-card2); }
    .penjual-item.selected { background:#fff4e8; }
    html.dark .penjual-item.selected { background:#3d2a10; }

    .avatar { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-family:'Poppins',sans-serif; font-size:12px; font-weight:700; color:#fff; flex-shrink:0; }

    .penjual-info { flex:1; min-width:0; }
    .penjual-nama { font-size:12px; font-weight:600; color:var(--text-main); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; transition:color 0.2s; }
    .penjual-meta { font-size:10px; color:var(--text-muted); margin-top:1px; transition:color 0.2s; }

    .status-badge  { font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; flex-shrink:0; }
    .badge-aktif   { background:#ecfdf5; color:#065f46; }
    .badge-idle    { background:#fffbeb; color:#92400e; }
    .badge-offline { background:var(--bg-card2); color:var(--text-muted); }
    html.dark .badge-aktif { background:#052e16; color:#4ade80; }
    html.dark .badge-idle  { background:#2d1f0a; color:#fbbf24; }

    .penjual-detail      { border-top:0.5px solid var(--border-soft); padding:1rem 1.25rem; display:none; }
    .penjual-detail.show { display:block; }
    .detail-title { font-family:'Poppins',sans-serif; font-size:12px; font-weight:600; color:var(--text-main); margin-bottom:8px; transition:color 0.2s; }
    .detail-row   { display:flex; justify-content:space-between; font-size:11px; color:var(--text-muted); padding:4px 0; border-bottom:0.5px solid var(--border-soft); transition:color 0.2s,border-color 0.2s; }
    .detail-row:last-child { border-bottom:none; }
    .detail-row strong { color:var(--text-sub); font-weight:500; transition:color 0.2s; }

    .focus-btn { width:100%; margin-top:10px; display:flex; align-items:center; justify-content:center; gap:6px; font-size:12px; font-weight:600; font-family:'Inter',sans-serif; padding:8px; border-radius:8px; border:none; background:#ff7f11; color:#fff; cursor:pointer; transition:opacity 0.15s; }
    .focus-btn:hover { opacity:0.88; }

    .empty-state { padding:2rem 1rem; text-align:center; font-size:12px; color:var(--text-muted); transition:color 0.2s; }
    .last-update { font-size:11px; color:var(--text-muted); text-align:right; margin-top:0.75rem; transition:color 0.2s; }

    @media (max-width:900px) { .map-layout { grid-template-columns:1fr; } .penjual-list { max-height:260px; } #peta-leaflet { height:320px; min-height:320px; } }
    @media (max-width:480px) { .peta-wrap { padding:1rem; } .stat-row { grid-template-columns:repeat(2,1fr); } }
</style>
@endpush

{{-- HTML sama persis --}}
<div class="peta-wrap">
    <div class="top-bar">
        <div class="page-title">Peta <span>Penjual</span></div>
        <div class="topbar-right"></div>
    </div>

    <div class="stat-row">
        <div class="stat-card"><div class="stat-icon icon-green"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><div><div class="stat-label">Aktif Keliling</div><div class="stat-value" id="s-aktif">—</div></div></div>
        <div class="stat-card"><div class="stat-icon icon-orange"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div><div class="stat-label">Idle / Berhenti</div><div class="stat-value" id="s-idle">—</div></div></div>
        <div class="stat-card"><div class="stat-icon icon-gray"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="1" y1="1" x2="23" y2="23"/><path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55M5 12.55a10.94 10.94 0 0 1 5.17-2.39M10.71 5.05A16 16 0 0 1 22.56 9M1.42 9a15.91 15.91 0 0 1 4.7-2.88M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01"/></svg></div><div><div class="stat-label">Offline</div><div class="stat-value" id="s-offline">—</div></div></div>
        <div class="stat-card"><div class="stat-icon icon-blue"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div><div><div class="stat-label">Total Penjual</div><div class="stat-value" id="s-total">—</div></div></div>
    </div>

    <div class="map-layout">
        <div class="map-card">
            <div class="map-card-header">
                <div>
                    <p class="map-card-title">Lokasi Real-time Penjual</p>
                    <p class="map-card-sub">Titik diperbarui otomatis setiap 10 detik</p>
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
                <input class="search-input" type="text" id="input-cari" placeholder="Cari nama penjual..." oninput="filterPenjual(this.value)">
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
    let map, markerLayer = {}, trailLayer = {};
    let dataPenjual = [];
    let selectedId = null;
    let sudahFitBounds = false;

    const TILE_LIGHT = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    const TILE_DARK  = 'https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png';
    let tileLayer;

    const STATUS_COLOR = { aktif:'#10b981', idle:'#f59e0b', offline:'#9ca3af' };
    const TRAIL_COLORS = ['#2563eb','#dc2626','#7c3aed','#0891b2','#d97706','#059669'];
    const userColorMap = {};
    let colorIdx = 0;

    function getTrailColor(userId) {
        if (!userColorMap[userId]) { userColorMap[userId] = TRAIL_COLORS[colorIdx % TRAIL_COLORS.length]; colorIdx++; }
        return userColorMap[userId];
    }

    async function fetchLokasi() {
        const res = await fetch('/boss/penjual/lokasi', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const json = await res.json();
        if (json.status !== 'success') throw new Error('status bukan success');
        return json.data;
    }

    function initMap() {
        map = L.map('peta-leaflet', { center:[-0.8917,119.8707], zoom:13, zoomControl:false });
        L.control.zoom({ position:'bottomright' }).addTo(map);
        const isDark = document.getElementById('html-root').classList.contains('dark');
        tileLayer = L.tileLayer(isDark ? TILE_DARK : TILE_LIGHT, {
            attribution:'© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> © <a href="https://carto.com">CARTO</a>',
            maxZoom:19
        }).addTo(map);
        window.addEventListener('resize', () => map.invalidateSize());
    }

    function updateMapTile(isDark) {
        if (!map || !tileLayer) return;
        map.removeLayer(tileLayer);
        tileLayer = L.tileLayer(isDark ? TILE_DARK : TILE_LIGHT, {
            attribution:'© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> © <a href="https://carto.com">CARTO</a>',
            maxZoom:19
        }).addTo(map);
    }

    function buatIkon(status, nama) {
        const warna   = STATUS_COLOR[status] || '#9ca3af';
        const inisial = nama.split(' ').map(w => w[0]).join('').toUpperCase().slice(0,2);
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" viewBox="0 0 40 50"><circle cx="20" cy="20" r="18" fill="${warna}" stroke="#fff" stroke-width="2.5"/><text x="20" y="25" text-anchor="middle" font-family="Poppins,sans-serif" font-size="11" font-weight="700" fill="#fff">${inisial}</text><polygon points="14,35 26,35 20,48" fill="${warna}"/></svg>`;
        return L.divIcon({ html:svg, className:'', iconSize:[40,50], iconAnchor:[20,48], popupAnchor:[0,-44] });
    }

    function renderMarker(p) {
        if (markerLayer[p.id]) map.removeLayer(markerLayer[p.id]);
        const marker = L.marker([p.lat,p.lng], { icon:buatIkon(p.status,p.nama) });
        marker.bindPopup(`<div style="font-family:'Inter',sans-serif;min-width:160px;"><div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:13px;color:#262626;margin-bottom:4px;">${p.nama}</div><div style="font-size:11px;color:#6b7280;margin-bottom:6px;">Update: ${p.update}</div><div style="display:flex;justify-content:space-between;font-size:11px;padding:3px 0;border-top:0.5px solid #f3f4f6;"><span style="color:#9ca3af">Hari ini</span><strong style="color:#374151">${p.jual}</strong></div><div style="display:flex;justify-content:space-between;font-size:11px;padding:3px 0;border-top:0.5px solid #f3f4f6;"><span style="color:#9ca3af">Qty</span><strong style="color:#374151">${p.qty}</strong></div></div>`, { maxWidth:200 });
        marker.on('click', () => pilihPenjual(p.id));
        marker.addTo(map);
        markerLayer[p.id] = marker;
    }

    function renderSidebar(data) {
        const container = document.getElementById('daftar-penjual');
        if (!data.length) { container.innerHTML = '<div class="empty-state">Tidak ada penjual ditemukan.</div>'; return; }
        container.innerHTML = data.map(p => {
            const inisial  = p.nama.split(' ').map(w => w[0]).join('').toUpperCase().slice(0,2);
            const avatarBg = STATUS_COLOR[p.status] || '#9ca3af';
            const badgeTeks = p.status === 'aktif' ? 'Aktif' : p.status === 'idle' ? 'Idle' : 'Offline';
            return `<div class="penjual-item ${selectedId===p.id?'selected':''}" id="item-${p.id}" onclick="pilihPenjual(${p.id})"><div class="avatar" style="background:${avatarBg}">${inisial}</div><div class="penjual-info"><div class="penjual-nama">${p.nama}</div><div class="penjual-meta">${p.update}</div></div><span class="status-badge badge-${p.status}">${badgeTeks}</span></div>`;
        }).join('');
    }

    function renderStat(data) {
        document.getElementById('s-aktif').textContent   = data.filter(p => p.status==='aktif').length;
        document.getElementById('s-idle').textContent    = data.filter(p => p.status==='idle').length;
        document.getElementById('s-offline').textContent = data.filter(p => p.status==='offline').length;
        document.getElementById('s-total').textContent   = data.length;
    }

    function pilihPenjual(id) {
        selectedId = id;
        const p = dataPenjual.find(x => x.id===id);
        if (!p) return;
        document.querySelectorAll('.penjual-item').forEach(el => el.classList.remove('selected'));
        const item = document.getElementById('item-'+id);
        if (item) { item.classList.add('selected'); item.scrollIntoView({ behavior:'smooth', block:'nearest' }); }
        const badgeTeks = p.status==='aktif' ? 'Aktif' : p.status==='idle' ? 'Idle' : 'Offline';
        document.getElementById('detail-nama').textContent      = p.nama;
        document.getElementById('detail-status').textContent    = badgeTeks;
        document.getElementById('detail-update').textContent    = p.update;
        document.getElementById('detail-koordinat').textContent = `${p.lat.toFixed(5)}, ${p.lng.toFixed(5)}`;
        document.getElementById('detail-jual').textContent      = p.jual;
        document.getElementById('detail-qty').textContent       = p.qty;
        document.getElementById('panel-detail').classList.add('show');
    }

    function fokusKePeta(id) {
        const p = dataPenjual.find(x => x.id===id);
        if (!p) return;
        map.flyTo([p.lat,p.lng], 16, { animate:true, duration:0.8 });
        if (markerLayer[id]) markerLayer[id].openPopup();
    }

    function filterPenjual(kata) {
        renderSidebar(dataPenjual.filter(p => p.nama.toLowerCase().includes(kata.toLowerCase())));
    }

    async function muatTrail(userId) {
        try {
            const res  = await fetch(`/boss/locations/${userId}/trail`, { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } });
            const json = await res.json();
            if (!json.data || json.data.length < 2) return;
            const color  = getTrailColor(userId);
            const points = json.data.map(p => [p.latitude,p.longitude]);
            if (trailLayer[userId]) map.removeLayer(trailLayer[userId]);
            trailLayer[userId] = L.polyline(points, { color, weight:3, opacity:0.75, lineJoin:'round' }).addTo(map);
            const first = json.data[0];
            L.circleMarker([first.latitude,first.longitude], { radius:5, color, fillColor:'#fff', fillOpacity:1, weight:2 }).addTo(map).bindTooltip(`Start ${first.time}`, { permanent:false });
        } catch(e) {}
    }

    async function muatSemuaLokasi() {
        const btn = document.getElementById('btn-refresh');
        if (btn) btn.classList.add('loading');
        try {
            const data = await fetchLokasi();
            dataPenjual = data;
            renderStat(data);
            renderSidebar(data);
            data.forEach(p => { try { renderMarker(p); muatTrail(p.id); } catch(e) { console.error('[renderMarker error]', p, e); } });
            if (data.length > 0 && !sudahFitBounds) {
                map.fitBounds(data.map(p => [p.lat,p.lng]), { padding:[40,40], maxZoom:15 });
                sudahFitBounds = true;
            }
            document.getElementById('tgl-update').textContent = new Date().toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
        } catch(err) {
            console.error('Gagal memuat lokasi:', err);
            document.getElementById('daftar-penjual').innerHTML = `<div class="empty-state" style="color:#ef4444">Gagal: ${err.message}</div>`;
        }
        if (btn) btn.classList.remove('loading');
    }

    let countdown = 10;
    function tickCountdown() {
        countdown--;
        if (countdown <= 0) { countdown = 10; muatSemuaLokasi(); }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initMap();
        setTimeout(function () { map.invalidateSize(); muatSemuaLokasi(); }, 150);
        setInterval(tickCountdown, 1000);
    });
</script>
@endpush
@endsection