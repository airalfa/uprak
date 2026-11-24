@extends('layouts.admin')

@section('title', 'Manajemen Hak Akses - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-status="Semua">Semua Role</button>
    <button type="button" class="admin-subnav-tab" data-status="Aktif">Aktif</button>
    <button type="button" class="admin-subnav-tab" data-status="Nonaktif">Nonaktif</button>
@endsection

@section('content')
@php
    $roles = $roles ?? [];
    $modules = $modules ?? [];
    $totalModules = count($modules);
    $totalRoles = count($roles);
    $activeRoles = collect($roles)->where('status', 'Aktif')->count();
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Manajemen Hak Akses (RBAC)</h1>
        <p class="admin-page-sub">Atur izin akses modul panel admin untuk setiap peran / role pengguna PT. Kusantara.</p>
    </div>
    <button type="button" id="openAddRoleModal" class="admin-btn-sm">
        <i class="fa-solid fa-shield-halved"></i> Tambah Role Baru
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

<!-- RINGKASAN RBAC -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(175px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Role</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalRoles }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Role Aktif</div>
        <div style="font-size:1.35rem;font-weight:800;color:#047857;margin-top:2px;">{{ $activeRoles }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Modul Terdaftar</div>
        <div style="font-size:1.35rem;font-weight:800;color:#b45309;margin-top:2px;">{{ $totalModules }} Modul</div>
    </div>
</div>

<!-- TOOLBAR PENCARIAN -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="roleSearchInput"
               class="admin-order-search"
               placeholder="Cari nama role, deskripsi, atau nama modul...">
    </div>
    <div id="roleCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalRoles }} role
    </div>
</div>

<!-- DAFTAR KARTU MATRIX HAK AKSES PER ROLE -->
<div id="rolesContainer" style="display:grid;gap:16px;">
    @forelse ($roles as $role)
        @php
            $rolePerms = $role['permissions'] ?? [];
            $permCount = count($rolePerms);
            $isActive = ($role['status'] ?? 'Aktif') === 'Aktif';
            $moduleSearchBlob = strtolower($role['role_name'] . ' ' . $role['description'] . ' ' . implode(' ', array_map(fn($k) => ($modules[$k] ?? '') . ' ' . $k, $rolePerms)));
        @endphp
        <div class="role-card"
             data-status="{{ $role['status'] }}"
             data-search="{{ $moduleSearchBlob }}"
             style="background:#fff;border:1px solid #e6dfd1;border-radius:16px;padding:18px;">
            <form action="{{ route('admin.hak_akses.update', $role['id']) }}" method="POST" class="role-perm-form">
                @csrf
                @method('PUT')

                <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-start;gap:12px;padding-bottom:14px;margin-bottom:14px;border-bottom:1px solid #f1ece1;">
                    <div style="flex:1;min-width:240px;">
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                            <input type="text"
                                   name="role_name"
                                   value="{{ $role['role_name'] }}"
                                   required
                                   style="font-size:1rem;font-weight:800;color:var(--navy-dark);padding:5px 10px;border-radius:8px;border:1px solid #dfd3be;background:#fcfaf5;">
                            <span class="admin-badge-status {{ $isActive ? 'is-done' : 'is-cancel' }}">
                                {{ $role['status'] }}
                            </span>
                            <span class="role-perm-counter" style="font-size:0.78rem;font-weight:700;color:#b45309;">
                                {{ $permCount }} / {{ $totalModules }} modul aktif
                            </span>
                        </div>
                        <input type="text"
                               name="description"
                               value="{{ $role['description'] }}"
                               placeholder="Deskripsi peran / tanggung jawab role"
                               style="width:100%;max-width:560px;font-size:0.82rem;color:#4b5563;padding:6px 10px;border-radius:8px;border:1px solid #e8e0d0;background:#fff;margin-top:4px;">
                    </div>

                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                        <button type="button" class="admin-btn-sm btn-select-all-perms">Pilih Semua</button>
                        <button type="button" class="admin-btn-sm btn-clear-all-perms">Kosongkan</button>
                        <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Akses
                        </button>
                    </div>
                </div>

                <!-- GRID CHECKBOX MODUL -->
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(195px,1fr));gap:10px;">
                    @foreach ($modules as $modKey => $modLabel)
                        @php $checked = in_array($modKey, $rolePerms, true); @endphp
                        <label style="display:flex;align-items:center;gap:8px;padding:9px 11px;border-radius:10px;border:1px solid {{ $checked ? '#e5c687' : '#ece5d8' }};background:{{ $checked ? '#fef9ec' : '#fcfaf5' }};cursor:pointer;font-size:0.84rem;font-weight:600;color:#1f2937;">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $modKey }}"
                                   class="role-perm-checkbox"
                                   {{ $checked ? 'checked' : '' }}
                                   style="width:15px;height:15px;accent-color:#c88a2b;cursor:pointer;">
                            <span>{{ $modLabel }}</span>
                        </label>
                    @endforeach
                </div>
            </form>

            <!-- AKSI TAMBAHAN ROLE (TOGGLE STATUS & HAPUS) -->
            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:14px;padding-top:12px;border-top:1px solid #f5f0e6;">
                <form action="{{ route('admin.hak_akses.toggle-status', $role['id']) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="admin-btn-sm">
                        <i class="fa-solid fa-power-off"></i> {{ $isActive ? 'Nonaktifkan Role' : 'Aktifkan Role' }}
                    </button>
                </form>

                <form action="{{ route('admin.hak_akses.destroy', $role['id']) }}"
                      method="POST"
                      style="display:inline;"
                      onsubmit="return confirm('Hapus role {{ addslashes($role['role_name']) }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-sm is-danger">
                        <i class="fa-solid fa-trash"></i> Hapus Role
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div style="text-align:center;padding:32px;background:#fff;border:1px solid #e6dfd1;border-radius:16px;color:var(--text-muted);">
            Belum ada role terdaftar. Klik <strong>Tambah Role Baru</strong> untuk membuat peran akses.
        </div>
    @endforelse
</div>

<!-- MODAL TAMBAH ROLE BARU -->
<div id="addRoleModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="addRoleModalTitle">
    <div class="admin-modal-box" style="max-width:620px;">
        <div class="admin-modal-head">
            <h2 id="addRoleModalTitle" class="admin-modal-title">Tambah Role Baru</h2>
            <button type="button" id="closeAddRoleModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form action="{{ route('admin.hak_akses.store') }}" method="POST">
            @csrf

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:12px;">
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Nama Role *</label>
                    <input type="text" name="role_name" required placeholder="Contoh: Supervisor Cabang"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Status</label>
                    <select name="status"
                            style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Deskripsi Peran</label>
                <input type="text" name="description" placeholder="Ringkasan tugas atau cakupan akses role"
                       style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
            </div>

            <div style="margin-bottom:8px;font-size:0.82rem;font-weight:700;color:var(--navy-dark);">
                Pilih Modul yang Diizinkan
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(165px,1fr));gap:8px;max-height:240px;overflow-y:auto;padding:4px;">
                @foreach ($modules as $modKey => $modLabel)
                    <label style="display:flex;align-items:center;gap:7px;padding:7px 9px;border-radius:8px;border:1px solid #ece5d8;background:#fcfaf5;cursor:pointer;font-size:0.8rem;font-weight:600;">
                        <input type="checkbox" name="permissions[]" value="{{ $modKey }}" style="accent-color:#c88a2b;">
                        <span>{{ $modLabel }}</span>
                    </label>
                @endforeach
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelAddRoleBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Role
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
    const totalModules = {{ $totalModules }};
    const subnavTabs = document.querySelectorAll('.admin-subnav .admin-subnav-tab');
    const searchInput = document.getElementById('roleSearchInput');
    const cards = Array.from(document.querySelectorAll('#rolesContainer .role-card'));
    const countLabel = document.getElementById('roleCountLabel');

    function applyRoleFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visible = 0;

        cards.forEach(function (card) {
            const st = card.getAttribute('data-status') || 'Aktif';
            const sr = card.getAttribute('data-search') || '';
            const show = (activeStatus === 'Semua' || st === activeStatus) && (!kw || sr.includes(kw));
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + cards.length + ' role';
        }
    }

    function syncCardVisual(card) {
        const boxes = Array.from(card.querySelectorAll('.role-perm-checkbox'));
        let checkedCount = 0;

        boxes.forEach(function (box) {
            const label = box.closest('label');
            if (box.checked) {
                checkedCount++;
                if (label) {
                    label.style.borderColor = '#e5c687';
                    label.style.background = '#fef9ec';
                }
            } else if (label) {
                label.style.borderColor = '#ece5d8';
                label.style.background = '#fcfaf5';
            }
        });

        const counter = card.querySelector('.role-perm-counter');
        if (counter) {
            counter.textContent = checkedCount + ' / ' + totalModules + ' modul aktif';
        }
    }

    cards.forEach(function (card) {
        card.querySelectorAll('.role-perm-checkbox').forEach(function (box) {
            box.addEventListener('change', function () { syncCardVisual(card); });
        });

        card.querySelector('.btn-select-all-perms')?.addEventListener('click', function () {
            card.querySelectorAll('.role-perm-checkbox').forEach(function (box) { box.checked = true; });
            syncCardVisual(card);
        });

        card.querySelector('.btn-clear-all-perms')?.addEventListener('click', function () {
            card.querySelectorAll('.role-perm-checkbox').forEach(function (box) { box.checked = false; });
            syncCardVisual(card);
        });
    });

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeStatus = tab.getAttribute('data-status') || 'Semua';
            applyRoleFilters();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyRoleFilters);

    const modal = document.getElementById('addRoleModal');
    function openModal() { if (modal) modal.classList.add('is-open'); }
    function closeModal() { if (modal) modal.classList.remove('is-open'); }

    document.getElementById('openAddRoleModal')?.addEventListener('click', openModal);
    document.getElementById('closeAddRoleModal')?.addEventListener('click', closeModal);
    document.getElementById('cancelAddRoleBtn')?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });
});
</script>
@endpush