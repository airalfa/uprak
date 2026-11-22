@extends('layouts.app')

@section('title', 'Bukti Beriman & Isu Suku Bunga - PT. Kusantara')

@section('content')
    <div class="container">
        <div class="topik-filter-bar" role="tablist" aria-label="Filter Topik">
            <button type="button" class="topik-filter-pill is-active" data-topik="all">Semua Bagian</button>
            <button type="button" class="topik-filter-pill" data-topik="beriman">Bukti Beriman</button>
            <button type="button" class="topik-filter-pill" data-topik="suku-bunga">Isu Suku Bunga</button>
        </div>
    </div>

    <!-- =====================================================================
         BAGIAN 1: BUKTI BERIMAN
         ===================================================================== -->
    <section class="section-beriman" id="bukti-beriman" data-topik-section="beriman">
        <div class="hero-beriman">
            <div class="container">
                <div class="hero-grid">
                    <div class="hero-text">
                        <h1>Bukti Beriman</h1>
                        <p class="subtitle">Refleksi Nilai, Amanah, dan Sikap Nyata dalam Kehidupan</p>
                    </div>
                    <div class="hero-image">
                        <img src="{{ asset('images/bukti-iman.jpeg') }}" alt="Ilustrasi Bukti Beriman">
                    </div>
                </div>
            </div>
        </div>

        <div class="container main-card-wrapper">
            <div class="card-beriman">
                <div class="dalil-col">
                    <h2>Landasan Nilai</h2>

                    <div class="dalil-item">
                        <div class="dalil-icon">✦</div>
                        <div class="dalil-content">
                            <h3>Kejujuran & Amanah</h3>
                            <p>"Menjaga kepercayaan dan berkata benar adalah fondasi utama integritas dalam berinteraksi."
                            </p>
                            <span>Nilai Dasar</span>
                        </div>
                    </div>

                    <hr class="divider">

                    <div class="dalil-item">
                        <div class="dalil-icon">❖</div>
                        <div class="dalil-content">
                            <h3>Keadilan & Kemanfaatan</h3>
                            <p>"Kebaikan yang berdampak nyata tampak dari sikap adil dan tidak merugikan sesama."</p>
                            <span>Etika Sosial</span>
                        </div>
                    </div>
                </div>

                <div class="penerapan-col">
                    <h2>Penerapan Sehari-hari</h2>

                    <ul class="checklist">
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Menepati janji, bekerja jujur, dan bertanggung jawab atas tugas yang diemban.</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Menghindari praktik yang merugikan atau membebani orang lain secara tidak adil.</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Menghadirkan manfaat sosial serta menjaga keseimbangan antara prinsip dan tindakan.</span>
                        </li>
                    </ul>

                    <div class="mosque-watermark" aria-hidden="true">☪</div>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================================
         BAGIAN 2: ISU SUKU BUNGA (ENGLISH TEXT DISCUSSION)
         ===================================================================== -->
    <section class="section-suku-bunga" id="isu-suku-bunga" data-topik-section="suku-bunga">
        <div class="container">
            <div class="section-header-flex">
                <div>
                    <h1>Interest Rate Issue</h1>
                    <p class="subtitle">Critical Text Discussion in Social Studies</p>
                </div>
                <div class="header-controls">
                    <span class="pill-badge">Discussion</span>
                    <button type="button" class="dots-btn" aria-label="Toggle discussion arguments"
                        id="btnToggleDetail">•••</button>
                </div>
            </div>

            <div class="suku-bunga-grid">
                <div class="suku-bunga-image">
                    <svg class="suku-bunga-svg" viewBox="0 0 360 340" fill="none" xmlns="http://www.w3.org/2000/svg"
                        role="img" aria-label="Illustration of interest rates, coins, and economic growth">
                        <rect width="360" height="340" fill="#f1efec" />
                        <!-- Desk / Table -->
                        <rect x="24" y="278" width="312" height="24" rx="8" fill="#c8a87e" />
                        <!-- Coin Stack -->
                        <ellipse cx="122" cy="270" rx="34" ry="9" fill="#94a3b8" />
                        <rect x="88" y="252" width="68" height="18" fill="#cbd5e1" />
                        <ellipse cx="122" cy="252" rx="34" ry="9" fill="#e2e8f0" />
                        <rect x="88" y="234" width="68" height="18" fill="#cbd5e1" />
                        <ellipse cx="122" cy="234" rx="34" ry="9" fill="#e2e8f0" />
                        <rect x="88" y="216" width="68" height="18" fill="#cbd5e1" />
                        <ellipse cx="122" cy="216" rx="34" ry="9" fill="#f8fafc" />
                        <!-- Sprout -->
                        <path d="M122 216 C122 172, 122 132, 122 92" stroke="#15803d" stroke-width="5"
                            stroke-linecap="round" />
                        <path d="M122 154 C88 148, 76 122, 86 104 C108 108, 121 128, 122 154 Z" fill="#22c55e" />
                        <path d="M122 126 C156 120, 168 94, 158 76 C136 80, 123 100, 122 126 Z" fill="#16a34a" />
                        <!-- Interest Rate Card -->
                        <rect x="184" y="106" width="112" height="150" rx="20" fill="#d4b48c" stroke="#b89265"
                            stroke-width="3" />
                        <circle cx="218" cy="154" r="11" stroke="#0f2137" stroke-width="6" />
                        <circle cx="262" cy="208" r="11" stroke="#0f2137" stroke-width="6" />
                        <line x1="268" y1="144" x2="212" y2="218" stroke="#0f2137" stroke-width="6"
                            stroke-linecap="round" />
                    </svg>
                    <div class="percent-tag">%</div>
                </div>

                <div>
                    <div class="discussion-card">
                        <span class="discussion-tag">Text Discussion Prompt</span>
                        <h3>How Do Interest Rates Shape Everyday Social &amp; Economic Life?</h3>
                        <p>
                            When central banks adjust interest rates, the decision ripples far beyond banking charts. Higher
                            borrowing costs can cool down inflation and encourage saving, yet they also raise loan expenses
                            for families and small businesses. In social studies, examining interest rates helps us ask who
                            benefits from financial stability and who bears the heavier burden when credit becomes more
                            expensive.
                        </p>
                    </div>

                    <div class="arguments-grid" id="argumentsGrid">
                        <div class="arg-card arg-for">
                            <h4>Supporting Arguments <span>(Price &amp; Monetary Stability)</span></h4>
                            <ul>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    <span>Helps curb inflation and protects purchasing power from rapid price spikes.</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    <span>Encourages prudent saving habits and more disciplined capital allocation.</span>
                                </li>
                            </ul>
                        </div>

                        <div class="arg-card arg-against">
                            <h4>Critical Counterpoints <span>(Social &amp; Household Impact)</span></h4>
                            <ul>
                                <li>
                                    <i class="fa-solid fa-xmark"></i>
                                    <span>Increases monthly installments and financing costs for households and
                                        MSMEs.</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-xmark"></i>
                                    <span>May widen economic inequality if smaller borrowers face tighter access to
                                        capital.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="conclusion-banner">
                <div class="bulb-icon">💡</div>
                <div class="conclusion-text">
                    <h3>Discussion Takeaway</h3>
                    <p>
                        A balanced social analysis of interest rates weighs macroeconomic stability alongside fairness,
                        livelihood resilience, and everyday impact on local communities.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const pills = document.querySelectorAll('.topik-filter-pill');
            const sections = document.querySelectorAll('[data-topik-section]');
            const btnToggleDetail = document.getElementById('btnToggleDetail');
            const argumentsGrid = document.getElementById('argumentsGrid');

            pills.forEach((pill) => {
                pill.addEventListener('click', () => {
                    const selected = pill.getAttribute('data-topik');

                    pills.forEach((p) => p.classList.remove('is-active'));
                    pill.classList.add('is-active');

                    sections.forEach((sec) => {
                        const secTopik = sec.getAttribute('data-topik-section');
                        if (selected === 'all' || selected === secTopik) {
                            sec.classList.remove('topik-hidden');
                        } else {
                            sec.classList.add('topik-hidden');
                        }
                    });
                });
            });

            if (btnToggleDetail && argumentsGrid) {
                btnToggleDetail.addEventListener('click', () => {
                    argumentsGrid.classList.toggle('topik-hidden');
                });
            }
        })();
    </script>
@endsection