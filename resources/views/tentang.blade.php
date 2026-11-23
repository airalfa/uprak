@extends('layouts.app')

@section('title', 'Tentang Kami - PT. Kusantara')

@section('content')
    <div class="about-page">

        <!-- 1. HERO BANNER -->
        <section class="about-hero">
            <img src="{{ asset('images/main-about.png') }}" alt="Restoran PT. Kusantara" class="about-hero-bg"
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
                        <img src="{{ asset('images/rempah_nusantara.png') }}" alt="Rempah Khas Nusantara"
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
                        <img src="{{ asset('images/interior_restoran.png') }}" alt="Interior Restoran PT. Kusantara"
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
                            'foto' => 'images/tim/anggota-1.jpeg',
                        ],
                        [
                            'nama' => 'Evan Kusumah Setiawan',
                            'posisi' => 'Product Manager & IT',
                            'foto' => 'images/tim/anggota-2.jpeg',
                        ],
                        [
                            'nama' => 'Adly Radithya Hamdani',
                            'posisi' => 'Sales & Marketing',
                            'foto' => 'images/tim/anggota-3.jpeg',
                        ],
                        [
                            'nama' => 'Gilang Aditya Pratama',
                            'posisi' => 'Product Manager & Customer Service',
                            'foto' => 'images/tim/anggota-4.jpeg',
                        ],
                        [
                            'nama' => 'Irfan Ainul Yaqin',
                            'posisi' => 'Finance & Accounting',
                            'foto' => 'images/tim/anggota-5.jpeg',
                        ],
                        [
                            'nama' => 'Radhythia Ardian Ilham G. N.',
                            'posisi' => 'COO / Operasional',
                            'foto' => 'images/tim/anggota-6.jpeg',
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

    </div>
@endsection