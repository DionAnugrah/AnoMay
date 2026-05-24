@extends('layouts.admin')

@section('title', 'Data Penjual - AnoMay')

@section('content')
    <div class="flex justify-between items-center mb-5">
        <h2 class="font-poppins text-2xl font-bold text-anomay-dark">Master Data Penjual</h2>
        <a href="/admin/users/create" class="bg-anomay-orange text-white px-4 py-2 rounded-lg font-semibold shadow-sm hover:bg-orange-600 transition">
            + Tambah Penjual
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-10">
        <table class="w-full text-left border-collapse">
            <thead class="bg-anomay-beige/50 border-b border-gray-200">
                <tr>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Nama Penjual</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark">Username</th>
                    <th class="p-4 text-sm font-semibold text-anomay-dark w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($users as $seller)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-700">{{ $seller->name }}</td>
                    <td class="p-4 text-gray-700">{{ $seller->username }}</td>
                    <td class="p-4 flex space-x-3">
                        <a href="/admin/users/{{ $seller->id }}/edit" class="text-anomay-orange font-semibold hover:underline">Edit</a>
                        <form action="/admin/users/{{ $seller->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus penjual ini?');">
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