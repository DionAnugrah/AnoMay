@extends('layouts.admin')

@section('title', 'Edit Produk - AnoMay')

@section('content')
<div class="dash">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="/admin/products"
           class="w-9 h-9 rounded-xl flex items-center justify-center transition"
           style="background:var(--bg-card); border:0.5px solid var(--border); color:var(--text-muted)"
           onmouseover="this.style.color='#ff7f11'" onmouseout="this.style.color='var(--text-muted)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h2 class="page-title">Edit Item Siomay</h2>
            <p class="page-sub">{{ $product->name }}</p>
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
        <form action="/admin/products/{{ $product->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-muted)">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                       class="filter-input w-full py-3" required>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--text-muted)">Harga Jual</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold" style="color:var(--text-muted)">Rp</span>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0"
                           class="filter-input w-full py-3 pl-10" required>
                </div>
                <p class="text-xs mt-1.5" style="color:var(--text-muted)">Harga per porsi yang akan dibayar pembeli</p>
            </div>

            {{-- Info harga saat ini --}}
            <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl"
                 style="background:rgba(255,127,17,0.08); border:0.5px solid rgba(255,127,17,0.2)">
                <svg class="w-4 h-4 text-anomay-orange flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-anomay-orange">
                    Harga saat ini <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong> per porsi
                </p>
            </div>

            <div class="flex items-center gap-3 pt-2" style="border-top:0.5px solid var(--border-soft)">
                <button type="submit"
                        class="bg-anomay-orange text-white px-6 py-2.5 rounded-xl font-poppins font-semibold text-sm shadow-sm hover:bg-orange-600 transition">
                    Perbarui Produk
                </button>
                <a href="/admin/products" class="btn-edit px-6 py-2.5">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection