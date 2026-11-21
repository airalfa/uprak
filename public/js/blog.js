document.addEventListener('DOMContentLoaded', () => {
    // DATA 1: PERBANDINGAN PERIODE (TAHAP 1 LKPD)
    const eraData = {
        'kolonial': {
            title: 'Masa Kolonial Hindia Belanda (Abad ke-19 – 1942)',
            summary: 'Sistem ekonomi kolonial berorientasi pada eksploitasi sumber daya alam dan ekspor komoditas perkebunan (Cultuurstelsel & Politik Pintu Terbuka) untuk kepentingan pemerintah Belanda dan pengusaha Eropa.',
            bank: 'Didirikannya De Javasche Bank (1828) sebagai bank sirkulasi uang gulden serta bank-bank perkebunan Belanda. Akses perbankan modern masih sangat terbatas bagi rakyat pribumi, sehingga muncul perintis bank rakyat seperti De Poerwokertosche Hulp en Spaarbank (1895).',
            source: 'Arsip Museum Bank Indonesia & Buku Sejarah Indonesia Kelas XI'
        },
        'awal-merdeka': {
            title: 'Masa Awal Kemerdekaan (1945 – 1949)',
            summary: 'Kondisi ekonomi sangat berat akibat inflasi tinggi (beredarnya tiga mata uang sekaligus: De Javasche Bank, Jepang, dan NICA), blokade ekonomi Belanda, serta kas negara yang kosong.',
            bank: 'Pemerintah RI mendirikan Bank Negara Indonesia (BNI) pada 5 Juli 1946 dan menerbitkan Oeang Republik Indonesia (ORI) pada 30 Oktober 1946 sebagai simbol kedaulatan ekonomi dan alat pembiayaan perjuangan mempertahankan kemerdekaan.',
            source: 'Publikasi Korporat BNI & Buku Sejarah Indonesia Kelas XI'
        },
        'kedaulatan': {
            title: 'Masa Setelah Pengakuan Kedaulatan (1950 – 1980-an)',
            summary: 'Fokus pemerintah beralih pada pemulihan ekonomi nasional, penataan ulang struktur moneter, dan penguatan peran bank milik negara untuk membangun sektor riil dalam negeri.',
            bank: 'Nasionalisasi De Javasche Bank menjadi Bank Indonesia (1 Juli 1953) sebagai bank sentral RI, pengesahan UU Pokok Perbankan 1968, serta peluncuran paket deregulasi Pakto 88 yang membuka ruang pertumbuhan perbankan nasional.',
            source: 'UU No. 11 Tahun 1953 & Arsip Resmi Bank Indonesia (bi.go.id)'
        },
        'modern': {
            title: 'Perkembangan Perbankan Modern & Digital (2000-an – Kini)',
            summary: 'Ekonomi Indonesia bergerak menuju digitalisasi transaksi, penguatan inklusi keuangan UMKM, dan tata kelola perbankan yang diawasi secara ketat.',
            bank: 'Pembentukan Otoritas Jasa Keuangan (OJK) tahun 2011, penerapan Gerbang Pembayaran Nasional (GPN), standar QRIS oleh Bank Indonesia, serta hadirnya layanan bank digital dan pembiayaan cicilan (PayLater) yang diawasi regulasi.',
            source: 'Otoritas Jasa Keuangan (ojk.go.id) & Bank Indonesia (bi.go.id)'
        }
    };

    const eraDisplayCard = document.getElementById('eraDisplayCard');
    const eraTabs = document.querySelectorAll('.era-tab');

    function renderEra(key) {
        const item = eraData[key];
        if (!item || !eraDisplayCard) return;

        eraDisplayCard.innerHTML = `
            <h3 style="font-family: 'Playfair Display', serif; color: #1A2B42; font-size: 1.35rem; margin-bottom: 6px;">
                ${item.title}
            </h3>
            <p style="font-size: 0.82rem; color: #B0802E; font-weight: 600;">
                <i class="fas fa-book"></i> Sumber: ${item.source}
            </p>
            <div class="era-card-grid">
                <div class="era-subbox">
                    <h4><i class="fas fa-coins"></i> Kondisi & Sistem Ekonomi</h4>
                    <p style="font-size: 0.92rem; color: #1E242B;">${item.summary}</p>
                </div>
                <div class="era-subbox">
                    <h4><i class="fas fa-landmark"></i> Perkembangan Lembaga Perbankan</h4>
                    <p style="font-size: 0.92rem; color: #1E242B;">${item.bank}</p>
                </div>
            </div>
        `;
    }

    eraTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            eraTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            renderEra(tab.dataset.era);
        });
    });

    renderEra('kolonial');

    // DATA 2: TIMELINE KRONOLOGI (TAHAP 2 LKPD)
    const timelineEvents = [
        {
            year: '24 Januari 1828',
            title: 'Pendirian De Javasche Bank di Batavia',
            tokoh: 'Pemerintah Kolonial Hindia Belanda (Raja Willem I)',
            desc: 'Didirikan sebagai bank sirkulasi pertama di Hindia Belanda yang memiliki hak oktroi (hak tunggal) untuk mencetak dan mengedarkan uang gulden.',
            impact: 'Menjadi pusat sistem keuangan kolonial, namun keuntungannya lebih banyak dinikmati perusahaan perkebunan dan perdagangan Eropa daripada rakyat pribumi.'
        },
        {
            year: '5 Juli 1946',
            title: 'Pendirian Bank Negara Indonesia (BNI 46)',
            tokoh: 'R. M. Margono Djojohadikusumo & Wakil Presiden Moh. Hatta',
            desc: 'Pemerintah Republik Indonesia mendirikan BNI sebagai bank sirkulasi dan bank sentral pertama milik bangsa Indonesia yang merdeka.',
            impact: 'Memungkinkan lahirnya Oeang Republik Indonesia (ORI) dan memutus ketergantungan ekonomi terhadap mata uang buatan penjajah.'
        },
        {
            year: '1951 – 1 Juli 1953',
            title: 'Nasionalisasi De Javasche Bank menjadi Bank Indonesia',
            tokoh: 'Mr. Sjafruddin Prawiranegara & Kabinet Sukiman-Wilopo',
            desc: 'Pemerintah RI mengambil alih kepemilikan saham De Javasche Bank (1951) dan meresmikannya menjadi Bank Indonesia melalui UU No. 11 Tahun 1953.',
            impact: 'Indonesia memperoleh kedaulatan penuh atas kebijakan moneter, pengaturan suku bunga, dan pengelolaan cadangan devisa negara.'
        },
        {
            year: '1968',
            title: 'Pengesahan UU Pokok Perbankan (UU No. 14 Tahun 1967/1968)',
            tokoh: 'Pemerintah Orde Baru & Bank Indonesia',
            desc: 'Menata ulang fungsi perbankan nasional dengan memisahkan tugas Bank Indonesia sebagai bank sentral dan bank-bank umum milik negara (BNI, BRI, BBD, BDN, BTN).',
            impact: 'Menciptakan spesialisasi pembiayaan sektor pembangunan (pertanian, industri, ekspor-impor, dan perumahan rakyat).'
        },
        {
            year: '27 Oktober 1988',
            title: 'Paket Kebijakan Oktober 1988 (Pakto 88)',
            tokoh: 'Menteri Keuangan J.B. Sumarlin & Bank Indonesia',
            desc: 'Kebijakan deregulasi besar-besaran yang mempermudah syarat pendirian bank swasta nasional dan pembukaan kantor cabang di berbagai daerah.',
            impact: 'Mendorong persaingan layanan bank yang lebih modern, memperluas akses kredit bagi pelaku usaha/UMKM, dan meningkatkan budaya menabung masyarakat.'
        },
        {
            year: '2011 – Sekarang',
            title: 'Era Pengawasan OJK, QRIS & Digitalisasi Keuangan',
            tokoh: 'Bank Indonesia (BI) & Otoritas Jasa Keuangan (OJK)',
            desc: 'Pengalihan fungsi pengawasan perbankan ke OJK (2011) serta peluncuran standar pembayaran digital nasional QRIS oleh Bank Indonesia.',
            impact: 'Mempermudah transaksi e-commerce modern seperti pada website PT. Kusantara dengan pembayaran instan yang aman, transparan, dan terawasi.'
        }
    ];

    const timelineNodes = document.querySelectorAll('.timeline-node');
    const timelineDetailCard = document.getElementById('timelineDetailCard');

    function renderTimeline(idx) {
        const ev = timelineEvents[idx];
        if (!ev || !timelineDetailCard) return;

        timelineDetailCard.innerHTML = `
            <div class="tl-detail-top">
                <h3 style="font-family: 'Playfair Display', serif; color: #1A2B42; font-size: 1.35rem;">
                    ${ev.title}
                </h3>
                <span class="tl-year-pill">${ev.year}</span>
            </div>
            <p style="font-size: 0.96rem; color: #1E242B;">${ev.desc}</p>
            <div class="tl-meta-grid">
                <div class="tl-meta-item">
                    <strong style="color: #1A2B42; display: block; margin-bottom: 4px;">
                        <i class="fas fa-user-tie" style="color: #C8963E;"></i> Tokoh / Lembaga Terkait:
                    </strong>
                    <span>${ev.tokoh}</span>
                </div>
                <div class="tl-meta-item">
                    <strong style="color: #1A2B42; display: block; margin-bottom: 4px;">
                        <i class="fas fa-bolt" style="color: #C8963E;"></i> Arti Penting & Dampak:
                    </strong>
                    <span>${ev.impact}</span>
                </div>
            </div>
        `;
    }

    timelineNodes.forEach(node => {
        node.addEventListener('click', () => {
            timelineNodes.forEach(n => n.classList.remove('active'));
            node.classList.add('active');
            renderTimeline(Number(node.dataset.index));
        });
    });

    renderTimeline(0);
});