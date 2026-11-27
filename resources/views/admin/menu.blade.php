@extends('layouts.admin')

@section('title', 'Menu & Produk - PT. Kusantara')

@section('content')
@php
    $menus = $menus ?? [];
    $fallbackAdminImg = file_exists(public_path('images/backgroud.jpg'))
        ? asset('images/' . rawurlencode('backgroud.jpg'))
        : '';
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Menu &amp; Produk</h1>
        <p class="admin-page-sub">Kelola daftar menu restoran, harga, stok, gambar, dan status ketersediaan PT. Kusantara.</p>
    </div>
    <button type="button" id="openAddMenuModal" class="admin-btn-sm">
        <i class="fa-solid fa-plus"></i> Tambah Menu Baru
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

<!-- TOOLBAR PENCARIAN & FILTER KATEGORI -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="menuSearchInput"
               class="admin-order-search"
               placeholder="Cari nama menu atau kategori...">

        <div class="admin-service-pills" id="menuCategoryFilter">
            <button type="button" class="admin-service-pill is-active" data-category="Semua">Semua</button>
            <button type="button" class="admin-service-pill" data-category="Makanan">Makanan</button>
            <button type="button" class="admin-service-pill" data-category="Minuman">Minuman</button>
            <button type="button" class="admin-service-pill" data-category="Catering">Catering</button>
            <button type="button" class="admin-service-pill" data-category="Camilan">Camilan</button>
        </div>
    </div>
    <div id="menuCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Total {{ count($menus) }} menu
    </div>
</div>

<!-- TABEL MENU & PRODUK -->
<div class="admin-table-wrap">
    <table class="admin-table" id="menusTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($menus as $item)
                <tr data-category="{{ $item['category'] }}"
                    data-search="{{ strtolower($item['name'] . ' ' . $item['category']) }}"
                    data-id="{{ $item['id'] }}"
                    data-name="{{ $item['name'] }}"
                    data-price="{{ $item['price'] }}"
                    data-stock="{{ $item['stock'] }}"
                    data-description="{{ $item['description'] }}"
                    data-image-url="{{ $item['image_url'] }}"
                    data-active="{{ $item['is_active'] ? '1' : '0' }}">
                    <td>#{{ $loop->iteration }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            @if (!empty($item['image_url']))
                                <img src="{{ $item['image_url'] }}"
                                     alt="{{ $item['name'] }}"
                                     @if ($fallbackAdminImg !== '')
                                         onerror="this.onerror=null;this.src='{{ $fallbackAdminImg }}';"
                                     @endif
                                     style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid #e6dfd1;flex-shrink:0;">
                            @else
                                <div style="width:48px;height:48px;border-radius:8px;background:#f4efe4;border:1px solid #e6dfd1;display:flex;align-items:center;justify-content:center;color:#9a8b70;font-size:0.75rem;flex-shrink:0;">
                                    No Img
                                </div>
                            @endif
                            <div>
                                <div style="font-weight:700;color:var(--navy-dark);">{{ $item['name'] }}</div>
                                @if (!empty($item['description']))
                                    <div style="font-size:0.78rem;color:var(--text-muted);">{{ \Illuminate\Support\Str::limit($item['description'], 60) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $item['category'] }}</td>
                    <td><strong>Rp{{ number_format($item['price'], 0, ',', '.') }}</strong></td>
                    <td>{{ $item['stock'] }}</td>
                    <td>
                        @if ($item['is_active'])
                            <span class="admin-badge-status is-done">Tersedia</span>
                        @else
                            <span class="admin-badge-status is-cancel">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="admin-action-group">
                            <button type="button" class="admin-btn-sm btn-edit-menu" title="Edit Menu">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.menu.destroy', $item['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus menu {{ addslashes($item['name']) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-sm is-danger" title="Hapus Menu">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:28px 16px;color:var(--text-muted);">
                        Belum ada data menu di database. Klik <strong>Tambah Menu Baru</strong> untuk mulai mengisi katalog.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH / EDIT MENU -->
<div id="menuFormModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="menuModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="menuModalTitle" class="admin-modal-title">Tambah Menu Baru</h2>
            <button type="button" id="closeMenuModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form id="menuFormElement" action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="menuFormMethod" value="POST">

            <div style="display:grid;gap:12px;">
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Nama Menu</label>
                    <input type="text" name="name" id="menuNameInput" required
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Kategori</label>
                        <select name="category" id="menuCategoryInput"
                                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Catering">Catering</option>
                            <option value="Camilan">Camilan</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Harga (Rp)</label>
                        <input type="number" name="price" id="menuPriceInput" min="0" step="500" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Stok Awal</label>
                        <input type="number" name="stock" id="menuStockInput" min="0" value="20" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label id="menuImageLabel" style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">
                            Gambar Menu <span style="color:#b91c1c;">*</span>
                        </label>
                        <input type="file" name="image" id="menuImageInput" accept=".jpg,.jpeg,.png,.webp,image/*" required
                               style="width:100%;padding:7px 10px;border-radius:8px;border:1px solid #d9ceba;background:#faf7f0;font-size:0.8rem;">
                    </div>
                </div>

                <div id="currentImagePreviewWrap" style="display:none;align-items:center;gap:10px;padding:8px 10px;border-radius:8px;background:#faf7f0;border:1px solid #e6dfd1;">
                    <img id="currentImagePreview" src="" alt="Preview Gambar" style="width:44px;height:44px;object-fit:cover;border-radius:6px;">
                    <span id="currentImageHint" style="font-size:0.78rem;color:var(--text-muted);">Kosongkan file jika tidak ingin mengganti gambar.</span>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Deskripsi Singkat</label>
                    <textarea name="description" id="menuDescInput" rows="2"
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>

                <div id="menuActiveWrap" style="display:none;">
                    <label style="display:inline-flex;align-items:center;gap:8px;font-size:0.84rem;font-weight:600;cursor:pointer;">
                        <input type="checkbox" name="is_active" id="menuActiveInput" value="1" checked>
                        <span>Menu aktif &amp; tersedia di katalog</span>
                    </label>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelMenuModalBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Menu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let activeCategory = 'Semua';
    const catBtns = document.querySelectorAll('#menuCategoryFilter .admin-service-pill');
    const searchInput = document.getElementById('menuSearchInput');
    const rows = Array.from(document.querySelectorAll('#menusTable tbody tr[data-id]'));
    const countLabel = document.getElementById('menuCountLabel');

    function applyMenuFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visible = 0;

        rows.forEach(function (row) {
            const cat = row.getAttribute('data-category');
            const sr = row.getAttribute('data-search') || '';
            const show = (activeCategory === 'Semua' || cat === activeCategory) && (!kw || sr.includes(kw));
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' menu';
        }
    }

    catBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            catBtns.forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            activeCategory = btn.getAttribute('data-category') || 'Semua';
            applyMenuFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyMenuFilters);
    }

    const modal = document.getElementById('menuFormModal');
    const form = document.getElementById('menuFormElement');
    const methodInput = document.getElementById('menuFormMethod');
    const titleEl = document.getElementById('menuModalTitle');
    const activeWrap = document.getElementById('menuActiveWrap');
    const imageInput = document.getElementById('menuImageInput');
    const imageLabel = document.getElementById('menuImageLabel');
    const previewWrap = document.getElementById('currentImagePreviewWrap');
    const previewImg = document.getElementById('currentImagePreview');

    function openModal() {
        if (modal) modal.classList.add('is-open');
    }

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    const addBtn = document.getElementById('openAddMenuModal');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            titleEl.textContent = 'Tambah Menu Baru';
            form.action = "{{ route('admin.menu.store') }}";
            methodInput.value = 'POST';
            form.reset();
            document.getElementById('menuStockInput').value = '20';

            imageInput.required = true;
            imageLabel.innerHTML = 'Gambar Menu <span style="color:#b91c1c;">*</span>';
            previewWrap.style.display = 'none';
            previewImg.src = '';
            activeWrap.style.display = 'none';

            openModal();
        });
    }

    document.querySelectorAll('.btn-edit-menu').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');
            const imgUrl = row.getAttribute('data-image-url') || '';

            titleEl.textContent = 'Edit Menu #' + id;
            form.action = '/admin/menu/' + id;
            methodInput.value = 'PUT';
            form.reset();

            document.getElementById('menuNameInput').value = row.getAttribute('data-name') || '';
            document.getElementById('menuCategoryInput').value = row.getAttribute('data-category') || 'Makanan';
            document.getElementById('menuPriceInput').value = row.getAttribute('data-price') || 0;
            document.getElementById('menuStockInput').value = row.getAttribute('data-stock') || 0;
            document.getElementById('menuDescInput').value = row.getAttribute('data-description') || '';

            imageInput.required = false;
            imageLabel.innerHTML = 'Ganti Gambar (Opsional)';
            if (imgUrl) {
                previewImg.src = imgUrl;
                previewWrap.style.display = 'flex';
            } else {
                previewImg.src = '';
                previewWrap.style.display = 'none';
            }

            activeWrap.style.display = 'block';
            document.getElementById('menuActiveInput').checked = row.getAttribute('data-active') === '1';

            openModal();
        });
    });

    const closeBtn = document.getElementById('closeMenuModal');
    const cancelBtn = document.getElementById('cancelMenuModalBtn');
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