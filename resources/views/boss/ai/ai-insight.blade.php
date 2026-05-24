@extends('layouts.boss')

@section('title', 'Insight AI')

@section('content')

@push('styles')
<style>
    .ai-wrap { padding:1.5rem; background:var(--bg-page,#f9fafb); min-height:100vh; transition:background 0.2s; }

    .page-title { font-family:'Poppins',sans-serif; font-size:20px; font-weight:700; color:var(--text-main,#262626); transition:color 0.2s; }
    .page-title span { color:#ff7f11; }

    .filter-row { display:flex; gap:8px; margin-bottom:1.5rem; flex-wrap:wrap; }
    .filter-btn {
        font-size:12px; font-weight:500; padding:6px 16px; border-radius:20px;
        border:1.5px solid var(--border,#e5e7eb); background:transparent; cursor:pointer;
        color:var(--text-muted,#9ca3af); transition:all 0.15s; font-family:'Inter',sans-serif;
    }
    .filter-btn.active { background:#ff7f11; border-color:#ff7f11; color:#fff; }
    .filter-btn:disabled { opacity:0.5; cursor:not-allowed; }

    .rekap-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:12px; margin-bottom:1.5rem; }
    .rekap-card { background:var(--bg-card,#fff); border-radius:12px; padding:1rem 1.25rem; border:0.5px solid var(--border,#e5e7eb); transition:background 0.2s,border-color 0.2s; }
    .rekap-label { font-size:11px; color:var(--text-muted,#9ca3af); font-weight:500; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px; transition:color 0.2s; }
    .rekap-value { font-family:'Poppins',sans-serif; font-size:22px; font-weight:700; color:var(--text-main,#262626); transition:color 0.2s; }

    .insight-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:1.5rem; }
    .insight-card { background:var(--bg-card,#fff); border:0.5px solid var(--border,#e5e7eb); border-radius:12px; padding:1.25rem; transition:background 0.2s,border-color 0.2s; }
    .insight-card.full { grid-column:1/-1; }

    .card-header { display:flex; align-items:center; gap:10px; margin-bottom:1rem; padding-bottom:0.75rem; border-bottom:0.5px solid var(--border-soft,#f3f4f6); transition:border-color 0.2s; }
    .card-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .icon-orange { background:rgba(255,127,17,0.1); color:#ff7f11; }
    .icon-green  { background:#ecfdf5; color:#059669; }
    .icon-red    { background:#fef2f2; color:#dc2626; }
    .icon-blue   { background:#eff6ff; color:#2563eb; }
    html.dark .icon-green { background:#052e16; }
    html.dark .icon-red   { background:#450a0a; }
    html.dark .icon-blue  { background:#0c2a4a; }

    .card-title { font-family:'Poppins',sans-serif; font-size:13px; font-weight:600; color:var(--text-main,#262626); margin:0; transition:color 0.2s; }
    .card-sub   { font-size:11px; color:var(--text-muted,#9ca3af); margin:2px 0 0; transition:color 0.2s; }

    .insight-text { font-size:13px; color:var(--text-sub,#374151); line-height:1.7; white-space:pre-wrap; transition:color 0.2s; }

    .warning-item { display:flex; align-items:flex-start; gap:10px; background:#fef9f0; border:0.5px solid #fde68a; border-radius:8px; padding:10px 12px; margin-bottom:8px; font-size:12px; color:#92400e; line-height:1.5; }
    .warning-item:last-child { margin-bottom:0; }
    html.dark .warning-item { background:#2d1f0a; border-color:#78450a; color:#fbbf24; }

    .saran-item { display:flex; align-items:flex-start; gap:10px; background:#f0fdf4; border:0.5px solid #bbf7d0; border-radius:8px; padding:10px 12px; margin-bottom:8px; font-size:12px; color:#14532d; line-height:1.5; }
    .saran-item:last-child { margin-bottom:0; }
    html.dark .saran-item { background:#0a2d1a; border-color:#166534; color:#4ade80; }

    .penjual-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:0.5px solid var(--border-soft,#f3f4f6); font-size:12px; transition:border-color 0.2s; }
    .penjual-row:last-child { border-bottom:none; }
    .penjual-nama { color:var(--text-sub,#374151); font-weight:500; transition:color 0.2s; }
    .penjual-val  { color:var(--text-muted,#9ca3af); transition:color 0.2s; }

    .date-label { font-size:11px; color:var(--text-muted,#9ca3af); text-align:right; margin-bottom:1rem; transition:color 0.2s; }

    .refresh-btn {
        display:inline-flex; align-items:center; gap:6px; font-size:12px; font-weight:500;
        padding:6px 14px; border-radius:8px; border:1.5px solid var(--border,#e5e7eb);
        background:transparent; cursor:pointer; color:var(--text-muted,#9ca3af);
        font-family:'Inter',sans-serif; transition:all 0.15s; margin-top:1rem;
    }
    .refresh-btn:hover { background:var(--bg-card2,#f9fafb); }

    /* ── Skeleton loading ── */
    @keyframes shimmer {
        0%   { background-position: -600px 0; }
        100% { background-position:  600px 0; }
    }
    .skeleton {
        border-radius:6px; height:14px; margin-bottom:8px;
        background: linear-gradient(90deg,
            var(--border-soft,#f3f4f6) 25%,
            var(--border,#e5e7eb)      50%,
            var(--border-soft,#f3f4f6) 75%);
        background-size:600px 100%;
        animation: shimmer 1.4s infinite linear;
    }
    .skeleton.w-full { width:100%; }
    .skeleton.w-3q   { width:75%; }
    .skeleton.w-half { width:50%; }
    .skeleton.w-sm   { width:35%; }
    .skeleton.h-lg   { height:22px; }
    .skeleton.rekap  { height:28px; margin-bottom:4px; }

    /* ── Fade-in saat konten muncul ── */
    .fade-in { animation: fadeIn 0.35s ease; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

    /* ── Overlay loading di atas card ── */
    .card-loading { position:relative; }
    .card-loading::after {
        content:''; position:absolute; inset:0; border-radius:12px;
        background:var(--bg-card,#fff); opacity:0.6; pointer-events:none;
        transition:opacity 0.2s;
    }

    @media (max-width:768px) {
        .ai-wrap { padding:1rem; }
        .insight-grid { grid-template-columns:1fr; }
        .insight-card.full { grid-column:1; }
    }
</style>
@endpush

<div class="ai-wrap">

    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div class="page-title">Insight <span>AI</span></div>
    </div>

    <div class="filter-row">
        <button class="filter-btn active" id="btn-harian"  onclick="gantiPeriode(this,'harian')">Harian</button>
        <button class="filter-btn"        id="btn-bulanan" onclick="gantiPeriode(this,'bulanan')">Bulanan</button>
    </div>

    <div class="date-label" id="tgl-label">Memuat...</div>

    {{-- Rekap --}}
    <div class="rekap-grid" id="rekap-grid">
        <div class="rekap-card">
            <div class="rekap-label">Total Qty Terjual</div>
            <div class="rekap-value" id="rekap-qty"><div class="skeleton rekap w-3q"></div></div>
        </div>
        <div class="rekap-card">
            <div class="rekap-label">Total Setoran</div>
            <div class="rekap-value" id="rekap-deposit"><div class="skeleton rekap w-full"></div></div>
        </div>
        <div class="rekap-card">
            <div class="rekap-label" id="label-rekap-penjual">Jumlah Penjual</div>
            <div class="rekap-value" id="rekap-penjual"><div class="skeleton rekap w-half"></div></div>
        </div>
    </div>

    <div class="insight-grid">

        {{-- Analisa Performa --}}
        <div class="insight-card full" id="card-analisa">
            <div class="card-header">
                <div class="card-icon icon-orange">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                </div>
                <div>
                    <p class="card-title">Analisa Performa</p>
                    <p class="card-sub">Ringkasan performa penjualan dari AI</p>
                </div>
            </div>
            <div id="teks-analisa">
                <div class="skeleton w-full"></div>
                <div class="skeleton w-full"></div>
                <div class="skeleton w-3q"></div>
                <div class="skeleton w-full"></div>
                <div class="skeleton w-half"></div>
            </div>
        </div>

        {{-- Prediksi --}}
        <div class="insight-card" id="card-prediksi">
            <div class="card-header">
                <div class="card-icon icon-blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div>
                    <p class="card-title" id="label-prediksi">Prediksi Besok</p>
                    <p class="card-sub">Estimasi dari AI berdasarkan tren</p>
                </div>
            </div>
            <div id="teks-prediksi">
                <div class="skeleton w-full"></div>
                <div class="skeleton w-3q"></div>
                <div class="skeleton w-half"></div>
            </div>
        </div>

        {{-- Peringatan --}}
        <div class="insight-card" id="card-peringatan">
            <div class="card-header">
                <div class="card-icon icon-red">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div>
                    <p class="card-title">Peringatan Performa</p>
                    <p class="card-sub">Hal yang perlu diperhatikan</p>
                </div>
            </div>
            <div id="list-peringatan">
                <div class="skeleton w-full"></div>
                <div class="skeleton w-3q"></div>
            </div>
        </div>

        {{-- Saran --}}
        <div class="insight-card full" id="card-saran">
            <div class="card-header">
                <div class="card-icon icon-green">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                </div>
                <div>
                    <p class="card-title">Saran untuk Owner</p>
                    <p class="card-sub">Rekomendasi actionable dari AI</p>
                </div>
            </div>
            <div id="list-saran">
                <div class="skeleton w-full"></div>
                <div class="skeleton w-3q"></div>
                <div class="skeleton w-half"></div>
            </div>
            <button class="refresh-btn" id="btn-refresh" onclick="muatInsight(periodeAktif)">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                Muat Ulang Insight
            </button>
        </div>

        {{-- Detail per Penjual --}}
        <div class="insight-card full" id="card-penjual">
            <div class="card-header">
                <div class="card-icon icon-orange">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <p class="card-title" id="label-detail-penjual">Detail Per Penjual</p>
                    <p class="card-sub" id="sub-detail-penjual">Rekap qty dan setoran tiap penjual</p>
                </div>
            </div>
            <div id="list-penjual">
                <div class="skeleton w-full"></div>
                <div class="skeleton w-3q"></div>
                <div class="skeleton w-full"></div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
let periodeAktif = 'harian';
let sedangMemuat  = false;

const SKELETONS = {
    analisa:    `<div class="skeleton w-full"></div><div class="skeleton w-full"></div><div class="skeleton w-3q"></div><div class="skeleton w-full"></div><div class="skeleton w-half"></div>`,
    prediksi:   `<div class="skeleton w-full"></div><div class="skeleton w-3q"></div><div class="skeleton w-half"></div>`,
    peringatan: `<div class="skeleton w-full"></div><div class="skeleton w-3q"></div>`,
    saran:      `<div class="skeleton w-full"></div><div class="skeleton w-3q"></div><div class="skeleton w-half"></div>`,
    penjual:    `<div class="skeleton w-full"></div><div class="skeleton w-3q"></div><div class="skeleton w-full"></div>`,
    rekap:      `<div class="skeleton rekap w-3q"></div>`,
};

function setLoading(aktif) {
    sedangMemuat = aktif;

    /* tombol periode & refresh */
    document.querySelectorAll('.filter-btn').forEach(b => b.disabled = aktif);
    const btnRefresh = document.getElementById('btn-refresh');
    btnRefresh.disabled = aktif;
    btnRefresh.innerHTML = aktif
        ? `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg> Memuat...`
        : `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg> Muat Ulang Insight`;

    if (aktif) {
        /* tampilkan skeleton di semua area konten */
        document.getElementById('tgl-label').textContent     = 'Menganalisis data...';
        document.getElementById('rekap-qty').innerHTML       = SKELETONS.rekap;
        document.getElementById('rekap-deposit').innerHTML   = SKELETONS.rekap;
        document.getElementById('rekap-penjual').innerHTML   = SKELETONS.rekap;
        document.getElementById('teks-analisa').innerHTML    = SKELETONS.analisa;
        document.getElementById('teks-prediksi').innerHTML   = SKELETONS.prediksi;
        document.getElementById('list-peringatan').innerHTML = SKELETONS.peringatan;
        document.getElementById('list-saran').innerHTML      = SKELETONS.saran;
        document.getElementById('list-penjual').innerHTML    = SKELETONS.penjual;
    }
}

function parseInsight(teks) {
    try {
        return JSON.parse(teks.replace(/```json|```/g, '').trim());
    } catch {
        return { analisa: teks, prediksi: '—', peringatan: [], saran: [] };
    }
}

function tampilKosong(pesan) {
    document.getElementById('tgl-label').textContent     = pesan;
    document.getElementById('rekap-qty').textContent     = '—';
    document.getElementById('rekap-deposit').textContent = '—';
    document.getElementById('rekap-penjual').textContent = '—';
    setFadeIn('teks-analisa',    '<p class="insight-text">Belum ada data untuk periode ini.</p>');
    setFadeIn('teks-prediksi',   '<p class="insight-text">—</p>');
    setFadeIn('list-peringatan', '<div class="warning-item">Tidak ada peringatan.</div>');
    setFadeIn('list-saran',      '<div class="saran-item">Tidak ada saran.</div>');
    setFadeIn('list-penjual',    '<div class="penjual-row"><span class="penjual-nama">Belum ada data.</span></div>');
}

function setFadeIn(id, html) {
    const el = document.getElementById(id);
    el.innerHTML = html;
    el.classList.remove('fade-in');
    void el.offsetWidth; /* reflow paksa restart animasi */
    el.classList.add('fade-in');
}

function render(data) {
    const labelTgl = data.date
        ? 'Data per tanggal: ' + data.date
        : 'Data periode: ' + (data.periode ?? '—');
    document.getElementById('tgl-label').textContent = labelTgl;

    document.getElementById('rekap-qty').textContent     = (data.rekap.total_qty_sold ?? '—') + ' pcs';
    document.getElementById('rekap-deposit').textContent = 'Rp ' + (data.rekap.total_deposit ?? '—');

    if (data.rekap.per_penjual) {
        document.getElementById('label-rekap-penjual').textContent  = 'Jumlah Penjual';
        document.getElementById('rekap-penjual').textContent        = data.rekap.per_penjual.length + ' orang';
        document.getElementById('label-prediksi').textContent       = 'Prediksi Besok';
        document.getElementById('label-detail-penjual').textContent = 'Detail Per Penjual';
        document.getElementById('sub-detail-penjual').textContent   = 'Rekap qty dan setoran tiap penjual';

        setFadeIn('list-penjual', data.rekap.per_penjual.map(p =>
            `<div class="penjual-row">
                <span class="penjual-nama">${p.nama}</span>
                <span class="penjual-val">${p.qty_sold} pcs &nbsp;|&nbsp; Rp ${p.total_deposit}</span>
            </div>`
        ).join(''));
    } else {
        document.getElementById('label-rekap-penjual').textContent  = 'Rata-rata / Hari';
        document.getElementById('rekap-penjual').textContent        = (data.rekap.rata_rata ?? '—') + ' pcs';
        document.getElementById('label-prediksi').textContent       = 'Tren Penjualan';
        document.getElementById('label-detail-penjual').textContent = 'Ringkasan Bulanan';
        document.getElementById('sub-detail-penjual').textContent   = 'Statistik operasional bulan ini';

        setFadeIn('list-penjual',
            `<div class="penjual-row">
                <span class="penjual-nama">Hari Aktif Berjualan</span>
                <span class="penjual-val">${data.rekap.hari_aktif ?? '—'} hari</span>
            </div>
            <div class="penjual-row">
                <span class="penjual-nama">Rata-rata Penjualan per Hari</span>
                <span class="penjual-val">${data.rekap.rata_rata ?? '—'} pcs</span>
            </div>
            <div class="penjual-row">
                <span class="penjual-nama">Total Qty Terjual</span>
                <span class="penjual-val">${data.rekap.total_qty_sold ?? '—'} pcs</span>
            </div>`
        );
    }

    const parsed = parseInsight(data.insight_ai);

    setFadeIn('teks-analisa',
        `<p class="insight-text">${parsed.analisa || data.insight_ai}</p>`
    );
    setFadeIn('teks-prediksi',
        `<p class="insight-text">${parsed.prediksi || '—'}</p>`
    );
    setFadeIn('list-peringatan',
        parsed.peringatan?.length
            ? parsed.peringatan.map(w =>
                `<div class="warning-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    ${w}
                </div>`).join('')
            : '<div class="warning-item">Tidak ada peringatan.</div>'
    );
    setFadeIn('list-saran',
        parsed.saran?.length
            ? parsed.saran.map(s =>
                `<div class="saran-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    ${s}
                </div>`).join('')
            : '<div class="saran-item">Tidak ada saran.</div>'
    );
}

async function muatInsight(periode) {
    if (sedangMemuat) return;
    setLoading(true);

    try {
        const res  = await fetch(`/boss/ai/insight/${periode}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept'      : 'application/json'
            }
        });
        const data = await res.json();
        if (!res.ok) tampilKosong(data.message ?? 'Tidak ada data untuk periode ini.');
        else         render(data);
    } catch (err) {
        console.error('Gagal memuat insight:', err);
        tampilKosong('Gagal memuat data. Periksa koneksi atau coba muat ulang.');
    }

    setLoading(false);
}

function gantiPeriode(btn, periode) {
    if (sedangMemuat || periodeAktif === periode) return;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    periodeAktif = periode;
    muatInsight(periode);
}

/* spin keyframe untuk ikon refresh */
const styleEl = document.createElement('style');
styleEl.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
document.head.appendChild(styleEl);

document.addEventListener('DOMContentLoaded', () => muatInsight('harian'));
</script>
@endpush

@endsection