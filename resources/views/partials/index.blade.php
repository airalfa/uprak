@extends('partials.main')

@section('title', 'PT. Kusantara - Rasa Nusantara dalam Setiap Suapan')

@section('content')
    <!-- ZONA 1: HERO SECTION -->
    <section class="hero-section" id="beranda" style="background-image: url('{{ asset('images/backgroud.jpg') }}');">
        <div class="hero-overlay"></div>
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Rasa Nusantara <br>
                    dalam Setiap Suapan
                </h1>
                <p class="hero-subtitle">
                    Nikmati kelezatan kuliner autentik Indonesia dengan kualitas terbaik dan cita rasa warisan dari PT. Kusantara.
                </p>
                <div class="hero-buttons">
                    <a href="/katalog" class="btn-pesan">
                        Pesan Sekarang <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ZONA 1: FEATURE HIGHLIGHTS BAR -->
    <section class="features-bar">
        <div class="features-container">
            <div class="feature-item">
                <div class="feature-icon icon-green">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="feature-text">
                    <h4>Kehalalan Terjaga</h4>
                    <p>Sertifikasi Halal Resmi MUI</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon icon-navy">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="feature-text">
                    <h4>Kualitas Premium</h4>
                    <p>Bahan baku segar & higienis</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon icon-gold">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="feature-text">
                    <h4>Layanan Lengkap</h4>
                    <p>Dine-in, Takeaway, Delivery & Catering</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon icon-navy">
                    <i class="fas fa-award"></i>
                </div>
                <div class="feature-text">
                    <h4>Pelayanan Terbaik</h4>
                    <p>Cepat, ramah & profesional</p>
                </div>
            </div>
        </div>
    </section>
@endsection