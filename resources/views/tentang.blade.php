@extends('layouts.app')

@section('title', 'Tentang Kami - PT. Kusantara')

@section('content')
    <div class="about-page">

        <!-- 1. HERO BANNER -->
        <section class="about-hero">
            <img src="{{ asset('images/tentang/main-about.png') }}" alt="Restoran PT. Kusantara" class="about-hero-bg"
                onerror="this.style.display='none'">

            <div class="about-hero-overlay">
                <div class="about-container">
                    <div class="about-hero-content">
                        <span class="about-gold-line"></span>
                        <h1 class="about-hero-title">Tentang<br>PT. Kusantara</h1>
                        <p class="about-hero-lead">
                            Melestarikan Kekayaan Kuliner Nusantara,<br>
                            Menghadirkan Cita Rasa untuk Semua.
                        </p>
                        <a href="#profil-perusahaan" class="about-hero-btn">
                            Kenali Lebih Lanjut <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. PROFIL PERUSAHAAN -->
        <section class="about-section" id="profil-perusahaan">
            <div class="about-container">
                <div class="about-split-grid">
                    <div class="about-photo-frame">
                        <img src="{{ asset('images/tentang/rempah_nusantara.png') }}" alt="Rempah Khas Nusantara"
                            onerror="this.src='https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=900&q=80'">
                    </div>

                    <div>
                        <span class="about-gold-line"></span>
                        <h2 class="about-heading">Profil Perusahaan</h2>
                        <p class="about-text">
                            PT. Kusantara merupakan perusahaan yang bergerak di bidang industri kuliner dan restoran dengan
                            fokus menghadirkan berbagai hidangan khas Indonesia yang autentik dan berkualitas.
                        </p>
                        <p class="about-text">
                            Kami percaya bahwa kekayaan kuliner Nusantara adalah warisan budaya yang perlu dijaga dan
                            diperkenalkan kepada masyarakat luas.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. SEJARAH KAMI -->
        <section class="about-section" style="padding-top: 12px;">
            <div class="about-container">
                <div class="about-split-grid is-reversed">
                    <div>
                        <span class="about-gold-line"></span>
                        <h2 class="about-heading">Sejarah Kami</h2>
                        <p class="about-text">
                            PT. Kusantara didirikan pada tahun 2026 dengan semangat untuk melestarikan dan mengembangkan
                            kekayaan kuliner khas Indonesia.
                        </p>
                        <p class="about-text">
                            Berangkat dari kecintaan terhadap kuliner Nusantara, kami hadir untuk menghadirkan pengalaman
                            kuliner terbaik dengan kualitas yang terjaga.
                        </p>
                    </div>

                    <div class="about-photo-frame">
                        <img src="{{ asset('images/tentang/interior_restoran.png') }}" alt="Interior Restoran PT. Kusantara"
                            onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80'">
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. VISI & MISI -->
        <section class="about-section" style="padding-top: 12px;">
            <div class="about-container">
                <div class="about-vm-card">
                    <div class="about-vm-col">
                        <div class="about-vm-icon">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div class="about-vm-body">
                            <h3>Visi</h3>
                            <p>
                                Menjadi perusahaan kuliner Nusantara terkemuka yang mampu memperkenalkan kekayaan cita rasa
                                dan budaya kuliner Indonesia kepada masyarakat luas hingga mancanegara.
                            </p>
                        </div>
                    </div>

                    <div class="about-vm-col">
                        <div class="about-vm-icon">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <div class="about-vm-body">
                            <h3>Misi</h3>
                            <ul class="about-misi-list">
                                <li>Menghadirkan produk kuliner Nusantara yang autentik dan berkualitas</li>
                                <li>Mengutamakan bahan baku lokal yang segar dan berkualitas</li>
                                <li>Menjaga standar kebersihan serta keamanan pangan</li>
                                <li>Memberikan pelayanan yang ramah dan profesional</li>
                                <li>Mengembangkan inovasi menu tanpa meninggalkan tradisi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. KEUNGGULAN KAMI -->
        <section class="about-section" style="padding-top: 20px;">
            <div class="about-container">
                <div class="about-center-head">
                    <div class="about-center-title-wrap">
                        <h2 class="about-center-title">Keunggulan Kami</h2>
                    </div>
                </div>

                <div class="about-usp-grid">
                    <div class="about-usp-card">
                        <div class="about-usp-icon"><i class="fa-solid fa-bell-concierge"></i></div>
                        <h4>Cita Rasa Autentik</h4>
                        <p>Resep khas Nusantara dengan rempah pilihan.</p>
                    </div>

                    <div class="about-usp-card">
                        <div class="about-usp-icon"><i class="fa-solid fa-leaf"></i></div>
                        <h4>Bahan Baku Berkualitas</h4>
                        <p>Mengutamakan bahan lokal yang segar.</p>
                    </div>

                    <div class="about-usp-card">
                        <div class="about-usp-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <h4>Standar Kebersihan Modern</h4>
                        <p>Proses higienis dengan standar keamanan pangan.</p>
                    </div>

                    <div class="about-usp-card">
                        <div class="about-usp-icon"><i class="fa-solid fa-users"></i></div>
                        <h4>Pelayanan Profesional</h4>
                        <p>Ramah, cepat, dan memuaskan.</p>
                    </div>

                    <div class="about-usp-card">
                        <div class="about-usp-icon"><i class="fa-solid fa-lightbulb"></i></div>
                        <h4>Inovasi Berkelanjutan</h4>
                        <p>Terus berinovasi untuk pengalaman terbaik.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. TIM KAMI (TIM INTI DARI PPT PROFIL PERUSAHAAN) -->
        <section class="about-section" style="padding-top: 12px; padding-bottom: 72px;">
            <div class="about-container">
                <div class="about-center-head">
                    <h2 class="about-center-title" style="margin-bottom: 8px;">Tim Kami</h2>
                    <p class="about-center-sub">
                        Didukung oleh tim profesional yang berdedikasi, PT. Kusantara terus berkomitmen memberikan pelayanan
                        terbaik serta menghadirkan inovasi kuliner untuk seluruh pelanggan.
                    </p>
                </div>

                @php
                    // Sesuaikan nama, posisi, dan file foto (taruh di public/images/tim/) untuk 6 anggota tim inti
                    $timInti = [
                        [
                            'nama' => 'M. Airlangga Alfarisi',
                            'posisi' => 'CEO / Direktur Utama',
                            'foto' => 'images/tentang/tim/anggota-1.jpeg',
                        ],
                        [
                            'nama' => 'Evan Kusumah Setiawan',
                            'posisi' => 'Product Manager & IT',
                            'foto' => 'images/tentang/tim/anggota-2.jpeg',
                        ],
                        [
                            'nama' => 'Adly Radithya Hamdani',
                            'posisi' => 'Sales & Marketing',
                            'foto' => 'images/tentang/tim/anggota-3.jpeg',
                        ],
                        [
                            'nama' => 'Gilang Aditya Pratama',
                            'posisi' => 'Product Manager & Customer Service',
                            'foto' => 'images/tentang/tim/anggota-4.jpeg',
                        ],
                        [
                            'nama' => 'Irfan Ainul Yaqin',
                            'posisi' => 'Finance & Accounting',
                            'foto' => 'images/tentang/tim/anggota-5.jpeg',
                        ],
                        [
                            'nama' => 'Radhythia Ardian Ilham G. N.',
                            'posisi' => 'COO / Operasional',
                            'foto' => 'images/tentang/tim/anggota-6.jpeg',
                        ],
                    ];
                @endphp

                <div class="about-team-grid">
                    @foreach ($timInti as $anggota)
                        <article class="about-team-card">
                            <div class="about-team-photo">
                                <img src="{{ asset($anggota['foto']) }}" alt="{{ $anggota['nama'] }}"
                                    onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($anggota['nama']) }}&background=132238&color=E5C17B&size=400'">
                            </div>
                            <div class="about-team-badge">
                                <div class="about-team-name">{{ $anggota['nama'] }}</div>
                                <div class="about-team-role">{{ $anggota['posisi'] }}</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 7. CTA BANNER BAWAH -->
        <section class="about-cta-banner">
            <div class="about-container">
                <div class="about-cta-inner">
                    <div class="about-cta-text">
                        <h2>Bersama Membangun Kuliner Nusantara</h2>
                        <p>Mari menjalin kerja sama dan menjadi bagian dari perjalanan PT. Kusantara.</p>
                    </div>
                    <a href="{{ url('/kontak') }}" class="about-cta-btn">
                        Hubungi Kami <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </section>
        @php
            $reviewsList = $publishedReviews ?? [];
        @endphp

        @php
            $reviewsList = $publishedReviews ?? [];
            $totalPublished = count($reviewsList);
            $avgPublished = $totalPublished > 0
                ? number_format(collect($reviewsList)->avg(fn($r) => (int) $r['rating']), 1)
                : '5.0';
        @endphp

        <style>
            #ulasan-pelanggan .kr-input,
            #ulasan-pelanggan .kr-select,
            #ulasan-pelanggan .kr-textarea {
                width: 100%;
                padding: 10px 13px;
                border-radius: 10px;
                border: 1px solid #dfd3be;
                background: #fffdf9;
                color: #1f2937;
                font-size: 0.9rem;
                transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
                box-sizing: border-box;
            }

            #ulasan-pelanggan .kr-input:focus,
            #ulasan-pelanggan .kr-select:focus,
            #ulasan-pelanggan .kr-textarea:focus {
                outline: none;
                border-color: #c88a2b;
                background: #ffffff;
                box-shadow: 0 0 0 3px rgba(200, 138, 43, 0.16);
            }

            #ulasan-pelanggan .kr-star-btn {
                background: #fffdf9;
                border: 1px solid #dfd3be;
                color: #d1d5db;
                width: 38px;
                height: 38px;
                border-radius: 10px;
                font-size: 1.15rem;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: transform 0.15s ease, color 0.15s ease, border-color 0.15s ease, background-color 0.15s ease;
            }

            #ulasan-pelanggan .kr-star-btn.is-active,
            #ulasan-pelanggan .kr-star-btn:hover {
                color: #d97706;
                border-color: #e5b55c;
                background: #fef9ec;
                transform: translateY(-1px);
            }

            #ulasan-pelanggan .kr-chip {
                padding: 5px 11px;
                border-radius: 999px;
                border: 1px dashed #d6c3a1;
                background: #fffdf9;
                color: #574934;
                font-size: 0.78rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.15s ease;
            }

            #ulasan-pelanggan .kr-chip:hover {
                background: #fef3c7;
                border-style: solid;
                border-color: #d97706;
                color: #78350f;
            }

            #ulasan-pelanggan .kr-submit-btn {
                padding: 10px 20px;
                border-radius: 10px;
                border: none;
                background: linear-gradient(135deg, #162238 0%, #243656 100%);
                color: #fff;
                font-weight: 700;
                font-size: 0.88rem;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                box-shadow: 0 6px 16px rgba(22, 34, 56, 0.18);
                transition: transform 0.15s ease, filter 0.15s ease;
            }

            #ulasan-pelanggan .kr-submit-btn:hover {
                filter: brightness(1.06);
                transform: translateY(-1px);
            }

            #ulasan-pelanggan .kr-submit-btn:disabled {
                opacity: 0.65;
                cursor: not-allowed;
                transform: none;
            }
        </style>

        <section id="ulasan-pelanggan"
            style="max-width:1120px;margin:36px auto 0;padding:28px;border-radius:20px;border:1px solid #e6dcc8;background:linear-gradient(180deg,#fcfaf5 0%,#f7f2e7 100%);box-shadow:0 10px 30px rgba(17,24,39,0.04);">
            <!-- HEADER + RINGKASAN RATING -->
            <div
                style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:14px;margin-bottom:20px;">
                <div>
                    <span
                        style="display:inline-block;font-size:0.72rem;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#b45309;margin-bottom:4px;">
                        Testimoni &amp; Masukan
                    </span>
                    <h2 style="margin:0 0 4px;font-size:1.45rem;font-weight:800;color:#111827;">Ulasan Pelanggan</h2>
                    <p style="margin:0;font-size:0.88rem;color:#6b7280;">
                        Bagikan pengalaman Anda atau baca ulasan pelanggan yang telah terbit.
                    </p>
                </div>

                <div
                    style="display:flex;align-items:center;gap:12px;background:#fff;border:1px solid #e6dcc8;border-radius:14px;padding:10px 16px;">
                    <div style="font-size:1.5rem;font-weight:800;color:#111827;line-height:1;">
                        {{ $avgPublished }}<span style="font-size:0.85rem;color:#6b7280;font-weight:600;">/5</span>
                    </div>
                    <div>
                        <div style="color:#d97706;font-size:0.88rem;letter-spacing:1px;">★★★★★</div>
                        <div style="font-size:0.76rem;color:#6b7280;font-weight:600;">{{ $totalPublished }} ulasan terbit
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM TULIS ULASAN -->
            <form id="publicReviewForm" action="{{ route('ulasan.store') }}" method="POST"
                style="background:#fff;border:1px solid #e6dcc8;border-radius:16px;padding:20px;margin-bottom:22px;box-shadow:0 4px 16px rgba(17,24,39,0.03);">
                @csrf

                <div
                    style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:10px;padding-bottom:14px;margin-bottom:16px;border-bottom:1px solid #f2ece0;">
                    <div style="font-weight:800;font-size:0.98rem;color:#111827;">
                        Tulis Ulasan Anda
                    </div>

                    <!-- PICKER BINTANG INTERAKTIF -->
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        <span style="font-size:0.8rem;font-weight:700;color:#4b5563;">Rating:</span>
                        <div id="krStarPicker" style="display:inline-flex;gap:5px;">
                            @for ($s = 1; $s <= 5; $s++)
                                <button type="button" class="kr-star-btn {{ $s <= 5 ? 'is-active' : '' }}" data-value="{{ $s }}"
                                    aria-label="Bintang {{ $s }}">
                                    ★
                                </button>
                            @endfor
                        </div>
                        <span id="krRatingCaption" style="font-size:0.8rem;font-weight:700;color:#b45309;min-width:95px;">
                            5/5 — Sangat Puas
                        </span>
                        <input type="hidden" name="rating" id="krRatingInput" value="5">
                    </div>
                </div>

                <div
                    style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:12px;">
                    <div>
                        <label style="display:block;font-size:0.76rem;font-weight:700;color:#4b5563;margin-bottom:5px;">Nama
                            Lengkap *</label>
                        <input type="text" name="customer_name" class="kr-input" placeholder="Contoh: Rina Kusuma" required>
                    </div>

                    <div>
                        <label style="display:block;font-size:0.76rem;font-weight:700;color:#4b5563;margin-bottom:5px;">No.
                            HP / Kode Pesanan</label>
                        <input type="text" name="phone_or_order" class="kr-input" placeholder="Opsional (mis. #KS001)">
                    </div>

                    <div>
                        <label
                            style="display:block;font-size:0.76rem;font-weight:700;color:#4b5563;margin-bottom:5px;">Layanan
                            / Menu</label>
                        <select name="service_or_menu" class="kr-select">
                            <option value="Delivery">Delivery</option>
                            <option value="Dine-in">Dine-in</option>
                            <option value="Catering">Catering</option>
                            <option value="Menu Nusantara">Menu Nusantara</option>
                        </select>
                    </div>
                </div>

                <!-- QUICK TAGS -->
                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:8px;">
                    <span style="font-size:0.75rem;color:#6b7280;font-weight:600;margin-right:2px;">Tambah cepat:</span>
                    <button type="button" class="kr-chip" data-tag="Bumbu khas Nusantara terasa pas">Bumbu terasa
                        pas</button>
                    <button type="button" class="kr-chip" data-tag="Pengiriman tepat waktu">Pengiriman tepat waktu</button>
                    <button type="button" class="kr-chip" data-tag="Kemasan rapi dan higienis">Kemasan rapi</button>
                    <button type="button" class="kr-chip" data-tag="Pelayanan ramah dan responsif">Pelayanan ramah</button>
                </div>

                <div>
                    <textarea id="krCommentTextarea" name="comment" rows="3" maxlength="500" class="kr-textarea"
                        placeholder="Ceritakan pengalaman rasa, pelayanan, atau pengiriman Anda..." required></textarea>
                    <div
                        style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;font-size:0.74rem;color:#6b7280;">
                        <span>Ulasan akan tampil setelah ditinjau oleh admin.</span>
                        <span id="krCharCount">0 / 500</span>
                    </div>
                </div>

                <div
                    style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:14px;">
                    <button type="submit" class="kr-submit-btn">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Ulasan
                    </button>
                    <span id="publicReviewFeedback" style="font-size:0.84rem;font-weight:600;"></span>
                </div>
            </form>

            <!-- DAFTAR ULASAN TERBIT -->
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:14px;">
                @forelse ($reviewsList as $item)
                    @php
                        $initial = strtoupper(mb_substr(trim($item['customer_name']), 0, 1)) ?: 'P';
                    @endphp
                    <div
                        style="background:#fff;border:1px solid #e6dcc8;border-radius:16px;padding:16px;display:flex;flex-direction:column;gap:10px;box-shadow:0 3px 12px rgba(17,24,39,0.03);">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div
                                    style="width:36px;height:36px;border-radius:999px;background:#fef3c7;color:#92400e;font-weight:800;font-size:0.88rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    {{ $initial }}
                                </div>
                                <div>
                                    <div style="font-size:0.92rem;font-weight:800;color:#111827;line-height:1.2;">
                                        {{ $item['customer_name'] }}</div>
                                    <div style="font-size:0.74rem;color:#6b7280;margin-top:3px;">
                                        <span
                                            style="background:#f3f4f6;color:#374151;padding:1px 7px;border-radius:999px;font-weight:600;">{{ $item['service_or_menu'] }}</span>
                                        • {{ $item['created_at'] }}
                                    </div>
                                </div>
                            </div>
                            <span
                                style="color:#d97706;font-weight:700;font-size:0.85rem;white-space:nowrap;">{{ str_repeat('★', $item['rating']) }}</span>
                        </div>

                        <p style="margin:0;font-size:0.88rem;color:#374151;line-height:1.55;">
                            "{{ $item['comment'] }}"
                        </p>

                        @if (!empty($item['admin_reply']))
                            <div
                                style="margin-top:2px;padding:10px 12px;border-radius:10px;background:#fcfaf5;border-left:3px solid #d97706;font-size:0.8rem;color:#374151;line-height:1.45;">
                                <div style="font-weight:800;color:#92400e;margin-bottom:2px;">Balasan Kusantara</div>
                                {{ $item['admin_reply'] }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div
                        style="grid-column:1/-1;text-align:center;padding:24px;background:#fff;border:1px dashed #dfd3be;border-radius:14px;color:#6b7280;font-size:0.88rem;">
                        Belum ada ulasan yang diterbitkan. Jadilah yang pertama memberi ulasan!
                    </div>
                @endforelse
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const revForm = document.getElementById('publicReviewForm');
                if (!revForm) return;

                const ratingLabels = {
                    1: '1/5 — Buruk',
                    2: '2/5 — Kurang',
                    3: '3/5 — Cukup',
                    4: '4/5 — Puas',
                    5: '5/5 — Sangat Puas'
                };

                const starBtns = Array.from(document.querySelectorAll('#krStarPicker .kr-star-btn'));
                const ratingInput = document.getElementById('krRatingInput');
                const ratingCaption = document.getElementById('krRatingCaption');
                const commentTextarea = document.getElementById('krCommentTextarea');
                const charCount = document.getElementById('krCharCount');

                function setRating(val) {
                    const num = Math.max(1, Math.min(5, parseInt(val, 10) || 5));
                    if (ratingInput) ratingInput.value = num;
                    if (ratingCaption) ratingCaption.textContent = ratingLabels[num] || (num + '/5');

                    starBtns.forEach(function (btn) {
                        const btnVal = parseInt(btn.getAttribute('data-value'), 10);
                        btn.classList.toggle('is-active', btnVal <= num);
                    });
                }

                starBtns.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        setRating(btn.getAttribute('data-value'));
                    });
                });

                function updateCharCount() {
                    if (!commentTextarea || !charCount) return;
                    charCount.textContent = commentTextarea.value.length + ' / 500';
                }

                commentTextarea?.addEventListener('input', updateCharCount);

                document.querySelectorAll('#ulasan-pelanggan .kr-chip').forEach(function (chip) {
                    chip.addEventListener('click', function () {
                        if (!commentTextarea) return;
                        const tag = chip.getAttribute('data-tag') || '';
                        const current = commentTextarea.value.trim();
                        if (!current) {
                            commentTextarea.value = tag;
                        } else if (!current.toLowerCase().includes(tag.toLowerCase())) {
                            commentTextarea.value = current.replace(/[.!?]+$/, '') + ', ' + tag.charAt(0).toLowerCase() + tag.slice(1);
                        }
                        updateCharCount();
                        commentTextarea.focus();
                    });
                });

                revForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const fb = document.getElementById('publicReviewFeedback');
                    const submitBtn = revForm.querySelector('button[type="submit"]');
                    const payload = Object.fromEntries(new FormData(revForm).entries());

                    if (submitBtn) submitBtn.disabled = true;

                    fetch(revForm.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': revForm.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    })
                        .then(async function (res) {
                            const data = await res.json();
                            if (fb) {
                                fb.style.color = data.ok ? '#047857' : '#b91c1c';
                                fb.textContent = data.message || 'Ulasan berhasil dikirim.';
                            }
                            if (data.ok) {
                                revForm.reset();
                                setRating(5);
                                updateCharCount();
                            }
                        })
                        .catch(function () {
                            if (fb) {
                                fb.style.color = '#b91c1c';
                                fb.textContent = 'Terjadi kesalahan saat mengirim ulasan.';
                            }
                        })
                        .finally(function () {
                            if (submitBtn) submitBtn.disabled = false;
                        });
                });
            });
        </script>
    </div>
@endsection