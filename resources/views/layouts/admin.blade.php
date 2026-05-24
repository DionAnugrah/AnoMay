<!DOCTYPE html>
<html lang="id" dir="ltr" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - AnoMay')</title>
    @vite('resources/css/app.css')

    <style>
        /* ── CSS Variables ── */
        :root {
            --bg-page         : #f7f8fa;
            --bg-card         : #ffffff;
            --bg-card2        : #f9fafb;
            --border          : #e5e7eb;
            --border-soft     : #f3f4f6;
            --text-main       : #1a1a2e;
            --text-sub        : #374151;
            --text-muted      : #9ca3af;
            --shadow          : rgba(0,0,0,0.04);
            --sidebar-bg      : #ffffff;
            --sidebar-border  : #e5e7eb;
            --sidebar-divider : #f3f4f6;
            --sidebar-text    : #4b5563;
            --sidebar-muted   : #9ca3af;
            --nav-hover-bg    : #fff7ed;
            --nav-active-bg   : #fff7ed;
            --nav-active-text : #ff7f11;
            --user-name       : #1f2937;
        }
        html.dark {
            --bg-page         : #1e293b;
            --bg-card         : #273549;
            --bg-card2        : #1e293b;
            --border          : #334155;
            --border-soft     : #2d3f55;
            --text-main       : #f1f5f9;
            --text-sub        : #cbd5e1;
            --text-muted      : #64748b;
            --shadow          : rgba(0,0,0,0.2);
            --sidebar-bg      : #0f172a;
            --sidebar-border  : #1e293b;
            --sidebar-divider : #1e293b;
            --sidebar-text    : #cbd5e1;
            --sidebar-muted   : #64748b;
            --nav-hover-bg    : #1e293b;
            --nav-active-bg   : #1e293b;
            --nav-active-text : #ff7f11;
            --user-name       : #f1f5f9;
        }

        body { transition: background 0.2s; }

        /* ── Sidebar ── */
        #sidebar {
            background  : var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            transition  : background 0.2s, border-color 0.2s;
        }
        #sidebar-logo-divider { border-color: var(--sidebar-divider); transition: border-color 0.2s; }
        #sidebar-bottom       { border-color: var(--sidebar-divider); transition: border-color 0.2s; }
        .nav-link { color: var(--sidebar-text); transition: background 0.15s, color 0.15s; }
        .nav-link:hover  { background: var(--nav-hover-bg);  color: var(--nav-active-text); }
        .nav-link.active { background: var(--nav-active-bg); color: var(--nav-active-text); }
        #sidebar-label { color: var(--sidebar-muted); transition: color 0.2s; }
        #user-name     { color: var(--user-name);     transition: color 0.2s; }
        #user-role     { color: var(--sidebar-muted); transition: color 0.2s; }
        #btn-toggle-admin {
            background: var(--nav-hover-bg);
            color     : var(--sidebar-text);
            transition: background 0.15s;
        }
        #btn-toggle-admin:hover { filter: brightness(0.95); }

        /* ── Main content ── */
        #main-content {
            background: var(--bg-page);
            transition: background 0.2s;
        }

        /* ── Shared page layout ── */
        .dash { padding: 1.5rem; background: var(--bg-page); min-height: 100vh; transition: background 0.2s; }
        .dash .page-title { font-family:'Poppins',sans-serif; font-size:1.25rem; font-weight:700; color:var(--text-main); transition:color 0.2s; }
        .dash .page-sub   { font-size:0.8rem; color:var(--text-muted); margin-top:2px; transition:color 0.2s; }

        /* ── Flash ── */
        .flash-success { background:#f0fdf4; border:0.5px solid #bbf7d0; color:#15803d; padding:0.75rem 1rem; border-radius:0.75rem; font-size:0.8rem; display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem; }
        .flash-error   { background:#fef2f2; border:0.5px solid #fecaca; color:#dc2626; padding:0.75rem 1rem; border-radius:0.75rem; font-size:0.8rem; display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem; }
        html.dark .flash-success { background:#052e16; border-color:#166534; color:#86efac; }
        html.dark .flash-error   { background:#450a0a; border-color:#991b1b; color:#fca5a5; }

        /* ── Stat card ── */
        .stat-card  { background:var(--bg-card); border:0.5px solid var(--border); border-radius:1rem; padding:1.25rem; box-shadow:0 1px 4px var(--shadow); display:flex; align-items:center; gap:1rem; transition:background 0.2s,border-color 0.2s; }
        .stat-label { font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted); transition:color 0.2s; }
        .stat-value { font-family:'Poppins',sans-serif; font-size:1.4rem; font-weight:700; color:var(--text-main); transition:color 0.2s; }
        .stat-sub   { font-size:0.7rem; color:var(--text-muted); transition:color 0.2s; }

        /* ── Panel ── */
        .panel         { background:var(--bg-card); border:0.5px solid var(--border); border-radius:1rem; box-shadow:0 1px 4px var(--shadow); overflow:hidden; transition:background 0.2s,border-color 0.2s; }
        .panel-header  { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:0.5px solid var(--border-soft); transition:border-color 0.2s; }
        .panel-title   { font-family:'Poppins',sans-serif; font-size:0.85rem; font-weight:600; color:var(--text-main); transition:color 0.2s; }
        .panel-sub     { font-size:0.7rem; color:var(--text-muted); margin-top:1px; transition:color 0.2s; }
        .panel-footer  { padding:0.65rem 1.25rem; border-top:0.5px solid var(--border-soft); transition:border-color 0.2s; }
        .panel-footer p { font-size:0.7rem; color:var(--text-muted); transition:color 0.2s; }
        .panel-row     { display:flex; align-items:center; justify-content:space-between; padding:0.75rem 1.25rem; border-bottom:0.5px solid var(--border-soft); transition:background 0.15s,border-color 0.2s; }
        .panel-row:last-child { border-bottom:none; }
        .panel-row:hover      { background:var(--bg-card2); }
        .row-name { font-size:0.85rem; font-weight:600; color:var(--text-main); transition:color 0.2s; }
        .row-sub  { font-size:0.7rem; color:var(--text-muted); transition:color 0.2s; }

        /* ── Table ── */
        .tbl         { width:100%; border-collapse:collapse; }
        .tbl thead   { background:var(--bg-card2); border-bottom:0.5px solid var(--border-soft); transition:background 0.2s; }
        .tbl th      { padding:0.75rem 1.25rem; text-align:left; font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-muted); transition:color 0.2s; }
        .tbl td      { padding:0.8rem 1.25rem; border-bottom:0.5px solid var(--border-soft); font-size:0.85rem; color:var(--text-sub); transition:color 0.2s,border-color 0.2s; }
        .tbl tbody tr:last-child td { border-bottom:none; }
        .tbl tbody tr               { transition:background 0.1s; }
        .tbl tbody tr:hover         { background:var(--bg-card2); }
        .td-name { font-size:0.85rem; font-weight:600; color:var(--text-main); transition:color 0.2s; }

        /* ── Filter ── */
        .filter-select,
        .filter-input,
        .search-input {
            border:0.5px solid var(--border); border-radius:0.625rem;
            padding:0.375rem 0.75rem; font-size:0.75rem;
            background:var(--bg-card2); color:var(--text-sub); outline:none;
            transition:background 0.2s,border-color 0.2s,color 0.2s;
        }
        .filter-select:focus,
        .filter-input:focus,
        .search-input:focus { border-color:#ff7f11; box-shadow:0 0 0 2px rgba(255,127,17,0.15); }
        .filter-reset { font-size:0.75rem; color:var(--text-muted); padding:0 0.5rem; transition:color 0.15s; text-decoration:none; }
        .filter-reset:hover { color:#ef4444; }
        .search-wrap     { position:relative; }
        .search-wrap svg { position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:var(--text-muted); pointer-events:none; }
        .search-input    { padding-left:2.25rem; width:13rem; }

        /* ── Badges ── */
        .badge-pending { font-size:0.7rem; font-weight:700; padding:0.25rem 0.625rem; border-radius:999px; background:#fefce8; color:#a16207; border:0.5px solid #fde68a; }
        .badge-valid   { font-size:0.7rem; font-weight:700; padding:0.25rem 0.625rem; border-radius:999px; background:#f0fdf4; color:#15803d; border:0.5px solid #bbf7d0; }
        html.dark .badge-pending { background:#422006; color:#fcd34d; border-color:#78350f; }
        html.dark .badge-valid   { background:#052e16; color:#86efac; border-color:#166534; }

        /* ── Buttons ── */
        .btn-edit     { font-size:0.7rem; font-weight:700; padding:0.35rem 0.75rem; border-radius:0.5rem; background:rgba(255,127,17,0.08); color:#ff7f11; border:0.5px solid rgba(255,127,17,0.25); text-decoration:none; display:inline-block; transition:background 0.15s; }
        .btn-edit:hover { background:rgba(255,127,17,0.16); }
        .btn-validate { font-size:0.7rem; font-weight:700; padding:0.35rem 0.75rem; border-radius:0.5rem; background:#f0fdf4; color:#15803d; border:0.5px solid #bbf7d0; cursor:pointer; transition:background 0.15s; }
        .btn-validate:hover { background:#dcfce7; }
        html.dark .btn-validate       { background:#052e16; color:#86efac; border-color:#166534; }
        html.dark .btn-validate:hover { background:#14532d; }
        .btn-validated-disabled { font-size:0.7rem; font-weight:700; padding:0.35rem 0.75rem; border-radius:0.5rem; background:var(--bg-card2); color:var(--text-muted); border:0.5px solid var(--border); cursor:not-allowed; }
        .btn-delete       { font-size:0.7rem; font-weight:700; padding:0.35rem 0.75rem; border-radius:0.5rem; background:#fef2f2; color:#dc2626; border:0.5px solid #fecaca; cursor:pointer; transition:background 0.15s; }
        .btn-delete:hover { background:#fee2e2; }
        html.dark .btn-delete       { background:#450a0a; color:#fca5a5; border-color:#991b1b; }
        html.dark .btn-delete:hover { background:#7f1d1d; }

        /* ── Quick action ── */
        .qa-item       { display:flex; align-items:center; gap:0.75rem; padding:0.75rem; border-radius:0.75rem; transition:background 0.15s; text-decoration:none; }
        .qa-item:hover { background:var(--bg-card2); }
        .qa-label      { font-size:0.85rem; font-weight:500; color:var(--text-sub); transition:color 0.2s; }

        /* ── Empty state ── */
        .empty-state { text-align:center; padding:3rem 1rem; color:var(--text-muted); font-size:0.85rem; transition:color 0.2s; }
    </style>

    @stack('styles')
</head>
<body class="flex h-screen font-inter">

    {{-- Sidebar --}}
    <div id="sidebar" class="w-64 flex flex-col shadow-sm z-10 flex-shrink-0">

        <div id="sidebar-logo-divider" class="p-6 border-b">
            <h1 class="font-poppins text-2xl font-bold" style="color:#ff7f11">AnoMay</h1>
            <span id="sidebar-label" class="text-xs uppercase tracking-wider font-semibold">Panel Admin</span>
        </div>

        <nav class="flex-1 p-4 space-y-1 mt-2">
            <a href="/admin/dashboard"
               class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }} flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                    <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="/admin/users"
               class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }} flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m10-4a4 4 0 11-8 0 4 4 0 018 0zM6 7a4 4 0 108 0 4 4 0 00-8 0z"/>
                </svg>
                <span>Data Penjual</span>
            </a>
            <a href="/admin/products"
               class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }} flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                <span>Katalog Produk</span>
            </a>
            <a href="/admin/stock-allocations"
               class="nav-link {{ request()->is('admin/stock-allocations*') ? 'active' : '' }} flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>Alokasi Stok Pagi</span>
            </a>
            <a href="/admin/laporan"
               class="nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }} flex items-center gap-3 py-2.5 px-4 rounded-lg font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Validasi Laporan</span>
            </a>
        </nav>

        <div id="sidebar-bottom" class="mt-auto border-t">
            <div class="px-5 py-3 flex items-center justify-between">
                <span class="text-xs font-medium" style="color:var(--sidebar-muted)">Tampilan</span>
                <button id="btn-toggle-admin" onclick="toggleDarkAdmin()"
                        class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full">
                    <span id="theme-icon-admin">🌙</span>
                    <span id="theme-label-admin">Gelap</span>
                </button>
            </div>
            <div class="px-5 py-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:#ff7f11">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p id="user-name" class="text-sm font-semibold font-poppins truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p id="user-role" class="text-xs truncate">Administrator</p>
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
        function applyThemeAdmin(isDark) {
            isDark
                ? document.documentElement.classList.add('dark')
                : document.documentElement.classList.remove('dark');
            document.getElementById('theme-icon-admin').textContent  = isDark ? '☀️' : '🌙';
            document.getElementById('theme-label-admin').textContent = isDark ? 'Terang' : 'Gelap';
        }
        function toggleDarkAdmin() {
            const isDark = !document.documentElement.classList.contains('dark');
            applyThemeAdmin(isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
        applyThemeAdmin(localStorage.getItem('theme') === 'dark');
    </script>
</body>
</html>