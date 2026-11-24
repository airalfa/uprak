@extends('layouts.app')

@section('title', 'Arsip & Sejarah Perbankan Indonesia - PT. Kusantara')

@section('content')
<div class="ed-wrapper" id="top-page">

    <!-- Indikator Progres Baca -->
    <div class="ed-progress" id="edProgress"></div>

    <!-- 1. MASTHEAD / HEADER ALA SURAT KABAR & ARSIP SEJARAH -->
    <header class="ed-masthead">
        <div class="ed-container">
            <div class="ed-meta-strip">
                <span>EDISI LITERASI KEUANGAN &amp; SEJARAH</span>
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
                        <a href="#bab-artikel-baru" class="ed-toc-link"><span>06</span> Artikel Terbaru</a>
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
                    <span class="ed-sheet-badge">PERIODE I • ABAD KE-19 – 1942</span>
                    <h3>Masa Kolonial Hindia Belanda</h3>
                    <div class="ed-sheet-cols">
                        <div class="ed-sheet-col">
                            <h4>Kondisi &amp; Sistem Ekonomi</h4>
                            <p>Sistem ekonomi berorientasi pada eksploitasi sumber daya alam dan ekspor komoditas perkebunan (<em>Cultuurstelsel</em> &amp; Politik Pintu Terbuka), yang seluruh keuntungannya mengalir ke pemerintah Belanda dan pengusaha Eropa.</p>
                        </div>
                        <div class="ed-sheet-col">
                            <h4>Perkembangan Lembaga Perbankan</h4>
                            <p>Didirikannya <em>De Javasche Bank</em> (1828) sebagai bank sirkulasi pencetak uang gulden serta bank-bank perkreditan rakyat yang belum sepenuhnya berpihak pada masyarakat pribumi.</p>
                        </div>
                    </div>
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
                <!-- Dirender langsung & juga interaktif via script di bawah -->
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

            <div class="ed-profiles">
                <!-- Tokoh 1 -->
                <article class="ed-profile-row ed-reveal">
                    <figure class="ed-portrait">
                        <img src="{{ asset('images/tokoh-margono.jpeg') }}"
                             alt="R. M. Margono Djojohadikusumo"
                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Margono+Djojohadikusumo&background=132238&color=E5C17B&size=400'">
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

                <!-- Tokoh 2 -->
                <article class="ed-profile-row ed-reverse ed-reveal">
                    <figure class="ed-portrait">
                        <img src="{{ asset('images/tokoh-sjafruddin.jpeg') }}"
                             alt="Mr. Sjafruddin Prawiranegara"
                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Sjafruddin+Prawiranegara&background=132238&color=E5C17B&size=400'">
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
                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Aria+Wirjaatmadja&background=132238&color=E5C17B&size=400'">
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

            <!-- Strip 3 Lembaga Penting -->
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

<!-- 7. TAMBAHAN: ARTIKEL TERBARU DARI DATABASE -->
    @php
        $extraPosts = $posts ?? [];
    @endphp
    <section class="ed-section ed-bg-warm" id="bab-artikel-baru">
        <div class="ed-container">
            <div class="ed-section-head">
                <span class="ed-kicker">BAGIAN VI — PUSTAKA ARTIKEL</span>
                <h2 class="ed-h2">Artikel &amp; Catatan Terbaru</h2>
                <p class="ed-subtext">Klik tombol baca untuk membuka isi artikel secara lengkap.</p>
            </div>

            @if (count($extraPosts) > 0)
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:18px;">
                    @foreach ($extraPosts as $post)
                        <article class="public-post-card"
                                 data-title="{{ e($post['title']) }}"
                                 data-post-category="{{ e($post['category']) }}"
                                 data-author="{{ e($post['author']) }}"
                                 data-date="{{ e($post['published_at']) }}"
                                 data-content="{{ e($post['content']) }}"
                                 style="background:#fff;border:1px solid #e6dfd1;border-radius:14px;padding:18px;display:flex;flex-direction:column;justify-content:space-between;gap:14px;">
                            <div>
                                <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;font-size:0.78rem;color:#6b7280;margin-bottom:8px;">
                                    <span style="padding:3px 10px;border-radius:999px;background:#fef3c7;color:#92400e;font-weight:700;">
                                        {{ $post['category'] }}
                                    </span>
                                    <span>{{ $post['published_at'] }}</span>
                                </div>

                                <h3 style="font-size:1.1rem;font-weight:800;margin:0 0 8px;line-height:1.35;">
                                    {{ $post['title'] }}
                                </h3>

                                <p style="margin:0;font-size:0.88rem;color:#4b5563;line-height:1.55;">
                                    {{ $post['excerpt'] }}
                                </p>
                            </div>

                            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:10px;border-top:1px solid #f1ece1;">
                                <span style="font-size:0.78rem;color:#6b7280;font-weight:600;">
                                    {{ $post['author'] }}
                                </span>

                                <button type="button" class="btn-read-post"
                                        style="padding:7px 13px;border-radius:8px;border:1px solid #d9ceba;background:#faf7f0;font-weight:700;font-size:0.82rem;cursor:pointer;">
                                    Baca Artikel <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div style="padding:24px;border-radius:14px;background:#fff;border:1px solid #e6dfd1;text-align:center;color:#6b7280;font-size:0.9rem;">
                    Belum ada artikel tambahan yang diterbitkan.
                </div>
            @endif
        </div>
    </section>

    <!-- MODAL BACA ARTIKEL LENGKAP -->
    <div id="readPostModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9999;padding:20px;overflow-y:auto;align-items:center;justify-content:center;">
        <div style="background:#fff;max-width:680px;width:100%;border-radius:16px;padding:24px;box-shadow:0 20px 50px rgba(0,0,0,0.2);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:12px;">
                <div>
                    <div id="readerMeta" style="font-size:0.8rem;color:#6b7280;font-weight:600;margin-bottom:6px;"></div>
                    <h2 id="readerTitle" style="font-size:1.35rem;font-weight:800;margin:0;color:#111827;"></h2>
                </div>
                <button type="button" id="closeReadPostModal"
                        style="background:none;border:none;font-size:1.5rem;line-height:1;cursor:pointer;color:#6b7280;">&times;</button>
            </div>

            <div id="readerContent" style="font-size:0.95rem;line-height:1.75;color:#374151;white-space:pre-line;max-height:60vh;overflow-y:auto;"></div>

            <div style="display:flex;justify-content:flex-end;margin-top:18px;">
                <button type="button" id="closeReadPostBtn"
                        style="padding:8px 16px;border-radius:8px;border:1px solid #d1d5db;background:#fff;font-weight:700;cursor:pointer;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL BACA ARTIKEL LENGKAP -->
    <div id="readPostModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9999;padding:20px;overflow-y:auto;align-items:center;justify-content:center;">
        <div style="background:#fff;max-width:680px;width:100%;border-radius:16px;padding:24px;box-shadow:0 20px 50px rgba(0,0,0,0.2);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:12px;">
                <div>
                    <div id="readerMeta" style="font-size:0.8rem;color:#6b7280;font-weight:600;margin-bottom:6px;"></div>
                    <h2 id="readerTitle" style="font-size:1.35rem;font-weight:800;margin:0;color:#111827;"></h2>
                </div>
                <button type="button" id="closeReadPostModal"
                        style="background:none;border:none;font-size:1.5rem;line-height:1;cursor:pointer;color:#6b7280;">&times;</button>
            </div>

            <div id="readerContent" style="font-size:0.95rem;line-height:1.75;color:#374151;white-space:pre-line;max-height:60vh;overflow-y:auto;"></div>

            <div style="display:flex;justify-content:flex-end;margin-top:18px;">
                <button type="button" id="closeReadPostBtn"
                        style="padding:8px 16px;border-radius:8px;border:1px solid #d1d5db;background:#fff;font-weight:700;cursor:pointer;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH ARTIKEL -->
    <div id="addPublicPostModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9999;padding:20px;overflow-y:auto;align-items:center;justify-content:center;">
        <div style="background:#fff;max-width:580px;width:100%;border-radius:16px;padding:24px;box-shadow:0 20px 50px rgba(0,0,0,0.2);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Progress Bar Baca
    const progressEl = document.getElementById('edProgress');
    window.addEventListener('scroll', function () {
        if (!progressEl) return;
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const pct = docHeight > 0 ? Math.min(100, Math.max(0, (scrollTop / docHeight) * 100)) : 0;
        progressEl.style.width = pct + '%';
    });

    // 2. Data Split Reader 4 Era
    const eraContent = {
        'kolonial': {
            badge: 'PERIODE I • ABAD KE-19 – 1942',
            title: 'Masa Kolonial Hindia Belanda',
            ekonomi: 'Sistem ekonomi berorientasi pada eksploitasi sumber daya alam dan ekspor komoditas perkebunan (<em>Cultuurstelsel</em> &amp; Politik Pintu Terbuka), yang seluruh keuntungannya mengalir ke pemerintah Belanda dan pengusaha Eropa.',
            bank: 'Didirikannya <em>De Javasche Bank</em> (1828) sebagai bank sirkulasi pencetak uang gulden serta bank-bank perkreditan rakyat yang belum sepenuhnya berpihak pada masyarakat pribumi.'
        },
        'awal-merdeka': {
            badge: 'PERIODE II • 1945 – 1949',
            title: 'Masa Awal Kemerdekaan &amp; Revolusi',
            ekonomi: 'Indonesia menghadapi blokade ekonomi Belanda, hiperinflasi akibat beredarnya mata uang Jepang dan NICA, serta kebutuhan mendesak membiayai perjuangan mempertahankan kedaulatan.',
            bank: 'Pemerintah mendirikan <strong>Bank Negara Indonesia (BNI 46)</strong> pada 5 Juli 1946 dan menerbitkan <strong>Oeang Republik Indonesia (ORI)</strong> sebagai simbol kedaulatan ekonomi bangsa.'
        },
        'kedaulatan': {
            badge: 'PERIODE III • 1950 – 1980-AN',
            title: 'Pasca Pengakuan Kedaulatan &amp; Deregulasi',
            ekonomi: 'Fokus pembangunan diarahkan pada penataan ulang struktur ekonomi nasional, pembiayaan sektor industri dalam negeri, serta penghimpunan dana masyarakat secara luas.',
            bank: 'Nasionalisasi <em>De Javasche Bank</em> menjadi <strong>Bank Indonesia (1953)</strong> sebagai bank sentral, disusul pengesahan UU Pokok Perbankan (1968) dan deregulasi <strong>Pakto 88</strong>.'
        },
        'modern': {
            badge: 'PERIODE IV • 2000-AN – SEKARANG',
            title: 'Era Modern, Pengawasan OJK &amp; Transaksi Digital',
            ekonomi: 'Ekosistem ekonomi terhubung secara digital dengan pertumbuhan UMKM, perdagangan daring, serta tuntutan transparansi biaya dan perlindungan konsumen.',
            bank: 'Penguatan pengawasan oleh <strong>OJK</strong> dan <strong>Bank Indonesia</strong>, penerapan standar pembayaran nasional <strong>QRIS</strong>, transfer real-time, hingga layanan pembiayaan digital yang diawasi ketat.'
        }
    };

    const eraSheet = document.getElementById('edEraSheet');
    const eraButtons = document.querySelectorAll('.ed-era-btn');

    eraButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const key = btn.getAttribute('data-era');
            const data = eraContent[key];
            if (!data || !eraSheet) return;

            eraButtons.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');

            eraSheet.innerHTML =
                '<div class="ed-sheet-anim">' +
                    '<span class="ed-sheet-badge">' + data.badge + '</span>' +
                    '<h3>' + data.title + '</h3>' +
                    '<div class="ed-sheet-cols">' +
                        '<div class="ed-sheet-col">' +
                            '<h4>Kondisi &amp; Sistem Ekonomi</h4>' +
                            '<p>' + data.ekonomi + '</p>' +
                        '</div>' +
                        '<div class="ed-sheet-col">' +
                            '<h4>Perkembangan Lembaga Perbankan</h4>' +
                            '<p>' + data.bank + '</p>' +
                        '</div>' +
                    '</div>' +
                '</div>';
        });
    });

    // 3. Data Kronologi 6 Tonggak Sejarah (Ledger Accordion)
    const milestones = [
        {
            year: '24 Jan 1828',
            title: 'Pendirian De Javasche Bank di Batavia',
            desc: 'Didirikan sebagai bank sirkulasi pertama di Hindia Belanda dengan hak tunggal (octrooi) mencetak dan mengedarkan uang gulden untuk kepentingan perdagangan ekspor kolonial.',
            tokoh: 'Pemerintah Kolonial Hindia Belanda',
            dampak: 'Memusatkan kendali moneter dan peredaran uang di tangan otoritas kolonial.'
        },
        {
            year: '16 Des 1895',
            title: 'Berdirinya Cikal Bakal Bank Rakyat Indonesia (BRI)',
            desc: 'Raden Bei Aria Wirjaatmadja mendirikan <em>De Poerwokertosche Hulp en Spaarbank der Inlandsche Hoofden</em> di Purwokerto untuk membantu pegawai pribumi dan rakyat kecil lepas dari jerat rentenir.',
            tokoh: 'Raden Bei Aria Wirjaatmadja',
            dampak: 'Menjadi pelopor lembaga simpan pinjam berbunga ringan yang berpihak pada rakyat kecil.'
        },
        {
            year: '5 Jul 1946',
            title: 'Lahirnya Bank Negara Indonesia (BNI 46)',
            desc: 'Didirikan oleh pemerintah Republik Indonesia tak lama setelah Proklamasi sebagai bank sirkulasi dan bank milik negara pertama untuk menopang ekonomi perjuangan dan mengedarkan ORI.',
            tokoh: 'R. M. Margono Djojohadikusumo',
            dampak: 'Memperkuat kedaulatan keuangan negara dan memutus ketergantungan pada mata uang kolonial.'
        },
        {
            year: '1 Jul 1953',
            title: 'Nasionalisasi De Javasche Bank menjadi Bank Indonesia',
            desc: 'Melalui UU No. 11 Tahun 1953, pemerintah RI resmi menasionalisasi De Javasche Bank menjadi Bank Indonesia sebagai bank sentral milik bangsa sendiri.',
            tokoh: 'Mr. Sjafruddin Prawiranegara (Gubernur BI Pertama)',
            dampak: 'Indonesia memiliki kendali penuh atas kebijakan moneter, cadangan devisa, dan stabilitas rupiah.'
        },
        {
            year: '27 Okt 1988',
            title: 'Paket Kebijakan Oktober 1988 (Pakto 88)',
            desc: 'Pemerintah meluncurkan deregulasi sektor perbankan yang mempermudah pendirian bank swasta nasional dan pembukaan jaringan kantor cabang di seluruh daerah.',
            tokoh: 'Otoritas Moneter &amp; Pemerintah RI',
            dampak: 'Memperluas akses layanan perbankan masyarakat dan meningkatkan mobilisasi dana pembangunan.'
        },
        {
            year: '2011 – Kini',
            title: 'Pengawasan OJK, Standar QRIS &amp; Ekosistem Digital',
            desc: 'Pembentukan Otoritas Jasa Keuangan (OJK) melalui UU No. 21 Tahun 2011 serta peluncuran standar pembayaran digital nasional QRIS oleh Bank Indonesia.',
            tokoh: 'Bank Indonesia &amp; Otoritas Jasa Keuangan (OJK)',
            dampak: 'Transaksi nontunai menjadi lebih cepat, terstandar, transparan, dan melindungi konsumen.'
        }
    ];

    const ledgerEl = document.getElementById('edLedger');
    if (ledgerEl) {
        ledgerEl.innerHTML = milestones.map(function (item, idx) {
            return (
                '<div class="ed-ledger-row' + (idx === 0 ? ' open' : '') + '">' +
                    '<button type="button" class="ed-ledger-trigger">' +
                        '<span class="ed-ledger-year">' + item.year + '</span>' +
                        '<span class="ed-ledger-title">' + item.title + '</span>' +
                        '<span class="ed-ledger-toggle"><i class="fas fa-plus"></i></span>' +
                    '</button>' +
                    '<div class="ed-ledger-panel">' +
                        '<div class="ed-ledger-inner">' +
                            '<p>' + item.desc + '</p>' +
                            '<div class="ed-ledger-meta">' +
                                '<p><strong>Tokoh / Lembaga:</strong> ' + item.tokoh + '</p>' +
                                '<p style="margin-top:6px;"><strong>Dampak Utama:</strong> ' + item.dampak + '</p>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
        }).join('');

        ledgerEl.querySelectorAll('.ed-ledger-trigger').forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                const row = trigger.closest('.ed-ledger-row');
                if (row) row.classList.toggle('open');
            });
        });
    }

    // 4. Modal Baca & Tambah Artikel Baru
    const readModal = document.getElementById('readPostModal');
    const readerTitle = document.getElementById('readerTitle');
    const readerMeta = document.getElementById('readerMeta');
    const readerContent = document.getElementById('readerContent');

    document.querySelectorAll('.btn-read-post').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const card = btn.closest('.public-post-card');
            if (!card || !readModal) return;

            readerTitle.textContent = card.getAttribute('data-title') || '';
            readerMeta.textContent =
                (card.getAttribute('data-post-category') || '') +
                ' • Oleh ' + (card.getAttribute('data-author') || 'Tim Kusantara') +
                ' • ' + (card.getAttribute('data-date') || '');
            readerContent.textContent = card.getAttribute('data-content') || '';

            readModal.style.display = 'flex';
        });
    });

    function closeReader() {
        if (readModal) readModal.style.display = 'none';
    }

    document.getElementById('closeReadPostModal')?.addEventListener('click', closeReader);
    document.getElementById('closeReadPostBtn')?.addEventListener('click', closeReader);
    readModal?.addEventListener('click', function (e) {
        if (e.target === readModal) closeReader();
    });

    const addModal = document.getElementById('addPublicPostModal');
    function openAddModal() {
        if (addModal) addModal.style.display = 'flex';
    }
    function closeAddModal() {
        if (addModal) addModal.style.display = 'none';
    }

    document.getElementById('openPublicAddPostModal')?.addEventListener('click', openAddModal);
    document.getElementById('closeAddPublicPostModal')?.addEventListener('click', closeAddModal);
    document.getElementById('cancelAddPublicPostBtn')?.addEventListener('click', closeAddModal);
    addModal?.addEventListener('click', function (e) {
        if (e.target === addModal) closeAddModal();
    });
});
</script>
@endpush