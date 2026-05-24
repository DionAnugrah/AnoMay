<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Penjual - AnoMay')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 pb-24 font-inter antialiased"> 

    <header class="bg-anomay-orange text-white p-5 shadow-md sticky top-0 z-10">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="font-poppins text-xl font-bold">{{ auth()->user()->name ?? 'Nama Penjual' }}</h1>
                <p class="text-xs font-medium opacity-90 mt-0.5">Penjual Siomay Keliling</p>
            </div>
        </div>
    </header>

    <main class="p-4 space-y-5">
        @yield('content')
    </main>

    <nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 flex justify-around p-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
        
        <a href="/penjual/dashboard" class="flex-1 flex flex-col items-center justify-center {{ request()->is('penjual/dashboard') ? 'text-anomay-orange' : 'text-gray-400 hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-[10px] font-poppins font-semibold">Beranda</span>
        </a>
        
        <a href="/penjual/riwayat-jualan" class="flex-1 flex flex-col items-center justify-center {{ request()->is('penjual/riwayat-jualan') ? 'text-anomay-orange' : 'text-gray-400 hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <span class="text-[10px] font-poppins font-semibold">Riwayat</span>
        </a>
        
        <form action="/logout" method="POST" class="flex-1 flex justify-center">
            @csrf
            <button type="submit" class="w-full flex flex-col items-center justify-center text-red-400 hover:text-red-600 transition">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="text-[10px] font-semibold">Keluar</span>
            </button>
        </form>

    </nav>
    @stack('scripts')
</body>
</html>