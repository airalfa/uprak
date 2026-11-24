@extends('layouts.admin')

@section('title', 'Promo & Diskon - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-status="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-status="Aktif">Aktif</button>
    <button type="button" class="admin-subnav-tab" data-status="Nonaktif">Nonaktif</button>
@endsection

@section('content')
@php
    $promos = $promos ?? [];
    $totalPromos = count($promos);
    $activePromos = collect($promos)->where('is_active', true)->count();
    $totalRedemptions = collect($promos)->sum(fn($p) => (int) $p['used_count']);
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Promo &amp; Kode Diskon</h1>
        <p class="admin-page-sub">Kelola kode promo untuk pemesanan menu—otomatis divalidasi saat pelanggan checkout.</p>
    </div>
    <button type="button" id="openAddPromoModal" class="admin-btn-sm">
        <i class="fa-solid fa-plus"></i> Tambah Promo
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

<!-- RINGKASAN PROMO -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Kode Promo</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalPromos }} Promo</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Promo Sedang Aktif</div>
        <div style="font-size:1.35rem;font-weight:800;color:#047857;margin-top:2px;">{{ $activePromos }} Aktif</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Pemakaian Promo</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalRedemptions }}x Dipakai</div>
    </div>
</div>

<!-- TOOLBAR PENCARIAN -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="promoSearchInput"
               class="admin-order-search"
               placeholder="Cari kode promo, judul, atau syarat...">
    </div>
    <div id="promoCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalPromos }} promo
    </div>
</div>

<!-- TABEL PROMO -->
<div class="admin-table-wrap">
    <table class="admin-table" id="promosTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode &amp; Nama Promo</th>
                <th>Potongan</th>
                <th>Min. Order</th>
                <th>Kuota / Terpakai</th>
                <th>Periode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($promos as $promo)
                @php
                    $statusLabel = $promo['is_active'] ? 'Aktif' : 'Nonaktif';
                    $potonganLabel = $promo['discount_type'] === 'fixed'
                        ? 'Rp' . number_format($promo['discount_value'], 0, ',', '.')
                        : $promo['discount_value'] . '%' . ($promo['max_discount'] > 0 ? ' (maks Rp' . number_format($promo['max_discount'], 0, ',', '.') . ')' : '');
                @endphp
                <tr data-id="{{ $promo['id'] }}"
                    data-status="{{ $statusLabel }}"
                    data-search="{{ strtolower($promo['code'] . ' ' . $promo['title'] . ' ' . $promo['description']) }}"
                    data-code="{{ $promo['code'] }}"
                    data-title="{{ $promo['title'] }}"
                    data-discount-type="{{ $promo['discount_type'] }}"
                    data-discount-value="{{ $promo['discount_value'] }}"
                    data-min-order="{{ $promo['min_order'] }}"
                    data-max-discount="{{ $promo['max_discount'] }}"
                    data-quota="{{ $promo['quota'] }}"
                    data-start-date="{{ $promo['start_date'] }}"
                    data-end-date="{{ $promo['end_date'] }}"
                    data-active="{{ $promo['is_active'] ? '1' : '0' }}"
                    data-description="{{ $promo['description'] }}">
                    <td>#{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:800;color:var(--navy-dark);letter-spacing:0.4px;">{{ $promo['code'] }}</div>
                        <div style="font-size:0.82rem;font-weight:600;">{{ $promo['title'] }}</div>
                        @if (!empty($promo['description']))
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ \Illuminate\Support\Str::limit($promo['description'], 55) }}</div>
                        @endif
                    </td>
                    <td><strong>{{ $potonganLabel }}</strong></td>
                    <td>Rp{{ number_format($promo['min_order'], 0, ',', '.') }}</td>
                    <td>{{ $promo['used_count'] }} / {{ $promo['quota'] > 0 ? $promo['quota'] : '∞' }}</td>
                    <td style="font-size:0.8rem;">
                        <div>{{ $promo['start_date'] }}</div>
                        <div style="color:var(--text-muted);">s/d {{ $promo['end_date'] }}</div>
                    </td>
                    <td>
                        <button type="button"
                                class="admin-badge-status {{ $promo['is_active'] ? 'is-done' : 'is-cancel' }} btn-toggle-promo"
                                style="cursor:pointer;border:none;">
                            {{ $statusLabel }}
                        </button>
                    </td>
                    <td>
                        <div class="admin-action-group">
                            <button type="button" class="admin-btn-sm btn-edit-promo" title="Edit Promo">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.promo.destroy', $promo['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus promo {{ addslashes($promo['code']) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-sm is-danger" title="Hapus Promo">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:28px 16px;color:var(--text-muted);">
                        Belum ada kode promo. Klik <strong>Tambah Promo</strong> untuk membuat diskon pemesanan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH / EDIT PROMO -->
<div id="promoFormModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="promoModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="promoModalTitle" class="admin-modal-title">Tambah Promo</h2>
            <button type="button" id="closePromoModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form id="promoFormElement" action="{{ route('admin.promo.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="promoFormMethod" value="POST">

            <div style="display:grid;gap:12px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Kode Promo</label>
                        <input type="text" name="code" id="promoCodeInput" placeholder="MISAL: HEMAT20" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;text-transform:uppercase;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Nama / Judul Promo</label>
                        <input type="text" name="title" id="promoTitleInput" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Tipe Diskon</label>
                        <select name="discount_type" id="promoTypeInput"
                                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                            <option value="percent">Persen (%)</option>
                            <option value="fixed">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Nilai Diskon</label>
                        <input type="number" name="discount_value" id="promoValueInput" min="1" value="10" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Maks. Diskon (Rp)</label>
                        <input type="number" name="max_discount" id="promoMaxInput" min="0" value="0"
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Minimal Belanja (Rp)</label>
                        <input type="number" name="min_order" id="promoMinOrderInput" min="0" value="0" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Kuota (0 = Tak Terbatas)</label>
                        <input type="number" name="quota" id="promoQuotaInput" min="0" value="100" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="promoStartInput" value="{{ now()->toDateString() }}"
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Tanggal Berakhir</label>
                        <input type="date" name="end_date" id="promoEndInput" value="{{ now()->addMonth()->toDateString() }}"
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Deskripsi / Syarat</label>
                    <textarea name="description" id="promoDescInput" rows="2"
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>

                <label style="display:inline-flex;align-items:center;gap:8px;font-size:0.84rem;font-weight:600;">
                    <input type="checkbox" name="is_active" id="promoActiveInput" value="1" checked>
                    Aktifkan promo langsung
                </label>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelPromoModalBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Promo
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
    const searchInput = document.getElementById('promoSearchInput');
    const rows = Array.from(document.querySelectorAll('#promosTable tbody tr[data-id]'));
    const countLabel = document.getElementById('promoCountLabel');
    const csrfToken = '{{ csrf_token() }}';

    function applyPromoFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visible = 0;

        rows.forEach(function (row) {
            const st = row.getAttribute('data-status');
            const sr = row.getAttribute('data-search') || '';
            const show = (activeStatus === 'Semua' || st === activeStatus) && (!kw || sr.includes(kw));
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' promo';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeStatus = tab.getAttribute('data-status') || 'Semua';
            applyPromoFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyPromoFilters);
    }

    document.querySelectorAll('.btn-toggle-promo').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');

            fetch('/admin/promo/' + id + '/toggle', {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (!data.ok) return;
                const nextLabel = data.is_active ? 'Aktif' : 'Nonaktif';
                row.setAttribute('data-status', nextLabel);
                row.setAttribute('data-active', data.is_active ? '1' : '0');
                btn.textContent = nextLabel;
                btn.className = 'admin-badge-status ' + (data.is_active ? 'is-done' : 'is-cancel') + ' btn-toggle-promo';
                applyPromoFilters();
            })
            .catch(function (err) {
                console.error('Gagal toggle status promo:', err);
            });
        });
    });

    const modal = document.getElementById('promoFormModal');
    const form = document.getElementById('promoFormElement');
    const methodInput = document.getElementById('promoFormMethod');
    const titleEl = document.getElementById('promoModalTitle');

    function openModal() {
        if (modal) modal.classList.add('is-open');
    }

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    const addBtn = document.getElementById('openAddPromoModal');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            titleEl.textContent = 'Tambah Promo';
            form.action = "{{ route('admin.promo.store') }}";
            methodInput.value = 'POST';
            form.reset();
            document.getElementById('promoTypeInput').value = 'percent';
            document.getElementById('promoValueInput').value = '10';
            document.getElementById('promoMaxInput').value = '0';
            document.getElementById('promoMinOrderInput').value = '0';
            document.getElementById('promoQuotaInput').value = '100';
            document.getElementById('promoStartInput').value = '{{ now()->toDateString() }}';
            document.getElementById('promoEndInput').value = '{{ now()->addMonth()->toDateString() }}';
            document.getElementById('promoActiveInput').checked = true;
            openModal();
        });
    }

    document.querySelectorAll('.btn-edit-promo').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');

            titleEl.textContent = 'Edit Promo';
            form.action = '/admin/promo/' + id;
            methodInput.value = 'PUT';

            document.getElementById('promoCodeInput').value = row.getAttribute('data-code') || '';
            document.getElementById('promoTitleInput').value = row.getAttribute('data-title') || '';
            document.getElementById('promoTypeInput').value = row.getAttribute('data-discount-type') || 'percent';
            document.getElementById('promoValueInput').value = row.getAttribute('data-discount-value') || 10;
            document.getElementById('promoMaxInput').value = row.getAttribute('data-max-discount') || 0;
            document.getElementById('promoMinOrderInput').value = row.getAttribute('data-min-order') || 0;
            document.getElementById('promoQuotaInput').value = row.getAttribute('data-quota') || 0;
            document.getElementById('promoStartInput').value = row.getAttribute('data-start-date') || '';
            document.getElementById('promoEndInput').value = row.getAttribute('data-end-date') || '';
            document.getElementById('promoDescInput').value = row.getAttribute('data-description') || '';
            document.getElementById('promoActiveInput').checked = row.getAttribute('data-active') === '1';

            openModal();
        });
    });

    const closeBtn = document.getElementById('closePromoModal');
    const cancelBtn = document.getElementById('cancelPromoModalBtn');
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