@extends('layouts.admin')

@section('title', 'Catering & Delivery - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-filter="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-filter="Catering">Catering</button>
    <button type="button" class="admin-subnav-tab" data-filter="Delivery">Delivery</button>
    <button type="button" class="admin-subnav-tab" data-filter="Dikirim">Dikirim</button>
    <button type="button" class="admin-subnav-tab" data-filter="Selesai">Selesai</button>
@endsection

@section('content')
@php
    $deliveries = $deliveries ?? [];
    $totalEntries = count($deliveries);
    $cateringCount = collect($deliveries)->where('service_type', 'Catering')->count();
    $deliveryCount = collect($deliveries)->where('service_type', 'Delivery')->count();
    $onTripCount = collect($deliveries)->where('status', 'Dikirim')->count();
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Catering &amp; Delivery</h1>
        <p class="admin-page-sub">Kelola jadwal pengiriman katering acara dan pengantaran pesanan pelanggan.</p>
    </div>
    <button type="button" id="openAddDeliveryModal" class="admin-btn-sm">
        <i class="fa-solid fa-plus"></i> Tambah Jadwal Kirim
    </button>
</div>

@if (session('success'))
    <div style="margin-bottom:14px;padding:12px 16px;border-radius:10px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;font-size:0.86rem;font-weight:600;">
        {{ session('success') }}
    </div>
@endif

@if (session('error') || $errors->any())
    <div style="margin-bottom:14px;padding:12px 16px;border-radius:10px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-size:0.86rem;font-weight:600;">
        {{ session('error') ?? $errors->first() }}
    </div>
@endif

<!-- RINGKASAN CATERING & DELIVERY -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Pengiriman</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalEntries }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Layanan Catering</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $cateringCount }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Layanan Delivery</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $deliveryCount }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Sedang Dikirim</div>
        <div style="font-size:1.35rem;font-weight:800;color:#1d4ed8;margin-top:2px;">{{ $onTripCount }}</div>
    </div>
</div>

<!-- TOOLBAR PENCARIAN & TANGGAL -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="deliverySearchInput"
               class="admin-order-search"
               placeholder="Cari kode, nama pelanggan, menu, kurir, atau alamat...">

        <input type="date"
               id="deliveryDateFilter"
               style="padding:8px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;font-size:0.84rem;">
    </div>
    <div id="deliveryCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalEntries }} data
    </div>
</div>

<!-- TABEL CATERING & DELIVERY -->
<div class="admin-table-wrap">
    <table class="admin-table" id="deliveriesTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode &amp; Jenis</th>
                <th>Pelanggan</th>
                <th>Jadwal &amp; Kurir</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($deliveries as $del)
                @php
                    $badgeClass = match($del['status']) {
                        'Diproses'   => 'is-pending',
                        'Dikirim'    => 'is-process',
                        'Selesai'    => 'is-done',
                        'Dibatalkan' => 'is-cancel',
                        default      => 'is-pending',
                    };
                @endphp
                <tr data-id="{{ $del['id'] }}"
                    data-service="{{ $del['service_type'] }}"
                    data-status="{{ $del['status'] }}"
                    data-date="{{ $del['delivery_date'] }}"
                    data-search="{{ strtolower($del['code'] . ' ' . $del['service_type'] . ' ' . $del['customer_name'] . ' ' . $del['phone'] . ' ' . $del['items_summary'] . ' ' . $del['courier_name'] . ' ' . $del['address_notes']) }}"
                    data-service-type="{{ $del['service_type'] }}"
                    data-customer-name="{{ $del['customer_name'] }}"
                    data-phone="{{ $del['phone'] }}"
                    data-items-summary="{{ $del['items_summary'] }}"
                    data-delivery-date="{{ $del['delivery_date'] }}"
                    data-delivery-time="{{ $del['delivery_time'] }}"
                    data-courier-name="{{ $del['courier_name'] }}"
                    data-total-amount="{{ $del['total_amount'] }}"
                    data-address-notes="{{ $del['address_notes'] }}">
                    <td>#{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:800;color:var(--navy-dark);">{{ $del['code'] }}</div>
                        <span class="admin-badge-status is-process">{{ $del['service_type'] }}</span>
                    </td>
                    <td>
                        <div style="font-weight:700;color:var(--navy-dark);">{{ $del['customer_name'] }}</div>
                        <div style="font-size:0.78rem;color:var(--text-muted);">{{ $del['phone'] }}</div>
                        <div style="font-size:0.8rem;margin-top:2px;">{{ \Illuminate\Support\Str::limit($del['items_summary'], 65) }}</div>
                        @if (!empty($del['address_notes']))
                            <div style="font-size:0.75rem;color:#78716c;margin-top:2px;">{{ \Illuminate\Support\Str::limit($del['address_notes'], 65) }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $del['delivery_date'] }} • {{ $del['delivery_time'] }}</div>
                        <div style="font-size:0.78rem;color:var(--text-muted);">{{ $del['courier_name'] }}</div>
                    </td>
                    <td><strong>{{ $del['total_label'] }}</strong></td>
                    <td>
                        <select class="del-status-select"
                                style="padding:5px 8px;border-radius:8px;border:1px solid #d9ceba;background:#fff;font-size:0.8rem;font-weight:600;">
                            @foreach (['Menunggu', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'] as $st)
                                <option value="{{ $st }}" {{ $del['status'] === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        <div style="margin-top:4px;">
                            <span class="admin-badge-status {{ $badgeClass }} del-status-badge">{{ $del['status'] }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="admin-action-group">
                            <button type="button" class="admin-btn-sm btn-edit-delivery" title="Edit Jadwal">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.catering.destroy', $del['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus jadwal {{ addslashes($del['code']) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-sm is-danger" title="Hapus">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:28px 16px;color:var(--text-muted);">
                        Belum ada data Catering atau Delivery. Pesanan bertipe Delivery/Catering akan otomatis masuk atau klik <strong>Tambah Jadwal Kirim</strong>.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH / EDIT CATERING & DELIVERY -->
<div id="deliveryFormModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="deliveryModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="deliveryModalTitle" class="admin-modal-title">Tambah Jadwal Kirim</h2>
            <button type="button" id="closeDeliveryModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form id="deliveryFormElement" action="{{ route('admin.catering.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="deliveryFormMethod" value="POST">

            <div style="display:grid;gap:12px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Jenis Layanan</label>
                        <select name="service_type" id="delServiceInput"
                                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                            <option value="Delivery">Delivery</option>
                            <option value="Catering">Catering</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Status</label>
                        <select name="status" id="delStatusInput"
                                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                            <option value="Menunggu">Menunggu</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Dikirim">Dikirim</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Nama Pelanggan / Instansi</label>
                        <input type="text" name="customer_name" id="delCustomerInput" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">No. Telepon</label>
                        <input type="text" name="phone" id="delPhoneInput" placeholder="08xxxxxxxxxx"
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Tanggal Kirim</label>
                        <input type="date" name="delivery_date" id="delDateInput" value="{{ now()->toDateString() }}" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Jam Kirim</label>
                        <input type="time" name="delivery_time" id="delTimeInput" value="11:00" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Nominal (Rp)</label>
                        <input type="number" name="total_amount" id="delAmountInput" min="0" step="100" value="0" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Kurir / Tim Penanggung Jawab</label>
                    <input type="text" name="courier_name" id="delCourierInput" value="Tim Kusantara"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Daftar Menu / Paket Pesanan</label>
                    <textarea name="items_summary" id="delItemsInput" rows="2" required
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Alamat / Catatan Lokasi</label>
                    <textarea name="address_notes" id="delNotesInput" rows="2"
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelDeliveryModalBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let activeFilter = 'Semua';
    const subnavTabs = document.querySelectorAll('.admin-subnav .admin-subnav-tab');
    const searchInput = document.getElementById('deliverySearchInput');
    const dateFilter = document.getElementById('deliveryDateFilter');
    const rows = Array.from(document.querySelectorAll('#deliveriesTable tbody tr[data-id]'));
    const countLabel = document.getElementById('deliveryCountLabel');
    const csrfToken = '{{ csrf_token() }}';

    function badgeClassFor(status) {
        switch (status) {
            case 'Diproses': return 'admin-badge-status is-pending del-status-badge';
            case 'Dikirim': return 'admin-badge-status is-process del-status-badge';
            case 'Selesai': return 'admin-badge-status is-done del-status-badge';
            case 'Dibatalkan': return 'admin-badge-status is-cancel del-status-badge';
            default: return 'admin-badge-status is-pending del-status-badge';
        }
    }

    function applyDeliveryFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        const dt = dateFilter ? dateFilter.value : '';
        let visible = 0;

        rows.forEach(function (row) {
            const svc = row.getAttribute('data-service') || '';
            const st = row.getAttribute('data-status') || '';
            const rDate = row.getAttribute('data-date') || '';
            const sr = row.getAttribute('data-search') || '';

            const matchTab = activeFilter === 'Semua'
                || svc === activeFilter
                || st === activeFilter;

            const show = matchTab && (!dt || rDate === dt) && (!kw || sr.includes(kw));
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' data';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeFilter = tab.getAttribute('data-filter') || 'Semua';
            applyDeliveryFilters();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyDeliveryFilters);
    if (dateFilter) dateFilter.addEventListener('change', applyDeliveryFilters);

    document.querySelectorAll('.del-status-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const row = select.closest('tr');
            const id = row.getAttribute('data-id');
            const nextStatus = select.value;

            row.setAttribute('data-status', nextStatus);
            const badge = row.querySelector('.del-status-badge');
            if (badge) {
                badge.className = badgeClassFor(nextStatus);
                badge.textContent = nextStatus;
            }

            fetch('/admin/catering-delivery/' + id + '/status', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: nextStatus })
            }).catch(function (err) {
                console.error('Gagal memperbarui status pengiriman:', err);
            });

            applyDeliveryFilters();
        });
    });

    const modal = document.getElementById('deliveryFormModal');
    const form = document.getElementById('deliveryFormElement');
    const methodInput = document.getElementById('deliveryFormMethod');
    const titleEl = document.getElementById('deliveryModalTitle');

    function openModal() {
        if (modal) modal.classList.add('is-open');
    }

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    const addBtn = document.getElementById('openAddDeliveryModal');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            titleEl.textContent = 'Tambah Jadwal Kirim';
            form.action = "{{ route('admin.catering.store') }}";
            methodInput.value = 'POST';
            form.reset();
            document.getElementById('delServiceInput').value = 'Delivery';
            document.getElementById('delStatusInput').value = 'Menunggu';
            document.getElementById('delDateInput').value = '{{ now()->toDateString() }}';
            document.getElementById('delTimeInput').value = '11:00';
            document.getElementById('delAmountInput').value = '0';
            document.getElementById('delCourierInput').value = 'Tim Kusantara';
            openModal();
        });
    }

    document.querySelectorAll('.btn-edit-delivery').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');

            titleEl.textContent = 'Edit Jadwal Kirim';
            form.action = '/admin/catering-delivery/' + id;
            methodInput.value = 'PUT';

            document.getElementById('delServiceInput').value = row.getAttribute('data-service-type') || 'Delivery';
            document.getElementById('delStatusInput').value = row.getAttribute('data-status') || 'Menunggu';
            document.getElementById('delCustomerInput').value = row.getAttribute('data-customer-name') || '';
            document.getElementById('delPhoneInput').value = row.getAttribute('data-phone') || '';
            document.getElementById('delDateInput').value = row.getAttribute('data-delivery-date') || '{{ now()->toDateString() }}';
            document.getElementById('delTimeInput').value = row.getAttribute('data-delivery-time') || '11:00';
            document.getElementById('delAmountInput').value = row.getAttribute('data-total-amount') || 0;
            document.getElementById('delCourierInput').value = row.getAttribute('data-courier-name') || 'Tim Kusantara';
            document.getElementById('delItemsInput').value = row.getAttribute('data-items-summary') || '';
            document.getElementById('delNotesInput').value = row.getAttribute('data-address-notes') || '';

            openModal();
        });
    });

    const closeBtn = document.getElementById('closeDeliveryModal');
    const cancelBtn = document.getElementById('cancelDeliveryModalBtn');
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
    }
});
</script>
@endpush