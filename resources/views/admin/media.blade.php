@extends('layouts.admin')

@section('title', 'Pustaka Media per Halaman - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-page="Semua">Semua Halaman</button>
    <button type="button" class="admin-subnav-tab" data-page="Tentang">Tentang</button>
    <button type="button" class="admin-subnav-tab" data-page="Katalog">Katalog</button>
    <button type="button" class="admin-subnav-tab" data-page="Blog">Blog</button>
    <button type="button" class="admin-subnav-tab" data-page="Beranda & Umum">Beranda &amp; Umum</button>
@endsection

@section('content')
@php
    $mediaItems = $mediaItems ?? [];
    $pages = $pages ?? ['Tentang', 'Katalog', 'Blog', 'Beranda & Umum'];
    $totalMedia = count($mediaItems);
    $grouped = collect($mediaItems)->groupBy('page_location');
@endphp

<style>
    .media-page-group {
        background: #fff;
        border: 1px solid #e6dfd1;
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 18px;
    }

    .media-page-group-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding-bottom: 12px;
        margin-bottom: 14px;
        border-bottom: 1px solid #f1ece1;
    }

    .media-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(225px, 1fr));
        gap: 14px;
    }

    .media-card-item {
        background: #fcfaf5;
        border: 1px solid #ece3d2;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.16s ease, box-shadow 0.16s ease, border-color 0.16s ease;
    }

    .media-card-item:hover {
        transform: translateY(-2px);
        border-color: #d7be8e;
        box-shadow: 0 8px 20px rgba(17, 24, 39, 0.06);
    }

    .media-thumb-box {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 10;
        background: #f3ede0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .media-thumb-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.25s ease;
    }

    .media-card-item:hover .media-thumb-box img {
        transform: scale(1.04);
    }
</style>

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Pustaka Media per Halaman</h1>
        <p class="admin-page-sub">Gambar dan aset dikelompokkan berdasarkan halaman agar mudah diatur dan ditinjau.</p>
    </div>
    <button type="button" id="openUploadMediaModal" class="admin-btn-sm">
        <i class="fa-solid fa-cloud-arrow-up"></i> Tambah Media ke Halaman
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

<!-- RINGKASAN JUMLAH MEDIA PER HALAMAN -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(165px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:13px 15px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.76rem;color:var(--text-muted);font-weight:600;">Total Semua Media</div>
        <div style="font-size:1.3rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalMedia }}</div>
    </div>
    @foreach ($pages as $pg)
        <div style="padding:13px 15px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
            <div style="font-size:0.76rem;color:var(--text-muted);font-weight:600;">Halaman {{ $pg }}</div>
            <div style="font-size:1.3rem;font-weight:800;color:#b45309;margin-top:2px;">{{ $grouped->get($pg, collect())->count() }}</div>
        </div>
    @endforeach
</div>

<!-- TOOLBAR PENCARIAN -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="mediaSearchInput"
               class="admin-order-search"
               placeholder="Cari nama gambar, halaman, atau keterangan...">
    </div>
    <div id="mediaCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalMedia }} aset media
    </div>
</div>

<!-- KELOMPOK MEDIA PER HALAMAN -->
<div id="mediaGroupsContainer">
    @foreach ($pages as $pg)
        @php
            $itemsInPage = $grouped->get($pg, collect());
        @endphp
        <section class="media-page-group" data-page-section="{{ $pg }}">
            <div class="media-page-group-head">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="admin-badge-status is-process">{{ $pg }}</span>
                    <h2 style="margin:0;font-size:1rem;font-weight:800;color:var(--navy-dark);">
                        Gambar di Halaman {{ $pg }}
                    </h2>
                </div>
                <span class="group-visible-badge" style="font-size:0.78rem;font-weight:700;color:var(--text-muted);">
                    {{ $itemsInPage->count() }} item
                </span>
            </div>

            <div class="media-cards-grid">
                @forelse ($itemsInPage as $item)
                    <article class="media-card-item"
                             data-page="{{ $item['page_location'] }}"
                             data-search="{{ strtolower($item['title'] . ' ' . $item['page_location'] . ' ' . $item['alt_text'] . ' ' . $item['file_path']) }}">
                        <div>
                            <div class="media-thumb-box">
                                @if ($item['is_image'] && $item['url'] !== '')
                                    <img src="{{ $item['url'] }}"
                                         alt="{{ $item['alt_text'] ?: $item['title'] }}"
                                         loading="lazy"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <div style="display:none;flex-direction:column;align-items:center;justify-content:center;gap:4px;color:#78716c;padding:12px;text-align:center;">
                                        <i class="fa-regular fa-image" style="font-size:1.5rem;"></i>
                                        <span style="font-size:0.72rem;font-weight:600;">{{ basename($item['file_path']) }}</span>
                                    </div>
                                @else
                                    <div style="color:#6b7280;text-align:center;padding:12px;">
                                        <i class="fa-regular fa-file-lines" style="font-size:1.8rem;margin-bottom:4px;display:block;"></i>
                                        <span style="font-size:0.74rem;font-weight:700;">DOKUMEN</span>
                                    </div>
                                @endif
                            </div>

                            <div style="padding:12px;">
                                <div style="font-weight:700;font-size:0.88rem;color:var(--navy-dark);line-height:1.35;margin-bottom:4px;">
                                    {{ $item['title'] }}
                                </div>
                                <div style="font-size:0.74rem;color:var(--text-muted);display:flex;justify-content:space-between;gap:6px;">
                                    <span>{{ $item['size_label'] }}</span>
                                    <span>{{ $item['created_at'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div style="padding:9px 12px;border-top:1px solid #ece3d2;background:#fff;display:flex;justify-content:space-between;align-items:center;gap:6px;">
                            <button type="button"
                                    class="admin-btn-sm btn-copy-media-url"
                                    data-url="{{ $item['url'] }}"
                                    title="Salin URL">
                                <i class="fa-regular fa-copy"></i> URL
                            </button>

                            <form action="{{ route('admin.media.destroy', $item['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus {{ addslashes($item['title']) }} dari halaman {{ $item['page_location'] }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-sm is-danger" title="Hapus">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="group-empty-msg" style="grid-column:1/-1;padding:18px;border-radius:12px;border:1px dashed #e0d5c1;background:#fcfaf5;text-align:center;font-size:0.84rem;color:var(--text-muted);">
                        Belum ada gambar yang dikelompokkan untuk halaman <strong>{{ $pg }}</strong>.
                    </div>
                @endforelse
            </div>
        </section>
    @endforeach
</div>

<!-- MODAL TAMBAH MEDIA KE HALAMAN -->
<div id="uploadMediaModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="mediaModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="mediaModalTitle" class="admin-modal-title">Tambah Media ke Halaman</h2>
            <button type="button" id="closeUploadMediaModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;gap:12px;">
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Kelompok Halaman</label>
                    <select name="page_location" id="mediaPageSelect"
                            style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                        @foreach ($pages as $pg)
                            <option value="{{ $pg }}">{{ $pg }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Judul / Nama Gambar</label>
                    <input type="text" name="title" placeholder="Contoh: Foto Menu Rendang Sapi"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Unggah File (JPG, PNG, WEBP, SVG, PDF — maks 5MB)</label>
                    <input type="file" name="file" accept=".jpg,.jpeg,.png,.gif,.webp,.svg,.pdf"
                           style="width:100%;padding:8px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Atau Masukkan URL / Path Gambar</label>
                    <input type="text" name="media_url" placeholder="https://... atau images/nama-file.jpg"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Keterangan / Alt Text (Opsional)</label>
                    <input type="text" name="alt_text" placeholder="Deskripsi gambar di halaman tersebut"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelUploadMediaBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Media
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let activePage = 'Semua';
    const subnavTabs = document.querySelectorAll('.admin-subnav .admin-subnav-tab');
    const searchInput = document.getElementById('mediaSearchInput');
    const sections = Array.from(document.querySelectorAll('#mediaGroupsContainer .media-page-group'));
    const allCards = Array.from(document.querySelectorAll('#mediaGroupsContainer .media-card-item'));
    const countLabel = document.getElementById('mediaCountLabel');
    const pageSelectInModal = document.getElementById('mediaPageSelect');

    function applyMediaFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let totalVisibleCards = 0;

        sections.forEach(function (sec) {
            const secPage = sec.getAttribute('data-page-section') || '';
            const pageMatch = (activePage === 'Semua' || secPage === activePage);
            sec.style.display = pageMatch ? '' : 'none';

            if (!pageMatch) return;

            const cardsInSec = Array.from(sec.querySelectorAll('.media-card-item'));
            let secVisible = 0;

            cardsInSec.forEach(function (card) {
                const sr = card.getAttribute('data-search') || '';
                const show = !kw || sr.includes(kw);
                card.style.display = show ? '' : 'none';
                if (show) {
                    secVisible++;
                    totalVisibleCards++;
                }
            });

            const badge = sec.querySelector('.group-visible-badge');
            if (badge) {
                badge.textContent = secVisible + ' item';
            }

            const emptyMsg = sec.querySelector('.group-empty-msg');
            if (emptyMsg && cardsInSec.length > 0) {
                emptyMsg.style.display = secVisible === 0 ? '' : 'none';
            }
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + totalVisibleCards + ' dari ' + allCards.length + ' aset media';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activePage = tab.getAttribute('data-page') || 'Semua';

            if (pageSelectInModal && activePage !== 'Semua') {
                pageSelectInModal.value = activePage;
            }

            applyMediaFilters();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyMediaFilters);

    document.querySelectorAll('.btn-copy-media-url').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const url = btn.getAttribute('data-url') || '';
            if (!url) return;
            navigator.clipboard?.writeText(url).then(function () {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin';
                setTimeout(function () { btn.innerHTML = orig; }, 1400);
            });
        });
    });

    const modal = document.getElementById('uploadMediaModal');
    function openModal() { if (modal) modal.classList.add('is-open'); }
    function closeModal() { if (modal) modal.classList.remove('is-open'); }

    document.getElementById('openUploadMediaModal')?.addEventListener('click', openModal);
    document.getElementById('closeUploadMediaModal')?.addEventListener('click', closeModal);
    document.getElementById('cancelUploadMediaBtn')?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });
});
</script>
@endpush