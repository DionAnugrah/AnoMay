@extends('layouts.boss')

@section('title', 'Insight AI')

@section('content')

<style>
    .ai-wrap * { box-sizing: border-box; }
    .ai-wrap { font-family: 'Inter', sans-serif; padding: 1.5rem; }

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

    .filter-row { display: flex; gap: 8px; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .filter-btn {
        font-size: 12px;
        font-weight: 500;
        padding: 6px 16px;
        border-radius: 20px;
        border: 1.5px solid #e5e7eb;
        background: transparent;
        cursor: pointer;
        color: #6b7280;
        transition: all 0.15s;
        font-family: 'Inter', sans-serif;
    }
    .filter-btn.active {
        background: #ff7f11;
        border-color: #ff7f11;
        color: #fff;
    }

    .rekap-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 1.5rem;
    }
    .rekap-card {
        background: #f9fafb;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        border: 0.5px solid #e5e7eb;
    }
    .rekap-label {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }
    .rekap-value {
        font-family: 'Poppins', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #262626;
    }

    .insight-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 1.5rem;
    }
    .insight-card {
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.25rem;
    }
    .insight-card.full { grid-column: 1 / -1; }

    .card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 0.5px solid #f3f4f6;
    }
    .card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-orange { background: #fff4e8; color: #ff7f11; }
    .icon-green  { background: #ecfdf5; color: #059669; }
    .icon-red    { background: #fef2f2; color: #dc2626; }
    .icon-blue   { background: #eff6ff; color: #2563eb; }

    .card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #262626;
        margin: 0;
    }
    .card-sub { font-size: 11px; color: #9ca3af; margin: 2px 0 0; }

    .insight-text {
        font-size: 13px;
        color: #374151;
        line-height: 1.7;
        white-space: pre-wrap;
    }
    .insight-text.loading { color: #9ca3af; font-style: italic; }

    .warning-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #fef9f0;
        border: 0.5px solid #fde68a;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 8px;
        font-size: 12px;
        color: #92400e;
        line-height: 1.5;
    }
    .warning-item:last-child { margin-bottom: 0; }

    .saran-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #f0fdf4;
        border: 0.5px solid #bbf7d0;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 8px;
        font-size: 12px;
        color: #14532d;
        line-height: 1.5;
    }
    .saran-item:last-child { margin-bottom: 0; }

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
        margin-top: 1rem;
    }
    .refresh-btn:hover { background: #f9fafb; }
    .refresh-btn.loading { opacity: 0.6; pointer-events: none; }

    .date-label {
        font-size: 11px;
        color: #d1d5db;
        text-align: right;
        margin-bottom: 1rem;
    }

    .penjual-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 0.5px solid #f3f4f6;
        font-size: 12px;
    }
    .penjual-row:last-child { border-bottom: none; }
    .penjual-nama { color: #374151; font-weight: 500; }
    .penjual-val  { color: #9ca3af; }

    @media (max-width: 768px) {
        .ai-wrap { padding: 1rem; }
        .insight-grid { grid-template-columns: 1fr; }
        .insight-card.full { grid-column: 1; }
    }
</style>

<div class="ai-wrap">

    <div class="top-bar">
        <div class="page-title">Insight <span>AI</span></div>
    </div>

    <div class="filter-row">
        <button class="filter-btn active" onclick="gantiPeriode(this,'harian')">Harian</button>
        <button class="filter-btn" onclick="gantiPeriode(this,'bulanan')">Bulanan</button>
    </div>

    <div class="date-label" id="tgl-label">Memuat...</div>

    <div class="rekap-grid">
        <div class="rekap-card">
            <div class="rekap-label">Total Qty Terjual</div>
            <div class="rekap-value" id="rekap-qty">—</div>
        </div>
        <div class="rekap-card">
            <div class="rekap-label">Total Setoran</div>
            <div class="rekap-value" id="rekap-deposit">—</div>
        </div>
        <div class="rekap-card">
            <div class="rekap-label" id="label-rekap-penjual">Jumlah Penjual</div>
            <div class="rekap-value" id="rekap-penjual">—</div>
        </div>
    </div>

    <div class="insight-grid">

        <div class="insight-card full">
            <div class="card-header">
                <div class="card-icon icon-orange">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                </div>
                <div>
                    <p class="card-title">Analisa Performa</p>
                    <p class="card-sub">Ringkasan performa penjualan dari AI</p>
                </div>
            </div>
            <p class="insight-text" id="teks-analisa">Memuat analisa...</p>
        </div>

        <div class="insight-card">
            <div class="card-header">
                <div class="card-icon icon-blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div>
                    <p class="card-title" id="label-prediksi">Prediksi Besok</p>
                    <p class="card-sub">Estimasi dari AI berdasarkan tren</p>
                </div>
            </div>
            <p class="insight-text" id="teks-prediksi">Memuat prediksi...</p>
        </div>

        <div class="insight-card">
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
                <div class="warning-item">Memuat peringatan...</div>
            </div>
        </div>

        <div class="insight-card full">
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
                <div class="saran-item">Memuat saran...</div>
            </div>
            <button class="refresh-btn" id="btn-refresh" onclick="muatInsight(periodeAktif)">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                Muat Ulang Insight
            </button>
        </div>

        <div class="insight-card full">
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
                <div class="penjual-row"><span class="penjual-nama">Memuat...</span></div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    let periodeAktif = 'harian';

    function parseInsight(teks) {
        try {
            const clean = teks.replace(/```json|```/g, '').trim();
            return JSON.parse(clean);
        } catch (e) {
            return {
                analisa: teks,
                prediksi: '—',
                peringatan: [],
                saran: []
            };
        }
    }

    function tampilKosong(pesan) {
        document.getElementById('tgl-label').textContent     = pesan;
        document.getElementById('rekap-qty').textContent     = '—';
        document.getElementById('rekap-deposit').textContent = '—';
        document.getElementById('rekap-penjual').textContent = '—';
        document.getElementById('teks-analisa').textContent  = 'Belum ada data untuk periode ini.';
        document.getElementById('teks-prediksi').textContent = '—';
        document.getElementById('list-peringatan').innerHTML = '<div class="warning-item">Tidak ada peringatan.</div>';
        document.getElementById('list-saran').innerHTML      = '<div class="saran-item">Tidak ada saran.</div>';
        document.getElementById('list-penjual').innerHTML    = '<div class="penjual-row"><span class="penjual-nama">Belum ada data.</span></div>';
    }

    function render(data) {
        const labelTgl = data.date
            ? 'Data per tanggal: ' + data.date
            : 'Data periode: ' + (data.periode ?? '—');
        document.getElementById('tgl-label').textContent = labelTgl;

        document.getElementById('rekap-qty').textContent     = (data.rekap.total_qty_sold ?? '—') + ' pcs';
        document.getElementById('rekap-deposit').textContent = 'Rp ' + (data.rekap.total_deposit ?? '—');

        if (data.rekap.per_penjual) {
            // ── MODE HARIAN ──
            document.getElementById('label-rekap-penjual').textContent = 'Jumlah Penjual';
            document.getElementById('rekap-penjual').textContent       = data.rekap.per_penjual.length + ' orang';
            document.getElementById('label-prediksi').textContent      = 'Prediksi Besok';
            document.getElementById('label-detail-penjual').textContent = 'Detail Per Penjual';
            document.getElementById('sub-detail-penjual').textContent  = 'Rekap qty dan setoran tiap penjual';

            document.getElementById('list-penjual').innerHTML = data.rekap.per_penjual.map(p =>
                `<div class="penjual-row">
                    <span class="penjual-nama">${p.nama}</span>
                    <span class="penjual-val">${p.qty_sold} pcs &nbsp;|&nbsp; Rp ${p.total_deposit}</span>
                </div>`
            ).join('');
        } else {
            // ── MODE BULANAN ──
            document.getElementById('label-rekap-penjual').textContent  = 'Rata-rata / Hari';
            document.getElementById('rekap-penjual').textContent        = (data.rekap.rata_rata ?? '—') + ' pcs';
            document.getElementById('label-prediksi').textContent       = 'Tren Penjualan';
            document.getElementById('label-detail-penjual').textContent = 'Ringkasan Bulanan';
            document.getElementById('sub-detail-penjual').textContent   = 'Statistik operasional bulan ini';

            document.getElementById('list-penjual').innerHTML =
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
                </div>`;
        }

        const parsed = parseInsight(data.insight_ai);
        document.getElementById('teks-analisa').textContent  = parsed.analisa  || data.insight_ai;
        document.getElementById('teks-prediksi').textContent = parsed.prediksi || '—';

        document.getElementById('list-peringatan').innerHTML = parsed.peringatan && parsed.peringatan.length
            ? parsed.peringatan.map(w =>
                `<div class="warning-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    ${w}
                </div>`).join('')
            : '<div class="warning-item">Tidak ada peringatan.</div>';

        document.getElementById('list-saran').innerHTML = parsed.saran && parsed.saran.length
            ? parsed.saran.map(s =>
                `<div class="saran-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    ${s}
                </div>`).join('')
            : '<div class="saran-item">Tidak ada saran.</div>';
    }

    async function muatInsight(periode) {
        const btn = document.getElementById('btn-refresh');
        btn.classList.add('loading');
        btn.innerHTML = `
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            Memuat...
        `;

        try {
            const res = await fetch(`/boss/ai/insight/${periode}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();

            if (!res.ok) {
                tampilKosong(data.message ?? 'Tidak ada data untuk periode ini.');
            } else {
                render(data);
            }

        } catch (err) {
            console.error('Gagal memuat insight:', err);
            tampilKosong('Gagal memuat data. Periksa koneksi atau coba muat ulang.');
        }

        btn.classList.remove('loading');
        btn.innerHTML = `
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            Muat Ulang Insight
        `;
    }

    function gantiPeriode(btn, periode) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        periodeAktif = periode;
        muatInsight(periode);
    }

    document.addEventListener('DOMContentLoaded', function () {
        muatInsight('harian');
    });
</script>
@endpush

@endsection