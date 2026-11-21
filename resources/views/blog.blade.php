@extends('partials.main')

@section('title', 'Arsip & Sejarah Perbankan Indonesia - PT. Kusantara')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
@endpush

@section('content')
<div class="ed-wrapper" id="top-page">

    <!-- Indikator Progres Baca -->
    <div class="ed-progress" id="edProgress"></div>

    <!-- 1. MASTHEAD / HEADER ALA SURAT KABAR & ARSIP SEJARAH -->
    <header class="ed-masthead">
        <div class="ed-container">
            <div class="ed-meta-strip">
                <span>EDISI LITERASI KEUANGAN & SEJARAH</span>
                <span class="ed-dot">•</span>
                <span>XI PPLG — SMKS MAHAPUTRA CERDAS UTAMA</span>
                <span class="ed-dot">•</span>
                <span>PT. KUSANTARA ARCHIVE</span>
            </div>

            <div class="ed-hero-grid">
                <div class="ed-hero-main">
                    <h1 class="ed-display-title">
                        Dari Gulden Kolonial Menuju <em>Kedaulatan Rupiah</em> &amp; Transaksi Digital
                    </h1>
                    <p class="ed-lead">
                        Menelusuri akar sejarah lembaga perbankan Indonesia sejak 1828, perjuangan melawan bunga rentenir, hingga bagaimana <strong>PT. Kusantara</strong> mengadopsi nilai transparansi moneter dalam sistem pembayaran modern.
                    </p>
                </div>

                <aside class="ed-hero-index">
                    <div class="ed-index-label">DAFTAR ISI KAJIAN</div>
                    <nav class="ed-toc">
                        <a href="#bab-periode" class="ed-toc-link"><span>01</span> Transformasi 4 Era Ekonomi</a>
                        <a href="#bab-kronologi" class="ed-toc-link"><span>02</span> Kronologi Perbankan (1828–Kini)</a>
                        <a href="#bab-tokoh" class="ed-toc-link"><span>03</span> Arsitek &amp; Lembaga Moneter</a>
                        <a href="#bab-analisis" class="ed-toc-link"><span>04</span> Bedah Fakta vs Dampak</a>
                        <a href="#bab-kusantara" class="ed-toc-link"><span>05</span> Adopsi di PT. Kusantara</a>
                    </nav>
                </aside>
            </div>
        </div>
    </header>

    <!-- 2. BAB 01: TRANSFORMASI 4 ERA (SPLIT INTERACTIVE READER) -->
    <section class="ed-section" id="bab-periode">
        <div class="ed-container">
            <div class="ed-section-head">
                <span class="ed-kicker">BAGIAN I — PERBANDINGAN PERIODE</span>
                <h2 class="ed-h2">Perubahan Sistem Ekonomi &amp; Kepemilikan Bank</h2>
            </div>

            <div class="ed-split-reader">
                <!-- Navigasi Era Kiri (Sticky List) -->
                <div class="ed-era-nav" role="tablist">
                    <button type="button" class="ed-era-btn active" data-era="kolonial">
                        <span class="era-num">I</span>
                        <div>
                            <strong>Masa Kolonial</strong>
                            <small>Abad ke-19 – 1942</small>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <button type="button" class="ed-era-btn" data-era="awal-merdeka">
                        <span class="era-num">II</span>
                        <div>
                            <strong>Awal Kemerdekaan</strong>
                            <small>1945 – 1949</small>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <button type="button" class="ed-era-btn" data-era="kedaulatan">
                        <span class="era-num">III</span>
                        <div>
                            <strong>Pasca Pengakuan Kedaulatan</strong>
                            <small>1950 – 1980-an</small>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <button type="button" class="ed-era-btn" data-era="modern">
                        <span class="era-num">IV</span>
                        <div>
                            <strong>Era Modern &amp; Digital</strong>
                            <small>2000-an – Sekarang</small>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

                <!-- Panel Baca Kanan (Editorial Sheet) -->
                <article class="ed-era-sheet" id="edEraSheet">
                    <!-- Dirender dinamis oleh blog.js -->
                </article>
            </div>
        </div>
    </section>

    <!-- 3. BAB 02: KRONOLOGI VERTICAL ARCHIVE TIMELINE -->
    <section class="ed-section ed-bg-warm" id="bab-kronologi">
        <div class="ed-container">
            <div class="ed-section-head">
                <span class="ed-kicker">BAGIAN II — GARIS WAKTU HISTORIS</span>
                <h2 class="ed-h2">Enam Tonggak Sejarah Perbankan Indonesia</h2>
                <p class="ed-subtext">Klik setiap baris peristiwa untuk membuka detail tokoh penggerak dan dampak ekonominya.</p>
            </div>

            <div class="ed-ledger" id="edLedger">
                <!-- Baris-baris kronologi interaktif dirender oleh blog.js -->
            </div>
        </div>
    </section>

    <!-- 4. BAB 03: BIOGRAFI TOKOH & LEMBAGA PENTING (ASYMMETRIC EDITORIAL LAYOUT) -->
    <section class="ed-section" id="bab-tokoh">
        <div class="ed-container">
            <div class="ed-section-head">
                <span class="ed-kicker">BAGIAN III — TOKOH &amp; LEMBAGA</span>
                <h2 class="ed-h2">Para Perintis Kemandirian Perbankan Nasional</h2>
            </div>

            <!-- Editorial Profile Rows (Bukan kartu kotak yang identik membosankan) -->
            <div class="ed-profiles">
                <!-- Tokoh 1 -->
                <article class="ed-profile-row ed-reveal">
                    <figure class="ed-portrait">
                        <img src="{{ asset('images/tokoh-margono.jpeg') }}"
                             alt="R. M. Margono Djojohadikusumo"
                             onerror="this.src='https://ui-avatars.com/api/?name=Margono+Djojohadikusumo&background=132238&color=E5C17B&size=400'">
                        <figcaption>Arsip Nasional RI / Wikimedia Commons</figcaption>
                    </figure>
                    <div class="ed-profile-content">
                        <div class="ed-profile-tag">PENDIRI &amp; DIRUT PERTAMA BNI 1946</div>
                        <h3>R. M. Margono Djojohadikusumo</h3>
                        <p class="ed-lifespan">Purwokerto, 16 Mei 1894 – 25 Juli 1978</p>
                        <p class="ed-profile-summary">
                            Lulusan ELS dan OSVIA yang meniti karier di dinas perkreditan rakyat masa Hindia Belanda serta menjabat Ketua Dewan Pertimbangan Agung (DPA) pertama RI. Ia menggagas lahirnya bank sirkulasi milik bangsa sendiri untuk memutus ketergantungan pada mata uang kolonial dan mendanai perjuangan kemerdekaan melalui pendirian <strong>Bank Negara Indonesia (BNI)</strong> pada 5 Juli 1946.
                        </p>
                        <div class="ed-quote-inline">
                            “Keteladanan: Patriotisme ekonomi, integritas tinggi, dan keberanian membangun lembaga keuangan nasional dari nol di tengah masa revolusi.”
                        </div>
                    </div>
                </article>

                <!-- Tokoh 2 (Posisi foto di kanan untuk ritme visual editorial) -->
                <article class="ed-profile-row ed-reverse ed-reveal">
                    <figure class="ed-portrait">
                        <img src="{{ asset('images/tokoh-sjafruddin.jpeg') }}"
                             alt="Mr. Sjafruddin Prawiranegara"
                             onerror="this.src='https://ui-avatars.com/api/?name=Sjafruddin+Prawiranegara&background=132238&color=E5C17B&size=400'">
                        <figcaption>Arsip Museum Bank Indonesia (bi.go.id)</figcaption>
                    </figure>
                    <div class="ed-profile-content">
                        <div class="ed-profile-tag">GUBERNUR PERTAMA BANK INDONESIA (1953)</div>
                        <h3>Mr. Sjafruddin Prawiranegara</h3>
                        <p class="ed-lifespan">Serang, 28 Februari 1911 – 15 Februari 1989</p>
                        <p class="ed-profile-summary">
                            Sarjana Hukum dari <em>Rechtshoogeschool te Batavia</em> (1939), mantan Menteri Keuangan RI, sekaligus Ketua Pemerintah Darurat Republik Indonesia (PDRI). Menjadi Presiden Direktur pribumi pertama <em>De Javasche Bank</em> pada 1951 dan memimpin langsung proses nasionalisasi hingga resmi bertransformasi menjadi <strong>Bank Indonesia</strong> pada 1 Juli 1953.
                        </p>
                        <div class="ed-quote-inline">
                            “Keteladanan: Kepemimpinan tegas, disiplin fiskal, dan dedikasi menjaga stabilitas nilai tukar rupiah di awal kedaulatan.”
                        </div>
                    </div>
                </article>

                <!-- Tokoh 3 -->
                <article class="ed-profile-row ed-reveal">
                    <figure class="ed-portrait">
                        <img src="{{ asset('images/tokoh-wirjaatmadja.jpeg') }}"
                             alt="Raden Bei Aria Wirjaatmadja"
                             onerror="this.src='https://ui-avatars.com/api/?name=Aria+Wirjaatmadja&background=132238&color=E5C17B&size=400'">
                        <figcaption>Arsip Museum BRI Purwokerto</figcaption>
                    </figure>
                    <div class="ed-profile-content">
                        <div class="ed-profile-tag">PERINTIS PERBANKAN RAKYAT (CIKAL BAKAL BRI)</div>
                        <h3>Raden Bei Aria Wirjaatmadja</h3>
                        <p class="ed-lifespan">Purwokerto, 1831 – 1902</p>
                        <p class="ed-profile-summary">
                            Patih Purwokerto yang prihatin melihat pegawai pribumi dan masyarakat kecil terjerat utang rentenir dengan bunga mencekik. Pada 16 Desember 1895, ia mendirikan <em>De Poerwokertosche Hulp en Spaarbank der Inlandsche Hoofden</em> sebagai lembaga simpan pinjam berbunga ringan yang kelak berkembang menjadi <strong>Bank Rakyat Indonesia (BRI)</strong>.
                        </p>
                        <div class="ed-quote-inline">
                            “Keteladanan: Perlindungan konsumen dari jeratan bunga mencekik/riba serta keberpihakan nyata pada ekonomi rakyat kecil.”
                        </div>
                    </div>
                </article>
            </div>

            <!-- Strip 3 Lembaga Penting (Gaya Tabel Arsip Minimalis) -->
            <div class="ed-institutions ed-reveal">
                <div class="ed-inst-item">
                    <span class="ed-inst-year">1828</span>
                    <h4>De Javasche Bank</h4>
                    <p>Bank sirkulasi era Hindia Belanda di Batavia pencetak uang gulden dan pembiayaan perdagangan ekspor kolonial sebelum dinasionalisasi Indonesia.</p>
                </div>
                <div class="ed-inst-item">
                    <span class="ed-inst-year">1946</span>
                    <h4>Bank Negara Indonesia (BNI)</h4>
                    <p>Bank milik negara pertama pasca-Proklamasi (5 Juli 1946) yang menerbitkan Oeang Republik Indonesia (ORI) dan menopang ekonomi perjuangan.</p>
                </div>
                <div class="ed-inst-item">
                    <span class="ed-inst-year">1953</span>
                    <h4>Bank Indonesia (BI)</h4>
                    <p>Lahir dari nasionalisasi <em>De Javasche Bank</em> melalui UU No. 11 Tahun 1953 sebagai bank sentral penjaga stabilitas rupiah dan sistem pembayaran nasional.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. BAB 04: ANALISIS PERISTIWA & DAMPAK (FAKTA VS ANALISIS) -->
    <section class="ed-section ed-bg-warm" id="bab-analisis">
        <div class="ed-container">
            <div class="ed-section-head">
                <span class="ed-kicker">BAGIAN IV — KAJIAN SEBAB-AKIBAT</span>
                <h2 class="ed-h2">Membedah Fakta Sejarah &amp; Dampak Sosial-Ekonomi</h2>
            </div>

            <div class="ed-comparison-table ed-reveal">
                <!-- Peristiwa 1 -->
                <div class="ed-comp-block">
                    <div class="ed-comp-title">
                        <span>PERISTIWA I • 1951–1953</span>
                        <h3>Nasionalisasi De Javasche Bank menjadi Bank Indonesia</h3>
                    </div>
                    <div class="ed-comp-cols">
                        <div class="ed-col-fact">
                            <strong><i class="fas fa-file-alt"></i> Fakta Historis (Arsip)</strong>
                            <p>Pasca-KMB, bank sentral masih dikuasai Belanda melalui <em>De Javasche Bank</em>. Pemerintah RI menerbitkan UU No. 24 Tahun 1951 untuk membeli saham DJB, lalu mengesahkan UU Pokok Bank Indonesia No. 11 Tahun 1953 yang berlaku efektif <strong>1 Juli 1953</strong>.</p>
                        </div>
                        <div class="ed-col-impact">
                            <strong><i class="fas fa-chart-line"></i> Analisis Dampak Negara &amp; Masyarakat</strong>
                            <p><strong>Negara:</strong> Meraih kedaulatan penuh dalam menentukan kebijakan moneter, suku bunga acuan, dan cadangan devisa.<br>
                            <strong>Masyarakat:</strong> Penyaluran kredit mulai diarahkan untuk membangun usaha pribumi dan UMKM nasional.</p>
                        </div>
                    </div>
                </div>

                <!-- Peristiwa 2 -->
                <div class="ed-comp-block">
                    <div class="ed-comp-title">
                        <span>PERISTIWA II • 27 OKTOBER 1988</span>
                        <h3>Paket Kebijakan Oktober 1988 (Pakto 88) &amp; Digitalisasi</h3>
                    </div>
                    <div class="ed-comp-cols">
                        <div class="ed-col-fact">
                            <strong><i class="fas fa-file-alt"></i> Fakta Historis (Arsip)</strong>
                            <p>Pemerintah meluncurkan deregulasi perbankan (Pakto 88) yang mempermudah syarat pendirian bank swasta nasional dan pembukaan kantor cabang guna memperluas penghimpunan dana masyarakat.</p>
                        </div>
                        <div class="ed-col-impact">
                            <strong><i class="fas fa-users"></i> Analisis Dampak Negara &amp; Masyarakat</strong>
                            <p><strong>Negara:</strong> Mobilisasi dana masyarakat meningkat pesat untuk mendanai pertumbuhan sektor industri.<br>
                            <strong>Masyarakat:</strong> Akses layanan bank semakin dekat dengan warga hingga melahirkan ekosistem pembayaran digital seperti EDC, QRIS, dan cicilan <em>PayLater</em>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. BAB 05: ADOPSI SEJARAH PERBANKAN DI PT. KUSANTARA -->
    <section class="ed-section ed-kusantara-dark" id="bab-kusantara">
        <div class="ed-container">
            <div class="ed-dark-head ed-reveal">
                <span class="ed-kicker-gold">BAGIAN V — RELEVANSI PROYEK PT. KUSANTARA</span>
                <h2 class="ed-h2-light">Bagaimana PT. Kusantara Mengadopsi Sejarah Perbankan?</h2>
                <p>
                    Keberadaan fitur kalkulasi bunga dan cicilan <strong>Kusantara PayLater</strong> pada halaman <em>Checkout</em> kami merupakan penerapan nyata dari empat pelajaran penting sejarah perbankan Indonesia:
                </p>
            </div>

            <div class="ed-adopsi-list">
                <div class="ed-adopsi-row ed-reveal">
                    <div class="ed-adopsi-idx">01</div>
                    <div class="ed-adopsi-body">
                        <h4>Kemandirian Ekonomi &amp; Rantai Pasok Lokal</h4>
                        <p>Mengadopsi semangat pendirian BNI 1946 dan nasionalisasi BI untuk berdikari secara ekonomi, PT. Kusantara menyerap bahan baku rempah, daging, dan hasil laut langsung dari <strong>petani dan nelayan lokal Nusantara</strong>.</p>
                    </div>
                </div>

                <div class="ed-adopsi-row ed-reveal">
                    <div class="ed-adopsi-idx">02</div>
                    <div class="ed-adopsi-body">
                        <h4>Perlindungan Konsumen dari Bunga Mencekik (Semangat 1895)</h4>
                        <p>Belajar dari perjuangan Raden Bei Aria Wirjaatmadja melawan jeratan rentenir, fitur <strong>Kusantara PayLater</strong> menampilkan simulasi cicilan secara terbuka di awal dan menyediakan <strong>opsi akad syariah (biaya admin tetap tanpa riba)</strong>.</p>
                    </div>
                </div>

                <div class="ed-adopsi-row ed-reveal">
                    <div class="ed-adopsi-idx">03</div>
                    <div class="ed-adopsi-body">
                        <h4>Kepatuhan pada Sistem Pembayaran Nasional (BI &amp; OJK)</h4>
                        <p>Mengikuti regulasi moneter modern yang diawasi Bank Indonesia dan OJK, transaksi di PT. Kusantara mendukung pembayaran sah Rupiah melalui <strong>QRIS, Transfer Bank Nasional, dan EDC</strong> dengan pencatatan pajak resto 10% yang transparan.</p>
                    </div>
                </div>

                <div class="ed-adopsi-row ed-reveal">
                    <div class="ed-adopsi-idx">04</div>
                    <div class="ed-adopsi-body">
                        <h4>Edukasi Literasi Keuangan Sebelum Bertransaksi</h4>
                        <p>Tidak hanya menyediakan fitur cicilan, website PT. Kusantara menghubungkan sistem pembayaran langsung dengan modul <strong>Bukti Beriman</strong> dan <strong>Blog Sejarah Perbankan</strong> agar pembeli memahami aspek etika maupun sejarah ekonominya.</p>
                    </div>
                </div>
            </div>

            <!-- Daftar Sumber & Referensi -->
            <footer class="ed-sources ed-reveal">
                <div class="ed-sources-title">DAFTAR SUMBER &amp; REFERENSI ARSIP</div>
                <ul>
                    <li>Bank Indonesia. (2023). <em>Sejarah Bank Indonesia: Periode Pra-Nasionalisasi hingga Bank Sentral Republik Indonesia</em>. Museum Bank Indonesia (bi.go.id).</li>
                    <li>Kemendikbudristek. (2021). <em>Buku Sejarah Indonesia Kelas XI SMA/SMK</em>. Jakarta: Pusat Kurikulum dan Perbukuan.</li>
                    <li>Djojohadikusumo, Margono. (1970). <em>Kenang-Kenangan dari Tiga Zaman</em>. Jakarta: Indira.</li>
                    <li>Otoritas Jasa Keuangan (OJK). <em>Literasi Keuangan &amp; Sejarah Perbankan Indonesia</em> (sikapiuangmu.ojk.go.id).</li>
                </ul>
            </footer>
        </div>
    </section>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/blog.js') }}"></script>
@endpush