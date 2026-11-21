@extends('layouts.app')

@section('title', 'PT. Kusantara - Bukti Beriman & Isu Suku Bunga')

@section('content')
    <!-- SECTION 1: BUKTI BERIMAN -->
    <section id="bukti-beriman" class="section-beriman">
        <div class="hero-beriman">
            <div class="container hero-grid">
                <div class="hero-text">
                    <h1>Bukti Beriman</h1>
                    <p class="subtitle">Keimanan dalam Setiap Transaksi</p>
                </div>
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1609599006353-e629aaabfeae?auto=format&fit=crop&w=800&q=80" alt="Al-Quran dan Lentera">
                </div>
            </div>
        </div>

        <div class="container main-card-wrapper">
            <div class="card-beriman">
                <!-- Kolom Kiri: Dalil & Referensi -->
                <div class="dalil-col">
                    <h2>Dalil & Referensi</h2>

                    @foreach ($dalilList as $index => $dalil)
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

                <!-- Kolom Kanan: Penerapan -->
                <div id="tentang" class="penerapan-col">
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

    <!-- SECTION 2: ISU SUKU BUNGA -->
    <section id="isu-suku-bunga" class="section-suku-bunga">
        <div class="container">
            <div class="section-header-flex">
                <div>
                    <h1>Isu Suku Bunga</h1>
                    <p class="subtitle">Antara Kemudahan dan Risiko</p>
                </div>
                <div class="header-controls">
                    <span class="pill-badge">02</span>
                    <button class="dots-btn" title="Opsi Lainnya" type="button">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                </div>
            </div>

            <div class="suku-bunga-grid">
                <!-- Kolom Gambar Kiri -->
                <div class="suku-bunga-image">
                    <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?auto=format&fit=crop&w=600&q=80" alt="Pertumbuhan Ekonomi & Persentase">
                    <div class="percent-tag">%</div>
                </div>

                <!-- Kolom Konten Kanan -->
                <div class="suku-bunga-content">
                    <div class="issue-box">
                        <h3><i class="fa-solid fa-circle-info"></i> Isu Utama</h3>
                        <p>Penggunaan suku bunga dalam fitur <em>PayLater</em> dan pembiayaan mikro digital yang kian marak di masyarakat.</p>
                    </div>

                    <div class="arguments-grid">
                        <!-- Arguments For -->
                        <div class="arg-card arg-for">
                            <h4>Arguments For <br><span>(Konvensional / Praktis)</span></h4>
                            <ul>
                                @foreach ($argumentsFor as $point)
                                    <li><i class="fa-solid fa-check"></i> {{ $point }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Arguments Against -->
                        <div class="arg-card arg-against">
                            <h4>Arguments Against <br><span>(Etika Syariah)</span></h4>
                            <ul>
                                @foreach ($argumentsAgainst as $point)
                                    <li><i class="fa-solid fa-xmark"></i> {{ $point }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conclusion / Recommendation Banner -->
            <div class="conclusion-banner">
                <div class="bulb-icon">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <div class="conclusion-text">
                    <h3>Conclusion / Recommendation</h3>
                    <p>Perusahaan perlu menyeimbangkan kemudahan transaksi dengan prinsip keadilan dan etika syariah. Rekomendasi utama adalah menerapkan skema pembiayaan berbasis akad jual beli (<em>Murabahah</em>) dengan margin yang disepakati di awal tanpa denda keterlambatan berbunga.</p>
                </div>
            </div>
        </div>
    </section>
@endsection