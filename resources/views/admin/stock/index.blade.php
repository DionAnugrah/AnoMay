@extends('layouts.admin')

@section('title', 'Alokasi Stok - AnoMay')

@section('content')
    <h2 class="font-poppins text-2xl font-bold text-anomay-dark mb-5">Distribusi Stok Panci Pagi</h2>
    
    @if (session('success'))
        <div class="mb-5 bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <form action="/admin/stock-allocations" method="POST" class="bg-white p-6 shadow-md rounded-lg mb-10">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            
            <div>
                <label class="block text-sm font-semibold text-anomay-dark mb-2">Penjual Keliling</label>
                <select name="user_id" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-anomay-orange outline-none bg-gray-50" required>
                    <option value="">-- Pilih Penjual --</option>
                    @foreach($sellers as $seller)
                        <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-anomay-dark mb-2">Item Siomay</label>
                <select name="product_id" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-anomay-orange outline-none bg-gray-50" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-anomay-dark mb-2">Jumlah Bawaan</label>
                <input type="number" name="qty_given" min="1" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-anomay-orange outline-none bg-gray-50" placeholder="0" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-anomay-dark mb-2">Tanggal</label>
                <input type="date" name="date" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-anomay-orange outline-none bg-gray-50" value="{{ date('Y-m-d') }}" required>
            </div>

        </div>
        <button type="submit" class="bg-anomay-sage text-anomay-dark px-6 py-2.5 rounded-lg font-poppins font-bold shadow-sm hover:opacity-90 transition">Simpan ke Panci</button>
    </form>

    <h2 class="font-poppins text-xl font-bold text-anomay-dark mb-4 mt-8">Riwayat Alokasi Stok</h2>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-anomay-beige/50 border-b border-gray-200">
                <tr>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Tanggal</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Penjual</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Item Siomay</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Jumlah Bawaan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($allocations as $alokasi)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-700">{{ \Carbon\Carbon::parse($alokasi->date)->translatedFormat('d M Y') }}</td>
                    <td class="p-4 text-gray-700">{{ $alokasi->user->name ?? 'N/A' }}</td>
                    <td class="p-4 text-gray-700">{{ $alokasi->product->name ?? 'N/A' }}</td>
                    <td class="p-4 text-anomay-orange font-bold">{{ $alokasi->qty_given }} pcs</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500 font-medium">Belum ada riwayat alokasi stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection