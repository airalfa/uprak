@extends('layouts.admin')

@section('title', 'Dashboard Admin - PT. Kusantara')

@section('content')
<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Selamat Datang, Admin!</h1>
        <p class="admin-page-sub">Berikut ringkasan aktivitas operasional PT. Kusantara hari ini.</p>
    </div>
    <div class="admin-date-pill">
        <i class="fa-regular fa-calendar"></i> {{ now()->translatedFormat('D, d M Y') }}
    </div>
</div>

<!-- 4 KARTU METRIK -->
<div class="admin-kpi-grid">
    <div class="admin-kpi-card">
        <div class="admin-kpi-top">
            <span class="admin-kpi-label">Total Pesanan</span>
            <span class="admin-kpi-icon"><i class="fa-solid fa-bag-shopping"></i></span>
        </div>
        <div class="admin-kpi-val">128</div>
        <div class="admin-kpi-trend">&uarr; 12% dari kemarin</div>
    </div>

    <div class="admin-kpi-card">
        <div class="admin-kpi-top">
            <span class="admin-kpi-label">Pendapatan</span>
            <span class="admin-kpi-icon"><i class="fa-solid fa-coins"></i></span>
        </div>
        <div class="admin-kpi-val">Rp8,5 jt</div>
        <div class="admin-kpi-trend">&uarr; 15% dari kemarin</div>
    </div>

    <div class="admin-kpi-card">
        <div class="admin-kpi-top">
            <span class="admin-kpi-label">Pelanggan</span>
            <span class="admin-kpi-icon"><i class="fa-solid fa-user-group"></i></span>
        </div>
        <div class="admin-kpi-val">864</div>
        <div class="admin-kpi-trend">&uarr; 8% bulan ini</div>
    </div>

    <div class="admin-kpi-card">
        <div class="admin-kpi-top">
            <span class="admin-kpi-label">Produk Aktif</span>
            <span class="admin-kpi-icon"><i class="fa-solid fa-bowl-rice"></i></span>
        </div>
        <div class="admin-kpi-val">42</div>
        <div class="admin-kpi-trend">&uarr; 2% menu baru</div>
    </div>
</div>

<!-- GRAFIK PENJUALAN & PESANAN TERBARU -->
<div class="admin-dash-grid">
    <div class="admin-panel">
        <div class="admin-panel-head">
            <h2 class="admin-panel-title">Grafik Penjualan</h2>
            <div class="admin-period-btns" id="salesPeriodSwitch">
                <button type="button" class="admin-period-btn is-active" data-period="harian">Harian</button>
                <button type="button" class="admin-period-btn" data-period="mingguan">Mingguan</button>
                <button type="button" class="admin-period-btn" data-period="bulanan">Bulanan</button>
            </div>
        </div>

        <div id="adminSalesChart" class="admin-bar-chart" aria-label="Grafik Penjualan"></div>
    </div>

    <div class="admin-panel">
        <div class="admin-panel-head">
            <h2 class="admin-panel-title">Pesanan Terbaru</h2>
            <a href="{{ url('/admin/pesanan') }}" style="font-size:0.82rem;font-weight:700;color:var(--navy-dark);text-decoration:none;">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="admin-recent-list">
            <div class="admin-recent-row">
                <div>
                    <div class="admin-recent-code">#KS001</div>
                    <div class="admin-recent-menu">Rendang Sapi &bull; Dine-in</div>
                </div>
                <span class="admin-badge-status is-process">Diproses</span>
            </div>

            <div class="admin-recent-row">
                <div>
                    <div class="admin-recent-code">#KS002</div>
                    <div class="admin-recent-menu">Paket Catering &bull; Catering</div>
                </div>
                <span class="admin-badge-status is-pending">Menunggu</span>
            </div>

            <div class="admin-recent-row">
                <div>
                    <div class="admin-recent-code">#KS003</div>
                    <div class="admin-recent-menu">Nasi Liwet &bull; Delivery</div>
                </div>
                <span class="admin-badge-status is-done">Selesai</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const datasets = {
        harian: [
            { label: 'Sen', pct: 55 },
            { label: 'Sel', pct: 45 },
            { label: 'Rab', pct: 78 },
            { label: 'Kam', pct: 58 },
            { label: 'Jum', pct: 80 },
            { label: 'Sab', pct: 56 },
            { label: 'Min', pct: 76 }
        ],
        mingguan: [
            { label: 'Mgg 1', pct: 62 },
            { label: 'Mgg 2', pct: 74 },
            { label: 'Mgg 3', pct: 68 },
            { label: 'Mgg 4', pct: 90 }
        ],
        bulanan: [
            { label: 'Jul', pct: 52 },
            { label: 'Agu', pct: 64 },
            { label: 'Sep', pct: 70 },
            { label: 'Okt', pct: 82 },
            { label: 'Nov', pct: 92 }
        ]
    };

    const chartEl = document.getElementById('adminSalesChart');
    const switchEl = document.getElementById('salesPeriodSwitch');

    function renderBars(periodKey) {
        if (!chartEl) return;
        const rows = datasets[periodKey] || datasets.harian;
        chartEl.innerHTML = rows.map(function (r) {
            return (
                '<div class="admin-bar-col">' +
                    '<div class="admin-bar-fill" style="height:' + r.pct + '%;"></div>' +
                    '<div class="admin-bar-label">' + r.label + '</div>' +
                '</div>'
            );
        }).join('');
    }

    if (switchEl) {
        switchEl.addEventListener('click', function (e) {
            const btn = e.target.closest('.admin-period-btn');
            if (!btn) return;
            switchEl.querySelectorAll('.admin-period-btn').forEach(function (b) {
                b.classList.remove('is-active');
            });
            btn.classList.add('is-active');
            renderBars(btn.getAttribute('data-period'));
        });
    }

    renderBars('harian');
});
</script>
@endpush