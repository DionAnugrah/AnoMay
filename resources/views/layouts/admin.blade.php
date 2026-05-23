<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - AnoMay</title>
    @vite('resources/css/app.css')
</head>
<body class="flex h-screen bg-gray-50 font-inter">
    <div class="w-64 bg-anomay-dark text-white flex flex-col shadow-md z-10">
        <div class="p-6 border-b border-gray-700">
            <h1 class="font-poppins text-2xl font-bold text-anomay-orange">AnoMay</h1>
            <span class="text-gray-300 text-xs uppercase tracking-wider font-semibold">Panel Admin</span>
        </div>
        <nav class="flex-1 p-4 space-y-2 mt-2">
            <a href="#" class="block py-2.5 px-4 bg-anomay-orange text-white rounded-lg font-medium shadow-sm">Master Data</a>
            <a href="#" class="block py-2.5 px-4 text-gray-300 hover:bg-gray-800 rounded-lg transition font-medium">Alokasi Stok</a>
        </nav>
    </div>

    <div class="flex-1 p-8 overflow-y-auto">
        <h2 class="font-poppins text-2xl font-bold text-anomay-dark mb-5">Katalog Item Siomay</h2>
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-10">
            <table class="w-full text-left border-collapse">
                <thead class="bg-anomay-beige/50 border-b border-gray-200">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-anomay-dark">Nama Item</th>
                        <th class="p-4 text-sm font-semibold text-anomay-dark">Harga Jual</th>
                        <th class="p-4 text-sm font-semibold text-anomay-dark w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition"><td class="p-4 text-gray-700">Siomay Ikan</td><td class="p-4 text-gray-700">Rp 2.000</td><td class="p-4"><button class="text-anomay-orange font-semibold hover:underline">Edit</button></td></tr>
                    <tr class="hover:bg-gray-50 transition"><td class="p-4 text-gray-700">Tahu Bakso</td><td class="p-4 text-gray-700">Rp 2.000</td><td class="p-4"><button class="text-anomay-orange font-semibold hover:underline">Edit</button></td></tr>
                </tbody>
            </table>
        </div>

        <h2 class="font-poppins text-2xl font-bold text-anomay-dark mb-5">Distribusi Stok Panci Pagi</h2>
        <form class="bg-white p-6 shadow-md rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-anomay-dark mb-2">Penjual Keliling</label>
                    <select class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-anomay-orange outline-none bg-gray-50"><option>Mang Ujang (Gerobak 1)</option></select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-anomay-dark mb-2">Item Siomay</label>
                    <select class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-anomay-orange outline-none bg-gray-50"><option>Siomay Ikan</option></select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-anomay-dark mb-2">Jumlah Bawaan</label>
                    <input type="number" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-anomay-orange outline-none bg-gray-50" value="0">
                </div>
            </div>
            <button class="bg-anomay-sage text-anomay-dark px-6 py-2.5 rounded-lg font-poppins font-bold shadow-sm hover:opacity-90 transition">Simpan ke Panci</button>
        </form>
    </div>
</body>
</html>