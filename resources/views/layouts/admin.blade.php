<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - AnoMay')</title>
    @vite('resources/css/app.css')
</head>
<body class="flex h-screen bg-gray-50 font-inter">
    
    <div class="w-64 bg-anomay-dark text-white flex flex-col shadow-md z-10">
        <div class="p-6 border-b border-gray-700">
            <h1 class="font-poppins text-2xl font-bold text-anomay-orange">AnoMay</h1>
            <span class="text-gray-300 text-xs uppercase tracking-wider font-semibold">Panel Admin</span>
        </div>
        <nav class="flex-1 p-4 space-y-2 mt-2">
            <a href="/admin/dashboard" class="block py-2.5 px-4 rounded-lg font-medium hover:bg-gray-800 transition">Dashboard</a>
            <a href="/admin/users" class="block py-2.5 px-4 rounded-lg font-medium hover:bg-gray-800 transition">Data Penjual</a>
            <a href="/admin/products" class="block py-2.5 px-4 rounded-lg font-medium hover:bg-gray-800 transition">Katalog Produk</a>
            <a href="/admin/stock-allocations" class="block py-2.5 px-4 rounded-lg font-medium hover:bg-gray-800 transition">Alokasi Stok Pagi</a>
            <a href="/admin/laporan" class="block py-2.5 px-4 rounded-lg font-medium hover:bg-gray-800 transition">Validasi Laporan</a>
        </nav>
        <div class="mt-auto border-t border-gray-700">
            <div class="px-5 py-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-anomay-orange flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold font-poppins text-white truncate">
                        {{ Auth::user()->name ?? 'Boss' }}
                    </p>
                    <p class="text-xs text-gray-400 truncate">Owner</p>
                </div>
            </div>
            <div class="px-4 pb-4">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full text-left flex items-center gap-3 text-red-400 p-3 rounded-lg hover:bg-red-500 hover:text-white transition">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="font-medium font-poppins">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="flex-1 p-8 overflow-y-auto">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>