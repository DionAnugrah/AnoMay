@extends('layouts.admin')

@section('title', 'Alokasi Stok - AnoMay')

@section('content')
<div class="dash">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="page-title">Distribusi Stok Pagi</h2>
            <p class="page-sub">Bagikan stok ke penjual untuk hari ini</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
    <div class="flash-success">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="flash-error">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stat Cards --}}
    @if($allocations->isNotEmpty())
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(255,127,17,0.1)">
                <svg class="w-6 h-6 text-anomay-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="stat-label">Total Riwayat</p>
                <p class="stat-value">{{ $allocations->count() }}</p>
                <p class="stat-sub">entri alokasi</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="stat-label">Alokasi Hari Ini</p>
                <p class="stat-value">
                    {{ $allocations->filter(fn($a) => \Carbon\Carbon::parse($a->date)->isToday())->count() }}
                </p>
                <p class="stat-sub">{{ now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
            </div>
            <div>
                <p class="stat-label">Total Porsi Dibagikan</p>
                <p class="stat-value">{{ $allocations->sum('qty_given') }}</p>
                <p class="stat-sub">pcs keseluruhan</p>
            </div>
        </div>

    </div>
    @endif

    {{-- Form Tambah Alokasi --}}
    <div class="panel p-6 mb-6">
        <h3 class="panel-title mb-4">Tambah Alokasi Stok</h3>
        <form action="/admin/stock-allocations" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-muted)">Penjual</label>
                    <select name="user_id" class="filter-select w-full py-2.5" required>
                        <option value="">-- Pilih Penjual --</option>
                        @foreach($sellers as $seller)
                            <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-muted)">Item Siomay</label>
                    <select name="product_id" class="filter-select w-full py-2.5" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-muted)">Jumlah Bawaan</label>
                    <input type="number" name="qty_given" min="1" placeholder="0"
                           class="filter-input w-full py-2.5" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-muted)">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}"
                           class="filter-input w-full py-2.5" required>
                </div>

            </div>
            <button type="submit"
                    class="bg-anomay-orange text-white px-6 py-2.5 rounded-xl font-poppins font-semibold text-sm shadow-sm hover:bg-orange-600 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Simpan Alokasi
            </button>
        </form>
    </div>

    {{-- Riwayat Alokasi --}}
    <div class="panel">

        <div class="panel-header">
            <h3 class="panel-title">Riwayat Alokasi Stok</h3>

            {{-- Filter --}}
            <form method="GET" action="/admin/stock-allocations" class="flex items-center gap-2">
                <select name="user_id" onchange="this.form.submit()" class="filter-select">
                    <option value="">Semua Penjual</option>
                    @foreach($sellers as $seller)
                        <option value="{{ $seller->id }}" {{ request('user_id') == $seller->id ? 'selected' : '' }}>
                            {{ $seller->name }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ request('date') }}"
                       onchange="this.form.submit()" class="filter-input">
                @if(request('date') || request('user_id'))
                    <a href="/admin/stock-allocations" class="filter-reset">✕ Reset</a>
                @endif
            </form>
        </div>

        <table class="tbl">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Penjual</th>
                    <th>Item Siomay</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($allocations as $alokasi)
                <tr>
                    <td>
                        @if(\Carbon\Carbon::parse($alokasi->date)->isToday())
                            <span class="badge-pending">Hari ini</span>
                        @else
                            {{ \Carbon\Carbon::parse($alokasi->date)->translatedFormat('d M Y') }}
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(255,127,17,0.12)">
                                <span class="text-xs font-bold text-anomay-orange">
                                    {{ strtoupper(substr($alokasi->user->name ?? '?', 0, 1)) }}
                                </span>
                            </div>
                            <span class="td-name">{{ $alokasi->user->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>{{ $alokasi->product->name ?? 'N/A' }}</td>
                    <td>
                        <span class="text-sm font-bold text-anomay-orange">{{ $alokasi->qty_given }}</span>
                        <span class="stat-sub ml-1">pcs</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <svg class="w-12 h-12 mb-3 mx-auto" style="color:var(--border)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                            <p class="font-medium">Belum ada riwayat alokasi stok</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="panel-footer">
            <p>Total {{ $allocations->count() }} entri alokasi</p>
        </div>

    </div>

</div>
@endsection