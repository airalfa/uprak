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
            <p class="katalog-subtitle">Pilihan hidangan otentik Nusantara dengan bahan lokal berkualitas dan 100% halal.
            </p>

            <form action="{{ route('katalog.index') }}" method="GET" class="katalog-search-form">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="katalog-search-input-wrap">
                    <span class="katalog-search-icon">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu nusantara..."
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
                        'Semua' => 'Semua Menu',
                        'Makanan' => 'Makanan Menu',
                        'Minuman' => 'Minuman',
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
                        'nasi liwet komplit' => 'nasi liwet.jpg',
                        'sate ayam madura' => 'sate ayam madura.jpg',
                        'rendang daging sapi' => 'rendang daging sapi.jpg',
                        'ayam bakar taliwang' => 'ayam bakar taliwang.jpg',
                        'gado gado siram' => 'gado gado siram.jpg',
                        'gado-gado siram' => 'gado gado siram.jpg',
                        'soto betawi daging' => 'Soto Betawi daging.jpg',
                        'es teh manis nusantara' => 'es teh manis nusantara.jpg',
                        'es cendol dawet' => 'es cendol dawet.jpg',
                        'wedang jahe hangat' => 'wedang jahe hangat.jpg',
                        'es kelapa muda jeruk' => 'es kelapa muda jeruk.jpg',
                    ];

                    $namaFile = $fotoMenu[$menuKey] ?? null;
                    $posisiClass = $menuKey === 'es cendol dawet' ? 'pos-cendol' : '';

                    // Cek file yang tersedia sebagai fallback utama (backgroud.jpg sesuai di folder images/)
                    $fallbackImg = file_exists(public_path('images/backgroud.jpg'))
                        ? asset('images/' . rawurlencode('backgroud.jpg'))
                        : asset('images/' . rawurlencode('nasi liwet.jpg'));

                    if ($namaFile && file_exists(public_path('images/' . $namaFile))) {
                        $img = asset('images/' . rawurlencode($namaFile));
                    } elseif (!empty($menu->image)) {
                        $cleanMenuImg = ltrim($menu->image, '/');
                        if (\Illuminate\Support\Str::startsWith($menu->image, ['http://', 'https://'])) {
                            $img = $menu->image;
                        } elseif (file_exists(public_path($cleanMenuImg))) {
                            $img = asset($cleanMenuImg);
                        } elseif (file_exists(public_path('images/' . basename($cleanMenuImg)))) {
                            $img = asset('images/' . rawurlencode(basename($cleanMenuImg)));
                        } else {
                            $img = $fallbackImg;
                        }
                    } else {
                        $img = $fallbackImg;
                    }
                @endphp

                <div class="katalog-card" data-menu-id="{{ $menu->id }}" data-name="{{ $menu->name }}"
                    data-price="{{ (int) $menu->price }}">
                    <div>
                        <div class="katalog-thumb">
                            <img src="{{ $img }}"
                                alt="{{ $menu->name }}"
                                loading="lazy"
                                class="{{ $posisiClass }}"
                                onerror="this.onerror=null;this.src='{{ $fallbackImg }}';">
                            <span class="katalog-badge">{{ $menu->category }}</span>
                        </div>
                        <div class="katalog-body">
                            <h2 class="katalog-name">{{ $menu->name }}</h2>
                            <p class="katalog-price">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                            <p class="katalog-desc">{{ $menu->description }}</p>
                        </div>
                    </div>

                    <div class="katalog-footer">
                        <div class="katalog-add-form" data-add>
                            <span class="katalog-available">✔ Tersedia</span>
                            <button type="button" class="katalog-add-btn">+ Keranjang</button>
                        </div>

                        <div class="katalog-cart-row hidden" data-stepper>
                            <span class="katalog-in-cart">🛒 Di keranjang</span>
                            <div class="katalog-qty-box">
                                <button type="button" class="katalog-qty-btn" data-minus>−</button>
                                <span class="katalog-qty-num" data-qty>0</span>
                                <button type="button" class="katalog-qty-btn" data-plus>+</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="katalog-empty">
                    Menu tidak ditemukan untuk pencarian atau kategori tersebut.
                </div>
            @endforelse
        </div>
    </div>

    <!-- BAR BAYAR SEKARANG -->
    <div id="cart-spacer" class="cart-spacer hidden"></div>
    <div id="cart-bar" class="floating-cart-bar hidden">
        <div class="floating-cart-inner">
            <div class="floating-cart-info">
                <p class="floating-cart-summary">
                    <span id="bar-items">0 item</span> &middot; <span id="bar-total">Rp0</span>
                </p>
                <p id="bar-names" class="floating-cart-names"></p>
            </div>
            <a href="{{ url('/checkout') }}" class="floating-cart-cta">
                Bayar Sekarang
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var CART_KEY = 'kusantara_cart';

            function loadCart() {
                try {
                    return JSON.parse(localStorage.getItem(CART_KEY)) || {};
                } catch (e) {
                    return {};
                }
            }

            function saveCart(c) {
                try {
                    localStorage.setItem(CART_KEY, JSON.stringify(c));
                } catch (e) { }
            }

            function rupiah(n) {
                return 'Rp' + Number(n || 0).toLocaleString('id-ID');
            }

            var cart = loadCart();

            function renderCart() {
                var ids = Object.keys(cart);
                var totalQty = 0;
                var totalPrice = 0;
                var names = [];

                ids.forEach(function (id) {
                    var it = cart[id];
                    if (!it || Number(it.qty || 0) <= 0) return;
                    totalQty += Number(it.qty);
                    totalPrice += Number(it.qty) * Number(it.price || 0);
                    names.push(it.qty + 'x ' + it.name);
                });

                var badge = document.getElementById('cart-count') || document.getElementById('navbar-cart-count');
                if (badge) {
                    badge.textContent = totalQty;
                }

                var has = totalQty > 0;
                var cartBar = document.getElementById('cart-bar');
                var cartSpacer = document.getElementById('cart-spacer');
                var barItems = document.getElementById('bar-items');
                var barTotal = document.getElementById('bar-total');
                var barNames = document.getElementById('bar-names');

                if (cartBar) cartBar.classList.toggle('hidden', !has);
                if (cartSpacer) cartSpacer.classList.toggle('hidden', !has);
                if (barItems) barItems.textContent = totalQty + ' item';
                if (barTotal) barTotal.textContent = rupiah(totalPrice);
                if (barNames) barNames.textContent = names.join(', ');

                document.querySelectorAll('[data-menu-id]').forEach(function (card) {
                    var id = card.dataset.menuId;
                    var qty = cart[id] ? Number(cart[id].qty || 0) : 0;
                    var addBtn = card.querySelector('[data-add]');
                    var stepper = card.querySelector('[data-stepper]');
                    var qtyEl = card.querySelector('[data-qty]');

                    if (addBtn) addBtn.classList.toggle('hidden', qty > 0);
                    if (stepper) {
                        stepper.classList.toggle('hidden', qty === 0);
                        stepper.classList.toggle('flex', qty > 0);
                    }
                    if (qtyEl) qtyEl.textContent = qty;
                });
            }

            document.addEventListener('click', function (e) {
                var card = e.target.closest('[data-menu-id]');
                if (!card) return;
                var id = card.dataset.menuId;

                if (e.target.closest('[data-add]') || e.target.closest('[data-plus]')) {
                    if (!cart[id]) {
                        cart[id] = {
                            id: id,
                            name: card.dataset.name,
                            price: parseInt(card.dataset.price, 10) || 0,
                            qty: 0
                        };
                    }
                    cart[id].qty += 1;
                } else if (e.target.closest('[data-minus]')) {
                    if (cart[id]) {
                        cart[id].qty -= 1;
                        if (cart[id].qty <= 0) {
                            delete cart[id];
                        }
                    }
                } else {
                    return;
                }

                saveCart(cart);
                renderCart();
            });

            renderCart();
        });
    </script>
@endpush