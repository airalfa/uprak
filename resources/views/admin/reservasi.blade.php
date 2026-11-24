@extends('layouts.admin')

@section('title', 'Reservasi Meja - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-status="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-status="Menunggu">Menunggu</button>
    <button type="button" class="admin-subnav-tab" data-status="Dikonfirmasi">Dikonfirmasi</button>
    <button type="button" class="admin-subnav-tab" data-status="Selesai">Selesai</button>
    <button type="button" class="admin-subnav-tab" data-status="Dibatalkan">Dibatalkan</button>
@endsection

@section('content')
@php
    $reservations = $reservations ?? [];
    $totalRes = count($reservations);
    $confirmedCount = collect($reservations)->where('status', 'Dikonfirmasi')->count();
    $pendingCount = collect($reservations)->where('status', 'Menunggu')->count();
    $totalGuests = collect($reservations)->where('status', '!=', 'Dibatalkan')->sum(fn($r) => (int) $r['guests']);
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Reservasi Meja</h1>
        <p class="admin-page-sub">Kelola jadwal reservasi pelanggan, jumlah tamu, meja/ruangan, dan status kedatangan.</p>
    </div>
    <button type="button" id="openAddReservationModal" class="admin-btn-sm">
        <i class="fa-solid fa-plus"></i> Tambah Reservasi
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

<!-- RINGKASAN RESERVASI -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Reservasi</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalRes }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Menunggu Konfirmasi</div>
        <div style="font-size:1.35rem;font-weight:800;color:#b45309;margin-top:2px;">{{ $pendingCount }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Terkonfirmasi</div>
        <div style="font-size:1.35rem;font-weight:800;color:#047857;margin-top:2px;">{{ $confirmedCount }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Estimasi Tamu Aktif</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalGuests }} Orang</div>
    </div>
</div>

<!-- TOOLBAR PENCARIAN & FILTER TANGGAL -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="reservationSearchInput"
               class="admin-order-search"
               placeholder="Cari nama tamu, no. HP, meja, atau catatan...">

        <input type="date"
               id="reservationDateFilter"
               style="padding:8px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;font-size:0.84rem;">
    </div>
    <div id="reservationCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalRes }} reservasi
    </div>
</div>

<!-- TABEL RESERVASI -->
<div class="admin-table-wrap">
    <table class="admin-table" id="reservationsTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Pelanggan</th>
                <th>Jadwal</th>
                <th>Tamu</th>
                <th>Meja / Area</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reservations as $res)
                @php
                    $badgeClass = match($res['status']) {
                        'Dikonfirmasi' => 'is-process',
                        'Selesai'      => 'is-done',
                        'Dibatalkan'   => 'is-cancel',
                        default        => 'is-pending',
                    };
                @endphp
                <tr data-id="{{ $res['id'] }}"
                    data-status="{{ $res['status'] }}"
                    data-date="{{ $res['reservation_date'] }}"
                    data-search="{{ strtolower($res['customer_name'] . ' ' . $res['phone'] . ' ' . $res['table_number'] . ' ' . $res['notes']) }}"
                    data-customer-name="{{ $res['customer_name'] }}"
                    data-phone="{{ $res['phone'] }}"
                    data-reservation-date="{{ $res['reservation_date'] }}"
                    data-reservation-time="{{ $res['reservation_time'] }}"
                    data-guests="{{ $res['guests'] }}"
                    data-table-number="{{ $res['table_number'] }}"
                    data-notes="{{ $res['notes'] }}">
                    <td>#{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:700;color:var(--navy-dark);">{{ $res['customer_name'] }}</div>
                        <div style="font-size:0.78rem;color:var(--text-muted);">{{ $res['phone'] }}</div>
                        @if (!empty($res['notes']))
                            <div style="font-size:0.75rem;color:#78716c;margin-top:2px;">{{ \Illuminate\Support\Str::limit($res['notes'], 55) }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $res['reservation_date'] }}</div>
                        <div style="font-size:0.8rem;color:var(--text-muted);">{{ $res['reservation_time'] }} WIB</div>
                    </td>
                    <td><strong>{{ $res['guests'] }}</strong> orang</td>
                    <td><span style="font-weight:600;">{{ $res['table_number'] }}</span></td>
                    <td>
                        <select class="res-status-select"
                                style="padding:5px 8px;border-radius:8px;border:1px solid #d9ceba;background:#fff;font-size:0.8rem;font-weight:600;">
                            @foreach (['Menunggu', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'] as $st)
                                <option value="{{ $st }}" {{ $res['status'] === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        <div style="margin-top:4px;">
                            <span class="admin-badge-status {{ $badgeClass }} res-status-badge">{{ $res['status'] }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="admin-action-group">
                            <button type="button" class="admin-btn-sm btn-edit-reservation" title="Edit Reservasi">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.reservasi.destroy', $res['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus reservasi {{ addslashes($res['customer_name']) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-sm is-danger" title="Hapus Reservasi">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:28px 16px;color:var(--text-muted);">
                        Belum ada data reservasi. Klik <strong>Tambah Reservasi</strong> untuk mencatat jadwal tamu.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH / EDIT RESERVASI -->
<div id="reservationFormModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="reservationModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="reservationModalTitle" class="admin-modal-title">Tambah Reservasi</h2>
            <button type="button" id="closeReservationModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form id="reservationFormElement" action="{{ route('admin.reservasi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="reservationFormMethod" value="POST">

            <div style="display:grid;gap:12px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Nama Pelanggan</label>
                        <input type="text" name="customer_name" id="resNameInput" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">No. Telepon / WA</label>
                        <input type="text" name="phone" id="resPhoneInput" placeholder="08xxxxxxxxxx"
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Tanggal Reservasi</label>
                        <input type="date" name="reservation_date" id="resDateInput" value="{{ now()->toDateString() }}" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Jam (WIB)</label>
                        <input type="time" name="reservation_time" id="resTimeInput" value="18:30" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Jumlah Tamu</label>
                        <input type="number" name="guests" id="resGuestsInput" min="1" max="200" value="4" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Meja / Area</label>
                        <input type="text" name="table_number" id="resTableInput" value="Meja 01" placeholder="Meja 01 / VIP-01" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Status</label>
                        <select name="status" id="resStatusInput"
                                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                            <option value="Menunggu">Menunggu</option>
                            <option value="Dikonfirmasi">Dikonfirmasi</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Catatan Khusus (Opsional)</label>
                    <textarea name="notes" id="resNotesInput" rows="2"
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelReservationModalBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Reservasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let activeStatus = 'Semua';
    const subnavTabs = document.querySelectorAll('.admin-subnav .admin-subnav-tab');
    const searchInput = document.getElementById('reservationSearchInput');
    const dateFilter = document.getElementById('reservationDateFilter');
    const rows = Array.from(document.querySelectorAll('#reservationsTable tbody tr[data-id]'));
    const countLabel = document.getElementById('reservationCountLabel');
    const csrfToken = '{{ csrf_token() }}';

    function badgeClassFor(status) {
        switch (status) {
            case 'Dikonfirmasi': return 'admin-badge-status is-process res-status-badge';
            case 'Selesai': return 'admin-badge-status is-done res-status-badge';
            case 'Dibatalkan': return 'admin-badge-status is-cancel res-status-badge';
            default: return 'admin-badge-status is-pending res-status-badge';
        }
    }

    function applyReservationFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        const dt = dateFilter ? dateFilter.value : '';
        let visible = 0;

        rows.forEach(function (row) {
            const st = row.getAttribute('data-status');
            const rDate = row.getAttribute('data-date') || '';
            const sr = row.getAttribute('data-search') || '';

            const show = (activeStatus === 'Semua' || st === activeStatus)
                      && (!dt || rDate === dt)
                      && (!kw || sr.includes(kw));

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' reservasi';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeStatus = tab.getAttribute('data-status') || 'Semua';
            applyReservationFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyReservationFilters);
    }

    if (dateFilter) {
        dateFilter.addEventListener('change', applyReservationFilters);
    }

    // Update status cepat per baris
    document.querySelectorAll('.res-status-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const row = select.closest('tr');
            const id = row.getAttribute('data-id');
            const nextStatus = select.value;

            row.setAttribute('data-status', nextStatus);
            const badge = row.querySelector('.res-status-badge');
            if (badge) {
                badge.className = badgeClassFor(nextStatus);
                badge.textContent = nextStatus;
            }

            fetch('/admin/reservasi/' + id + '/status', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: nextStatus })
            }).catch(function (err) {
                console.error('Gagal mengubah status reservasi:', err);
            });

            applyReservationFilters();
        });
    });

    // Modal Tambah / Edit
    const modal = document.getElementById('reservationFormModal');
    const form = document.getElementById('reservationFormElement');
    const methodInput = document.getElementById('reservationFormMethod');
    const titleEl = document.getElementById('reservationModalTitle');

    function openModal() {
        if (modal) modal.classList.add('is-open');
    }

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    const addBtn = document.getElementById('openAddReservationModal');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            titleEl.textContent = 'Tambah Reservasi';
            form.action = "{{ route('admin.reservasi.store') }}";
            methodInput.value = 'POST';
            form.reset();
            document.getElementById('resDateInput').value = '{{ now()->toDateString() }}';
            document.getElementById('resTimeInput').value = '18:30';
            document.getElementById('resGuestsInput').value = '4';
            document.getElementById('resTableInput').value = 'Meja 01';
            document.getElementById('resStatusInput').value = 'Menunggu';
            openModal();
        });
    }

    document.querySelectorAll('.btn-edit-reservation').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');

            titleEl.textContent = 'Edit Reservasi';
            form.action = '/admin/reservasi/' + id;
            methodInput.value = 'PUT';

            document.getElementById('resNameInput').value = row.getAttribute('data-customer-name') || '';
            document.getElementById('resPhoneInput').value = row.getAttribute('data-phone') || '';
            document.getElementById('resDateInput').value = row.getAttribute('data-reservation-date') || '{{ now()->toDateString() }}';
            document.getElementById('resTimeInput').value = row.getAttribute('data-reservation-time') || '18:30';
            document.getElementById('resGuestsInput').value = row.getAttribute('data-guests') || 2;
            document.getElementById('resTableInput').value = row.getAttribute('data-table-number') || 'Meja 01';
            document.getElementById('resStatusInput').value = row.getAttribute('data-status') || 'Menunggu';
            document.getElementById('resNotesInput').value = row.getAttribute('data-notes') || '';

            openModal();
        });
    });

    const closeBtn = document.getElementById('closeReservationModal');
    const cancelBtn = document.getElementById('cancelReservationModalBtn');
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