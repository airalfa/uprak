@extends('partials.main')

@section('title', 'Blog & Sejarah Perbankan Indonesia - PT. Kusantara')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
@endpush

@section('content')
<div class="sejarah-page" id="blog">

    <!-- HERO HEADER BLOG SEJARAH -->
    <section class="sejarah-hero">
        <div class="sejarah-container">
            <span class="sejarah-badge">
                <i class="fas fa-landmark"></i> LITERASI SEJARAH & EKONOMI NUSANTARA
            </span>
            <h1 class="sejarah-title">
                Sejarah Perbankan & Perkembangan Ekonomi Indonesia
            </h1>
            <p class="sejarah-subtitle">
                Menelusuri perjalanan sistem moneter dari era kolonial hingga kemandirian perbankan nasional, serta bagaimana <strong>PT. Kusantara</strong> mengadopsi nilai transparansi dan inklusi keuangan dalam fitur pembayaran modern.
            </p>
            <div class="sejarah-meta">
                <span><i class="fas fa-user-edit"></i> Tim Konten Sejarah — XI PPLG SMKS Mahaputra Cerdas Utama</span>
                <span>•</span>
                <span><i class="fas fa-book-open"></i> Referensi: Arsip Bank Indonesia & Buku Sejarah Indonesia XI</span>
            </div>
        </div>
    </section>

    <!-- BAGIAN 1: PERBANDINGAN ERA (TAHAP 1 LKPD) -->
    <section class="sejarah-section">
        <div class="sejarah-container">
            <div class="section-header-row">
                <div>
                    <span class="step-label">01. PERBANDINGAN PERIODE</span>
                    <h2 class="section-heading">Transformasi Ekonomi & Lembaga Perbankan</h2>
                </div>
                <!-- Pill Toggle Periode -->
                <div class="era-tabs" role="tablist">
                    <button class="era-tab active" data-era="kolonial">Masa Kolonial</button>
                    <button class="era-tab" data-era="awal-merdeka">Awal Kemerdekaan</button>
                    <button class="era-tab" data-era="kedaulatan">Pasca Kedaulatan</button>
                    <button class="era-tab" data-era="modern">Era Modern & Digital</button>
                </div>
            </div>

            <!-- Konten Dinamis Tab Periode -->
            <div class="era-card" id="eraDisplayCard">
                <!-- Diisi otomatis via sejarah.js, default tampil Masa Kolonial -->
            </div>
        </div>
    </section>

    <!-- BAGIAN 2: TIMELINE KRONOLOGI PERBANKAN (TAHAP 2 LKPD) -->
    <section class="sejarah-section bg-soft">
        <div class="sejarah-container">
            <div class="section-header-center">
                <span class="step-label">02. KRONOLOGI HISTORIS</span>
                <h2 class="section-heading">Jejak Langkah Perbankan Indonesia (1828 – Kini)</h2>
                <p class="section-desc">Klik setiap titik tahun untuk melihat peristiwa, tokoh penggerak, serta dampaknya terhadap perekonomian rakyat.</p>
            </div>

            <!-- Horizontal Milestone Selector -->
            <div class="timeline-track" id="timelineTrack">
                <button class="timeline-node active" data-index="0">
                    <span class="node-year">1828</span>
                    <span class="node-label">De Javasche Bank</span>
                </button>
                <button class="timeline-node" data-index="1">
                    <span class="node-year">1946</span>
                    <span class="node-label">Lahirnya BNI 46</span>
                </button>
                <button class="timeline-node" data-index="2">
                    <span class="node-year">1951–1953</span>
                    <span class="node-label">Bank Indonesia</span>
                </button>
                <button class="timeline-node" data-index="3">
                    <span class="node-year">1968</span>
                    <span class="node-label">UU Pokok Perbankan</span>
                </button>
                <button class="timeline-node" data-index="4">
                    <span class="node-year">1988</span>
                    <span class="node-label">Deregulasi Pakto 88</span>
                </button>
                <button class="timeline-node" data-index="5">
                    <span class="node-year">2011–Kini</span>
                    <span class="node-label">OJK & Bank Digital</span>
                </button>
            </div>

            <!-- Detail Kartu Timeline -->
            <div class="timeline-detail-card" id="timelineDetailCard">
                <!-- Diisi otomatis oleh sejarah.js -->
            </div>
        </div>
    </section>

    <!-- BAGIAN 3: BIOGRAFI TOKOH & LEMBAGA PENTING (TAHAP 3 LKPD) -->
    <section class="sejarah-section">
        <div class="sejarah-container">
            <div class="section-header-center">
                <span class="step-label">03. TOKOH & LEMBAGA PENGGERAK</span>
                <h2 class="section-heading">Arsitek Kedaulatan Moneter & Perbankan Nasional</h2>
                <p class="section-desc">Mengenal tokoh-tokoh pelopor dan lembaga kunci yang membangun fondasi perbankan Indonesia.</p>
            </div>

           <!-- Grid 3 Tokoh Perbankan -->
<div class="tokoh-grid">
    <!-- Tokoh 1 -->
    <article class="tokoh-card">
        <div class="tokoh-photo-wrap">
            <img src="{{ asset('images/tokoh-margono.jpeg') }}"
                 alt="R. M. Margono Djojohadikusumo"
                 onerror="this.src='https://ui-avatars.com/api/?name=Margono+Djojohadikusumo&background=1A2B42&color=C8963E&size=300'">
            <span class="photo-caption">Sumber Foto: Wikimedia Commons / Arsip Nasional RI</span>
        </div>
        <div class="tokoh-body">
            <span class="tokoh-role">Pendiri & Dirut Pertama BNI 1946</span>
            <h3>R. M. Margono Djojohadikusumo</h3>
            <p class="tokoh-birth"><i class="fas fa-calendar-alt"></i> Purwokerto, 16 Mei 1894 – 25 Juli 1978</p>
            <ul class="tokoh-list">
                <li><strong>Latar Belakang:</strong> Lulusan ELS dan OSVIA, berkarir di dinas perkreditan rakyat masa Hindia Belanda serta menjabat Ketua Dewan Pertimbangan Agung (DPA) pertama RI.</li>
                <li><strong>Gagasan & Kontribusi:</strong> Menggagas perlunya bank sirkulasi milik bangsa sendiri untuk menggantikan mata uang kolonial dan mendanai perjuangan kemerdekaan melalui pendirian <strong>Bank Negara Indonesia (BNI)</strong> pada 5 Juli 1946.</li>
                <li><strong>Nilai Keteladanan:</strong> Patriotisme ekonomi, integritas tinggi, dan keberanian membangun lembaga keuangan dari nol di tengah masa revolusi.</li>
            </ul>
        </div>
    </article>

    <!-- Tokoh 2 -->
    <article class="tokoh-card">
        <div class="tokoh-photo-wrap">
            <img src="{{ asset('images/tokoh-sjafruddin.jpeg') }}"
                 alt="Mr. Sjafruddin Prawiranegara"
                 onerror="this.src='https://ui-avatars.com/api/?name=Sjafruddin+Prawiranegara&background=1A2B42&color=C8963E&size=300'">
            <span class="photo-caption">Sumber Foto: Arsip Museum Bank Indonesia (bi.go.id)</span>
        </div>
        <div class="tokoh-body">
            <span class="tokoh-role">Gubernur Pertama Bank Indonesia (1953)</span>
            <h3>Mr. Sjafruddin Prawiranegara</h3>
            <p class="tokoh-birth"><i class="fas fa-calendar-alt"></i> Serang, 28 Februari 1911 – 15 Februari 1989</p>
            <ul class="tokoh-list">
                <li><strong>Latar Belakang:</strong> Sarjana Hukum dari <em>Rechtshoogeschool te Batavia</em> (1939), mantan Menteri Keuangan RI, dan Ketua Pemerintah Darurat Republik Indonesia (PDRI).</li>
                <li><strong>Gagasan & Kontribusi:</strong> Presiden Direktur pribumi pertama <em>De Javasche Bank</em> (1951) yang memimpin proses nasionalisasi hingga resmi bertransformasi menjadi <strong>Bank Indonesia</strong> pada 1 Juli 1953.</li>
                <li><strong>Nilai Keteladanan:</strong> Kepemimpinan tegas, disiplin fiskal, dan dedikasi menjaga stabilitas nilai tukar rupiah di masa awal kedaulatan.</li>
            </ul>
        </div>
    </article>

    <!-- Tokoh 3 -->
    <article class="tokoh-card">
        <div class="tokoh-photo-wrap">
            <img src="{{ asset('images/tokoh-wirjaatmadja.jpeg') }}"
                 alt="Raden Bei Aria Wirjaatmadja"
                 onerror="this.src='https://ui-avatars.com/api/?name=Aria+Wirjaatmadja&background=1A2B42&color=C8963E&size=300'">
            <span class="photo-caption">Sumber Foto: Arsip Museum BRI Purwokerto</span>
        </div>
        <div class="tokoh-body">
            <span class="tokoh-role">Perintis Perbankan Rakyat & Cikal Bakal BRI</span>
            <h3>Raden Bei Aria Wirjaatmadja</h3>
            <p class="tokoh-birth"><i class="fas fa-calendar-alt"></i> Purwokerto, 1831 – 1902</p>
            <ul class="tokoh-list">
                <li><strong>Latar Belakang:</strong> Patih Purwokerto yang prihatin melihat pegawai pribumi dan rakyat kecil terjerat utang rentenir dengan bunga mencekik.</li>
                <li><strong>Gagasan & Kontribusi:</strong> Mendirikan <em>De Poerwokertosche Hulp en Spaarbank der Inlandsche Hoofden</em> pada 16 Desember 1895 sebagai lembaga simpan pinjam berbunga ringan yang menjadi cikal bakal <strong>Bank Rakyat Indonesia (BRI)</strong>.</li>
                <li><strong>Nilai Keteladanan:</strong> Kepedulian sosial, perlindungan konsumen dari riba/rentenir, dan pemberdayaan ekonomi rakyat kecil.</li>
            </ul>
        </div>
    </article>
</div>

            <!-- Identifikasi 3 Lembaga Penting -->
            <div class="lembaga-grid">
                <div class="lembaga-card">
                    <div class="lembaga-icon"><i class="fas fa-university"></i></div>
                    <h4>1. De Javasche Bank (1828)</h4>
                    <p>Didirikan pemerintah Hindia Belanda di Batavia sebagai bank sirkulasi pencetak uang gulden dan pembiayaan perdagangan ekspor kolonial sebelum dinasionalisasi Indonesia.</p>
                </div>
                <div class="lembaga-card">
                    <div class="lembaga-icon"><i class="fas fa-landmark"></i></div>
                    <h4>2. Bank Negara Indonesia / BNI (1946)</h4>
                    <p>Bank milik negara pertama yang didirikan pasca-Proklamasi (5 Juli 1946) untuk menerbitkan Oeang Republik Indonesia (ORI) dan menopang ekonomi perjuangan.</p>
                </div>
                <div class="lembaga-card">
                    <div class="lembaga-icon"><i class="fas fa-balance-scale"></i></div>
                    <h4>3. Bank Indonesia / BI (1953)</h4>
                    <p>Lahir dari nasionalisasi <em>De Javasche Bank</em> melalui UU No. 11 Tahun 1953 sebagai bank sentral independen penjaga stabilitas rupiah, moneter, dan sistem pembayaran nasional.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- BAGIAN 4: ANALISIS PERISTIWA & DAMPAK SEBAB-AKIBAT (TAHAP 4 LKPD) -->
    <section class="sejarah-section bg-soft">
        <div class="sejarah-container">
            <div class="section-header-center">
                <span class="step-label">04. ANALISIS SEBAB-AKIBAT</span>
                <h2 class="section-heading">Peristiwa Kunci & Dampaknya bagi Ekonomi Masyarakat</h2>
                <p class="section-desc">Memisahkan fakta sejarah berdasarkan arsip dengan analisis dampak sosial-ekonomi bagi masyarakat Indonesia.</p>
            </div>

            <div class="analisis-grid">
                <!-- Peristiwa 1 -->
                <div class="analisis-card">
                    <div class="analisis-header">
                        <span class="tag-fakta">PERISTIWA 1 (1951–1953)</span>
                        <h3>Nasionalisasi De Javasche Bank menjadi Bank Indonesia</h3>
                    </div>
                    <div class="analisis-box box-fakta">
                        <h5><i class="fas fa-check-circle"></i> Fakta Sejarah (Berdasarkan Sumber)</h5>
                        <p>Setelah Konferensi Meja Bundar (KMB), bank sentral masih dikuasai Belanda melalui <em>De Javasche Bank</em>. Pemerintah RI menerbitkan UU No. 24 Tahun 1951 untuk membeli saham DJB, lalu mengesahkan UU Pokok Bank Indonesia No. 11 Tahun 1953 yang berlaku efektif pada <strong>1 Juli 1953</strong>.</p>
                    </div>
                    <div class="analisis-box box-dampak">
                        <h5><i class="fas fa-chart-line"></i> Analisis Dampak Negara & Masyarakat</h5>
                        <p><strong>Bagi Negara:</strong> Indonesia meraih kedaulatan penuh dalam mengatur kebijakan moneter, suku bunga acuan, dan pencetakan mata uang rupiah.<br>
                        <strong>Bagi Masyarakat:</strong> Kebijakan kredit mulai diarahkan untuk membangun sektor usaha pribumi dan UMKM, tidak lagi hanya menguntungkan perusahaan asing.</p>
                    </div>
                </div>

                <!-- Peristiwa 2 -->
                <div class="analisis-card">
                    <div class="analisis-header">
                        <span class="tag-fakta">PERISTIWA 2 (27 OKTOBER 1988)</span>
                        <h3>Paket Kebijakan Oktober 1988 (Pakto 88) & Era Digital</h3>
                    </div>
                    <div class="analisis-box box-fakta">
                        <h5><i class="fas fa-check-circle"></i> Fakta Sejarah (Berdasarkan Sumber)</h5>
                        <p>Pemerintah meluncurkan paket deregulasi perbankan (Pakto 88) yang mempermudah izin pendirian bank swasta baru, pembukaan kantor cabang, serta memperluas layanan penghimpunan dana dan penyaluran kredit di seluruh daerah.</p>
                    </div>
                    <div class="analisis-box box-dampak">
                        <h5><i class="fas fa-users"></i> Analisis Dampak Negara & Masyarakat</h5>
                        <p><strong>Bagi Negara:</strong> Terjadi lonjakan mobilisasi dana masyarakat yang mendorong pertumbuhan industri dan perdagangan nasional.<br>
                        <strong>Bagi Masyarakat:</strong> Masyarakat semakin akrab dengan budaya menabung, akses kredit usaha, hingga lahirnya inovasi sistem pembayaran modern seperti EDC, QRIS, dan cicilan digital (<em>PayLater</em>).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BAGIAN 5: BAGAIMANA PT. KUSANTARA MENGADOPSI SEJARAH PERBANKAN -->
    <section class="sejarah-section">
        <div class="sejarah-container">
            <div class="adopsi-wrapper">
                <div class="adopsi-intro">
                    <span class="step-label-gold"><i class="fas fa-crown"></i> IMPLEMENTASI DI PT. KUSANTARA</span>
                    <h2>Bagaimana PT. Kusantara Mengadopsi Nilai Sejarah Perbankan?</h2>
                    <p>
                        Fitur perhitungan bunga, cicilan <strong>Kusantara PayLater</strong>, dan transaksi digital di website <strong>PT. Kusantara</strong> tidak dibuat tanpa dasar. Kami mengadopsi 4 pelajaran utama dari sejarah perbankan Indonesia ke dalam sistem e-commerce kuliner kami:
                    </p>
                </div>

                <div class="adopsi-grid">
                    <div class="adopsi-item">
                        <div class="adopsi-num">01</div>
                        <h4>Semangat Kemandirian Ekonomi Lokal</h4>
                        <p>Terinspirasi dari lahirnya BNI 1946 dan nasionalisasi Bank Indonesia demi kemandirian bangsa, PT. Kusantara memprioritaskan kerja sama rantai pasok bahan baku langsung dengan <strong>petani dan nelayan lokal Nusantara</strong> agar perputaran ekonomi dirasakan masyarakat dalam negeri.</p>
                    </div>

                    <div class="adopsi-item">
                        <div class="adopsi-num">02</div>
                        <h4>Perlindungan Konsumen dari Bunga Mencekik</h4>
                        <p>Belajar dari perjuangan Raden Bei Aria Wirjaatmadja melawan jeratan rentenir tahun 1895, fitur <strong>Kusantara PayLater</strong> dirancang dengan simulasi cicilan transparan, batas tenor terukur, serta pilihan <strong>skema akad syariah (biaya admin tetap tanpa riba)</strong> agar pelanggan tidak terjebak utang konsumtif.</p>
                    </div>

                    <div class="adopsi-item">
                        <div class="adopsi-num">03</div>
                        <h4>Kepatuhan pada Regulasi Moneter & Sistem Pembayaran BI</h4>
                        <p>Mengikuti standar sistem pembayaran modern yang diawasi Bank Indonesia dan OJK, halaman <em>Checkout</em> PT. Kusantara mendukung pembayaran sah Rupiah melalui kanal resmi seperti <strong>QRIS, Transfer Bank Nasional, dan EDC</strong> dengan pencatatan pajak resto 10% yang akuntabel.</p>
                    </div>

                    <div class="adopsi-item">
                        <div class="adopsi-num">04</div>
                        <h4>Edukasi Literasi Keuangan Terintegrasi</h4>
                        <p>Sebagaimana bank modern berperan mengedukasi nasabah, website PT. Kusantara menghubungkan langsung fitur transaksi dan simulasi bunga dengan halaman <strong>Bukti Beriman</strong>, <strong>Discussion Text Isu Suku Bunga</strong>, dan <strong>Blog Sejarah Perbankan</strong> agar pembeli cerdas secara finansial maupun etika.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BAGIAN 6: DAFTAR SUMBER & REFERENSI ARSIP (TAHAP 5 LKPD) -->
    <section class="sejarah-footer-ref">
        <div class="sejarah-container">
            <div class="ref-box">
                <h4><i class="fas fa-bookmark"></i> Daftar Sumber & Referensi Sejarah</h4>
                <ol class="ref-list">
                    <li>Bank Indonesia. (2023). <em>Sejarah Bank Indonesia: Periode Pra-Nasionalisasi hingga Bank Sentral Republik Indonesia</em>. Arsip Resmi Museum Bank Indonesia (<a href="https://www.bi.go.id" target="_blank" rel="noopener">https://www.bi.go.id</a>).</li>
                    <li>Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi. (2021). <em>Buku Panduan Guru dan Siswa Sejarah Indonesia Kelas XI SMA/SMK</em>. Jakarta: Pusat Kurikulum dan Perbukuan.</li>
                    <li>Djojohadikusumo, Margono. (1970). <em>Kenang-Kenangan dari Tiga Zaman: Satu Kisah Kekeluargaan Tertulis</em>. Jakarta: Indira.</li>
                    <li>Bank Negara Indonesia (Persero) Tbk. <em>Sejarah Berdirinya BNI 5 Juli 1946</em>. Publikasi Resmi Korporat (<a href="https://www.bni.co.id" target="_blank" rel="noopener">https://www.bni.co.id</a>).</li>
                    <li>Otoritas Jasa Keuangan (OJK). <em>Literasi Keuangan: Sejarah Lembaga Perbankan dan Perlindungan Konsumen Jasa Keuangan di Indonesia</em> (<a href="https://sikapiuangmu.ojk.go.id" target="_blank" rel="noopener">https://sikapiuangmu.ojk.go.id</a>).</li>
                </ol>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/blog.js') }}"></script>
@endpush