@extends('layouts.admin')
@section('title', 'Validasi Laporan - AnoMay')
@section('content')
<div class="dash">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="page-title">Validasi Laporan Penjualan</h2>
            <p class="page-sub">Periksa dan validasi laporan dari penjual</p>
        </div>
    </div>

    @if(session('success'))
    <div class="flash-success">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error') || $errors->any())
    <div class="flash-error">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') ?? $errors->first() }}
    </div>
    @endif

    @if($reports->isNotEmpty())
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(255,127,17,0.1)">
                <svg class="w-6 h-6 text-anomay-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="stat-label">Total Laporan</p>
                <p class="stat-value">{{ $reports->count() }}</p>
                <p class="stat-sub">semua laporan masuk</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="stat-label">Menunggu Validasi</p>
                <p class="stat-value">{{ $reports->where('status','pending')->count() }}</p>
                <p class="stat-sub" style="color:{{ $reports->where('status','pending')->count() > 0 ? '#eab308' : 'var(--text-muted)' }}">
                    {{ $reports->where('status','pending')->count() > 0 ? 'Perlu ditindaklanjuti' : 'Semua beres ✓' }}
                </p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-anomay-orange to-orange-400 rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-white/70 uppercase tracking-wide">Total Setoran Tervalidasi</p>
                <p class="font-poppins text-xl font-bold text-white">Rp {{ number_format($reports->where('status','accepted')->sum('total_deposit'), 0, ',', '.') }}</p>
                <p class="text-xs text-white/70">dari laporan tervalidasi</p>
            </div>
        </div>
    </div>
    @endif

    <div class="panel">
        <div class="panel-header">
            <p class="panel-title">Daftar Laporan</p>
            <form method="GET" action="/admin/laporan" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status')==='pending'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="accepted" {{ request('status')==='accepted' ? 'selected' : '' }}>Tervalidasi</option>
                </select>
                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="filter-input">
                @if(request('status') || request('date'))
                    <a href="/admin/laporan" class="filter-reset">✕ Reset</a>
                @endif
            </form>
        </div>
        <div style="overflow-x:auto">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Tanggal</th><th>Penjual</th><th>Item Siomay</th><th>Sisa</th><th>Setoran</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($report->date)->translatedFormat('d M Y') }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(255,127,17,0.12)">
                                    <span class="text-xs font-bold text-anomay-orange">{{ strtoupper(substr($report->user->name ?? '?', 0, 1)) }}</span>
                                </div>
                                <span class="td-name">{{ $report->user->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>{{ $report->stockAllocation?->product?->name ?? 'N/A' }}</td>
                        <td><span style="font-weight:700;color:#ef4444">{{ $report->qty_returned }}</span> <span style="font-size:0.7rem;color:var(--text-muted)">pcs</span></td>
                        <td><span style="font-weight:700;color:#16a34a">Rp {{ number_format($report->total_deposit, 0, ',', '.') }}</span></td>
                        <td>
                            @if($report->status === 'pending')
                                <span class="badge-pending">Menunggu</span>
                            @else
                                <span class="badge-valid">✓ Tervalidasi</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                @if($report->status === 'pending')
                                    <form action="/admin/laporan/{{ $report->id }}/status" method="POST" class="inline m-0">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn-validate">Validasi</button>
                                    </form>
                                @else
                                    <span class="btn-validated-disabled">Tervalidasi</span>
                                @endif
                                <form action="/admin/laporan/{{ $report->id }}" method="POST" class="inline m-0"
                                      onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7"><div class="empty-state">
                        <svg class="w-12 h-12 mb-3 mx-auto" style="color:var(--border)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="font-medium">Belum ada laporan masuk</p>
                    </div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="panel-footer"><p>Total {{ $reports->count() }} laporan</p></div>
    </div>

</div>
@endsection