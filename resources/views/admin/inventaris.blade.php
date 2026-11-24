@extends('layouts.admin')

@section('title', 'Inventaris & Stok - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-status="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-status="Aman">Aman</button>
    <button type="button" class="admin-subnav-tab" data-status="Menipis">Menipis</button>
    <button type="button" class="admin-subnav-tab" data-status="Habis">Habis</button>
@endsection

@section('content')
@php
    $items = $items ?? [];
    $totalItems = count($items);
    $lowOrEmpty = collect($items)->whereIn('status', ['Menipis', 'Habis'])->count();
    $totalAssetValue = collect($items)->sum(fn($i) => (int) $i['stock'] * (int) $i['unit_price']);
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Inventaris &amp; Stok</h1>
        <p class="admin-page-sub">Pantau stok bahan baku, kemasan, dan perlengkapan operasional PT. Kusantara.</p>
    </div>
    <button type="button" id="openAddInventoryModal" class="admin-btn-sm">
        <i class="fa-solid fa-plus"></i> Tambah Item Stok
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

<!-- RINGKASAN SINGKAT INVENTARIS -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Item Terdaftar</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalItems }} Item</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Perlu Restok (Menipis / Habis)</div>
        <div style="font-size:1.35rem;font-weight:800;color:#b91c1c;margin-top:2px;">{{ $lowOrEmpty }} Item</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Estimasi Nilai Stok</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">Rp{{ number_format($totalAssetValue, 0, ',', '.') }}</div>
    </div>
</div>

<!-- TOOLBAR PENCARIAN & FILTER KATEGORI -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="inventorySearchInput"
               class="admin-order-search"
               placeholder="Cari nama bahan, kategori, atau catatan...">

        <div class="admin-service-pills" id="inventoryCategoryFilter">
            <button type="button" class="admin-service-pill is-active" data-category="Semua">Semua Kategori</button>
            <button type="button" class="admin-service-pill" data-category="Bahan Baku">Bahan Baku</button>
            <button type="button" class="admin-service-pill" data-category="Kemasan">Kemasan</button>
            <button type="button" class="admin-service-pill" data-category="Perlengkapan">Perlengkapan</button>
        </div>
    </div>
    <div id="inventoryCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalItems }} item
    </div>
</div>

<!-- TABEL INVENTARIS -->
<div class="admin-table-wrap">
    <table class="admin-table" id="inventoryTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Item</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Min. Stok</th>
                <th>Harga Satuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                @php
                    $badgeClass = match($item['status']) {
                        'Aman'    => 'is-done',
                        'Menipis' => 'is-pending',
                        'Habis'   => 'is-cancel',
                        default   => 'is-process',
                    };
                @endphp
                <tr data-id="{{ $item['id'] }}"
                    data-status="{{ $item['status'] }}"
                    data-category="{{ $item['category'] }}"
                    data-search="{{ strtolower($item['name'] . ' ' . $item['category'] . ' ' . $item['notes']) }}"
                    data-name="{{ $item['name'] }}"
                    data-stock="{{ $item['stock'] }}"
                    data-min-stock="{{ $item['min_stock'] }}"
                    data-unit="{{ $item['unit'] }}"
                    data-unit-price="{{ $item['unit_price'] }}"
                    data-notes="{{ $item['notes'] }}">
                    <td>#{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:700;color:var(--navy-dark);">{{ $item['name'] }}</div>
                        @if (!empty($item['notes']))
                            <div style="font-size:0.78rem;color:var(--text-muted);">{{ \Illuminate\Support\Str::limit($item['notes'], 60) }}</div>
                        @endif
                    </td>
                    <td>{{ $item['category'] }}</td>
                    <td>
                        <div style="display:inline-flex;align-items:center;gap:6px;">
                            <button type="button" class="admin-btn-sm btn-stock-delta" data-delta="-1" title="Kurangi 1" style="padding:3px 8px;">-</button>
                            <strong class="inv-stock-val">{{ $item['stock'] }}</strong>
                            <span style="font-size:0.8rem;color:var(--text-muted);">{{ $item['unit'] }}</span>
                            <button type="button" class="admin-btn-sm btn-stock-delta" data-delta="1" title="Tambah 1" style="padding:3px 8px;">+</button>
                        </div>
                    </td>
                    <td>{{ $item['min_stock'] }} {{ $item['unit'] }}</td>
                    <td><strong>Rp{{ number_format($item['unit_price'], 0, ',', '.') }}</strong></td>
                    <td>
                        <span class="admin-badge-status {{ $badgeClass }} inv-status-badge">{{ $item['status'] }}</span>
                    </td>
                    <td>
                        <div class="admin-action-group">
                            <button type="button" class="admin-btn-sm btn-edit-inventory" title="Edit Item">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.inventaris.destroy', $item['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus item {{ addslashes($item['name']) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-sm is-danger" title="Hapus Item">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:28px 16px;color:var(--text-muted);">
                        Belum ada data inventaris. Klik <strong>Tambah Item Stok</strong> untuk mulai mencatat bahan dan barang.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH / EDIT INVENTARIS -->
<div id="inventoryFormModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="inventoryModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="inventoryModalTitle" class="admin-modal-title">Tambah Item Stok</h2>
            <button type="button" id="closeInventoryModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form id="inventoryFormElement" action="{{ route('admin.inventaris.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="inventoryFormMethod" value="POST">

            <div style="display:grid;gap:12px;">
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Nama Bahan / Item</label>
                    <input type="text" name="name" id="invNameInput" required
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Kategori</label>
                        <select name="category" id="invCategoryInput"
                                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                            <option value="Bahan Baku">Bahan Baku</option>
                            <option value="Kemasan">Kemasan</option>
                            <option value="Perlengkapan">Perlengkapan</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Satuan</label>
                        <input type="text" name="unit" id="invUnitInput" value="kg" placeholder="kg, pcs, liter, pack" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Stok Saat Ini</label>
                        <input type="number" name="stock" id="invStockInput" min="0" value="10" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Batas Min. Stok</label>
                        <input type="number" name="min_stock" id="invMinStockInput" min="0" value="5" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Harga Satuan (Rp)</label>
                        <input type="number" name="unit_price" id="invPriceInput" min="0" step="100" value="0" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Catatan (Opsional)</label>
                    <textarea name="notes" id="invNotesInput" rows="2"
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelInventoryModalBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Item
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
    let activeCategory = 'Semua';

    const subnavTabs = document.querySelectorAll('.admin-subnav .admin-subnav-tab');
    const catBtns = document.querySelectorAll('#inventoryCategoryFilter .admin-service-pill');
    const searchInput = document.getElementById('inventorySearchInput');
    const rows = Array.from(document.querySelectorAll('#inventoryTable tbody tr[data-id]'));
    const countLabel = document.getElementById('inventoryCountLabel');
    const csrfToken = '{{ csrf_token() }}';

    function computeStatus(stock, minStock) {
        if (stock <= 0) return 'Habis';
        if (stock <= minStock) return 'Menipis';
        return 'Aman';
    }

    function badgeClassFor(status) {
        switch (status) {
            case 'Aman': return 'admin-badge-status is-done inv-status-badge';
            case 'Menipis': return 'admin-badge-status is-pending inv-status-badge';
            case 'Habis': return 'admin-badge-status is-cancel inv-status-badge';
            default: return 'admin-badge-status is-process inv-status-badge';
        }
    }

    function applyInventoryFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visible = 0;

        rows.forEach(function (row) {
            const st = row.getAttribute('data-status');
            const cat = row.getAttribute('data-category');
            const sr = row.getAttribute('data-search') || '';

            const show = (activeStatus === 'Semua' || st === activeStatus)
                      && (activeCategory === 'Semua' || cat === activeCategory)
                      && (!kw || sr.includes(kw));

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' item';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeStatus = tab.getAttribute('data-status') || 'Semua';
            applyInventoryFilters();
        });
    });

    catBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            catBtns.forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            activeCategory = btn.getAttribute('data-category') || 'Semua';
            applyInventoryFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyInventoryFilters);
    }

    // Tombol cepat +1 / -1 stok
    document.querySelectorAll('.btn-stock-delta').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');
            const delta = parseInt(btn.getAttribute('data-delta'), 10) || 0;
            const currentStock = parseInt(row.getAttribute('data-stock'), 10) || 0;
            const minStock = parseInt(row.getAttribute('data-min-stock'), 10) || 0;
            const nextStock = Math.max(0, currentStock + delta);
            const nextStatus = computeStatus(nextStock, minStock);

            row.setAttribute('data-stock', nextStock);
            row.setAttribute('data-status', nextStatus);

            const valEl = row.querySelector('.inv-stock-val');
            if (valEl) valEl.textContent = nextStock;

            const badge = row.querySelector('.inv-status-badge');
            if (badge) {
                badge.className = badgeClassFor(nextStatus);
                badge.textContent = nextStatus;
            }

            fetch('/admin/inventaris/' + id + '/adjust-stock', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ delta: delta })
            }).catch(function (err) {
                console.error('Gagal memperbarui stok:', err);
            });

            applyInventoryFilters();
        });
    });

    // Modal Tambah / Edit
    const modal = document.getElementById('inventoryFormModal');
    const form = document.getElementById('inventoryFormElement');
    const methodInput = document.getElementById('inventoryFormMethod');
    const titleEl = document.getElementById('inventoryModalTitle');

    function openModal() {
        if (modal) modal.classList.add('is-open');
    }

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    const addBtn = document.getElementById('openAddInventoryModal');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            titleEl.textContent = 'Tambah Item Stok';
            form.action = "{{ route('admin.inventaris.store') }}";
            methodInput.value = 'POST';
            form.reset();
            document.getElementById('invUnitInput').value = 'kg';
            document.getElementById('invStockInput').value = '10';
            document.getElementById('invMinStockInput').value = '5';
            document.getElementById('invPriceInput').value = '0';
            openModal();
        });
    }

    document.querySelectorAll('.btn-edit-inventory').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');

            titleEl.textContent = 'Edit Item Inventaris';
            form.action = '/admin/inventaris/' + id;
            methodInput.value = 'PUT';

            document.getElementById('invNameInput').value = row.getAttribute('data-name') || '';
            document.getElementById('invCategoryInput').value = row.getAttribute('data-category') || 'Bahan Baku';
            document.getElementById('invUnitInput').value = row.getAttribute('data-unit') || 'kg';
            document.getElementById('invStockInput').value = row.getAttribute('data-stock') || 0;
            document.getElementById('invMinStockInput').value = row.getAttribute('data-min-stock') || 0;
            document.getElementById('invPriceInput').value = row.getAttribute('data-unit-price') || 0;
            document.getElementById('invNotesInput').value = row.getAttribute('data-notes') || '';

            openModal();
        });
    });

    const closeBtn = document.getElementById('closeInventoryModal');
    const cancelBtn = document.getElementById('cancelInventoryModalBtn');
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