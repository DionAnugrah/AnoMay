@extends('layouts.boss')
@section('title', 'Dashboard Boss')
@section('content')

@push('styles')
<style>
    .dash * { box-sizing: border-box; margin: 0; padding: 0; }
    .dash {
        font-family: 'Inter', sans-serif;
        padding: 1.5rem;
        background: var(--bg-page);
        min-height: 100vh;
        transition: background 0.2s;
    }

    .top-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:10px; }
    .page-title { font-family:'Poppins',sans-serif; font-size:20px; font-weight:700; color:var(--text-main); transition:color 0.2s; }
    .page-title span { color:#ff7f11; }
    .date-pill { font-size:11px; color:var(--text-muted); background:var(--bg-card); border:0.5px solid var(--border); border-radius:20px; padding:5px 14px; box-shadow:0 1px 3px var(--shadow); transition:background 0.2s,color 0.2s,border-color 0.2s; }

    .section-label { font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-muted); margin-bottom:10px; margin-top:0.25rem; transition:color 0.2s; }

    .metric-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:12px; margin-bottom:1.5rem; }
    .metric-card { background:var(--bg-card); border-radius:14px; padding:1.1rem 1.25rem; border:0.5px solid var(--border); box-shadow:0 1px 4px var(--shadow); transition:transform 0.15s,box-shadow 0.15s,background 0.2s,border-color 0.2s; position:relative; overflow:hidden; }
    .metric-card::before { content:''; position:absolute; top:0; left:0; width:3px; height:100%; background:#ff7f11; border-radius:14px 0 0 14px; opacity:0; transition:opacity 0.15s; }
    .metric-card:hover { transform:translateY(-2px); box-shadow:0 4px 12px var(--shadow); }
    .metric-card:hover::before { opacity:1; }
    .metric-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:10px; font-size:15px; }
    .icon-orange { background:rgba(255,127,17,0.1); color:#ff7f11; }
    .icon-green  { background:#ecfdf5; color:#059669; }
    .icon-blue   { background:#eff6ff; color:#2563eb; }
    .icon-purple { background:#f5f3ff; color:#7c3aed; }
    html.dark .icon-green  { background:#052e16; }
    html.dark .icon-blue   { background:#0c2a4a; }
    html.dark .icon-purple { background:#2e1065; }
    .metric-label { font-size:10px; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:4px; transition:color 0.2s; }
    .metric-value { font-family:'Poppins',sans-serif; font-size:20px; font-weight:700; color:var(--text-main); line-height:1.2; transition:color 0.2s; }
    .metric-sub   { font-size:11px; color:var(--text-muted); margin-top:4px; transition:color 0.2s; }

    /* Live banner & profit card sudah gelap secara desain — tidak perlu dark variant */
    .live-banner { background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%); border-radius:14px; padding:1.25rem 1.5rem; margin-bottom:1rem; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; border:0.5px solid rgba(255,127,17,0.2); box-shadow:0 2px 12px rgba(26,26,46,0.12); }
    .live-banner-left { display:flex; align-items:center; gap:14px; }
    .live-pulse { position:relative; width:36px; height:36px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .live-pulse::before { content:''; position:absolute; width:36px; height:36px; border-radius:50%; background:rgba(255,127,17,0.2); animation:pulse-ring 1.8s ease-out infinite; }
    .live-dot { width:12px; height:12px; border-radius:50%; background:#ff7f11; box-shadow:0 0 8px rgba(255,127,17,0.6); }
    @keyframes pulse-ring { 0% { transform:scale(0.6); opacity:0.8; } 100% { transform:scale(1.6); opacity:0; } }
    .live-title { font-family:'Poppins',sans-serif; font-size:13px; font-weight:600; color:#fff; margin-bottom:2px; }
    .live-sub   { font-size:11px; color:rgba(255,255,255,0.45); }
    .live-stats { display:flex; gap:20px; }
    .live-stat  { text-align:center; }
    .live-stat-val   { font-family:'Poppins',sans-serif; font-size:18px; font-weight:700; color:#ff7f11; }
    .live-stat-label { font-size:10px; color:rgba(255,255,255,0.45); }
    .live-link { font-size:11px; font-weight:700; background:rgba(255,127,17,0.15); color:#ff7f11; border:1px solid rgba(255,127,17,0.3); border-radius:20px; padding:6px 16px; text-decoration:none; white-space:nowrap; transition:background 0.15s; }
    .live-link:hover { background:rgba(255,127,17,0.25); }

    .map-placeholder { background:var(--bg-card2); border:1.5px solid #ff7f11; border-radius:14px; height:200px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; margin-bottom:1.5rem; position:relative; overflow:hidden; cursor:pointer; transition:box-shadow 0.15s,background 0.2s; text-decoration:none; }
    .map-placeholder:hover { box-shadow:0 4px 20px rgba(255,127,17,0.15); }
    .map-placeholder::before { content:''; position:absolute; inset:0; background-image:linear-gradient(rgba(100,116,139,0.06) 1px,transparent 1px),linear-gradient(90deg,rgba(100,116,139,0.06) 1px,transparent 1px); background-size:24px 24px; }
    .map-badge { position:absolute; top:14px; right:14px; font-size:10px; font-weight:700; background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; border-radius:20px; padding:3px 10px; letter-spacing:0.05em; z-index:1; }
    .map-icon { width:52px; height:52px; background:var(--bg-card); border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 10px var(--shadow); position:relative; z-index:1; transition:background 0.2s; }
    .map-placeholder p { font-size:13px; color:var(--text-sub); font-weight:600; position:relative; z-index:1; transition:color 0.2s; }
    .map-preview-stat { display:flex; gap:20px; position:relative; z-index:1; }
    .map-cta { font-size:11px; color:#ff7f11; font-weight:600; position:relative; z-index:1; margin-top:2px; }

    .charts-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:1.5rem; }
    .chart-card { background:var(--bg-card); border:0.5px solid var(--border); border-radius:14px; padding:1.25rem; box-shadow:0 1px 4px var(--shadow); transition:background 0.2s,border-color 0.2s; }
    .chart-card.full { grid-column:1/-1; }
    .chart-title { font-family:'Poppins',sans-serif; font-size:13px; font-weight:600; color:var(--text-main); margin-bottom:2px; transition:color 0.2s; }
    .chart-sub   { font-size:11px; color:var(--text-muted); margin-bottom:14px; transition:color 0.2s; }

    .rank-table { width:100%; border-collapse:collapse; font-size:12px; }
    .rank-table th { text-align:left; padding:6px 10px; color:var(--text-muted); font-weight:600; font-size:10px; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid var(--border-soft); transition:color 0.2s,border-color 0.2s; }
    .rank-table td { padding:9px 10px; border-bottom:0.5px solid var(--border-soft); color:var(--text-sub); transition:color 0.2s,border-color 0.2s; }
    .rank-table tr:last-child td { border-bottom:none; }
    .rank-table tbody tr { transition:background 0.1s; }
    .rank-table tbody tr:hover { background:var(--bg-card2); }
    .rank-num  { font-family:'Poppins',sans-serif; font-weight:700; color:#ff7f11; font-size:13px; }
    .rank-1    { color:#f59e0b; }
    .rank-2    { color:#94a3b8; }
    .rank-3    { color:#a16207; }
    .bar-mini  { height:5px; border-radius:3px; background:linear-gradient(90deg,#ff7f11,#ffab5e); min-width:4px; transition:width 0.6s ease; }
    .nama-cell  { font-weight:500; color:var(--text-main); }
    .omset-cell { font-weight:600; color:var(--text-sub); }

    .profit-card { background:linear-gradient(135deg,#ff7f11 0%,#ff9a3c 100%); border-radius:14px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; box-shadow:0 4px 16px rgba(255,127,17,0.25); }
    .profit-left .profit-label    { font-size:11px; color:rgba(255,255,255,0.7); font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:4px; }
    .profit-left .profit-value    { font-family:'Poppins',sans-serif; font-size:26px; font-weight:700; color:#fff; }
    .profit-left .profit-sub      { font-size:11px; color:rgba(255,255,255,0.65); margin-top:2px; }
    .profit-right .profit-omset-label { font-size:10px; color:rgba(255,255,255,0.6); margin-bottom:2px; }
    .profit-right .profit-omset-val   { font-family:'Poppins',sans-serif; font-size:15px; font-weight:700; color:rgba(255,255,255,0.9); }
    .profit-right .profit-period      { font-size:11px; color:rgba(255,255,255,0.55); margin-top:6px; }
    .profit-right                     { text-align:right; }

    .error-state { text-align:center; padding:2rem; color:var(--text-muted); font-size:12px; }

    @media (max-width:768px) {
        .dash { padding:1rem; }
        .charts-grid { grid-template-columns:1fr; }
        .chart-card.full { grid-column:1; }
        .metric-grid { grid-template-columns:repeat(2,1fr); }
        .live-stats { display:none; }
    }
    @media (max-width:480px) { .metric-grid { grid-template-columns:1fr 1fr; } }
</style>
@endpush

{{-- konten HTML sama persis, tidak ada perubahan --}}
<div class="dash">
    {{-- Top Bar --}}
    <div class="top-bar">
        <div class="page-title">Dashboard <span>Boss</span></div>
        <div class="date-pill" id="tgl-now"></div>
    </div>

    <div class="profit-card">
        <div class="profit-left">
            <div class="profit-label">Estimasi Profit Bersih</div>
            <div class="profit-value" id="p-profit">—</div>
            <div class="profit-sub">40% dari total setoran bulan ini</div>
        </div>
        <div class="profit-right">
            <div class="profit-omset-label">Omset Kotor</div>
            <div class="profit-omset-val" id="p-omset">—</div>
            <div class="profit-period" id="p-period">—</div>
        </div>
    </div>

    <div class="section-label">Ringkasan Penjualan</div>
    <div class="metric-grid">
        <div class="metric-card">
            <div class="metric-icon icon-orange">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="metric-label">Total Omset</div>
            <div class="metric-value" id="m-omset">—</div>
            <div class="metric-sub">Akumulatif semua laporan</div>
        </div>
        <div class="metric-card">
            <div class="metric-icon icon-green">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </div>
            <div class="metric-label">Omset Hari Ini</div>
            <div class="metric-value" id="m-today">—</div>
            <div class="metric-sub" id="m-today-date">—</div>
        </div>
        <div class="metric-card">
            <div class="metric-icon icon-blue">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            </div>
            <div class="metric-label">Total Somay Terjual</div>
            <div class="metric-value" id="m-somay">—</div>
            <div class="metric-sub">Seluruh penjual</div>
        </div>
        <div class="metric-card">
            <div class="metric-icon icon-purple">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div class="metric-label">Jumlah Penjual</div>
            <div class="metric-value" id="m-penjual">—</div>
            <div class="metric-sub">Terdaftar di sistem</div>
        </div>
    </div>

    <div class="section-label">Pemantauan Live</div>
    <div class="live-banner">
        <div class="live-banner-left">
            <div class="live-pulse"><div class="live-dot"></div></div>
            <div class="live-info">
                <div class="live-title">Live Tracking Penjual</div>
                <div class="live-sub">Posisi penjual diperbarui otomatis setiap 30 detik</div>
            </div>
        </div>
        <div class="live-stats">
            <div class="live-stat"><div class="live-stat-val" id="live-aktif">—</div><div class="live-stat-label">Aktif Keliling</div></div>
            <div class="live-stat"><div class="live-stat-val" id="live-idle">—</div><div class="live-stat-label">Idle</div></div>
            <div class="live-stat"><div class="live-stat-val" id="live-total">—</div><div class="live-stat-label">Total Penjual</div></div>
        </div>
        <a href="/boss/live-map" class="live-link">Buka Peta →</a>
    </div>

    <a href="/boss/live-map" class="map-placeholder">
        <div class="map-badge">⚡ GPS LIVE</div>
        <div class="map-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff7f11" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <p>Lihat Peta Live Penjual</p>
        <div class="map-preview-stat" id="map-preview-stat">
            <span style="font-size:12px; color:#9ca3af;">Memuat data...</span>
        </div>
        <span class="map-cta">Klik untuk buka peta lengkap →</span>
    </a>

    <div class="section-label">Analitik Performa</div>
    <div class="charts-grid">
        {{-- <div class="chart-card full">
            <div class="chart-title">Ranking Penjual</div>
            <div class="chart-sub">Berdasarkan total setoran keseluruhan</div>
            <div style="overflow-x:auto">
                <table class="rank-table">
                    <thead>
                        <tr>
                            <th>#</th><th>Nama Penjual</th><th>Total Setoran</th><th>Total Porsi</th><th>Porsi</th>
                        </tr>
                    </thead>
                    <tbody id="rank-tbody">
                        <tr><td colspan="5"><div class="error-state">Memuat data...</div></td></tr>
                    </tbody>
                </table>
            </div>
        </div> --}}
        <div class="chart-card full">
    <div class="chart-title">🏆 Peringkat Performa Penjual (Metode SAW)</div>
    <div class="chart-sub">Peringkat keputusan berdasarkan kriteria akumulasi penjualan, sisa stok, dan setoran</div>
    <div style="overflow-x:auto">
        <table class="rank-table">
            <thead>
                <tr>
                    <th style="text-align: center; width: 50px;">#</th>
                    <th>Nama Penjual</th>
                    <th style="text-align: center;">Total Jual</th>
                    <th style="text-align: center;">Sisa Stok</th>
                    <th style="text-align: center;">Total Setoran</th>
                    <th style="text-align: center; background: rgba(255,127,17,0.05); color: #ff7f11;">Skor SAW (V)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sawRankings as $index => $rank)
                    <tr>
                        <td style="text-align: center;">
                            <span class="rank-num {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : '')) }}">
                                @if($index == 0) 🥇 @elif($index == 1) 🥈 @elif($index == 2) 🥉 @else {{ $index + 1 }} @endif
                            </span>
                        </td>
                        <td class="nama-cell">{{ $rank['name'] }}</td>
                        <td style="text-align: center;">{{ number_format($rank['detail_aktual']['sales']) }} porsi</td>
                        <td style="text-align: center;">{{ $rank['detail_aktual']['stock'] }} pcs</td>
                        <td class="omset-cell" style="text-align: center;">Rp {{ number_format($rank['detail_aktual']['deposit'], 0, ',', '.') }}</td>
                        <td style="text-align: center; font-weight: 700; background: rgba(255,127,17,0.05); color: #ff7f11;">
                            {{ $rank['skor'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"><div class="error-state">Belum ada data laporan harian penjual untuk dihitung.</div></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
        <div class="chart-card full">
            <div class="chart-title">Performa Setoran per Penjual</div>
            <div class="chart-sub">Visualisasi total setoran semua penjual</div>
            <div style="position:relative;width:100%;height:200px"><canvas id="chartPerforma"></canvas></div>
        </div>
        <div class="chart-card">
            <div class="chart-title">Penjualan per Produk</div>
            <div class="chart-sub">Total semua waktu (porsi tiap produk)</div>
            <div style="position:relative;width:100%;height:220px"><canvas id="chartProdukBoss"></canvas></div>
        </div>
        <div class="chart-card">
            <div class="chart-title">Penjualan Hari Ini per Penjual</div>
            <div class="chart-sub" id="chart-today-sub">—</div>
            <div style="position:relative;width:100%;height:220px"><canvas id="chartHariIni"></canvas></div>
        </div>
    </div>
</div>

@push('scripts')
{{-- script sama persis, tidak ada perubahan --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
    let chartPerforma;

    function rupiah(n) {
        if (n >= 1000000000) return 'Rp ' + (n/1000000000).toFixed(1) + ' M';
        if (n >= 1000000)    return 'Rp ' + (n/1000000).toFixed(1) + ' Jt';
        if (n >= 1000)       return 'Rp ' + (n/1000).toFixed(0) + ' Rb';
        return 'Rp ' + n.toLocaleString('id-ID');
    }
    function rupiahFull(n) { return 'Rp ' + n.toLocaleString('id-ID'); }

    async function loadSales() {
        try {
            const res = await fetch('/boss/sales', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } });
            const json = await res.json();
            if (json.status !== 'success') return;
            const d = json.data;
            document.getElementById('m-omset').textContent = rupiah(d.total_omset_global);
            document.getElementById('m-today').textContent = rupiah(d.omset_hari_ini);
            document.getElementById('m-somay').textContent = d.total_somay_terjual.toLocaleString('id-ID') + ' pcs';
            const now = new Date();
            document.getElementById('m-today-date').textContent = now.toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
            if (d.per_produk && Object.keys(d.per_produk).length > 0) {
                const COLORS = ['#ff7f11','#2563eb','#16a34a','#7c3aed','#dc2626','#0891b2','#d97706'];
                new Chart(document.getElementById('chartProdukBoss'), {
                    type: 'doughnut',
                    data: { labels: Object.keys(d.per_produk), datasets: [{ data: Object.values(d.per_produk), backgroundColor: COLORS, borderWidth: 2, borderColor: '#fff' }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position:'right', labels:{ font:{ size:11 }, boxWidth:12 } }, tooltip: { callbacks: { label: c => ` ${c.label}: ${c.raw} pcs` } } } }
                });
            }
        } catch(e) { console.error('Gagal load sales:', e); }
    }

    async function loadProfit() {
        try {
            const res = await fetch('/boss/profit', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } });
            const json = await res.json();
            if (json.status !== 'success') return;
            const d = json.data;
            document.getElementById('p-profit').textContent = rupiah(d.estimasi_profit);
            document.getElementById('p-omset').textContent  = rupiah(d.omset_kotor);
            document.getElementById('p-period').textContent = d.bulan_berjalan;
        } catch(e) { console.error('Gagal load profit:', e); }
    }

    // async function loadPerformance() {
    //     try {
    //         const res = await fetch('/boss/performance', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } });
    //         const json = await res.json();
    //         if (json.status !== 'success') return;
    //         const penjual = json.data;
    //         document.getElementById('m-penjual').textContent = penjual.length + ' orang';
    //         const maxSetoran = Math.max(...penjual.map(p => p.total_setoran));
    //         const tbody = document.getElementById('rank-tbody');
    //         if (penjual.length === 0) {
    //             tbody.innerHTML = '<tr><td colspan="5"><div class="error-state">Belum ada data penjual.</div></td></tr>';
    //         } else {
    //             tbody.innerHTML = penjual.map((p, i) => {
    //                 const rankClass = i === 0 ? 'rank-1' : i === 1 ? 'rank-2' : i === 2 ? 'rank-3' : '';
    //                 const porsi = maxSetoran > 0 ? Math.round((p.total_setoran / maxSetoran) * 100) : 0;
    //                 return `<tr><td><span class="rank-num ${rankClass}">${i+1}</span></td><td class="nama-cell">${p.name}</td><td class="omset-cell">${rupiahFull(p.total_setoran)}</td><td>${p.total_porsi_terjual.toLocaleString('id-ID')} pcs</td><td style="min-width:90px"><div class="bar-mini" style="width:${porsi}%"></div></td></tr>`;
    //             }).join('');
    //         }
    //         buildBarChart(penjual);
    //     } catch(e) {
    //         console.error('Gagal load performance:', e);
    //         document.getElementById('rank-tbody').innerHTML = '<tr><td colspan="5"><div class="error-state">Gagal memuat data.</div></td></tr>';
    //     }
    // }

    async function loadLiveMap() {
        try {
            const res = await fetch('/boss/penjual/lokasi', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } });
            const json = await res.json();
            if (json.status !== 'success') return;
            const data = json.data;
            const aktif   = data.filter(p => p.status === 'aktif').length;
            const idle    = data.filter(p => p.status === 'idle').length;
            const offline = data.filter(p => p.status === 'offline').length;
            document.getElementById('live-aktif').textContent = aktif;
            document.getElementById('live-idle').textContent  = idle;
            document.getElementById('live-total').textContent = data.length;
            document.getElementById('map-preview-stat').innerHTML =
                `<span style="font-size:12px;color:#10b981;font-weight:600;">● ${aktif} Aktif</span>
                 <span style="font-size:12px;color:#f59e0b;font-weight:600;">● ${idle} Idle</span>
                 <span style="font-size:12px;color:#9ca3af;font-weight:600;">● ${offline} Offline</span>`;
        } catch(e) { console.error('Gagal load live map:', e); }
    }

    function buildBarChart(penjual) {
        const ctx = document.getElementById('chartPerforma');
        if (chartPerforma) chartPerforma.destroy();
        const colors = ['#ff7f11','#ffab5e','#ffd4a8','#acbfa4','#c9d9b3','#a5b4fc','#86efac','#fca5a5'];
        chartPerforma = new Chart(ctx, {
            type: 'bar',
            data: { labels: penjual.map(p => p.name.split(' ')[0]), datasets: [{ label:'Total Setoran', data: penjual.map(p => p.total_setoran), backgroundColor: penjual.map((_,i) => colors[i % colors.length]), borderRadius:8, borderSkipped:false }] },
            options: { responsive:true, maintainAspectRatio:false, plugins: { legend:{ display:false }, tooltip:{ callbacks:{ label: c => ' ' + rupiahFull(c.raw) } } }, scales: { x:{ grid:{ display:false }, ticks:{ font:{ size:11 }, color:'#9ca3af' } }, y:{ grid:{ color:'rgba(0,0,0,0.04)' }, ticks:{ font:{ size:10 }, color:'#9ca3af', callback: v => rupiah(v) } } } }
        });
        const ctxHariIni = document.getElementById('chartHariIni');
        if (ctxHariIni && penjual.length > 0) {
            const now = new Date();
            const sub = document.getElementById('chart-today-sub');
            if (sub) sub.textContent = now.toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });
            new Chart(ctxHariIni, {
                type: 'bar',
                data: { labels: penjual.map(p => p.name.split(' ')[0]), datasets: [{ label:'Terjual (pcs)', data: penjual.map(p => p.total_terjual), backgroundColor: colors, borderRadius:8, borderSkipped:false }] },
                options: { responsive:true, maintainAspectRatio:false, plugins: { legend:{ display:false }, tooltip:{ callbacks:{ label: c => ` ${c.raw} pcs` } } }, scales: { x:{ grid:{ display:false }, ticks:{ font:{ size:11 }, color:'#9ca3af' } }, y:{ grid:{ color:'rgba(0,0,0,0.04)' }, ticks:{ font:{ size:10 }, color:'#9ca3af' } } } }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const now = new Date();
        document.getElementById('tgl-now').textContent = now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
        loadSales();
        loadProfit();
        loadPerformance();
        loadLiveMap();
        setInterval(loadLiveMap, 30000);
    });
</script>
@endpush
@endsection