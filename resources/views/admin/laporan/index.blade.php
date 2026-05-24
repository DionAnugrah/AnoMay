@extends('layouts.admin')

@section('title', 'Validasi Laporan - AnoMay')

@section('content')
    <h2 class="font-poppins text-2xl font-bold text-anomay-dark mb-5">Validasi Laporan Penjualan</h2>

    @if (session('success'))
        <div class="mb-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error') || $errors->any())
        <div class="mb-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
            {{ session('error') ?? $errors->first() }}
        </div>
    @endif
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-10">
        <table class="w-full text-left border-collapse">
            <thead class="bg-anomay-beige/50 border-b border-gray-200">
                <tr>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Tanggal</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Penjual</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Item Siomay</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Sisa Panci</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Setoran</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Status</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($reports as $report)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-700">{{ \Carbon\Carbon::parse($report->date)->translatedFormat('d M Y') }}</td>
                    <td class="p-4 text-gray-700 font-medium">{{ $report->user->name ?? 'N/A' }}</td>
                    <td class="p-4 text-gray-700">{{ $report->stockAllocation->product->name ?? 'N/A' }}</td>
                    <td class="p-4 text-red-500 font-bold">{{ $report->qty_returned }} pcs</td>
                    <td class="p-4 text-green-600 font-bold">Rp {{ number_format($report->total_deposit, 0, ',', '.') }}</td>
                    <td class="p-4">
                        @if($report->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full border border-yellow-200">Menunggu</span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full border border-green-200">Tervalidasi</span>
                        @endif
                    </td>
                    <td class="p-4 flex space-x-3 items-center">
                        @if($report->status === 'pending')
                            <form action="/admin/laporan/{{ $report->id }}/status" method="POST" class="inline m-0">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="text-green-600 font-semibold hover:underline">Validasi</button>
                            </form>
                        @else
                            <button class="text-gray-400 font-semibold cursor-not-allowed" disabled>Tervalidasi</button>
                        @endif

                        <form action="/admin/laporan/{{ $report->id }}" method="POST" class="inline m-0" onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 font-semibold hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-6 text-center text-gray-500 font-medium">Belum ada data laporan penjualan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection