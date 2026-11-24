@extends('layouts.admin')

@section('title', 'Laporan Operasional - PT. Kusantara')

@section('subnav')
    <a href="{{ route('admin.laporan') }}" class="admin-subnav-tab is-active">Ringkasan Periode</a>
    <a href="{{ route('admin.keuangan') }}" class="admin-subnav-tab">Detail Keuangan</a>
    <a href="{{ route('admin.pesanan') }}" class="admin-subnav-tab">Detail Pesanan</a>
@endsection

@section('content')
@php
    $report = $report ?? [
        'start_date'   => now()->startOfMonth()->toDateString(),
        'end_date'     => now()->toDateString(),
        'orders'       => ['total' => 0, 'completed' => 0, 'active' => 0, 'cancelled' => 0, 'by_service' => [], 'rows' => []],
        'finance'      => ['income' => 0, 'expense' => 0, 'net' => 0],
        'reservations' => ['total' => 0, 'confirmed' => 0, 'active_guests' => 0],
        'customers'    => ['total' => 0],
        'promos'       => ['total' => 0, 'used' => 0],
        'low_stock'    => [],
    ];
    $startDate = $report['start_date'];
    $endDate = $report['end_date'];
    $fin = $report['finance'];
    $ord = $report['orders'];
    $res = $report['reservations'];
    $cust = $report['customers'];
    $prm = $report['promos'];
    $lowStock = $report['low_stock'];
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Laporan Operasional &amp; Rekap</h1>
        <p class="admin-page-sub">Rekapitulasi performa pesanan, arus kas, reservasi, pelanggan, dan stok untuk periode {{ $startDate }} s/d {{ $endDate }}.</p>
    </div>
    <div class="admin-action-group">
        <a href="{{ route('admin.laporan', ['tanggal_mulai' => $startDate, 'tanggal_selesai' => $endDate, 'export' => 'csv']) }}"
           class="admin-btn-sm">
            <i class="fa-solid fa-file-csv"></i> Export CSV
        </a>
        <button type="button" onclick="window.print()" class="admin-btn-sm">
            <i class="fa-solid fa-print"></i> Cetak Laporan
        </button>
    </div>
</div>

<!-- FILTER PERIODE TANGGAL -->
<form method="GET" action="{{ route('admin.laporan') }}" class="admin-order-toolbar" style="margin-bottom:16px;">
    <div class="admin-order-filters" style="flex-wrap:wrap;gap:10px;align-items:center;">
        <label style="font-size:0.82rem;font-weight:700;">Dari:</label>
        <input type="date" name="tanggal_mulai" value="{{ $startDate }}"
               style="padding:8px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;font-size:0.84rem;">

        <label style="font-size:0.82rem;font-weight:700;">Sampai:</label>
        <input type="date" name="tanggal_selesai" value="{{ $endDate }}"
               style="padding:8px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;font-size:0.84rem;">

        <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
            <i class="fa-solid fa-filter"></i> Terapkan Periode
        </button>
    </div>
</form>

<!-- KARTU KPI UTAMA -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(185px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Pemasukan Periode</div>
        <div style="font-size:1.3rem;font-weight:800;color:#047857;margin-top:2px;">+Rp{{ number_format($fin['income'], 0, ',', '.') }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Pengeluaran Periode</div>
        <div style="font-size:1.3rem;font-weight:800;color:#b91c1c;margin-top:2px;">-Rp{{ number_format($fin['expense'], 0, ',', '.') }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Saldo Bersih</div>
        <div style="font-size:1.3rem;font-weight:800;color:{{ $fin['net'] >= 0 ? 'var(--navy-dark)' : '#b91c1c' }};margin-top:2px;">
            {{ $fin['net'] < 0 ? '-' : '' }}Rp{{ number_format(abs($fin['net']), 0, ',', '.') }}
        </div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Pesanan</div>
        <div style="font-size:1.3rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">
            {{ $ord['total'] }} <span style="font-size:0.78rem;font-weight:600;color:var(--text-muted);">({{ $ord['completed'] }} selesai)</span>
        </div>
    </div>
</div>

<!-- RINGKASAN PER LAYANAN & OPERASIONAL -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px;margin-bottom:18px;">
    <div style="padding:16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <h3 style="font-size:0.95rem;font-weight:800;margin-bottom:10px;color:var(--navy-dark);">Ringkasan Layanan Pesanan</h3>
        @forelse ($ord['by_service'] as $svc)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px dashed #ebe4d6;font-size:0.84rem;">
                <div>
                    <strong>{{ $svc['service'] }}</strong>
                    <span style="color:var(--text-muted);">({{ $svc['count'] }} order)</span>
                </div>
                <strong>Rp{{ number_format($svc['amount'], 0, ',', '.') }}</strong>
            </div>
        @empty
            <div style="font-size:0.83rem;color:var(--text-muted);">Belum ada data pesanan pada periode ini.</div>
        @endforelse
    </div>

    <div style="padding:16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <h3 style="font-size:0.95rem;font-weight:800;margin-bottom:10px;color:var(--navy-dark);">Reservasi, Pelanggan &amp; Promo</h3>
        <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px dashed #ebe4d6;font-size:0.84rem;">
            <span>Reservasi Periode</span>
            <strong>{{ $res['total'] }} ({{ $res['confirmed'] }} terkonfirmasi)</strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px dashed #ebe4d6;font-size:0.84rem;">
            <span>Estimasi Tamu Aktif</span>
            <strong>{{ $res['active_guests'] }} Orang</strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px dashed #ebe4d6;font-size:0.84rem;">
            <span>Total Pelanggan Terdaftar</span>
            <strong>{{ $cust['total'] }} Orang</strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:7px 0;font-size:0.84rem;">
            <span>Pemakaian Kode Promo</span>
            <strong>{{ $prm['used'] }}x dari {{ $prm['total'] }} promo</strong>
        </div>
    </div>

    <div style="padding:16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <h3 style="font-size:0.95rem;font-weight:800;margin-bottom:10px;color:var(--navy-dark);">Peringatan Stok Inventaris</h3>
        @forelse ($lowStock as $item)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px dashed #ebe4d6;font-size:0.84rem;">
                <span>{{ $item['name'] }}</span>
                <span class="admin-badge-status is-cancel">Sisa {{ $item['stock'] }} {{ $item['unit'] }}</span>
            </div>
        @empty
            <div style="font-size:0.83rem;color:#047857;font-weight:600;">Seluruh stok bahan baku berada di atas batas minimum.</div>
        @endforelse
    </div>
</div>

<!-- TABEL RINCIAN PESANAN PERIODE -->
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ord['rows'] as $row)
                <tr>
                    <td>#{{ $loop->iteration }}</td>
                    <td>{{ $row['date'] }}</td>
                    <td><strong>{{ $row['code'] }}</strong></td>
                    <td>{{ $row['customer'] }}</td>
                    <td>{{ $row['service'] }}</td>
                    <td>{{ $row['payment'] }}</td>
                    <td><span class="admin-badge-status is-process">{{ $row['status'] }}</span></td>
                    <td><strong>Rp{{ number_format($row['amount'], 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:24px 16px;color:var(--text-muted);">
                        Tidak ada transaksi pesanan pada rentang tanggal {{ $startDate }} s/d {{ $endDate }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection