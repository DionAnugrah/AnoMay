@extends('layouts.admin')

@section('title', 'Data Penjual - AnoMay')

@section('content')
<div class="dash">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="page-title">Master Data Penjual</h2>
            <p class="page-sub">Kelola seluruh akun penjual aktif</p>
        </div>
        <a href="/admin/users/create"
           class="bg-anomay-orange text-white px-4 py-2.5 rounded-xl font-poppins font-semibold text-sm shadow-sm hover:bg-orange-600 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Penjual
        </a>
    </div>

    {{-- Stat Card --}}
    <div class="stat-card mb-6">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(255,127,17,0.1)">
            <svg class="w-6 h-6 text-anomay-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="stat-label">Total Penjual</p>
            <p class="stat-value">{{ $users->count() }}</p>
            <p class="stat-sub">terdaftar di sistem</p>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="panel">

        <div class="panel-header">
            <h3 class="panel-title">Daftar Penjual</h3>
            <div class="search-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" oninput="filterTable()"
                       placeholder="Cari nama atau username..."
                       class="search-input">
            </div>
        </div>

        <table class="tbl">
            <thead>
                <tr>
                    <th>Penjual</th>
                    <th>Username</th>
                    <th class="w-28">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse ($users as $seller)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(255,127,17,0.12)">
                                <span class="text-xs font-bold text-anomay-orange">
                                    {{ strtoupper(substr($seller->name, 0, 1)) }}
                                </span>
                            </div>
                            <span class="td-name">{{ $seller->name }}</span>
                        </div>
                    </td>
                    <td>{{ $seller->username }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="/admin/users/{{ $seller->id }}/edit" class="btn-edit">Edit</a>
                            <form action="/admin/users/{{ $seller->id }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus penjual ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">
                        <div class="empty-state">
                            <svg class="w-12 h-12 mb-3 mx-auto" style="color:var(--border)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="font-medium">Belum ada data penjual</p>
                            <a href="/admin/users/create"
                               class="mt-3 inline-block text-xs bg-anomay-orange text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 transition">
                                Tambah Penjual Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="panel-footer">
            <p>Total {{ $users->count() }} penjual</p>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#tableBody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
@endpush