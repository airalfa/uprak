/* =========================================================
   EDITORIAL INTERACTION & SCROLL FIX - PT. KUSANTARA
   File: public/js/blog.js
   ========================================================= */

// 1. PAKSA HALAMAN SELALU MULAI DARI PALING ATAS SAAT DI-RELOAD
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}
window.scrollTo(0, 0);

document.addEventListener('DOMContentLoaded', () => {
    window.scrollTo(0, 0);

    // 2. READING PROGRESS BAR
    const progressEl = document.getElementById('edProgress');
    window.addEventListener('scroll', () => {
        if (!progressEl) return;
        const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
        const pct = maxScroll > 0 ? (window.scrollY / maxScroll) * 100 : 0;
        progressEl.style.width = `${Math.min(100, Math.max(0, pct))}%`;
    });

    // 3. SMOOTH SCROLL UNTUK DAFTAR ISI (TOC) TANPA MERUSAK RELOAD
    document.querySelectorAll('.ed-toc-link').forEach(link => {
        link.addEventListener('click', (e) => {
            const targetId = link.getAttribute('href');
            const targetEl = document.querySelector(targetId);
            if (targetEl) {
                e.preventDefault();
                const offsetTop = targetEl.getBoundingClientRect().top + window.scrollY - 72;
                window.scrollTo({ top: offsetTop, behavior: 'smooth' });
            }
        });
    });

    // 4. DATA & SPLIT READER 4 ERA EKONOMI
    const eraContent = {
        'kolonial': {
            period: 'PERIODE I • ABAD KE-19 – 1942',
            title: 'Masa Kolonial Hindia Belanda',
            economy: 'Sistem ekonomi berorientasi pada eksploitasi sumber daya alam dan ekspor komoditas perkebunan (Cultuurstelsel & Politik Pintu Terbuka) yang seluruh keuntungannya mengalir ke pemerintah Belanda dan pengusaha Eropa.',
            banking: 'Didirikannya De Javasche Bank (1828) sebagai bank sirkulasi pencetak uang gulden serta bank-bank perkebunan Belanda. Rakyat pribumi sulit mengakses bank formal sehingga muncul perintis bank rakyat De Poerwokertosche Hulp en Spaarbank (1895).',
            source: 'Arsip Museum Bank Indonesia & Buku Sejarah Indonesia Kelas XI'
        },
        'awal-merdeka': {
            period: 'PERIODE II • 1945 – 1949',
            title: 'Masa Awal Kemerdekaan & Revolusi',
            economy: 'Indonesia menghadapi hiperinflasi akibat beredarnya tiga mata uang sekaligus (De Javasche Bank, Jepang, dan NICA), blokade ekonomi Belanda, serta kekosongan kas negara di awal kemerdekaan.',
            banking: 'Pemerintah mendirikan Bank Negara Indonesia (BNI) pada 5 Juli 1946 dan menerbitkan Oeang Republik Indonesia (ORI) pada 30 Oktober 1946 sebagai simbol kedaulatan ekonomi dan alat pembiayaan perjuangan.',
            source: 'Publikasi Korporat BNI & Buku Sejarah Indonesia Kelas XI'
        },
        'kedaulatan': {
            period: 'PERIODE III • 1950 – 1980-AN',
            title: 'Masa Pasca Pengakuan Kedaulatan',
            economy: 'Fokus pembangunan diarahkan pada pemulihan ekonomi nasional, penataan struktur moneter, dan penguatan sektor usaha dalam negeri.',
            banking: 'Nasionalisasi De Javasche Bank menjadi Bank Indonesia (1 Juli 1953) sebagai bank sentral RI, pengesahan UU Pokok Perbankan 1968, serta paket deregulasi Pakto 88 yang memperluas layanan perbankan.',
            source: 'UU No. 11 Tahun 1953 & Arsip Resmi Bank Indonesia (bi.go.id)'
        },
        'modern': {
            period: 'PERIODE IV • 2000-AN – SEKARANG',
            title: 'Era Modern, Regulasi OJK & Digitalisasi',
            economy: 'Transformasi menuju ekonomi digital, penguatan inklusi keuangan UMKM, serta perlindungan konsumen dalam transaksi keuangan modern.',
            banking: 'Pembentukan Otoritas Jasa Keuangan (OJK) tahun 2011, penerapan Gerbang Pembayaran Nasional (GPN), standar QRIS oleh Bank Indonesia, serta layanan cicilan digital (PayLater) yang diawasi regulasi.',
            source: 'Otoritas Jasa Keuangan (ojk.go.id) & Bank Indonesia (bi.go.id)'
        }
    };

    const eraSheet = document.getElementById('edEraSheet');
    const eraBtns = document.querySelectorAll('.ed-era-btn');

    function renderEraSheet(key) {
        const data = eraContent[key];
        if (!data || !eraSheet) return;

        eraSheet.classList.remove('ed-sheet-anim');
        void eraSheet.offsetWidth;
        eraSheet.classList.add('ed-sheet-anim');

        eraSheet.innerHTML = `
            <span style="font-size:0.75rem; font-weight:700; letter-spacing:0.1em; color:#9A7428;">${data.period}</span>
            <h3 style="font-family:'Playfair Display', serif; font-size:1.65rem; color:#132238; margin:6px 0 10px;">${data.title}</h3>
            <p style="font-size:0.82rem; color:#5A6573;">Sumber rujukan: <em>${data.source}</em></p>
            <div class="ed-sheet-cols">
                <div class="ed-sheet-col">
                    <h4>Kondisi &amp; Sistem Ekonomi</h4>
                    <p style="font-size:0.94rem; color:#1E242B;">${data.economy}</p>
                </div>
                <div class="ed-sheet-col">
                    <h4>Perkembangan Lembaga Perbankan</h4>
                    <p style="font-size:0.94rem; color:#1E242B;">${data.banking}</p>
                </div>
            </div>
        `;
    }

    eraBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            eraBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderEraSheet(btn.dataset.era);
        });
    });

    renderEraSheet('kolonial');

    // 5. LEDGER ACCORDION TIMELINE (6 PERISTIWA KUNCI)
    const milestones = [
        {
            year: '24 Jan 1828',
            title: 'Pendirian De Javasche Bank di Batavia',
            desc: 'Didirikan sebagai bank sirkulasi pertama di Hindia Belanda dengan hak tunggal (oktroi) mencetak dan mengedarkan uang gulden untuk kepentingan perdagangan kolonial.',
            actor: 'Pemerintah Kolonial Hindia Belanda (Raja Willem I)',
            impact: 'Menjadi pusat keuangan kolonial, namun manfaat ekonominya dinikmati perusahaan Eropa, bukan rakyat pribumi.'
        },
        {
            year: '5 Jul 1946',
            title: 'Lahirnya Bank Negara Indonesia (BNI 46)',
            desc: 'Pemerintah Republik Indonesia mendirikan BNI sebagai bank sirkulasi dan bank sentral pertama milik bangsa Indonesia yang merdeka.',
            actor: 'R. M. Margono Djojohadikusumo & Wakil Presiden Moh. Hatta',
            impact: 'Memungkinkan penerbitan Oeang Republik Indonesia (ORI) dan memutus ketergantungan pada uang penjajah.'
        },
        {
            year: '1 Jul 1953',
            title: 'Nasionalisasi De Javasche Bank menjadi Bank Indonesia',
            desc: 'Melalui UU No. 24 Tahun 1951 dan UU No. 11 Tahun 1953, saham DJB diambil alih pemerintah RI dan resmi beroperasi sebagai Bank Indonesia.',
            actor: 'Mr. Sjafruddin Prawiranegara (Gubernur Pertama BI)',
            impact: 'Indonesia meraih kedaulatan penuh dalam mengatur kebijakan moneter, suku bunga, dan cadangan devisa.'
        },
        {
            year: '1968',
            title: 'Pengesahan UU Pokok Perbankan',
            desc: 'Menata ulang fungsi perbankan nasional dengan memisahkan tugas Bank Indonesia sebagai bank sentral dan bank-bank umum milik negara (BNI, BRI, BBD, BDN, BTN).',
            actor: 'Pemerintah RI & Bank Indonesia',
            impact: 'Menciptakan spesialisasi pembiayaan pembangunan di sektor pertanian, industri, ekspor, dan perumahan.'
        },
        {
            year: '27 Okt 1988',
            title: 'Paket Kebijakan Oktober 1988 (Pakto 88)',
            desc: 'Deregulasi perbankan yang mempermudah pendirian bank swasta baru dan pembukaan cabang bank di seluruh wilayah Indonesia.',
            actor: 'Menteri Keuangan J.B. Sumarlin & Bank Indonesia',
            impact: 'Meningkatkan budaya menabung, persaingan layanan bank modern, serta memperluas akses kredit bagi UMKM.'
        },
        {
            year: '2011 – Kini',
            title: 'Pengawasan OJK, Standar QRIS & Keuangan Digital',
            desc: 'Pengalihan fungsi pengawasan perbankan ke Otoritas Jasa Keuangan (OJK) serta penerapan standar pembayaran QRIS nasional oleh Bank Indonesia.',
            actor: 'Bank Indonesia (BI) & Otoritas Jasa Keuangan (OJK)',
            impact: 'Menjamin transaksi e-commerce modern seperti di PT. Kusantara berjalan cepat, transparan, dan terlindungi.'
        }
    ];

    const ledgerEl = document.getElementById('edLedger');
    if (ledgerEl) {
        ledgerEl.innerHTML = milestones.map((item, i) => `
            <div class="ed-ledger-row ${i === 0 ? 'open' : ''}">
                <button type="button" class="ed-ledger-trigger" aria-expanded="${i === 0 ? 'true' : 'false'}">
                    <span class="ed-ledger-year">${item.year}</span>
                    <span class="ed-ledger-title">${item.title}</span>
                    <span class="ed-ledger-toggle"><i class="fas fa-plus"></i></span>
                </button>
                <div class="ed-ledger-panel">
                    <div class="ed-ledger-inner">
                        <p style="font-size:0.94rem; color:#1E242B;">${item.desc}</p>
                        <div class="ed-ledger-meta">
                            <p style="margin-bottom:6px;"><strong>Tokoh / Lembaga:</strong> ${item.actor}</p>
                            <p><strong>Dampak Ekonomi:</strong> ${item.impact}</p>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        ledgerEl.querySelectorAll('.ed-ledger-trigger').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const row = trigger.parentElement;
                const isOpen = row.classList.contains('open');

                ledgerEl.querySelectorAll('.ed-ledger-row').forEach(r => {
                    r.classList.remove('open');
                    r.querySelector('.ed-ledger-trigger').setAttribute('aria-expanded', 'false');
                });

                if (!isOpen) {
                    row.classList.add('open');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            });
        });
    }

    // 6. SCROLL REVEAL HALUS
    const reveals = document.querySelectorAll('.ed-reveal');
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    reveals.forEach(el => io.observe(el));
});