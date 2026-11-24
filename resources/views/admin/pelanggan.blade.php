@extends('layouts.admin')

@section('title', 'Data Pelanggan - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-tier="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-tier="Setia">Pelanggan Setia</button>
    <button type="button" class="admin-subnav-tab" data-tier="Baru">Pelanggan Baru</button>
@endsection

@section('content')
@php
    $customers = $customers ?? [];
    $totalCustomers = count($customers);
    $loyalCount = collect($customers)->where('tier', 'Setia')->count();
    $totalRevenue = collect($customers)->sum(fn($c) => (int) $c['total_spent']);
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Data Pelanggan</h1>
        <p class="admin-page-sub">Daftar pelanggan unik berdasarkan nomor telepon—nomor yang sama otomatis digabung agar tidak duplikat.</p>
    </div>
    <button type="button" id="openAddCustomerModal" class="admin-btn-sm">
        <i class="fa-solid fa-plus"></i> Tambah / Sinkron Pelanggan
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

<!-- RINGKASAN PELANGGAN -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Pelanggan Unik (No. HP)</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalCustomers }} Orang</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Pelanggan Setia</div>
        <div style="font-size:1.35rem;font-weight:800;color:#047857;margin-top:2px;">{{ $loyalCount }} Orang</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Akumulasi Transaksi Pelanggan</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
</div>

<!-- TOOLBAR PENCARIAN -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="customerSearchInput"
               class="admin-order-search"
               placeholder="Cari nama, nomor telepon, email, atau alamat...">
    </div>
    <div id="customerCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalCustomers }} pelanggan
    </div>
</div>

<!-- TABEL PELANGGAN -->
<div class="admin-table-wrap">
    <table class="admin-table" id="customersTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Pelanggan</th>
                <th>No. Telepon</th>
                <th>Jumlah Order</th>
                <th>Total Belanja</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $cust)
                <tr data-id="{{ $cust['id'] }}"
                    data-tier="{{ $cust['tier'] }}"
                    data-search="{{ strtolower($cust['name'] . ' ' . $cust['phone'] . ' ' . $cust['email'] . ' ' . $cust['address']) }}"
                    data-name="{{ $cust['name'] }}"
                    data-phone="{{ $cust['phone'] }}"
                    data-email="{{ $cust['email'] }}"
                    data-address="{{ $cust['address'] }}"
                    data-notes="{{ $cust['notes'] }}">
                    <td>#{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:700;color:var(--navy-dark);">{{ $cust['name'] }}</div>
                        @if (!empty($cust['address']))
                            <div style="font-size:0.78rem;color:var(--text-muted);">{{ \Illuminate\Support\Str::limit($cust['address'], 60) }}</div>
                        @endif
                        @if (!empty($cust['notes']))
                            <div style="font-size:0.74rem;color:#78716c;margin-top:2px;">{{ \Illuminate\Support\Str::limit($cust['notes'], 60) }}</div>
                        @endif
                    </td>
                    <td><strong>{{ $cust['phone'] }}</strong></td>
                    <td>{{ $cust['total_orders'] }}x</td>
                    <td><strong>Rp{{ number_format($cust['total_spent'], 0, ',', '.') }}</strong></td>
                    <td>
                        @if ($cust['tier'] === 'Setia')
                            <span class="admin-badge-status is-done">Pelanggan Setia</span>
                        @else
                            <span class="admin-badge-status is-pending">Pelanggan Baru</span>
                        @endif
                    </td>
                    <td>
                        <div class="admin-action-group">
                            <button type="button" class="admin-btn-sm btn-edit-customer" title="Edit Pelanggan">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.pelanggan.destroy', $cust['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus data pelanggan {{ addslashes($cust['name']) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-sm is-danger" title="Hapus Pelanggan">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:28px 16px;color:var(--text-muted);">
                        Belum ada data pelanggan. Pelanggan akan otomatis masuk saat melakukan pesanan/reservasi atau klik <strong>Tambah / Sinkron Pelanggan</strong>.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH / EDIT PELANGGAN -->
<div id="customerFormModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="customerModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="customerModalTitle" class="admin-modal-title">Tambah / Sinkron Pelanggan</h2>
            <button type="button" id="closeCustomerModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form id="customerFormElement" action="{{ route('admin.pelanggan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="customerFormMethod" value="POST">

            <div style="display:grid;gap:12px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Nama Pelanggan</label>
                        <input type="text" name="name" id="custNameInput" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">No. Telepon (Kunci Unik)</label>
                        <input type="text" name="phone" id="custPhoneInput" placeholder="08xxxxxxxxxx" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Email (Opsional)</label>
                    <input type="email" name="email" id="custEmailInput" placeholder="nama@email.com"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Alamat (Opsional)</label>
                    <textarea name="address" id="custAddressInput" rows="2"
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Catatan (Opsional)</label>
                    <textarea name="notes" id="custNotesInput" rows="2"
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelCustomerModalBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Pelanggan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let activeTier = 'Semua';
    const subnavTabs = document.querySelectorAll('.admin-subnav .admin-subnav-tab');
    const searchInput = document.getElementById('customerSearchInput');
    const rows = Array.from(document.querySelectorAll('#customersTable tbody tr[data-id]'));
    const countLabel = document.getElementById('customerCountLabel');

    function applyCustomerFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visible = 0;

        rows.forEach(function (row) {
            const tr = row.getAttribute('data-tier');
            const sr = row.getAttribute('data-search') || '';
            const show = (activeTier === 'Semua' || tr === activeTier) && (!kw || sr.includes(kw));
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' pelanggan';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeTier = tab.getAttribute('data-tier') || 'Semua';
            applyCustomerFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyCustomerFilters);
    }

    const modal = document.getElementById('customerFormModal');
    const form = document.getElementById('customerFormElement');
    const methodInput = document.getElementById('customerFormMethod');
    const titleEl = document.getElementById('customerModalTitle');

    function openModal() {
        if (modal) modal.classList.add('is-open');
    }

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    const addBtn = document.getElementById('openAddCustomerModal');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            titleEl.textContent = 'Tambah / Sinkron Pelanggan';
            form.action = "{{ route('admin.pelanggan.store') }}";
            methodInput.value = 'POST';
            form.reset();
            openModal();
        });
    }

    document.querySelectorAll('.btn-edit-customer').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');

            titleEl.textContent = 'Edit Pelanggan';
            form.action = '/admin/pelanggan/' + id;
            methodInput.value = 'PUT';

            document.getElementById('custNameInput').value = row.getAttribute('data-name') || '';
            document.getElementById('custPhoneInput').value = row.getAttribute('data-phone') || '';
            document.getElementById('custEmailInput').value = row.getAttribute('data-email') || '';
            document.getElementById('custAddressInput').value = row.getAttribute('data-address') || '';
            document.getElementById('custNotesInput').value = row.getAttribute('data-notes') || '';

            openModal();
        });
    });

    const closeBtn = document.getElementById('closeCustomerModal');
    const cancelBtn = document.getElementById('cancelCustomerModalBtn');
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