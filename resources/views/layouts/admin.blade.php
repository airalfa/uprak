<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - PT. Kusantara')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/kusantara.css') }}">
    @stack('styles')
</head>
<body>
@php
    $adminUser = session('kusantara_admin_user', ['name' => 'Admin', 'role' => 'super_admin']);
    $adminRole = session('kusantara_admin_role', 'super_admin');
    $roleLabel = \App\Support\AdminAccess::roleLabel($adminRole);

    $canMain = \App\Support\AdminAccess::can($adminRole, 'dashboard')
        || \App\Support\AdminAccess::can($adminRole, 'pesanan')
        || \App\Support\AdminAccess::can($adminRole, 'menu')
        || \App\Support\AdminAccess::can($adminRole, 'inventaris')
        || \App\Support\AdminAccess::can($adminRole, 'reservasi')
        || \App\Support\AdminAccess::can($adminRole, 'pelanggan');

    $canBusiness = \App\Support\AdminAccess::can($adminRole, 'promo')
        || \App\Support\AdminAccess::can($adminRole, 'catering')
        || \App\Support\AdminAccess::can($adminRole, 'keuangan')
        || \App\Support\AdminAccess::can($adminRole, 'laporan');

    $canContent = \App\Support\AdminAccess::can($adminRole, 'website')
        || \App\Support\AdminAccess::can($adminRole, 'blog')
        || \App\Support\AdminAccess::can($adminRole, 'ulasan')
        || \App\Support\AdminAccess::can($adminRole, 'media');
@endphp

<div class="admin-shell">
    <div id="adminSidebarBackdrop" class="admin-sidebar-backdrop"></div>

    <!-- SIDEBAR NAVIGASI UTAMA -->
    <aside id="adminSidebar" class="admin-sidebar">
        <div class="admin-brand">
            <div class="admin-brand-main">
                <div class="admin-brand-logo">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <div>
                    <div class="admin-brand-title">PT. KUSANTARA</div>
                    <div class="admin-brand-sub">Admin Panel</div>
                </div>
            </div>
            <span class="admin-status-pill">Online</span>
        </div>

        <nav class="admin-nav-scroll" aria-label="Navigasi Admin">
            <!-- MAIN -->
            @if ($canMain)
            <div class="admin-nav-group">
                <div class="admin-nav-group-label">MAIN</div>
                <ul class="admin-nav-list">
                    @if (\App\Support\AdminAccess::can($adminRole, 'dashboard'))
                        <li><a href="{{ url('/admin/dashboard') }}" class="admin-nav-link {{ request()->is('admin/dashboard') ? 'is-active' : '' }}"><i class="fa-solid fa-gauge-high"></i><span>Dashboard</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'pesanan'))
                        <li><a href="{{ url('/admin/pesanan') }}" class="admin-nav-link {{ request()->is('admin/pesanan*') ? 'is-active' : '' }}"><i class="fa-solid fa-receipt"></i><span>Pesanan</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'menu'))
                        <li><a href="{{ url('/admin/menu') }}" class="admin-nav-link {{ request()->is('admin/menu*') ? 'is-active' : '' }}"><i class="fa-solid fa-utensils"></i><span>Menu &amp; Produk</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'inventaris'))
                        <li><a href="{{ url('/admin/inventaris') }}" class="admin-nav-link {{ request()->is('admin/inventaris*') ? 'is-active' : '' }}"><i class="fa-solid fa-boxes-stacked"></i><span>Inventaris</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'reservasi'))
                        <li><a href="{{ url('/admin/reservasi') }}" class="admin-nav-link {{ request()->is('admin/reservasi*') ? 'is-active' : '' }}"><i class="fa-solid fa-calendar-check"></i><span>Reservasi</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'pelanggan'))
                        <li><a href="{{ url('/admin/pelanggan') }}" class="admin-nav-link {{ request()->is('admin/pelanggan*') ? 'is-active' : '' }}"><i class="fa-solid fa-users"></i><span>Pelanggan</span></a></li>
                    @endif
                </ul>
            </div>
            @endif

            <!-- BUSINESS -->
            @if ($canBusiness)
            <div class="admin-nav-group">
                <div class="admin-nav-group-label">BUSINESS</div>
                <ul class="admin-nav-list">
                    @if (\App\Support\AdminAccess::can($adminRole, 'promo'))
                        <li><a href="{{ url('/admin/promo') }}" class="admin-nav-link {{ request()->is('admin/promo*') ? 'is-active' : '' }}"><i class="fa-solid fa-tags"></i><span>Promo &amp; Diskon</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'catering'))
                        <li><a href="{{ url('/admin/catering-delivery') }}" class="admin-nav-link {{ request()->is('admin/catering-delivery*') ? 'is-active' : '' }}"><i class="fa-solid fa-truck-fast"></i><span>Catering &amp; Delivery</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'keuangan'))
                        <li><a href="{{ url('/admin/keuangan') }}" class="admin-nav-link {{ request()->is('admin/keuangan*') ? 'is-active' : '' }}"><i class="fa-solid fa-wallet"></i><span>Keuangan</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'laporan'))
                        <li><a href="{{ url('/admin/laporan') }}" class="admin-nav-link {{ request()->is('admin/laporan*') ? 'is-active' : '' }}"><i class="fa-solid fa-chart-column"></i><span>Laporan</span></a></li>
                    @endif
                </ul>
            </div>
            @endif

            <!-- CONTENT MANAGEMENT -->
            @if ($canContent)
            <div class="admin-nav-group">
                <div class="admin-nav-group-label">CONTENT MANAGEMENT</div>
                <ul class="admin-nav-list">
                    @if (\App\Support\AdminAccess::can($adminRole, 'website'))
                        <li><a href="{{ url('/admin/kelola-website') }}" class="admin-nav-link {{ request()->is('admin/kelola-website*') ? 'is-active' : '' }}"><i class="fa-solid fa-globe"></i><span>Kelola Website</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'blog'))
                        <li><a href="{{ url('/admin/blog') }}" class="admin-nav-link {{ request()->is('admin/blog*') ? 'is-active' : '' }}"><i class="fa-solid fa-newspaper"></i><span>Blog &amp; Artikel</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'ulasan'))
                        <li><a href="{{ url('/admin/ulasan') }}" class="admin-nav-link {{ request()->is('admin/ulasan*') ? 'is-active' : '' }}"><i class="fa-solid fa-star"></i><span>Ulasan</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'media'))
                        <li><a href="{{ url('/admin/media') }}" class="admin-nav-link {{ request()->is('admin/media*') ? 'is-active' : '' }}"><i class="fa-solid fa-images"></i><span>Media &amp; Galeri</span></a></li>
                    @endif
                </ul>
            </div>
            @endif

            <!-- ADMINISTRATION -->
            <div class="admin-nav-group">
                <div class="admin-nav-group-label">ADMINISTRATION</div>
                <ul class="admin-nav-list">
                    @if (\App\Support\AdminAccess::can($adminRole, 'karyawan'))
                        <li><a href="{{ url('/admin/karyawan') }}" class="admin-nav-link {{ request()->is('admin/karyawan*') ? 'is-active' : '' }}"><i class="fa-solid fa-id-badge"></i><span>Karyawan</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'hak_akses'))
                        <li><a href="{{ url('/admin/hak-akses') }}" class="admin-nav-link {{ request()->is('admin/hak-akses*') ? 'is-active' : '' }}"><i class="fa-solid fa-user-shield"></i><span>Hak Akses</span></a></li>
                    @endif
                    @if (\App\Support\AdminAccess::can($adminRole, 'pengaturan'))
                        <li><a href="{{ url('/admin/pengaturan') }}" class="admin-nav-link {{ request()->is('admin/pengaturan*') ? 'is-active' : '' }}"><i class="fa-solid fa-gear"></i><span>Pengaturan</span></a></li>
                    @endif
                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="admin-nav-link" style="width:100%;background:transparent;border:none;cursor:pointer;text-align:left;">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>

    <!-- KONTEN UTAMA -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div class="admin-topbar-left">
                <button type="button" id="adminMobileToggle" class="admin-mobile-btn" aria-label="Buka Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="admin-search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="admin-search-input" placeholder="Cari menu, pesanan, atau fitur...">
                </div>
            </div>

            <div class="admin-topbar-right">
                <button type="button" class="admin-notif-btn" title="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    <span class="admin-notif-dot"></span>
                </button>
                <div class="admin-profile-chip">
                    <span class="admin-profile-avatar"><i class="fa-solid fa-user"></i></span>
                    <span>{{ $adminUser['name'] ?? 'Admin' }}</span>
                    <span style="font-size:0.72rem;padding:2px 8px;border-radius:999px;background:var(--navy-dark);color:var(--gold);">
                        {{ $roleLabel }}
                    </span>
                </div>
            </div>
        </header>

        @hasSection('subnav')
            <div class="admin-subnav">
                @yield('subnav')
            </div>
        @endif

        <main class="admin-content">
            @yield('content')
        </main>

        <footer class="admin-footer-note">
            <span>PT. Kusantara &bull; Management System</span>
            <span>Restoran &bull; Catering &bull; Takeaway &bull; Delivery</span>
        </footer>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('adminMobileToggle');
    const sidebar = document.getElementById('adminSidebar');
    const backdrop = document.getElementById('adminSidebarBackdrop');

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('is-open');
        if (backdrop) backdrop.classList.remove('is-open');
    }

    if (toggleBtn && sidebar && backdrop) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('is-open');
            backdrop.classList.toggle('is-open');
        });
        backdrop.addEventListener('click', closeSidebar);
    }
});
</script>
@stack('scripts')
</body>
</html>