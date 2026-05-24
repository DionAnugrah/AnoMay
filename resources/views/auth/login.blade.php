<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>AnoMay</title>
</head>
<body class="bg-anomay-beige font-inter min-h-screen relative overflow-hidden flex flex-col justify-center items-center p-6">

    <div class="absolute bottom-0 left-0 right-0 z-0 opacity-20 text-anomay-sage flex justify-center items-end pointer-events-none">
        <svg viewBox="0 0 1440 320" class="w-full h-auto">
            <g transform="translate(100, 50)">
                <ellipse cx="200" cy="200" rx="150" ry="80" stroke="currentColor" stroke-width="4" fill="none" />
                <path d="M100,180 Q200,280 300,180 M150,160 Q200,240 250,160 M120,200 Q200,300 280,200" stroke="currentColor" stroke-width="3" fill="none"/>
                <path d="M180,80 Q200,0 220,80 M150,100 Q180,30 210,100" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="10 10"/>
            </g>
            <g transform="translate(700, 150) scale(0.6)">
                <path d="M100,100 C150,50 150,150 200,100 M100,120 Q150,170 200,120" stroke="currentColor" stroke-width="4" fill="none"/>
            </g>
            <g transform="translate(1000, 100) scale(0.7)">
                <path d="M100,100 C150,50 150,150 200,100 M100,120 Q150,170 200,120" stroke="currentColor" stroke-width="4" fill="none"/>
            </g>
             <path fill="currentColor" fill-opacity="0.3" d="M0,224L48,224C96,224,192,224,288,208C384,192,480,160,576,160C672,160,768,192,864,208C960,224,1056,224,1152,213.3C1248,203,1344,181,1392,170.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <div class="w-full max-w-sm relative z-10">
        
        <div class="text-center mb-10">
            <h1 class="font-poppins text-4xl font-extrabold text-anomay-dark tracking-tighter">AnoMay</h1>
            <p class="text-sm text-gray-500 mt-2 font-medium">Sistem Penjualan Siomay Keliling</p>
        </div>

        <form action="/login" method="POST">
            @csrf
            
            @if ($errors->any() || session('error'))
            <div class="mb-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-sm text-center font-medium">
                {{ session('error') ?? $errors->first() }}
            </div>
            @endif
            
            <div class="mb-5 relative">
                <label class="block text-anomay-dark text-sm font-semibold mb-2">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" name="username" class="w-full bg-white border border-transparent rounded-xl pl-12 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-anomay-orange shadow-sm transition" placeholder="Masukkan username anda..." required>
                </div>
            </div>

            <div class="mb-5 relative">
                <label class="block text-anomay-dark text-sm font-semibold mb-2 flex justify-between">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 002-2H6a2 2 0 002 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input type="password" name="password" class="w-full bg-white border border-transparent rounded-xl pl-12 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-anomay-orange shadow-sm transition" placeholder="Masukkan password anda" required>
                </div>
            </div>

            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" class="h-4 w-4 text-anomay-orange border-transparent rounded-md focus:ring-anomay-orange transition">
                    <label for="remember_me" class="ml-2 block text-sm text-anomay-dark font-medium">Ingat Saya</label>
                </div>
            </div>

            <button type="submit" class="w-full bg-anomay-orange text-white font-poppins font-bold py-3.5 px-4 rounded-xl shadow-md hover:bg-orange-600 active:scale-95 transition-all duration-200">
                Masuk
            </button>
            
        </form>
        </div>
    </div>

</body>
</html>