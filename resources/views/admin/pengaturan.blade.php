@extends('layouts.admin')

@section('title', 'Pengaturan Sistem - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-group="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-group="Umum">Umum</button>
    <button type="button" class="admin-subnav-tab" data-group="Operasional">Operasional</button>
    <button type="button" class="admin-subnav-tab" data-group="Pesanan & Pembayaran">Pesanan &amp; Pembayaran</button>
    <button type="button" class="admin-subnav-tab" data-group="Keamanan & Sistem">Keamanan &amp; Sistem</button>
@endsection

@section('content')
@php
    $groups = $groups ?? [];
    $defaults = $defaults ?? [];
    $values = $values ?? [];
    $groupedSettings = collect($defaults)
        ->map(function ($meta, $key) use ($values) {
            return array_merge($meta, [
                'key'   => $key,
                'value' => $values[$key] ?? $meta['value'],
            ]);
        })
        ->groupBy('group');
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Pengaturan Sistem</h1>
        <p class="admin-page-sub">Konfigurasi identitas brand, jam operasional, aturan pesanan, dan preferensi sistem PT. Kusantara.</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <form action="{{ route('admin.pengaturan.reset') }}"
              method="POST"
              style="display:inline;"
              onsubmit="return confirm('Kembalikan seluruh pengaturan ke nilai bawaan?');">
            @csrf
            <button type="submit" class="admin-btn-sm">
                <i class="fa-solid fa-rotate-left"></i> Reset Default
            </button>
        </form>
        <button type="submit" form="settingsMainForm" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan
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

<!-- TOOLBAR PENCARIAN -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="settingsSearchInput"
               class="admin-order-search"
               placeholder="Cari nama pengaturan, keterangan, atau nilai...">
    </div>
    <div id="settingsCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        {{ count($defaults) }} pengaturan tersedia
    </div>
</div>

<form id="settingsMainForm" action="{{ route('admin.pengaturan.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div id="settingsGroupsContainer" style="display:grid;gap:16px;">
        @foreach ($groups as $grp)
            @php $itemsInGroup = $groupedSettings->get($grp, collect()); @endphp
            <section class="settings-group-card"
                     data-group-section="{{ $grp }}"
                     style="background:#fff;border:1px solid #e6dfd1;border-radius:16px;padding:18px;">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;padding-bottom:12px;margin-bottom:14px;border-bottom:1px solid #f1ece1;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span class="admin-badge-status is-process">{{ $grp }}</span>
                        <h2 style="margin:0;font-size:1rem;font-weight:800;color:var(--navy-dark);">
                            Pengaturan {{ $grp }}
                        </h2>
                    </div>
                    <span class="group-visible-count" style="font-size:0.78rem;font-weight:700;color:var(--text-muted);">
                        {{ $itemsInGroup->count() }} item
                    </span>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:14px;">
                    @foreach ($itemsInGroup as $item)
                        @php
                            $k = $item['key'];
                            $val = (string) $item['value'];
                            $isOn = ($val === '1' || strtolower($val) === 'true' || strtolower($val) === 'aktif');
                        @endphp
                        <div class="setting-field-item"
                             data-search="{{ strtolower($item['label'] . ' ' . $item['hint'] . ' ' . $val . ' ' . $grp) }}"
                             style="padding:12px 14px;border-radius:12px;border:1px solid #ece4d5;background:#fcfaf5;display:flex;flex-direction:column;justify-content:space-between;gap:6px;">
                            <div>
                                <label for="set_{{ $k }}" style="display:block;font-size:0.84rem;font-weight:700;color:var(--navy-dark);margin-bottom:4px;">
                                    {{ $item['label'] }}
                                </label>

                                @if ($item['type'] === 'toggle')
                                    <input type="hidden" name="settings[{{ $k }}]" value="0">
                                    <label style="display:inline-flex;align-items:center;gap:8px;margin-top:4px;cursor:pointer;font-size:0.84rem;font-weight:600;color:#374151;">
                                        <input type="checkbox"
                                               id="set_{{ $k }}"
                                               name="settings[{{ $k }}]"
                                               value="1"
                                               {{ $isOn ? 'checked' : '' }}
                                               style="width:16px;height:16px;accent-color:#c88a2b;cursor:pointer;">
                                        <span>{{ $isOn ? 'Aktif' : 'Nonaktif' }}</span>
                                    </label>
                                @elseif ($item['type'] === 'number')
                                    <input type="number"
                                           id="set_{{ $k }}"
                                           name="settings[{{ $k }}]"
                                           value="{{ $val }}"
                                           style="width:100%;padding:8px 11px;border-radius:8px;border:1px solid #d9ceba;background:#fff;font-size:0.88rem;">
                                @else
                                    <input type="text"
                                           id="set_{{ $k }}"
                                           name="settings[{{ $k }}]"
                                           value="{{ $val }}"
                                           style="width:100%;padding:8px 11px;border-radius:8px;border:1px solid #d9ceba;background:#fff;font-size:0.88rem;">
                                @endif
                            </div>

                            <div style="font-size:0.74rem;color:var(--text-muted);line-height:1.35;">
                                {{ $item['hint'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
        <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Semua Pengaturan
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let activeGroup = 'Semua';
    const subnavTabs = document.querySelectorAll('.admin-subnav .admin-subnav-tab');
    const searchInput = document.getElementById('settingsSearchInput');
    const sections = Array.from(document.querySelectorAll('#settingsGroupsContainer .settings-group-card'));
    const allItems = Array.from(document.querySelectorAll('#settingsGroupsContainer .setting-field-item'));
    const countLabel = document.getElementById('settingsCountLabel');

    function applySettingsFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visibleTotal = 0;

        sections.forEach(function (sec) {
            const secGroup = sec.getAttribute('data-group-section') || '';
            const groupMatch = (activeGroup === 'Semua' || secGroup === activeGroup);
            sec.style.display = groupMatch ? '' : 'none';
            if (!groupMatch) return;

            const items = Array.from(sec.querySelectorAll('.setting-field-item'));
            let secVisible = 0;

            items.forEach(function (item) {
                const sr = item.getAttribute('data-search') || '';
                const show = !kw || sr.includes(kw);
                item.style.display = show ? '' : 'none';
                if (show) {
                    secVisible++;
                    visibleTotal++;
                }
            });

            const badge = sec.querySelector('.group-visible-count');
            if (badge) badge.textContent = secVisible + ' item';
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visibleTotal + ' dari ' + allItems.length + ' pengaturan';
        }
    }

    // Perbarui label Aktif/Nonaktif saat checkbox toggle diubah
    document.querySelectorAll('.setting-field-item input[type="checkbox"]').forEach(function (cb) {
        cb.addEventListener('change', function () {
            const span = cb.nextElementSibling;
            if (span) span.textContent = cb.checked ? 'Aktif' : 'Nonaktif';
        });
    });

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeGroup = tab.getAttribute('data-group') || 'Semua';
            applySettingsFilters();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applySettingsFilters);
});
</script>
@endpush