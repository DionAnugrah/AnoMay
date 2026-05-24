@extends('layouts.penjual')

@section('title', 'Riwayat Setoran - Penjual')

@section('content')
    <h2 class="font-poppins font-bold text-xl text-anomay-dark mb-4 px-1">Riwayat Setoran</h2>

    <div class="space-y-4">
        @forelse ($reports as $report)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $report->status === 'accepted' ? 'bg-green-500' : 'bg-yellow-400' }}"></div>
            
            <div class="flex justify-between items-start mb-3 pl-2">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block mb-1">
                        {{ \Carbon\Carbon::parse($report->date)->translatedFormat('l, d M Y') }}
                    </span>
                    <h3 class="font-poppins font-bold text-anomay-dark">{{ $report->stockAllocation?->product?->name ?? 'Produk Siomay' }}</h3>
                </div>
                
                @if($report->status === 'accepted')
                    <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-md border border-green-200">
                        Tervalidasi
                    </span>
                @else
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2 py-1 rounded-md border border-yellow-200">
                        Menunggu
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-lg pl-2">
                <div>
                    <span class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Laku</span>
                    <p class="font-bold text-anomay-dark">{{ $report->qty_sold }} pcs</p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Sisa Panci</span>
                    <p class="font-bold text-red-500">{{ $report->qty_returned }} pcs</p>
                </div>
            </div>

            <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center pl-2">
                <span class="text-xs font-semibold text-gray-500">Total Setoran</span>
                <span class="font-poppins font-bold text-green-600">Rp {{ number_format($report->total_deposit, 0, ',', '.') }}</span>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <p class="text-gray-500 font-medium text-sm">Anda belum memiliki riwayat setoran.</p>
        </div>
        @endforelse
    </div>
@endsection