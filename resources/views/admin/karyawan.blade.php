@extends('layouts.admin')

@section('title', 'Manajemen Karyawan - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-status="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-status="Aktif">Aktif</button>
    <button type="button" class="admin-subnav-tab" data-status="Cuti">Cuti</button>
    <button type="button" class="admin-subnav-tab" data-status="Nonaktif">Nonaktif</button>
@endsection

@section('content')
@php
    $employees = $employees ?? [];
    $statuses = $statuses ?? ['Aktif', 'Cuti', 'Nonaktif'];
    $totalEmp = count($employees);
    $activeEmp = collect($employees)->where('status', 'Aktif')->count();
    $leaveEmp = collect($employees)->where('status', 'Cuti')->count();
    $inactiveEmp = collect($employees)->where('status', 'Nonaktif')->count();
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Manajemen Karyawan</h1>
        <p class="admin-page-sub">Kelola data staf, jabatan, divisi, shift kerja, dan status kepegawaian PT. Kusantara.</p>
    </div>
    <button type="button" id="openAddEmployeeModal" class="admin-btn-sm">
        <i class="fa-solid fa-user-plus"></i> Tambah Karyawan
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

<!-- RINGKASAN KARYAWAN -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Karyawan</div>
        <div style="font-size:1.35rem;font-weight:800;color:var(--navy-dark);margin-top:2px;">{{ $totalEmp }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Aktif Bertugas</div>
        <div style="font-size:1.35rem;font-weight:800;color:#047857;margin-top:2px;">{{ $activeEmp }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Sedang Cuti</div>
        <div style="font-size:1.35rem;font-weight:800;color:#b45309;margin-top:2px;">{{ $leaveEmp }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Nonaktif</div>
        <div style="font-size:1.35rem;font-weight:800;color:#b91c1c;margin-top:2px;">{{ $inactiveEmp }}</div>
    </div>
</div>

<!-- TOOLBAR PENCARIAN -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="employeeSearchInput"
               class="admin-order-search"
               placeholder="Cari nama, NIP/kode, jabatan, divisi, atau kontak...">
    </div>
    <div id="employeeCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalEmp }} karyawan
    </div>
</div>

<!-- TABEL KARYAWAN -->
<div class="admin-table-card">
    <div class="admin-table-wrap">
        <table class="admin-table" id="employeeTable">
            <thead>
                <tr>
                    <th>ID / Nama</th>
                    <th>Jabatan &amp; Divisi</th>
                    <th>Shift</th>
                    <th>Kontak</th>
                    <th>Bergabung</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $emp)
                    @php
                        $st = $emp['status'];
                        $badgeClass = $st === 'Aktif'
                            ? 'is-done'
                            : ($st === 'Cuti' ? 'is-process' : 'is-cancel');
                    @endphp
                    <tr class="employee-row"
                        data-status="{{ $st }}"
                        data-search="{{ strtolower($emp['employee_code'] . ' ' . $emp['name'] . ' ' . $emp['role_title'] . ' ' . $emp['department'] . ' ' . $emp['phone'] . ' ' . $emp['email']) }}">
                        <td>
                            <div style="font-weight:700;color:var(--navy-dark);">{{ $emp['name'] }}</div>
                            <div style="font-size:0.74rem;color:var(--text-muted);">{{ $emp['employee_code'] }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $emp['role_title'] }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $emp['department'] }}</div>
                        </td>
                        <td>{{ $emp['shift'] }}</td>
                        <td>
                            <div style="font-size:0.82rem;">{{ $emp['phone'] }}</div>
                            <div style="font-size:0.74rem;color:var(--text-muted);">{{ $emp['email'] }}</div>
                        </td>
                        <td>{{ $emp['joined_at'] }}</td>
                        <td>
                            <form action="{{ route('admin.karyawan.status', $emp['id']) }}" method="POST" style="display:inline-flex;align-items:center;gap:6px;">
                                @csrf
                                @method('PATCH')
                                <span class="admin-badge-status {{ $badgeClass }}">{{ $st }}</span>
                                <select name="status"
                                        onchange="this.form.submit()"
                                        style="padding:4px 8px;border-radius:7px;border:1px solid #d9ceba;background:#fff;font-size:0.76rem;cursor:pointer;">
                                    @foreach ($statuses as $opt)
                                        <option value="{{ $opt }}" {{ $st === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td style="text-align:right;white-space:nowrap;">
                            <button type="button"
                                    class="admin-btn-sm btn-edit-employee"
                                    data-id="{{ $emp['id'] }}"
                                    data-code="{{ $emp['employee_code'] }}"
                                    data-name="{{ $emp['name'] }}"
                                    data-role="{{ $emp['role_title'] }}"
                                    data-dept="{{ $emp['department'] }}"
                                    data-shift="{{ $emp['shift'] }}"
                                    data-phone="{{ $emp['phone'] === '-' ? '' : $emp['phone'] }}"
                                    data-email="{{ $emp['email'] === '-' ? '' : $emp['email'] }}"
                                    data-status="{{ $emp['status'] }}"
                                    data-joined="{{ $emp['joined_at'] === '-' ? '' : $emp['joined_at'] }}">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>

                            <form action="{{ route('admin.karyawan.destroy', $emp['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus data karyawan {{ addslashes($emp['name']) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-sm is-danger">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted);">
                            Belum ada data karyawan. Klik <strong>Tambah Karyawan</strong> untuk menambahkan data baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH / EDIT KARYAWAN -->
<div id="employeeModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="employeeModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="employeeModalTitle" class="admin-modal-title">Tambah Karyawan</h2>
            <button type="button" id="closeEmployeeModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form id="employeeForm" action="{{ route('admin.karyawan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="employeeFormMethod" value="POST">

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Kode / NIP</label>
                    <input type="text" name="employee_code" id="empCode" placeholder="Otomatis (mis. KRY-005)"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Nama Lengkap *</label>
                    <input type="text" name="name" id="empName" required placeholder="Nama karyawan"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Jabatan *</label>
                    <input type="text" name="role_title" id="empRole" required placeholder="Contoh: Chef / Kasir / Kurir"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Divisi / Departemen</label>
                    <input type="text" name="department" id="empDept" value="Operasional"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Shift Kerja</label>
                    <select name="shift" id="empShift"
                            style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                        <option value="Pagi">Pagi</option>
                        <option value="Siang">Siang</option>
                        <option value="Malam">Malam</option>
                        <option value="Penuh Waktu">Penuh Waktu</option>
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Status</label>
                    <select name="status" id="empStatus"
                            style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                        @foreach ($statuses as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">No. Telepon / WA</label>
                    <input type="text" name="phone" id="empPhone" placeholder="0812-xxxx-xxxx"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Email</label>
                    <input type="email" name="email" id="empEmail" placeholder="nama@kusantara.id"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div style="grid-column:1/-1;">
                    <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:4px;">Tanggal Bergabung</label>
                    <input type="date" name="joined_at" id="empJoined" value="{{ now()->toDateString() }}"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelEmployeeBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Karyawan
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
    const searchInput = document.getElementById('employeeSearchInput');
    const rows = Array.from(document.querySelectorAll('#employeeTable tbody tr.employee-row'));
    const countLabel = document.getElementById('employeeCountLabel');

    function applyEmployeeFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visible = 0;

        rows.forEach(function (row) {
            const st = row.getAttribute('data-status') || 'Aktif';
            const sr = row.getAttribute('data-search') || '';
            const show = (activeStatus === 'Semua' || st === activeStatus) && (!kw || sr.includes(kw));
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' karyawan';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeStatus = tab.getAttribute('data-status') || 'Semua';
            applyEmployeeFilters();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyEmployeeFilters);

    const modal = document.getElementById('employeeModal');
    const form = document.getElementById('employeeForm');
    const methodInput = document.getElementById('employeeFormMethod');
    const modalTitle = document.getElementById('employeeModalTitle');
    const storeUrl = @json(route('admin.karyawan.store'));
    const updateBaseUrl = @json(url('/admin/karyawan'));

    function openAddModal() {
        if (!modal || !form) return;
        form.reset();
        form.action = storeUrl;
        if (methodInput) methodInput.value = 'POST';
        if (modalTitle) modalTitle.textContent = 'Tambah Karyawan';
        const joinedEl = document.getElementById('empJoined');
        if (joinedEl) joinedEl.value = @json(now()->toDateString());
        modal.classList.add('is-open');
    }

    function openEditModal(btn) {
        if (!modal || !form) return;
        const id = btn.getAttribute('data-id');
        form.action = updateBaseUrl + '/' + id;
        if (methodInput) methodInput.value = 'PUT';
        if (modalTitle) modalTitle.textContent = 'Edit Data Karyawan';

        document.getElementById('empCode').value = btn.getAttribute('data-code') || '';
        document.getElementById('empName').value = btn.getAttribute('data-name') || '';
        document.getElementById('empRole').value = btn.getAttribute('data-role') || '';
        document.getElementById('empDept').value = btn.getAttribute('data-dept') || 'Operasional';
        document.getElementById('empShift').value = btn.getAttribute('data-shift') || 'Pagi';
        document.getElementById('empPhone').value = btn.getAttribute('data-phone') || '';
        document.getElementById('empEmail').value = btn.getAttribute('data-email') || '';
        document.getElementById('empStatus').value = btn.getAttribute('data-status') || 'Aktif';
        document.getElementById('empJoined').value = btn.getAttribute('data-joined') || '';

        modal.classList.add('is-open');
    }

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    document.getElementById('openAddEmployeeModal')?.addEventListener('click', openAddModal);
    document.querySelectorAll('.btn-edit-employee').forEach(function (btn) {
        btn.addEventListener('click', function () { openEditModal(btn); });
    });
    document.getElementById('closeEmployeeModal')?.addEventListener('click', closeModal);
    document.getElementById('cancelEmployeeBtn')?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });
});
</script>
@endpush