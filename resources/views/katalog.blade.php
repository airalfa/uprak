<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Menu - PT. Kusantara Kita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F5EFE6; color: #1E293B; }
        .font-serif-title { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between">

    <!-- NAVBAR MULTI-HALAMAN (Sesuai Zona 1 & 2) -->
    <header class="bg-[#132238] text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 font-serif-title text-xl font-bold text-[#E5C17B]">
                <span>🍽️</span> PT. Kusantara
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="/" class="hover:text-[#E5C17B] transition">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="text-[#E5C17B] font-semibold border-b-2 border-[#E5C17B] pb-1">Katalog</a>
                <a href="/bukti-beriman" class="hover:text-[#E5C17B] transition">Bukti Beriman</a>
                <a href="/isu-suku-bunga" class="hover:text-[#E5C17B] transition">Isu Suku Bunga</a>
                <a href="/sejarah" class="hover:text-[#E5C17B] transition">Sejarah & Tim Kami</a>
            </nav>
            <div class="flex items-center gap-3">
                <a href="/checkout" class="text-sm bg-[#1E3557] hover:bg-[#294670] text-white px-3.5 py-1.5 rounded-lg transition">
                    Checkout 🛒
                </a>
                <a href="/login" class="bg-[#C8A252] hover:bg-[#b58e3e] text-[#132238] font-semibold text-sm px-4 py-1.5 rounded-lg transition">
                    Login
                </a>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA: KATALOG MENU -->
    <main class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow">
        
        <!-- Header Katalog, Search Bar & Filter Kategori -->
        <div class="max-w-3xl space-y-4">
            <h1 class="font-serif-title text-3xl sm:text-4xl font-bold text-[#132238]">Katalog Menu</h1>
            <p class="text-sm text-gray-600">Pilihan hidangan otentik Nusantara dengan bahan lokal berkualitas dan 100% halal.</p>

            <!-- Search Bar -->
            <form action="{{ route('katalog.index') }}" method="GET" class="relative flex items-center gap-2">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="relative flex-grow">
                    <span class="absolute left-3.5 top-2.5 text-gray-400 text-sm">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari menu nusantara..." 
                           class="w-full bg-white border border-[#D8CFC2] rounded-full pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-[#132238] shadow-sm">
                </div>
                <button type="submit" class="bg-[#132238] hover:bg-[#1e3557] text-white text-sm font-medium px-5 py-2 rounded-full transition">
                    Cari
                </button>
                @if(request('search') || (request('category') && request('category') !== 'Semua'))
                    <a href="{{ route('katalog.index') }}" class="text-xs text-gray-500 hover:text-[#132238] underline ml-1">Reset</a>
                @endif
            </form>
            <div class="flex flex-wrap gap-2 pt-1">
                @php $activeCat = request('category', 'Semua'); @endphp
                
                <a href="{{ route('katalog.index', ['category' => 'Semua', 'search' => request('search')]) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ $activeCat === 'Semua' ? 'bg-[#132238] text-white shadow-sm' : 'bg-[#EAE2D6] text-[#132238] hover:bg-[#dfd4c4]' }}">
                    Semua Menu
                </a>

                <a href="{{ route('katalog.index', ['category' => 'Makanan', 'search' => request('search')]) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ $activeCat === 'Makanan' ? 'bg-[#132238] text-white shadow-sm' : 'bg-[#EAE2D6] text-[#132238] hover:bg-[#dfd4c4]' }}">
                    Makanan Menu
                </a>

                <a href="{{ route('katalog.index', ['category' => 'Minuman', 'search' => request('search')]) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ $activeCat === 'Minuman' ? 'bg-[#132238] text-white shadow-sm' : 'bg-[#EAE2D6] text-[#132238] hover:bg-[#dfd4c4]' }}">
                    Minuman
                </a>

                <a href="{{ route('katalog.index', ['category' => 'Paket Katering', 'search' => request('search')]) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ $activeCat === 'Paket Katering' ? 'bg-[#132238] text-white shadow-sm' : 'bg-[#EAE2D6] text-[#132238] hover:bg-[#dfd4c4]' }}">
                    Paket Katering
                </a>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5 mt-8">
            @forelse ($menus as $menu)
                <div class="bg-[#FCF9F4] border border-[#E2D9C8] rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <!-- Foto Menu & Badge Kategori -->
                        <div class="relative h-36 bg-gray-200">
                            <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                            <span class="absolute top-2.5 right-2.5 bg-[#2E5A44] text-white text-[10px] font-semibold px-2.5 py-0.5 rounded-full shadow">
                                {{ $menu->category }}
                            </span>
                        </div>
                        <div class="p-4">
                            <h2 class="font-bold text-sm text-[#132238] leading-snug">{{ $menu->name }}</h2>
                            <p class="text-sm font-bold text-[#9A7428] mt-1">
                                Rp{{ number_format($menu->price, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $menu->description }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Kartu -->
                    <div class="px-4 pb-3 pt-2 border-t border-[#F0E9DC] flex items-center justify-between">
                        <span class="text-[11px] text-emerald-700 font-medium">✔ Tersedia</span>
                        <a href="/checkout" class="bg-[#EAE2D6] hover:bg-[#C8A252] text-[#132238] hover:text-white text-xs font-semibold px-3 py-1 rounded-lg transition">
                            Pesan
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-[#FCF9F4] rounded-2xl border border-[#E2D9C8]">
                    <p class="text-gray-500 text-sm">Menu tidak ditemukan untuk pencarian atau kategori tersebut.</p>
                </div>
            @endforelse
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#132238] text-gray-300 text-xs py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-2">
            <p class="font-semibold text-[#E5C17B]">©2026 PT. Kusantara Kita</p>
            <p>Semua Hak Dilindungi (All Rights Reserved)</p>
        </div>
    </footer>

</body>
</html>