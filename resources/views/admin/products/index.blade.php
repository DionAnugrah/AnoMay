@extends('layouts.admin')
@section('title', 'Katalog Item Siomay - AnoMay')
@section('content')
<div class="dash">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="page-title">Katalog Item Siomay</h2>
            <p class="page-sub">Kelola daftar produk dan harga jual</p>
        </div>
        <a href="/admin/products/create"
           class="bg-anomay-orange text-white px-4 py-2.5 rounded-xl font-poppins font-semibold text-sm shadow-sm hover:bg-orange-600 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Produk
        </a>
    </div>

    @if(session('success'))
    <div class="flash-success">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flash-error">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    @if($products->isNotEmpty())
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(255,127,17,0.1)">
                <svg class="w-6 h-6 text-anomay-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
            </div>
            <div>
                <p class="stat-label">Total Produk</p>
                <p class="stat-value">{{ $products->count() }}</p>
                <p class="stat-sub">item di katalog</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <p class="stat-label">Harga Tertinggi</p>
                <p class="stat-value">Rp {{ number_format($products->max('price'), 0, ',', '.') }}</p>
                <p class="stat-sub">per porsi</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg>
            </div>
            <div>
                <p class="stat-label">Harga Terendah</p>
                <p class="stat-value">Rp {{ number_format($products->min('price'), 0, ',', '.') }}</p>
                <p class="stat-sub">per porsi</p>
            </div>
        </div>
    </div>
    @endif

    <div class="panel">
        <div class="panel-header">
            <p class="panel-title">Daftar Produk</p>
            <div class="search-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="searchInput" oninput="filterTable()" placeholder="Cari nama produk..." class="search-input">
            </div>
        </div>
        <div style="overflow-x:auto">
            <table class="tbl">
                <thead>
                    <tr><th>Nama Produk</th><th>Harga Jual</th><th style="width:7rem">Aksi</th></tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($products as $item)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(255,127,17,0.12)">
                                    <svg class="w-4 h-4 text-anomay-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                </div>
                                <span class="td-name">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight:600;color:var(--text-main)">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            <span style="font-size:0.7rem;color:var(--text-muted)"> / porsi</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="/admin/products/{{ $item->id }}/edit" class="btn-edit">Edit</a>
                                <form action="/admin/products/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3"><div class="empty-state">
                        <svg class="w-12 h-12 mb-3 mx-auto" style="color:var(--border)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                        <p class="font-medium">Belum ada produk di katalog</p>
                        <a href="/admin/products/create" class="mt-3 inline-block text-xs bg-anomay-orange text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 transition">Tambah Produk Pertama</a>
                    </div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="panel-footer"><p>Total {{ $products->count() }} produk</p></div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#tableBody tr').forEach(r => r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none');
}
</script>
@endpush