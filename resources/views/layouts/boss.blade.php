<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Boss')</title>

    @vite('resources/css/app.css')

    @stack('styles')
</head>
<body class="flex h-screen bg-gray-50 font-inter">

    {{-- Sidebar --}}
    <div class="w-64 bg-anomay-dark text-white flex flex-col shadow-md z-10 flex-shrink-0">

        {{-- Logo --}}
        <div class="p-6 border-b border-gray-700">
            <h1 class="font-poppins text-2xl font-bold text-anomay-orange">AnoMay</h1>
            <span class="text-gray-300 text-xs uppercase tracking-wider font-semibold">Panel Boss</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 p-4 space-y-1 mt-2">

            <a href="/boss/dashboard"
               class="flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium hover:bg-gray-800 transition
                      {{ request()->is('boss/dashboard') ? 'bg-gray-800 text-anomay-orange' : 'text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="/boss/live-map"
               class="flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium hover:bg-gray-800 transition
                      {{ request()->is('boss/live-map') ? 'bg-gray-800 text-anomay-orange' : 'text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span>Peta Penjual</span>
            </a>

            <a href="/boss/ai/insight"
               class="flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium hover:bg-gray-800 transition
                      {{ request()->is('boss/ai/insight') ? 'bg-gray-800 text-anomay-orange' : 'text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <span>Insight AI</span>
            </a>

        </nav>

        {{-- User info + Logout --}}
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

    {{-- Main Content --}}
    <div class="flex-1 overflow-y-auto">
        @yield('content')
    </div>

    @stack('scripts')

</body>
</html>