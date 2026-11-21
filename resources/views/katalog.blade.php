@extends('layouts.app')

@section('title', 'Katalog Menu - PT. Kusantara Kita')

@section('content')
<div class="katalog-wrap">
    @if(session('success'))
        <div class="katalog-alert">
            <span>✔ {{ session('success') }}</span>
            <a href="{{ url('/checkout') }}">Lihat Keranjang →</a>
        </div>
    @endif

    <div class="katalog-head">
        <h1 class="katalog-title">Katalog Menu</h1>
        <p class="katalog-subtitle">Pilihan hidangan otentik Nusantara dengan bahan lokal berkualitas dan 100% halal.</p>

        <form action="{{ route('katalog.index') }}" method="GET" class="katalog-search-form">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <div class="katalog-search-input-wrap">
                <span class="katalog-search-icon">🔍</span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari menu nusantara..."
                       class="katalog-search-input">
            </div>
            <button type="submit" class="katalog-search-btn">Cari</button>
            @if(request('search') || (request('category') && request('category') !== 'Semua'))
                <a href="{{ route('katalog.index') }}" class="katalog-reset">Reset</a>
            @endif
        </form>

        <div class="katalog-cats">
            @php
                $activeCat = request('category', 'Semua');
                $categories = [
                    'Semua'          => 'Semua Menu',
                    'Makanan'        => 'Makanan Menu',
                    'Minuman'        => 'Minuman',
                    'Paket Katering' => 'Paket Katering',
                ];
            @endphp

            @foreach ($categories as $catKey => $catLabel)
                <a href="{{ route('katalog.index', ['category' => $catKey, 'search' => request('search')]) }}"
                   class="katalog-cat-btn {{ $activeCat === $catKey ? 'is-active' : '' }}">
                    {{ $catLabel }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="katalog-grid">
        @forelse ($menus as $menu)
            @php
                $menuKey = strtolower(trim($menu->name));

                $fotoMenu = [
                    'nasi liwet komplit'     => 'nasi liwet.jpg',
                    'sate ayam madura'       => 'sate ayam madura.jpg',
                    'rendang daging sapi'    => 'rendang daging sapi.jpg',
                    'es teh manis nusantara' => 'es teh manis nusantara.jpg',
                    'es cendol dawet'        => 'es cendol dawet.jpg',
                    'wedang jahe hangat'     => 'wedang jahe hangat.jpg',
                ];
                $namaFile = $fotoMenu[$menuKey] ?? null;
                $posisiClass = $menuKey === 'es cendol dawet' ? 'pos-cendol' : '';

                if ($namaFile) {
                    $img = asset('images/' . rawurlencode($namaFile));
                } elseif (!empty($menu->image)) {
                    $img = \Illuminate\Support\Str::startsWith($menu->image, ['http://', 'https://', '/'])
                        ? $menu->image
                        : asset($menu->image);
                } else {
                    $img = asset('images/background.jpg');
                }

                $qtyDiCart = session('cart.' . $menu->id . '.qty', 0);
            @endphp

            <div class="katalog-card">
                <div>
                    <div class="katalog-thumb">
                        <img src="{{ $img }}" alt="{{ $menu->name }}" loading="lazy" class="{{ $posisiClass }}">
                        <span class="katalog-badge">{{ $menu->category }}</span>
                    </div>
                    <div class="katalog-body">
                        <h2 class="katalog-name">{{ $menu->name }}</h2>
                        <p class="katalog-price">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                        <p class="katalog-desc">{{ $menu->description }}</p>
                    </div>
                </div>

                <div class="katalog-footer">
                    @if($qtyDiCart > 0)
                        <div class="katalog-cart-row">
                            <span class="katalog-in-cart">🛒 Di keranjang</span>

                            <div class="katalog-qty-box">
                                <form action="{{ route('cart.update', $menu->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="decrease">
                                    <button type="submit" class="katalog-qty-btn">−</button>
                                </form>

                                <span class="katalog-qty-num">{{ $qtyDiCart }}</span>

                                <form action="{{ route('cart.update', $menu->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="increase">
                                    <button type="submit" class="katalog-qty-btn">+</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('cart.store') }}" method="POST" class="katalog-add-form">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <input type="hidden" name="qty" value="1">

                            <span class="katalog-available">✔ Tersedia</span>

                            <button type="submit" class="katalog-add-btn">+ Keranjang</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="katalog-empty">
                Menu tidak ditemukan untuk pencarian atau kategori tersebut.
            </div>
        @endforelse
    </div>
</div>
@endsection