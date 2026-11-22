<nav class="kusantara-navbar">
    <div class="nav-container">
        <!-- Brand Logo -->
        <a href="/" class="nav-brand">
            <div class="brand-logo-icon">
                <i class="fas fa-crown"></i>
            </div>
            <span class="brand-text">PT. Kusantara</span>
        </a>

        <!-- Menu Links -->
<ul class="nav-links" id="navLinks">
    <li>
        <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a>
    </li>
    <li>
        <a href="/katalog" class="{{ request()->is('katalog*') ? 'active' : '' }}">Katalog</a>
    </li>
    <li>
        <a href="/bukti-beriman" class="{{ request()->is('bukti-beriman*') ? 'active' : '' }}">Bukti Beriman</a>
    </li>
    <li>
        <a href="/blog" class="{{ request()->is('blog*') ? 'active' : '' }}">
            Blog <span class="badge-new">Baru</span>
        </a>
    </li>
    <li>
        <a href="/tentang" class="{{ request()->is('tentang*') ? 'active' : '' }}">Tentang Kami</a>
    </li>
    <li>
        <a href="/kontak" class="{{ request()->is('kontak*') ? 'active' : '' }}">Kontak</a>
    </li>
</ul>

        <!-- Right Actions -->
        <div class="nav-actions">
            <button class="icon-btn" id="searchToggle" title="Pencarian Global">
                <i class="fas fa-search"></i>
            </button>

            @php
                $totalCartQty = collect(session('cart', []))->sum(function ($item) {
                    return is_array($item) ? (int) ($item['qty'] ?? 0) : (int) $item;
                });
            @endphp

            <a href="{{ url('/checkout') }}" aria-label="Keranjang" class="kusantara-icon-btn">
                <span class="kusantara-cart-wrap">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M3 3h2.2l.5 2H21l-2.2 8.2a2 2 0 01-1.9 1.5H8.4L8.8 16H19v2H7.2L5 5H3V3zm5.5 16a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm9 0a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                    </svg>
                    <span id="navbar-cart-count" class="kusantara-cart-count">{{ $totalCartQty }}</span>
                </span>
            </a>

            <div class="login-wrapper">
                <button class="btn-login-nav" id="loginDropdownBtn">Login</button>
                
                <!-- Dropdown Login Box sesuai preview -->
                <div class="login-dropdown" id="loginDropdown">
                    <p class="login-title">Login Admin / Akun</p>
                    <form action="#" method="POST">
                        @csrf
                        <input type="text" placeholder="Username / Email" class="login-input" required>
                        <input type="password" placeholder="Password" class="login-input" required>
                        <button type="submit" class="btn-login-submit">Masuk</button>
                    </form>
                </div>
            </div>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Bar Pencarian Global (Muncul saat ikon kaca pembesar diklik) -->
    <div class="global-search-bar" id="globalSearchBar">
        <div class="search-inner">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari menu kuliner Nusantara, artikel blog, atau layanan...">
            <button id="closeSearch">&times;</button>
        </div>
    </div>
</nav>