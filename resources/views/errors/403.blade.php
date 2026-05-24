<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak — AnoMay</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="text-center max-w-md">
        <div class="text-8xl mb-6">🚫</div>
        <h1 class="font-poppins text-3xl font-bold text-gray-800 mb-2">Akses Ditolak</h1>
        <p class="text-gray-500 mb-2">Halaman ini tidak bisa diakses dengan role <strong class="text-red-500">{{ $yourRole ?? auth()->user()?->role ?? '-' }}</strong>.</p>
        @if(!empty($required))
        <p class="text-sm text-gray-400 mb-8">Dibutuhkan role: <strong>{{ implode(' atau ', $required) }}</strong></p>
        @endif
        <a href="javascript:history.back()"
           class="inline-block bg-gray-800 text-white font-poppins font-semibold px-6 py-3 rounded-xl hover:bg-gray-700 transition mr-2">
            ← Kembali
        </a>
        <form action="/logout" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-red-500 text-white font-poppins font-semibold px-6 py-3 rounded-xl hover:bg-red-600 transition">
                Ganti Akun
            </button>
        </form>
    </div>
</body>
</html>
