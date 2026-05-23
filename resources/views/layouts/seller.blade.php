<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Penjual - AnoMay</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 pb-24 font-inter antialiased"> 

    <header class="bg-anomay-orange text-white p-5 shadow-md sticky top-0 z-10">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="font-poppins text-xl font-bold">Mang Ujang</h1>
                <p class="text-xs font-medium opacity-90 mt-0.5">Gerobak 1 - Rute Area Kampus</p>
            </div>
        </div>
    </header>

    <main class="p-4 space-y-5">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-anomay-beige p-3 border-b border-gray-200">
                <h3 class="font-poppins font-bold text-anomay-dark">Isi Panci Hari Ini</h3>
            </div>
            <div class="p-4">
                <ul class="space-y-3">
                    <li class="flex justify-between items-center border-b border-gray-100 pb-2"><span class="text-anomay-dark font-medium">Siomay Ikan</span><span class="font-bold text-anomay-dark bg-gray-100 px-3 py-1 rounded-md">100 pcs</span></li>
                    <li class="flex justify-between items-center"><span class="text-anomay-dark font-medium">Tahu Bakso</span><span class="font-bold text-anomay-dark bg-gray-100 px-3 py-1 rounded-md">50 pcs</span></li>
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 border-t-4 border-anomay-sage">
            <h3 class="font-poppins font-bold text-anomay-dark mb-4">Lapor Penjualan Harian</h3>
            <form>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-anomay-dark mb-1">Total Sisa Siomay Ikan</label>
                        <input type="number" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-anomay-sage outline-none bg-gray-50" placeholder="Sisa di panci...">
                    </div>
                </div>
                <button type="submit" class="w-full bg-anomay-sage text-anomay-dark py-3 mt-6 rounded-lg font-poppins font-bold shadow-md hover:opacity-90 transition">Kirim Setoran</button>
            </form>
        </div>
    </main>

    <nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 flex justify-around p-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
        <a href="#" class="flex flex-col items-center text-anomay-orange">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-[10px] font-poppins font-semibold">Beranda</span>
        </a>
    </nav>
</body>
</html>