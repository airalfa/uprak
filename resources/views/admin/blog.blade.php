@extends('layouts.admin')

@section('title', 'Kelola Blog & Artikel - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-status="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-status="Terbit">Terbit</button>
    <button type="button" class="admin-subnav-tab" data-status="Draft">Draft</button>
@endsection

@section('content')
@php
    $posts = $posts ?? [];
    $totalPosts = count($posts);
    $publishedCount = collect($posts)->where('status', 'Terbit')->count();
    $draftCount = collect($posts)->where('status', 'Draft')->count();
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Kelola Blog &amp; Artikel</h1>
        <p class="admin-page-sub">Tulis, edit, dan atur status publikasi artikel kuliner, tips katering, serta cerita PT. Kusantara.</p>
    </div>
    <div class="admin-action-group">
        <a href="{{ route('blog.index') }}" target="_blank" rel="noopener" class="admin-btn-sm">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Blog Publik
        </a>
        <button type="button" id="openAddBlogModal" class="admin-btn-sm">
            <i class="fa-solid fa-plus"></i> Tulis Artikel
        </button>
    </div>
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

<!-- RINGKASAN BLOG -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Artikel</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalPosts }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Artikel Terbit</div>
        <div style="font-size:1.35rem;font-weight:800;color:#047857;margin-top:2px;">{{ $publishedCount }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Draft Tersimpan</div>
        <div style="font-size:1.35rem;font-weight:800;color:#b45309;margin-top:2px;">{{ $draftCount }}</div>
    </div>
</div>

<!-- TOOLBAR PENCARIAN -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="blogSearchInput"
               class="admin-order-search"
               placeholder="Cari judul artikel, kategori, penulis, atau ringkasan...">
    </div>
    <div id="blogCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalPosts }} artikel
    </div>
</div>

<!-- TABEL ARTIKEL -->
<div class="admin-table-wrap">
    <table class="admin-table" id="blogTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Judul &amp; Ringkasan</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $post)
                <tr data-id="{{ $post['id'] }}"
                    data-status="{{ $post['status'] }}"
                    data-search="{{ strtolower($post['title'] . ' ' . $post['category'] . ' ' . $post['author'] . ' ' . $post['excerpt']) }}"
                    data-title="{{ $post['title'] }}"
                    data-category="{{ $post['category'] }}"
                    data-author="{{ $post['author'] }}"
                    data-excerpt="{{ $post['excerpt'] }}"
                    data-content="{{ $post['content'] }}"
                    data-status-val="{{ $post['status'] }}">
                    <td>#{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:700;color:var(--navy-dark);">{{ $post['title'] }}</div>
                        <div style="font-size:0.74rem;color:#78716c;">/{{ $post['slug'] }}</div>
                        @if (!empty($post['excerpt']))
                            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:2px;">{{ \Illuminate\Support\Str::limit($post['excerpt'], 85) }}</div>
                        @endif
                    </td>
                    <td><span class="admin-badge-status is-process">{{ $post['category'] }}</span></td>
                    <td>{{ $post['author'] }}</td>
                    <td>{{ $post['published_at'] }}</td>
                    <td>
                        <button type="button"
                                class="admin-badge-status {{ $post['status'] === 'Terbit' ? 'is-done' : 'is-pending' }} btn-toggle-blog-status"
                                style="cursor:pointer;border:none;">
                            {{ $post['status'] }}
                        </button>
                    </td>
                    <td>
                        <div class="admin-action-group">
                            <button type="button" class="admin-btn-sm btn-edit-blog" title="Edit Artikel">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.blog.destroy', $post['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus artikel {{ addslashes($post['title']) }}?');">
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
                        Belum ada artikel blog. Klik <strong>Tulis Artikel</strong> untuk membuat konten baru.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH / EDIT ARTIKEL -->
<div id="blogFormModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="blogModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="blogModalTitle" class="admin-modal-title">Tulis Artikel</h2>
            <button type="button" id="closeBlogModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form id="blogFormElement" action="{{ route('admin.blog.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="blogFormMethod" value="POST">

            <div style="display:grid;gap:12px;">
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Judul Artikel</label>
                    <input type="text" name="title" id="blogTitleInput" required
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Kategori</label>
                        <input type="text" name="category" id="blogCategoryInput" value="Kuliner Nusantara" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Penulis</label>
                        <input type="text" name="author" id="blogAuthorInput" value="Tim Kusantara"
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Status</label>
                        <select name="status" id="blogStatusInput"
                                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                            <option value="Terbit">Terbit</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Ringkasan Singkat (Opsional)</label>
                    <textarea name="excerpt" id="blogExcerptInput" rows="2"
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Isi Artikel</label>
                    <textarea name="content" id="blogContentInput" rows="5" required
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelBlogModalBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Artikel
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
    const searchInput = document.getElementById('blogSearchInput');
    const rows = Array.from(document.querySelectorAll('#blogTable tbody tr[data-id]'));
    const countLabel = document.getElementById('blogCountLabel');
    const csrfToken = '{{ csrf_token() }}';

    function applyBlogFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visible = 0;

        rows.forEach(function (row) {
            const st = row.getAttribute('data-status') || '';
            const sr = row.getAttribute('data-search') || '';
            const show = (activeStatus === 'Semua' || st === activeStatus) && (!kw || sr.includes(kw));
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' artikel';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeStatus = tab.getAttribute('data-status') || 'Semua';
            applyBlogFilters();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyBlogFilters);

    document.querySelectorAll('.btn-toggle-blog-status').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');

            fetch('/admin/blog/' + id + '/toggle-status', {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (!data.ok) return;
                row.setAttribute('data-status', data.status);
                row.setAttribute('data-status-val', data.status);
                btn.textContent = data.status;
                btn.className = 'admin-badge-status ' + (data.status === 'Terbit' ? 'is-done' : 'is-pending') + ' btn-toggle-blog-status';
                applyBlogFilters();
            })
            .catch(function (err) {
                console.error('Gagal ubah status artikel:', err);
            });
        });
    });

    const modal = document.getElementById('blogFormModal');
    const form = document.getElementById('blogFormElement');
    const methodInput = document.getElementById('blogFormMethod');
    const titleEl = document.getElementById('blogModalTitle');

    function openModal() {
        if (modal) modal.classList.add('is-open');
    }

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    const addBtn = document.getElementById('openAddBlogModal');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            titleEl.textContent = 'Tulis Artikel';
            form.action = "{{ route('admin.blog.store') }}";
            methodInput.value = 'POST';
            form.reset();
            document.getElementById('blogCategoryInput').value = 'Kuliner Nusantara';
            document.getElementById('blogAuthorInput').value = 'Tim Kusantara';
            document.getElementById('blogStatusInput').value = 'Terbit';
            openModal();
        });
    }

    document.querySelectorAll('.btn-edit-blog').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');

            titleEl.textContent = 'Edit Artikel';
            form.action = '/admin/blog/' + id;
            methodInput.value = 'PUT';

            document.getElementById('blogTitleInput').value = row.getAttribute('data-title') || '';
            document.getElementById('blogCategoryInput').value = row.getAttribute('data-category') || 'Kuliner Nusantara';
            document.getElementById('blogAuthorInput').value = row.getAttribute('data-author') || 'Tim Kusantara';
            document.getElementById('blogExcerptInput').value = row.getAttribute('data-excerpt') || '';
            document.getElementById('blogContentInput').value = row.getAttribute('data-content') || '';
            document.getElementById('blogStatusInput').value = row.getAttribute('data-status-val') || 'Terbit';

            openModal();
        });
    });

    const closeBtn = document.getElementById('closeBlogModal');
    const cancelBtn = document.getElementById('cancelBlogModalBtn');
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