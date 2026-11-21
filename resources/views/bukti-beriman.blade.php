@extends('layouts.app')

@section('title', 'Bukti Beriman - PT. Kusantara')

@section('content')
<section class="section-beriman">
    <!-- Hero Banner -->
    <div class="hero-beriman">
        <div class="container hero-grid">
            <div class="hero-text">
                <h1>Bukti Beriman</h1>
                <p class="subtitle">Keimanan dalam Setiap Transaksi</p>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1609599006353-e629aaabfeae?auto=format&fit=crop&w=800&q=80" alt="Al-Qur'an dan Lentera">
            </div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="container main-card-wrapper">
        <div class="card-beriman">
            <!-- Kolom Kiri: Dalil & Referensi -->
            <div class="dalil-col">
                <h2>Dalil &amp; Referensi</h2>

                @foreach ($dalilList as $dalil)
                    <div class="dalil-item">
                        <div class="dalil-icon">
                            <i class="fa-solid {{ $dalil['icon'] }}"></i>
                        </div>
                        <div class="dalil-content">
                            <h3>{{ $dalil['title'] }}</h3>
                            <p>{{ $dalil['quote'] }} <span>{{ $dalil['source'] }}</span></p>
                        </div>
                    </div>

                    @if (! $loop->last)
                        <hr class="divider">
                    @endif
                @endforeach
            </div>

            <!-- Kolom Kanan: Penerapan di PT. Kusantara -->
            <div class="penerapan-col">
                <h2>Penerapan di PT. Kusantara</h2>
                <ul class="checklist">
                    @foreach ($penerapanList as $item)
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mosque-watermark">
                    <i class="fa-solid fa-mosque"></i>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection