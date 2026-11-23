@extends('layouts.admin')

@section('title', 'Manajemen Pesanan - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-status="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-status="Menunggu">Menunggu</button>
    <button type="button" class="admin-subnav-tab" data-status="Diproses">Diproses</button>
    <button type="button" class="admin-subnav-tab" data-status="Siap Diambil">Siap Diambil</button>
    <button type="button" class="admin-subnav-tab" data-status="Dikirim">Dikirim</button>
    <button type="button" class="admin-subnav-tab" data-status="Selesai">Selesai</button>
    <button type="button" class="admin-subnav-tab" data-status="Dibatalkan">Dibatalkan</button>
@endsection

@section('content')
@php
    $orders = $orders ?? [];
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Manajemen Pesanan</h1>
        <p class="admin-page-sub">Kelola pesanan pelanggan, ubah status, lihat detail pembayaran, dan cetak struk/invoice.</p>
    </div>
    <button type="button" class="admin-btn-sm no-print" onclick="window.print()">
        <i class="fa-solid fa-print"></i> Cetak Daftar Pesanan
    </button>
</div>

<!-- TOOLBAR PENCARIAN & FILTER LAYANAN -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="orderSearchInput"
               class="admin-order-search"
               placeholder="Cari no. pesanan atau nama pelanggan...">

        <div class="admin-service-pills" id="orderServiceFilter">
            <button type="button" class="admin-service-pill is-active" data-service="Semua">Semua Layanan</button>
            <button type="button" class="admin-service-pill" data-service="Dine-in">Dine-in</button>
            <button type="button" class="admin-service-pill" data-service="Takeaway">Takeaway</button>
            <button type="button" class="admin-service-pill" data-service="Delivery">Delivery</button>
            <button type="button" class="admin-service-pill" data-service="Catering">Catering</button>
        </div>
    </div>
    <div id="orderCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ count($orders) }} pesanan
    </div>
</div>

<!-- TABEL PESANAN -->
<div class="admin-table-wrap">
    <table class="admin-table" id="ordersTable">
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Jenis</th>
                <th>Pelanggan</th>
                <th>Pembayaran</th>
                <th>Total</th>
                <th>Status</th>
                <th class="no-print">Ubah Status</th>
                <th class="no-print">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $ord)
                @php
                    $badgeClass = match($ord['status']) {
                        'Diproses' => 'is-process',
                        'Menunggu' => 'is-pending',
                        'Siap Diambil' => 'is-ready',
                        'Dikirim' => 'is-shipping',
                        'Selesai' => 'is-done',
                        'Dibatalkan' => 'is-cancel',
                        default => 'is-process',
                    };
                @endphp
                <tr data-id="{{ $ord['id'] ?? '' }}"
                    data-status="{{ $ord['status'] }}"
                    data-service="{{ $ord['service'] }}"
                    data-search="{{ strtolower($ord['code'] . ' ' . $ord['customer'] . ' ' . $ord['service']) }}"
                    data-code="{{ $ord['code'] }}"
                    data-phone="{{ $ord['phone'] }}"
                    data-items="{{ $ord['items'] }}"
                    data-payment="{{ $ord['payment'] }}"
                    data-total="{{ $ord['total'] }}"
                    data-note="{{ $ord['note'] }}"
                    data-cancel-reason="{{ $ord['cancel_reason'] }}">
                    <td><strong>{{ $ord['code'] }}</strong></td>
                    <td>{{ $ord['service'] }}</td>
                    <td>
                        <div style="font-weight:600;color:var(--navy-dark);">{{ $ord['customer'] }}</div>
                        <div style="font-size:0.76rem;color:var(--text-muted);">{{ $ord['phone'] }}</div>
                    </td>
                    <td>{{ $ord['payment'] }}</td>
                    <td><strong>{{ $ord['total'] }}</strong></td>
                    <td>
                        <span class="admin-badge-status {{ $badgeClass }} order-status-badge">{{ $ord['status'] }}</span>
                    </td>
                    <td class="no-print">
                        <select class="admin-status-select order-status-select" aria-label="Status {{ $ord['code'] }}">
                            @foreach (['Menunggu', 'Diproses', 'Siap Diambil', 'Dikirim', 'Selesai', 'Dibatalkan'] as $st)
                                <option value="{{ $st }}" {{ $ord['status'] === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="no-print">
                        <div class="admin-action-group">
                            <button type="button" class="admin-btn-sm btn-detail-order" title="Lihat Detail &amp; Struk">
                                <i class="fa-regular fa-eye"></i> Detail
                            </button>
                            <button type="button" class="admin-btn-sm is-danger btn-cancel-order" title="Batalkan dengan Alasan">
                                <i class="fa-solid fa-ban"></i> Batal
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr id="emptyOrdersRow">
                    <td colspan="8" style="text-align:center;padding:28px 16px;color:var(--text-muted);">
                        Belum ada pesanan masuk dari pelanggan. Silakan lakukan checkout pada halaman publik untuk menguji.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL DETAIL & INVOICE PESANAN -->
<div id="orderDetailModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="orderModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="orderModalTitle" class="admin-modal-title">Detail Pesanan</h2>
            <button type="button" id="closeOrderModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>
        <div id="orderModalBody" style="font-size:0.88rem;line-height:1.6;color:var(--text-dark);"></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
            <button type="button" class="admin-btn-sm" onclick="window.print()">
                <i class="fa-solid fa-file-invoice"></i> Cetak Invoice
            </button>
            <button type="button" id="btnCloseOrderModalFooter" class="admin-btn-sm">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let activeStatus = 'Semua';
    let activeService = 'Semua';

    const subnavTabs = document.querySelectorAll('.admin-subnav .admin-subnav-tab');
    const serviceBtns = document.querySelectorAll('#orderServiceFilter .admin-service-pill');
    const searchInput = document.getElementById('orderSearchInput');
    const rows = Array.from(document.querySelectorAll('#ordersTable tbody tr[data-code]'));
    const countLabel = document.getElementById('orderCountLabel');
    const csrfToken = '{{ csrf_token() }}';

    function badgeClassFor(status) {
        switch (status) {
            case 'Menunggu': return 'admin-badge-status is-pending order-status-badge';
            case 'Siap Diambil': return 'admin-badge-status is-ready order-status-badge';
            case 'Dikirim': return 'admin-badge-status is-shipping order-status-badge';
            case 'Selesai': return 'admin-badge-status is-done order-status-badge';
            case 'Dibatalkan': return 'admin-badge-status is-cancel order-status-badge';
            default: return 'admin-badge-status is-process order-status-badge';
        }
    }

    function persistOrderStatus(orderId, status, cancelReason) {
        if (!orderId) return;
        const payload = { status: status };
        if (typeof cancelReason !== 'undefined') {
            payload.cancel_reason = cancelReason;
        }

        fetch('/admin/pesanan/' + orderId + '/status', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        }).catch(function (err) {
            console.error('Gagal menyimpan status pesanan:', err);
        });
    }

    function applyFilters() {
        const keyword = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visibleCount = 0;

        rows.forEach(function (row) {
            const st = row.getAttribute('data-status');
            const sv = row.getAttribute('data-service');
            const sr = row.getAttribute('data-search') || '';

            const matchStatus = (activeStatus === 'Semua') || (st === activeStatus);
            const matchService = (activeService === 'Semua') || (sv === activeService);
            const matchSearch = !keyword || sr.includes(keyword);

            const show = matchStatus && matchService && matchSearch;
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visibleCount + ' dari ' + rows.length + ' pesanan';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeStatus = tab.getAttribute('data-status') || 'Semua';
            applyFilters();
        });
    });

    serviceBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            serviceBtns.forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            activeService = btn.getAttribute('data-service') || 'Semua';
            applyFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    // Ubah status langsung dari dropdown & simpan ke DB
    document.querySelectorAll('.order-status-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const row = select.closest('tr');
            const orderId = row.getAttribute('data-id');
            const nextStatus = select.value;
            row.setAttribute('data-status', nextStatus);
            const badge = row.querySelector('.order-status-badge');
            if (badge) {
                badge.className = badgeClassFor(nextStatus);
                badge.textContent = nextStatus;
            }
            persistOrderStatus(orderId, nextStatus);
            applyFilters();
        });
    });

    // Batalkan pesanan dengan alasan & simpan ke DB
    document.querySelectorAll('.btn-cancel-order').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const orderId = row.getAttribute('data-id');
            const code = row.getAttribute('data-code');
            const reason = window.prompt('Masukkan alasan pembatalan pesanan ' + code + ':');
            if (reason && reason.trim() !== '') {
                const cleanReason = reason.trim();
                row.setAttribute('data-status', 'Dibatalkan');
                row.setAttribute('data-cancel-reason', cleanReason);
                const sel = row.querySelector('.order-status-select');
                if (sel) sel.value = 'Dibatalkan';
                const badge = row.querySelector('.order-status-badge');
                if (badge) {
                    badge.className = badgeClassFor('Dibatalkan');
                    badge.textContent = 'Dibatalkan';
                }
                persistOrderStatus(orderId, 'Dibatalkan', cleanReason);
                applyFilters();
            }
        });
    });

    // Modal detail pesanan
    const modal = document.getElementById('orderDetailModal');
    const modalTitle = document.getElementById('orderModalTitle');
    const modalBody = document.getElementById('orderModalBody');

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    document.querySelectorAll('.btn-detail-order').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const code = row.getAttribute('data-code');
            const service = row.getAttribute('data-service');
            const status = row.getAttribute('data-status');
            const payment = row.getAttribute('data-payment');
            const total = row.getAttribute('data-total');
            const items = row.getAttribute('data-items');
            const note = row.getAttribute('data-note');
            const cancelReason = row.getAttribute('data-cancel-reason');

            modalTitle.textContent = 'Invoice & Detail Pesanan ' + code;
            modalBody.innerHTML =
                '<p><strong>Layanan:</strong> ' + service + ' &bull; <strong>Status:</strong> ' + status + '</p>' +
                '<p><strong>Item Pesanan:</strong><br>' + items + '</p>' +
                '<p><strong>Metode Pembayaran:</strong> ' + payment + ' &bull; <strong>Total:</strong> ' + total + '</p>' +
                '<p><strong>Catatan:</strong> ' + (note || '-') + '</p>' +
                (cancelReason ? '<p style="color:#991b1b;"><strong>Alasan Pembatalan:</strong> ' + cancelReason + '</p>' : '');

            modal.classList.add('is-open');
        });
    });

    const closeBtn = document.getElementById('closeOrderModal');
    const closeFooterBtn = document.getElementById('btnCloseOrderModalFooter');
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (closeFooterBtn) closeFooterBtn.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
    }
});
</script>
@endpush