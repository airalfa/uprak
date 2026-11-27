@extends('layouts.app')

@section('title', 'Kontak Kami - PT. Kusantara')

@section('content')
    <div class="contact-page">

        <!-- 1. HERO BANNER -->
        <section class="contact-hero">
            {{-- Gambar 1: Foto Customer Service / Layanan Pelanggan (simpan di public/images/kontak/cs-hero.jpg) --}}
            <img src="{{ asset('images/kontak/cs-hero.png') }}" alt="Layanan Pelanggan PT. Kusantara"
                class="contact-hero-bg"
                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=1200&q=80'">

            <div class="contact-hero-overlay">
                <div class="contact-container">
                    <div class="contact-hero-content">
                        <span class="contact-gold-line"></span>
                        <h1 class="contact-hero-title">Kontak Kami</h1>
                        <p class="contact-hero-lead">
                            Kami siap membantu Anda<br>
                            untuk informasi lebih lanjut.
                        </p>
                        <a href="#kirim-pesan" class="contact-hero-btn">
                            Hubungi Kami <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. STRIP 4 INFO KONTAK -->
        <div class="contact-strip-wrap">
            <div class="contact-container">
                <div class="contact-info-bar">
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="contact-info-label">Telepon</div>
                        <div class="contact-info-value">+62 21 1234 5678</div>
                        <div class="contact-info-sub">(Setiap Hari, 08.00 - 22.00)</div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div class="contact-info-label">Email</div>
                        <div class="contact-info-value">info@kusantara.id</div>
                        <div class="contact-info-sub">(Balas dalam 1x24 jam)</div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="contact-info-label">Alamat</div>
                        <div class="contact-info-value">Jl. Raya Nusantara No. 88,</div>
                        <div class="contact-info-sub">Rancamulya, Kab. Bandung</div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa-brands fa-instagram"></i></div>
                        <div class="contact-info-label">Ikuti Kami</div>
                        <div class="contact-info-value">@pt.kusantara</div>
                        <div class="contact-socmed-links">
                            <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                            <a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn">
                                <i class="fa-brands fa-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. KIRIM PESAN & FAQ -->
        <section class="contact-main-section" id="kirim-pesan">
            <div class="contact-container">
                <div class="contact-main-grid">
                    <!-- Kolom Kiri: Form Kirim Pesan -->
                    <div>
                        <h2 class="contact-heading">Kirim Pesan</h2>
                        <p class="contact-subcopy">
                            Isi formulir di bawah ini untuk menghubungi kami.<br>
                            Kami akan segera merespons pesan Anda.
                        </p>

                        <form id="contactMessageForm">
                            <div class="contact-form-row-2">
                                <input type="text" name="nama_lengkap" class="contact-input" placeholder="Nama Lengkap *"
                                    required>
                                <input type="email" name="email" class="contact-input" placeholder="Email Anda *" required>
                            </div>

                            <div class="contact-form-group">
                                <select name="subjek" class="contact-select" required>
                                    <option value="" selected disabled>Subjek *</option>
                                    <option value="Informasi Menu & Pemesanan">Informasi Menu &amp; Pemesanan</option>
                                    <option value="Layanan Katering & Acara">Layanan Katering &amp; Acara</option>
                                    <option value="Kerja Sama & Kemitraan">Kerja Sama &amp; Kemitraan</option>
                                    <option value="Kritik, Saran & Layanan Pelanggan">Kritik, Saran &amp; Layanan Pelanggan
                                    </option>
                                </select>
                            </div>

                            <div class="contact-form-group">
                                <textarea name="pesan" class="contact-textarea" placeholder="Pesan Anda *"
                                    required></textarea>
                            </div>

                            <button type="submit" class="contact-submit-btn">
                                Kirim Pesan <i class="fa-solid fa-chevron-right"></i>
                            </button>

                            <div id="contactFormFeedback" class="contact-alert-box" role="status"></div>
                        </form>
                    </div>

                    <!-- Kolom Kanan: Foto Tim CS + Pertanyaan yang Sering Diajukan -->
                    <div class="contact-side-stack">
                        {{-- Gambar 2: Foto Tim Layanan Pelanggan (simpan di public/images/kontak/cs-tim.jpg) --}}
                        <div class="contact-photo-card">
                            <img src="{{ asset('images/kontak/cs-tim.png') }}" alt="Tim Layanan Pelanggan PT. Kusantara"
                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1556745757-8d76bdb6984b?auto=format&fit=crop&w=900&q=80'">
                        </div>

                        <div class="contact-faq-card">
                            <div class="contact-faq-head">
                                <i class="fa-solid fa-comment-dots"></i>
                                <h3>Pertanyaan yang Sering Diajukan</h3>
                            </div>

                            <div class="contact-faq-list">
                                <div class="contact-faq-item">
                                    <button type="button" class="contact-faq-trigger" aria-expanded="false">
                                        <span>Bagaimana cara melakukan pemesanan?</span>
                                        <span class="contact-faq-sign">+</span>
                                    </button>
                                    <div class="contact-faq-panel">
                                        Anda dapat memilih menu pada halaman Katalog, mengatur jumlah pesanan di keranjang,
                                        lalu melanjutkan ke halaman Checkout untuk memilih layanan Dine-in, Delivery, atau
                                        Katering.
                                    </div>
                                </div>

                                <div class="contact-faq-item">
                                    <button type="button" class="contact-faq-trigger" aria-expanded="false">
                                        <span>Apakah tersedia layanan catering?</span>
                                        <span class="contact-faq-sign">+</span>
                                    </button>
                                    <div class="contact-faq-panel">
                                        Ya, PT. Kusantara melayani pesanan katering untuk rapat, acara keluarga, maupun
                                        kegiatan instansi. Silakan hubungi kami atau pilih opsi Katering saat checkout.
                                    </div>
                                </div>

                                <div class="contact-faq-item">
                                    <button type="button" class="contact-faq-trigger" aria-expanded="false">
                                        <span>Bagaimana cara menjadi mitra PT. Kusantara?</span>
                                        <span class="contact-faq-sign">+</span>
                                    </button>
                                    <div class="contact-faq-panel">
                                        Silakan kirimkan pesan melalui formulir di samping dengan subjek "Kerja Sama &amp;
                                        Kemitraan" atau hubungi tim kami melalui email resmi.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. LOKASI KAMI -->
        <section class="contact-location-section">
            <div class="contact-container">
                <div class="contact-location-grid">
                    <div class="contact-map-box">
                        <iframe title="Peta Lokasi PT. Kusantara"
                            src="https://www.google.com/maps?q=Rancamulya,+Bandung,+Jawa+Barat&output=embed" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <div class="contact-location-info">
                        <div>
                            <span class="contact-gold-line"></span>
                            <h2 class="contact-heading">Lokasi Kami</h2>
                            <p class="contact-subcopy" style="margin-bottom: 10px;">
                                Kunjungi kami di alamat berikut untuk informasi lebih lanjut maupun pemesanan langsung.
                            </p>

                            <div class="contact-address-row">
                                <i class="fa-solid fa-location-dot"></i>
                                <div>
                                    Jl. Raya Nusantara No. 88,<br>
                                    Rancamulya, Pameungpeuk,<br>
                                    Kabupaten Bandung
                                </div>
                            </div>

                            <div class="contact-location-meta">
                                <div class="contact-meta-box">
                                    <div class="contact-meta-title">Jam Operasional</div>
                                    <div class="contact-meta-desc">Setiap Hari<br>08.00 – 21.00 WIB</div>
                                </div>
                                <div class="contact-meta-box">
                                    <div class="contact-meta-title">Layanan Tersedia</div>
                                    <div class="contact-meta-desc">Dine-in, Delivery<br>&amp; Katering</div>
                                </div>
                            </div>
                        </div>

                        <a href="https://www.google.com/maps/search/?api=1&query=Rancamulya,+Bandung,+Jawa+Barat"
                            target="_blank" rel="noopener" class="contact-maps-btn">
                            Lihat Lokasi di Google Maps <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle Accordion FAQ
            document.querySelectorAll('.contact-faq-trigger').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const item = btn.closest('.contact-faq-item');
                    const sign = btn.querySelector('.contact-faq-sign');
                    const isOpen = item.classList.contains('is-open');

                    item.classList.toggle('is-open', !isOpen);
                    btn.setAttribute('aria-expanded', String(!isOpen));
                    if (sign) sign.textContent = !isOpen ? '−' : '+';
                });
            });

            // Simulasi Kirim Pesan
            const contactForm = document.getElementById('contactMessageForm');
            const feedbackEl = document.getElementById('contactFormFeedback');

            if (contactForm && feedbackEl) {
                contactForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const nama = contactForm.querySelector('[name="nama_lengkap"]').value.trim();
                    feedbackEl.textContent = 'Terima kasih, ' + (nama || 'Kak') + '! Pesan Anda telah tercatat dan akan segera kami balas.';
                    feedbackEl.classList.add('is-visible');
                    contactForm.reset();
                });
            }
        });
    </script>
@endpush