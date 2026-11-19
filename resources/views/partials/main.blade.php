
<main>
    <section class="hero-section">

        <div class="hero-copy">
            <span class="eyebrow">Cita Rasa Asli Nusantara</span>

            <h1>
                Rasa Nusantara<br>
                dalam Setiap Suapan
            </h1>

            <p>
                Nikmati kelezatan kuliner khas Indonesia
                dengan kualitas terbaik dari PT. Kusantara.
            </p>

            <a href="/katalog" class="primary-button">
                Pesan Sekarang
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="hero-photo"></div>

        <div class="hero-stamp">
            <i class="bi bi-award"></i>
            <span>
                Warisan rasa<br>
                <strong>Nusantara</strong>
            </span>
        </div>

    </section>

    <section class="benefit-strip">

        <article class="benefit-item">
            <span class="benefit-icon leaf">
                <i class="bi bi-leaf-fill"></i>
            </span>
            <div>
                <h3>Bahan Lokal</h3>
                <p>Segar dari petani lokal</p>
            </div>
        </article>

        <article class="benefit-item">
            <span class="benefit-icon">
                <i class="bi bi-shield-check"></i>
            </span>
            <div>
                <h3>Standar Higienis</h3>
                <p>Kualitas terjaga</p>
            </div>
        </article>

        <article class="benefit-item">
            <span class="benefit-icon">
                <i class="bi bi-shop"></i>
            </span>
            <div>
                <h3>Layanan Lengkap</h3>
                <p>Dine-in, delivery, catering</p>
            </div>
        </article>

        <article class="benefit-item">
            <span class="benefit-icon">
                <i class="bi bi-truck"></i>
            </span>
            <div>
                <h3>Pengiriman Cepat</h3>
                <p>Antar dengan aman</p>
            </div>
        </article>

    </section>

    <section class="quick-tools">

        <form class="global-search" id="globalSearch">
            <label for="searchInput">
                Pencarian Global
            </label>

            <div class="search-control">
                <input
                    id="searchInput"
                    type="search"
                    placeholder="Cari menu, layanan, atau informasi..."
                >

                <button type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <button class="cart-summary" onclick="openCart()">
            <span class="tool-label">
                Keranjang Belanja
            </span>

            <span class="tool-subtitle">
                Lihat pesanan kamu
            </span>

            <span class="cart-visual">
                <i class="bi bi-cart3"></i>
                <b id="cartCountLarge">0</b>
            </span>
        </button>

        <div class="login-card">
            <span class="login-card-title">
                <i class="bi bi-person-circle"></i>
                Login Admin
            </span>

            <a href="/login" class="login-card-button">
                Login
            </a>
        </div>

    </section>

    <section class="welcome-note">
        <span class="note-line"></span>

        <p>
            <strong>PT. Kusantara</strong>
            menghadirkan pengalaman kuliner yang
            mengutamakan rasa, kualitas, dan pelayanan.
        </p>

        <span class="note-line"></span>
    </section>

</main>

<div class="cart-toast" id="cartToast">
    <i class="bi bi-cart-check"></i>
    Keranjang belanja dibuka.
</div>
