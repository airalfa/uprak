@extends('layouts.admin')

@section('title', 'Ulasan & Rating Pelanggan - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-status="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-status="Menunggu">Menunggu</button>
    <button type="button" class="admin-subnav-tab" data-status="Terbit">Terbit</button>
    <button type="button" class="admin-subnav-tab" data-status="Disembunyikan">Disembunyikan</button>
@endsection

@section('content')
@php
    $reviews = $reviews ?? [];
    $totalReviews = count($reviews);
    $avgRating = $totalReviews > 0
        ? number_format(collect($reviews)->avg(fn($r) => (int) $r['rating']), 1)
        : '0.0';
    $pendingReviews = collect($reviews)->where('status', 'Menunggu')->count();
    $publishedReviews = collect($reviews)->where('status', 'Terbit')->count();
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Ulasan &amp; Rating Pelanggan</h1>
        <p class="admin-page-sub">Rating dan komentar asli dari pelanggan tidak dapat diubah; admin dapat membalas serta mengatur status publikasi ulasan.</p>
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

<!-- RINGKASAN ULASAN -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(175px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Rating Rata-Rata</div>
        <div style="font-size:1.35rem;font-weight:800;color:#b45309;margin-top:2px;">{{ $avgRating }} / 5.0 ★</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Ulasan</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalReviews }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Menunggu Moderasi</div>
        <div style="font-size:1.35rem;font-weight:800;color:#d97706;margin-top:2px;">{{ $pendingReviews }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Ulasan Terbit</div>
        <div style="font-size:1.35rem;font-weight:800;color:#047857;margin-top:2px;">{{ $publishedReviews }}</div>
    </div>
</div>

<!-- TOOLBAR PENCARIAN -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="reviewSearchInput"
               class="admin-order-search"
               placeholder="Cari nama pelanggan, layanan/menu, isi ulasan, atau balasan...">
    </div>
    <div id="reviewCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalReviews }} ulasan
    </div>
</div>

<!-- TABEL ULASAN -->
<div class="admin-table-wrap">
    <table class="admin-table" id="reviewsTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Pelanggan</th>
                <th>Layanan / Menu</th>
                <th>Rating</th>
                <th>Ulasan Pelanggan &amp; Balasan Admin</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reviews as $rev)
                @php
                    $badgeClass = match($rev['status']) {
                        'Terbit'        => 'is-done',
                        'Disembunyikan' => 'is-cancel',
                        default         => 'is-pending',
                    };
                @endphp
                <tr data-id="{{ $rev['id'] }}"
                    data-status="{{ $rev['status'] }}"
                    data-search="{{ strtolower($rev['customer_name'] . ' ' . $rev['phone_or_order'] . ' ' . $rev['service_or_menu'] . ' ' . $rev['comment'] . ' ' . $rev['admin_reply']) }}"
                    data-customer-name="{{ $rev['customer_name'] }}"
                    data-service-or-menu="{{ $rev['service_or_menu'] }}"
                    data-rating="{{ $rev['rating'] }}"
                    data-comment="{{ $rev['comment'] }}"
                    data-admin-reply="{{ $rev['admin_reply'] }}"
                    data-status-val="{{ $rev['status'] }}">
                    <td>#{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:700;color:var(--navy-dark);">{{ $rev['customer_name'] }}</div>
                        <div style="font-size:0.76rem;color:var(--text-muted);">{{ $rev['phone_or_order'] }} • {{ $rev['created_at'] }}</div>
                    </td>
                    <td><span class="admin-badge-status is-process">{{ $rev['service_or_menu'] }}</span></td>
                    <td>
                        <strong style="color:#d97706;">{{ str_repeat('★', $rev['rating']) }}</strong>
                        <span style="font-size:0.78rem;color:var(--text-muted);">({{ $rev['rating'] }}/5)</span>
                    </td>
                    <td>
                        <div style="font-size:0.84rem;color:var(--navy-dark);">{{ $rev['comment'] }}</div>
                        @if (!empty($rev['admin_reply']))
                            <div style="margin-top:6px;padding:7px 10px;border-radius:8px;background:#faf7f0;border-left:3px solid var(--gold);font-size:0.78rem;color:#374151;">
                                <strong>Balasan Admin:</strong> {{ $rev['admin_reply'] }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <select class="rev-status-select"
                                style="padding:5px 8px;border-radius:8px;border:1px solid #d9ceba;background:#fff;font-size:0.8rem;font-weight:600;">
                            @foreach (['Menunggu', 'Terbit', 'Disembunyikan'] as $st)
                                <option value="{{ $st }}" {{ $rev['status'] === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        <div style="margin-top:4px;">
                            <span class="admin-badge-status {{ $badgeClass }} rev-status-badge">{{ $rev['status'] }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="admin-action-group">
                            <button type="button" class="admin-btn-sm btn-reply-review" title="Balas Ulasan">
                                <i class="fa-regular fa-comment-dots"></i> Balas
                            </button>
                            <form action="{{ route('admin.ulasan.destroy', $rev['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus ulasan dari {{ addslashes($rev['customer_name']) }}?');">
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
                        Belum ada ulasan pelanggan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL BALAS ULASAN (RATING & KOMENTAR PELANGGAN READ-ONLY) -->
<div id="replyReviewModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="replyModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="replyModalTitle" class="admin-modal-title">Balas Ulasan Pelanggan</h2>
            <button type="button" id="closeReplyModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <div style="padding:12px;border-radius:10px;background:#faf7f0;border:1px solid #e6dfd1;margin-bottom:14px;">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:6px;">
                <strong id="replyCustomerInfo" style="font-size:0.9rem;color:var(--navy-dark);"></strong>
                <span id="replyRatingInfo" style="font-size:0.84rem;font-weight:700;color:#d97706;"></span>
            </div>
            <p id="replyCustomerComment" style="margin:0;font-size:0.85rem;color:#374151;line-height:1.5;"></p>
        </div>

        <form id="replyFormElement" method="POST">
            @csrf
            @method('PATCH')

            <div style="display:grid;gap:12px;">
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Status Publikasi</label>
                    <select name="status" id="replyStatusInput"
                            style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Terbit">Terbit</option>
                        <option value="Disembunyikan">Disembunyikan</option>
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Balasan Admin</label>
                    <textarea name="admin_reply" id="replyTextInput" rows="3" placeholder="Tulis balasan untuk pelanggan..."
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelReplyModalBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Balasan
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
    const searchInput = document.getElementById('reviewSearchInput');
    const rows = Array.from(document.querySelectorAll('#reviewsTable tbody tr[data-id]'));
    const countLabel = document.getElementById('reviewCountLabel');
    const csrfToken = '{{ csrf_token() }}';

    function badgeClassFor(status) {
        switch (status) {
            case 'Terbit': return 'admin-badge-status is-done rev-status-badge';
            case 'Disembunyikan': return 'admin-badge-status is-cancel rev-status-badge';
            default: return 'admin-badge-status is-pending rev-status-badge';
        }
    }

    function applyReviewFilters() {
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
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' ulasan';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeStatus = tab.getAttribute('data-status') || 'Semua';
            applyReviewFilters();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyReviewFilters);

    document.querySelectorAll('.rev-status-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const row = select.closest('tr');
            const id = row.getAttribute('data-id');
            const nextStatus = select.value;

            row.setAttribute('data-status', nextStatus);
            row.setAttribute('data-status-val', nextStatus);
            const badge = row.querySelector('.rev-status-badge');
            if (badge) {
                badge.className = badgeClassFor(nextStatus);
                badge.textContent = nextStatus;
            }

            fetch('/admin/ulasan/' + id + '/status', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: nextStatus })
            }).catch(function (err) {
                console.error('Gagal ubah status ulasan:', err);
            });

            applyReviewFilters();
        });
    });

    const modal = document.getElementById('replyReviewModal');
    const form = document.getElementById('replyFormElement');

    function openModal() {
        if (modal) modal.classList.add('is-open');
    }

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    document.querySelectorAll('.btn-reply-review').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');
            const ratingNum = parseInt(row.getAttribute('data-rating') || '5', 10) || 5;

            form.action = '/admin/ulasan/' + id + '/reply';
            document.getElementById('replyCustomerInfo').textContent =
                (row.getAttribute('data-customer-name') || '') + ' (' + (row.getAttribute('data-service-or-menu') || '') + ')';
            document.getElementById('replyRatingInfo').textContent =
                '★'.repeat(ratingNum) + ' (' + ratingNum + '/5)';
            document.getElementById('replyCustomerComment').textContent =
                '"' + (row.getAttribute('data-comment') || '') + '"';

            document.getElementById('replyStatusInput').value = row.getAttribute('data-status-val') || 'Menunggu';
            document.getElementById('replyTextInput').value = row.getAttribute('data-admin-reply') || '';

            openModal();
        });
    });

    document.getElementById('closeReplyModal')?.addEventListener('click', closeModal);
    document.getElementById('cancelReplyModalBtn')?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });
});
</script>
@endpush