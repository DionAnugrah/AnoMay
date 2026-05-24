@extends('layouts.admin')

@section('title', 'Katalog Item Siomay')

@section('content')
    <div class="flex justify-between items-center mb-5">
        <h2 class="font-poppins text-2xl font-bold text-anomay-dark">Katalog Item Siomay</h2>
        <a href="/admin/products/create" class="bg-anomay-orange text-white px-4 py-2 rounded-lg font-semibold shadow-sm hover:bg-orange-600 transition">
            + Tambah Produk
        </a>
    </div>

    @if (session('success'))
        <div class="mb-5 bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
            {{ session('error') }}
        </div>  
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-10">
        <table class="w-full text-left border-collapse">
            <thead class="bg-anomay-beige/50 border-b border-gray-200">
                <tr>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Nama Item</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Harga Jual</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($products as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-700">{{ $item->name }}</td>
                    <td class="p-4 text-gray-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="p-4 flex space-x-3">
                      <a href="/admin/products/{{ $item->id }}/edit" class="text-anomay-orange font-semibold hover:underline">Edit</a>
                      <form action="/admin/products/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 font-semibold hover:underline">Hapus</button>
                      </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection