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

    <!-- NAVBAR (disamakan dengan halaman Beranda/Dashboard) -->
    <header class="bg-[#132238] text-white sticky top-0 z-50 shadow-md">
        <div class="w-full px-5 sm:px-8 lg:px-14 h-20 lg:h-24 flex items-center justify-between">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-3">
                <span class="w-11 h-11 rounded-full bg-[#C8A252] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#132238]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M3 8l4.5 4L12 5l4.5 7L21 8l-2 11H5L3 8z"/>
                    </svg>
                </span>
                <span class="font-serif-title text-2xl font-bold text-[#E5C17B]">PT. Kusantara</span>
            </a>

            <!-- Menu -->
            <nav class="hidden lg:flex items-center gap-9 text-[17px] font-medium">
                <a href="/" class="py-1 hover:text-[#E5C17B] transition {{ request()->is('/') ? 'text-[#E5C17B] border-b-2 border-[#E5C17B]' : '' }}">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="py-1 hover:text-[#E5C17B] transition {{ request()->is('katalog*') ? 'text-[#E5C17B] border-b-2 border-[#E5C17B]' : '' }}">Katalog</a>
                <a href="/bukti-beriman" class="py-1 hover:text-[#E5C17B] transition {{ request()->is('bukti-beriman*') ? 'text-[#E5C17B] border-b-2 border-[#E5C17B]' : '' }}">Bukti Beriman</a>
                <a href="/blog" class="py-1 flex items-center gap-1.5 hover:text-[#E5C17B] transition {{ request()->is('blog*') ? 'text-[#E5C17B] border-b-2 border-[#E5C17B]' : '' }}">
                    Blog
                    <span class="bg-[#C8A252] text-[#132238] text-[11px] font-bold px-2 py-0.5 rounded-full">Baru</span>
                </a>
                <a href="/tentang-kami" class="py-1 hover:text-[#E5C17B] transition {{ request()->is('tentang-kami*') ? 'text-[#E5C17B] border-b-2 border-[#E5C17B]' : '' }}">Tentang Kami</a>
                <a href="/kontak" class="py-1 hover:text-[#E5C17B] transition {{ request()->is('kontak*') ? 'text-[#E5C17B] border-b-2 border-[#E5C17B]' : '' }}">Kontak</a>
            </nav>

            <!-- Kanan: cari, keranjang, login -->
            <div class="flex items-center gap-5">
                <a href="{{ route('katalog.index') }}" aria-label="Cari" class="hover:text-[#E5C17B] transition">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>
                    </svg>
                </a>
                <a href="/checkout" aria-label="Keranjang" class="relative hover:text-[#E5C17B] transition">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M3 3h2.2l.5 2H21l-2.2 8.2a2 2 0 01-1.9 1.5H8.4L8.8 16H19v2H7.2L5 5H3V3zm5.5 16a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm9 0a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                    </svg>
                    <span class="absolute -top-2 -right-2.5 bg-[#C8A252] text-[#132238] text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center">0</span>
                </a>
                <a href="/login" class="bg-[#C8A252] hover:bg-[#b58e3e] text-[#132238] font-bold text-base px-6 py-2.5 rounded-lg transition">
                    Login
                </a>
                <!-- Tombol menu mobile -->
                <button id="menu-toggle" class="lg:hidden" aria-label="Buka menu">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>

        <!-- Menu mobile -->
        <nav id="mobile-menu" class="hidden lg:hidden border-t border-white/10 px-5 py-3 flex-col gap-3 text-base font-medium">
            <a href="/" class="block py-1.5">Beranda</a>
            <a href="{{ route('katalog.index') }}" class="block py-1.5 text-[#E5C17B]">Katalog</a>
            <a href="/bukti-beriman" class="block py-1.5">Bukti Beriman</a>
            <a href="/blog" class="block py-1.5">Blog <span class="bg-[#C8A252] text-[#132238] text-[11px] font-bold px-2 py-0.5 rounded-full ml-1">Baru</span></a>
            <a href="/tentang-kami" class="block py-1.5">Tentang Kami</a>
            <a href="/kontak" class="block py-1.5">Kontak</a>
        </nav>
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

        <!-- Grid Menu -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5 mt-8">
            @forelse ($menus as $menu)
                @php
                    // Mendukung URL penuh (http...) maupun path file di folder public (mis. images/menu/nasi-liwet.jpg)
                    $img = \Illuminate\Support\Str::startsWith($menu->image, ['http://', 'https://', '/'])
                        ? $menu->image
                        : asset($menu->image);
                @endphp
                <div class="bg-[#FCF9F4] border border-[#E2D9C8] rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <!-- Foto Menu & Badge Kategori -->
                        <div class="relative h-36 bg-gray-200">
                            <img src="{{ $img }}" alt="{{ $menu->name }}" loading="lazy" class="w-full h-full object-cover">
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
    <footer class="bg-[#132238] text-gray-300 text-xs py-6 mt-12 border-t-4 border-[#C8A252]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-2">
            <p class="font-semibold text-[#E5C17B]">©2026 PT. Kusantara Kita</p>
            <p>Semua Hak Dilindungi (All Rights Reserved)</p>
        </div>
    </footer>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            var m = document.getElementById('mobile-menu');
            m.classList.toggle('hidden');
            m.classList.toggle('flex');
        });
    </script>

</body>
</html>