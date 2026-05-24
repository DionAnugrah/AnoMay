<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>AnoMay - Login</title>
</head>
<body class="bg-[#fef3e9] font-inter min-h-screen relative overflow-hidden flex flex-col justify-center items-center p-6">

    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        
        

        <img src="{{ asset('images/indomie.png') }}" alt="Mie Instan" class="absolute top-[10%] left-[8%] w-[180px] lg:w-[220px] rotate-[-15deg] drop-shadow-2xl">

        <img src="{{ asset('images/saus-kacang.png') }}" alt="Saus Cokelat" class="absolute top-[45%] left-[3%] w-[90px] lg:w-[120px] rotate-[20deg] drop-shadow-xl">

        <img src="{{ asset('images/dimsum-box.png') }}" alt="Keranjang Klakat" class="absolute bottom-[5%] left-[-2%] w-[250px] lg:w-[320px] rotate-[10deg] drop-shadow-2xl">

        <img src="{{ asset('images/saus-sambal.png') }}" alt="Saus Merah" class="absolute top-[20%] right-[10%] w-[100px] lg:w-[130px] rotate-[-25deg] drop-shadow-xl">

        <img src="{{ asset('images/dimsum-piring.png') }}" alt="Piring Siomay" class="absolute bottom-[5%] right-[-2%] w-[280px] lg:w-[360px] rotate-[-10deg] drop-shadow-2xl">
        
        <svg viewBox="0 0 1440 320" class="absolute bottom-0 left-0 right-0 w-full h-auto object-cover" style="min-height: 250px; transform: translateY(10px);">
            <path fill="#ff9d3b" d="M0,320 L0,220 Q720,50 1440,220 L1440,320 Z"></path>
        </svg>
    </div>
   
    <div class="absolute top-10 right-10 text-anomay-orange/50 animate-pulse hidden md:block">
        <svg width="100" height="100" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/></svg>
    </div>

    <div class="w-full max-w-sm bg-[#ff9d3b] rounded-[2.5rem] shadow-2xl relative z-10 p-8 pt-10 pb-12">
        
        <div class="text-center mb-10">
            <h1 class="font-poppins text-5xl font-extrabold text-anomay-dark tracking-tighter [text-shadow:_2px_2px_0_rgb(0_0_0_/_20%)]">Ano<span class="text-anomay-orange">May</span></h1>
            <p class="text-xs text-orange-200 mt-2 font-medium">Website Manajemen Usaha Siomay</p>
        </div>

        <form action="/login" method="POST">
            @csrf
            
            @if ($errors->any() || session('error'))
            <div class="mb-5 bg-red-100/10 border border-red-400 text-red-200 px-4 py-3 rounded-xl text-sm text-center font-medium">
                {{ session('error') ?? $errors->first() }}
            </div>
            @endif
            
            <div class="mb-5 relative">
                <input type="text" name="username" class="w-full bg-white/10 border border-white/30 text-white placeholder-white/80 rounded-full pl-6 pr-12 py-3.5 shadow-md focus:outline-none focus:ring-2 focus:ring-white transition" placeholder="Masukkan username anda" required>
                <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-white/70">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>

            <div class="mb-6 relative">
                <input type="password" name="password" class="w-full bg-white/10 border border-white/30 text-white placeholder-white/80 rounded-full pl-6 pr-12 py-3.5 shadow-md focus:outline-none focus:ring-2 focus:ring-white transition" placeholder="Masukkan password anda" required>
                <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-white/70">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 002-2H6a2 2 0 002 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
            </div>

            <div class="flex items-center justify-between mb-8 px-2">
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" class="h-4 w-4 bg-[#ff9d3b] text-anomay-orange border-transparent rounded focus:ring-anomay-orange transition cursor-pointer">
                    <label for="remember_me" class="ml-2 block text-xs text-white/90 font-medium cursor-pointer">Ingat Saya</label>
                </div>
                <a href="#" class="text-xs text-white/80 hover:text-white hover:underline transition">Forgot Password?</a>
            </div>

            <button type="submit" class="w-full bg-anomay-dark text-white font-poppins font-bold py-3.5 px-4 rounded-full shadow-lg hover:bg-gray-800 active:scale-95 transition-all duration-200">
            Masuk
            </button>
            
        </form>
    </div>

</body>
</html>