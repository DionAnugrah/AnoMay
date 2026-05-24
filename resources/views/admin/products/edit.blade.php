@extends('layouts.admin')

@section('title', 'Edit Produk - AnoMay')

@section('content')
    <div class="flex items-center mb-5 space-x-3">
        <a href="/admin/products" class="text-gray-400 hover:text-anomay-orange transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="font-poppins text-2xl font-bold text-anomay-dark">Edit Item Siomay</h2>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 max-w-2xl">
        @if ($errors->any())
            <div class="mb-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/products/{{ $product->id }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label class="block text-sm font-semibold text-anomay-dark mb-2">Nama Produk</label>
                <input type="text" name="name" value="{{ $product->name }}" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-anomay-orange outline-none bg-gray-50" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-anomay-dark mb-2">Harga Jual (Rp)</label>
                <input type="number" name="price" value="{{ $product->price }}" min="0" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-anomay-orange outline-none bg-gray-50" required>
            </div>

            <button type="submit" class="bg-anomay-orange text-white px-6 py-3 rounded-lg font-poppins font-bold shadow-sm hover:bg-orange-600 transition">Perbarui Produk</button>
        </form>
    </div>
@endsection