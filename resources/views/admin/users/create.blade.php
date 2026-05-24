@extends('layouts.admin')

@section('title', 'Tambah Penjual - AnoMay')

@section('content')
<div class="dash">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="/admin/users"
           class="w-9 h-9 rounded-xl flex items-center justify-center transition"
           style="background:var(--bg-card); border:0.5px solid var(--border); color:var(--text-muted)"
           onmouseover="this.style.color='#ff7f11'" onmouseout="this.style.color='var(--text-muted)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h2 class="page-title">Tambah Akun Penjual</h2>
            <p class="page-sub">Buat akun baru untuk penjual</p>
        </div>
    </div>

    {{-- Error --}}
    @if ($errors->any())
    <div class="flash-error max-w-xl">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="panel p-6 max-w-xl">
        <form action="/admin/users" method="POST">
            @csrf
            <input type="hidden" name="role" value="penjual">

            <div class="mb-5">
                <label class="block text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-muted)">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="Contoh: Budi Santoso"
                       class="filter-input w-full py-3" required>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-muted)">Username Login</label>
                <input type="text" name="username" value="{{ old('username') }}"
                       placeholder="Contoh: budi.s"
                       class="filter-input w-full py-3" required>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-muted)">Password</label>
                <input type="password" name="password"
                       placeholder="Minimal 8 karakter"
                       class="filter-input w-full py-3" required>
            </div>

            <div class="flex items-center gap-3 pt-2" style="border-top:0.5px solid var(--border-soft)">
                <button type="submit"
                        class="bg-anomay-orange text-white px-6 py-2.5 rounded-xl font-poppins font-semibold text-sm shadow-sm hover:bg-orange-600 transition">
                    Simpan Akun
                </button>
                <a href="/admin/users" class="btn-edit px-6 py-2.5">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection