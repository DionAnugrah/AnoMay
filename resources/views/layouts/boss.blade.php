<!DOCTYPE html>
<html lang="id" dir="ltr" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Boss')</title>

    @vite('resources/css/app.css')

    <style>
        :root {
            --sidebar-bg      : #ffffff;
            --sidebar-border  : #e5e7eb;
            --sidebar-divider : #f3f4f6;
            --sidebar-text    : #4b5563;
            --sidebar-muted   : #9ca3af;
            --nav-hover-bg    : #fff7ed;
            --nav-active-bg   : #fff7ed;
            --nav-active-text : #ff7f11;
            --user-name       : #1f2937;

            --bg-page         : #f9fafb;
            --bg-card         : #ffffff;
            --bg-card2        : #f9fafb;
            --border          : #e5e7eb;
            --border-soft     : #f3f4f6;
            --text-main       : #262626;
            --text-sub        : #374151;
            --text-muted      : #9ca3af;
            --shadow          : rgba(0,0,0,0.04);
        }
        html.dark {
            --sidebar-bg      : #0f172a;
            --sidebar-border  : #1e293b;
            --sidebar-divider : #1e293b;
            --sidebar-text    : #cbd5e1;
            --sidebar-muted   : #64748b;
            --nav-hover-bg    : #1e293b;
            --nav-active-bg   : #1e293b;
            --nav-active-text : #ff7f11;
            --user-name       : #f1f5f9;

            --bg-page         : #1e293b;
            --bg-card         : #273549;
            --bg-card2        : #1e293b;
            --border          : #334155;
            --border-soft     : #2d3f55;
            --text-main       : #f1f5f9;
            --text-sub        : #cbd5e1;
            --text-muted      : #64748b;
            --shadow          : rgba(0,0,0,0.2);
        }

        body { background: var(--bg-page); transition: background 0.2s; }

        #sidebar {
            background   : var(--sidebar-bg);
            border-right : 1px solid var(--sidebar-border);
            transition   : background 0.2s, border-color 0.2s;
        }
        #sidebar-logo-divider { border-color: var(--sidebar-divider); transition: border-color 0.2s; }
        #sidebar-bottom       { border-color: var(--sidebar-divider); transition: border-color 0.2s; }

        .nav-link { color: var(--sidebar-text); transition: background 0.15s, color 0.15s; }
        .nav-link:hover  { background: var(--nav-hover-bg);  color: var(--nav-active-text); }
        .nav-link.active { background: var(--nav-active-bg); color: var(--nav-active-text); }

        #sidebar-label { color: var(--sidebar-muted); transition: color 0.2s; }
        #user-name     { color: var(--user-name);     transition: color 0.2s; }
        #user-role     { color: var(--sidebar-muted); transition: color 0.2s; }
        #toggle-label  { color: var(--sidebar-muted); transition: color 0.2s; }

        #main-content { background: var(--bg-page); transition: background 0.2s; }
    </style>

    @stack('styles')
</head>
<body class="flex h-screen font-inter">

    <div id="sidebar" class="w-64 flex flex-col shadow-sm z-10 flex-shrink-0">

        <div id="sidebar-logo-divider" class="p-6 border-b">
            <h1 class="font-poppins text-2xl font-bold" style="color:#ff7f11">AnoMay</h1>
            <span id="sidebar-label" class="text-xs uppercase tracking-wider font-semibold">Panel Boss</span>
        </div>

        <nav class="flex-1 p-4 space-y-1 mt-2">
            <a href="/boss/dashboard"
               class="nav-link {{ request()->is('boss/dashboard') ? 'active' : '' }} flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="/boss/live-map"
               class="nav-link {{ request()->is('boss/live-map') ? 'active' : '' }} flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span>Peta Penjual</span>
            </a>
            <a href="/boss/ai/insight"
               class="nav-link {{ request()->is('boss/ai/insight') ? 'active' : '' }} flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <span>Insight AI</span>
            </a>
        </nav>

        <div id="sidebar-bottom" class="mt-auto border-t">
            <div class="px-5 py-3 flex items-center justify-between">
                <span id="toggle-label" class="text-xs font-medium">Tampilan</span>
                <label style="display:flex;align-items:center;cursor:pointer" onclick="toggleDark()">
                    <div id="toggle-track-boss" style="position:relative;width:44px;height:24px;background:#e5e7eb;border-radius:999px;transition:background 0.3s;flex-shrink:0">
                        <div id="toggle-thumb-boss" style="position:absolute;top:3px;left:3px;width:18px;height:18px;background:#fff;border-radius:50%;transition:transform 0.3s;box-shadow:0 1px 3px rgba(0,0,0,0.2);display:flex;align-items:center;justify-content:center;overflow:hidden">
                            <span id="thumb-icon-sun-boss" style="position:absolute;font-size:11px;transition:opacity 0.2s;opacity:1">☀️</span>
                            <span id="thumb-icon-moon-boss" style="position:absolute;font-size:11px;transition:opacity 0.2s;opacity:0">🌙</span>
                        </div>
                    </div>
                </label>
            </div>
            <div class="px-5 py-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:#ff7f11">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p id="user-name" class="text-sm font-semibold font-poppins truncate">{{ Auth::user()->name ?? 'Boss' }}</p>
                    <p id="user-role" class="text-xs truncate">Owner</p>
                </div>
            </div>
            <div class="px-4 pb-4">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full text-left flex items-center gap-3 p-3 rounded-lg transition text-red-400 hover:bg-red-50 hover:text-red-600">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="font-medium font-poppins">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="main-content" class="flex-1 overflow-y-auto">
        @yield('content')
    </div>

    @stack('scripts')

    <script>
        function applyTheme(isDark) {
            isDark
                ? document.getElementById('html-root').classList.add('dark')
                : document.getElementById('html-root').classList.remove('dark');
            document.getElementById('toggle-track-boss').style.background = isDark ? '#ff7f11' : '#e5e7eb';
            document.getElementById('toggle-thumb-boss').style.transform  = isDark ? 'translateX(20px)' : 'translateX(0)';
            document.getElementById('thumb-icon-sun-boss').style.opacity  = isDark ? '0' : '1';
            document.getElementById('thumb-icon-moon-boss').style.opacity = isDark ? '1' : '0';
            if (typeof updateMapTile === 'function') updateMapTile(isDark);
        }
        function toggleDark() {
            const isDark = !document.getElementById('html-root').classList.contains('dark');
            applyTheme(isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
        applyTheme(localStorage.getItem('theme') === 'dark');
    </script>

</body>
</html>