@extends('layouts.penjual')

@section('title', 'Beranda - Penjual')

@section('content')
    
    @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm font-medium mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg text-sm font-medium mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-anomay-beige p-3 border-b border-gray-200">
            <h3 class="font-poppins font-bold text-anomay-dark">Katalog Item di Panci</h3>
        </div>
        <div class="p-4">
            <ul class="space-y-3">
                @foreach ($products as $item)
                <li class="flex justify-between items-center border-b border-gray-100 pb-2 last:border-0">
                    <span class="text-anomay-dark font-medium">{{ $item->name }}</span>
                    <span class="font-bold text-anomay-dark bg-gray-100 px-3 py-1 rounded-md">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-5 border-t-4 border-anomay-sage">
        <h3 class="font-poppins font-bold text-anomay-dark mb-4">Lapor Penjualan Harian</h3>
        <form action="/penjual/lapor-jualan" method="POST">
            @csrf
            
            @php
                $alokasiSaya = \App\Models\StockAllocation::where('user_id', auth()->id())->where('date', date('Y-m-d'))->first();
            @endphp
            
            @if($alokasiSaya)
                <input type="hidden" name="stock_allocation_id" value="{{ $alokasiSaya->id }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-anomay-dark mb-1">Berapa Siomay yang laku terjual hari ini?</label>
                        <input type="number" name="qty_sold" min="0" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-anomay-sage outline-none bg-gray-50" placeholder="Masukkan jumlah terjual..." required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-anomay-sage text-anomay-dark py-3 mt-6 rounded-lg font-poppins font-bold shadow-md hover:opacity-90 transition">Kirim Setoran</button>
            @else
                <p class="text-sm text-red-500 font-medium">Admin belum membagikan stok panci untuk Anda hari ini.</p>
            @endif
        </form>
    </div>
@endsection