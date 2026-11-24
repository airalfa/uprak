@extends('layouts.admin')

@section('title', 'Keuangan & Arus Kas - PT. Kusantara')

@section('subnav')
    <button type="button" class="admin-subnav-tab is-active" data-filter="Semua">Semua</button>
    <button type="button" class="admin-subnav-tab" data-filter="Pemasukan">Pemasukan</button>
    <button type="button" class="admin-subnav-tab" data-filter="Pengeluaran">Pengeluaran</button>
    <button type="button" class="admin-subnav-tab" data-filter="Lunas">Lunas</button>
    <button type="button" class="admin-subnav-tab" data-filter="Tertunda">Tertunda</button>
@endsection

@section('content')
@php
    $transactions = $transactions ?? [];
    $totalIncome = collect($transactions)->where('type', 'income')->sum(fn($t) => (int) $t['amount']);
    $totalExpense = collect($transactions)->where('type', 'expense')->sum(fn($t) => (int) $t['amount']);
    $netBalance = $totalIncome - $totalExpense;
    $totalCount = count($transactions);
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Keuangan &amp; Arus Kas</h1>
        <p class="admin-page-sub">Pantau pemasukan pesanan otomatis dan catat pengeluaran operasional PT. Kusantara.</p>
    </div>
    <button type="button" id="openAddFinanceModal" class="admin-btn-sm">
        <i class="fa-solid fa-plus"></i> Catat Transaksi
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

<!-- RINGKASAN ARUS KAS -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;margin-bottom:16px;">
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Pemasukan</div>
        <div style="font-size:1.35rem;font-weight:800;color:#047857;margin-top:2px;">+Rp{{ number_format($totalIncome, 0, ',', '.') }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Total Pengeluaran</div>
        <div style="font-size:1.35rem;font-weight:800;color:#b91c1c;margin-top:2px;">-Rp{{ number_format($totalExpense, 0, ',', '.') }}</div>
    </div>
    <div style="padding:14px 16px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;">
        <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">Saldo Bersih</div>
        <div style="font-size:1.35rem;font-weight:800;color:{{ $netBalance >= 0 ? 'var(--navy-dark)' : '#b91c1c' }};margin-top:2px;">
            {{ $netBalance < 0 ? '-' : '' }}Rp{{ number_format(abs($netBalance), 0, ',', '.') }}
        </div>
    </div>
</div>

<!-- TOOLBAR PENCARIAN & TANGGAL -->
<div class="admin-order-toolbar">
    <div class="admin-order-filters">
        <input type="text"
               id="financeSearchInput"
               class="admin-order-search"
               placeholder="Cari kode, judul transaksi, kategori, atau metode...">

        <input type="date"
               id="financeDateFilter"
               style="padding:8px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;font-size:0.84rem;">
    </div>
    <div id="financeCountLabel" style="font-size:0.82rem;font-weight:600;color:var(--text-muted);">
        Menampilkan {{ $totalCount }} transaksi
    </div>
</div>

<!-- TABEL TRANSAKSI KEUANGAN -->
<div class="admin-table-wrap">
    <table class="admin-table" id="financeTable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode &amp; Jenis</th>
                <th>Uraian &amp; Kategori</th>
                <th>Tanggal</th>
                <th>Metode</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $tx)
                <tr data-id="{{ $tx['id'] }}"
                    data-type-label="{{ $tx['type_label'] }}"
                    data-status="{{ $tx['status'] }}"
                    data-date="{{ $tx['transaction_date'] }}"
                    data-search="{{ strtolower($tx['reference_code'] . ' ' . $tx['type_label'] . ' ' . $tx['category'] . ' ' . $tx['title'] . ' ' . $tx['payment_method'] . ' ' . $tx['notes']) }}"
                    data-type="{{ $tx['type'] }}"
                    data-category="{{ $tx['category'] }}"
                    data-title="{{ $tx['title'] }}"
                    data-payment-method="{{ $tx['payment_method'] }}"
                    data-amount="{{ $tx['amount'] }}"
                    data-transaction-date="{{ $tx['transaction_date'] }}"
                    data-notes="{{ $tx['notes'] }}">
                    <td>#{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:800;color:var(--navy-dark);">{{ $tx['reference_code'] }}</div>
                        <span class="admin-badge-status {{ $tx['type'] === 'income' ? 'is-done' : 'is-cancel' }}">
                            {{ $tx['type_label'] }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:700;color:var(--navy-dark);">{{ $tx['title'] }}</div>
                        <div style="font-size:0.78rem;color:var(--text-muted);">{{ $tx['category'] }}</div>
                        @if (!empty($tx['notes']))
                            <div style="font-size:0.75rem;color:#78716c;margin-top:2px;">{{ \Illuminate\Support\Str::limit($tx['notes'], 55) }}</div>
                        @endif
                    </td>
                    <td>{{ $tx['transaction_date'] }}</td>
                    <td>{{ $tx['payment_method'] }}</td>
                    <td>
                        <strong style="color:{{ $tx['type'] === 'income' ? '#047857' : '#b91c1c' }};">
                            {{ $tx['type'] === 'income' ? '+' : '-' }}Rp{{ number_format($tx['amount'], 0, ',', '.') }}
                        </strong>
                    </td>
                    <td>
                        <button type="button"
                                class="admin-badge-status {{ $tx['status'] === 'Lunas' ? 'is-done' : 'is-pending' }} btn-toggle-finance-status"
                                style="cursor:pointer;border:none;">
                            {{ $tx['status'] }}
                        </button>
                    </td>
                    <td>
                        <div class="admin-action-group">
                            <button type="button" class="admin-btn-sm btn-edit-finance" title="Edit Transaksi">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.keuangan.destroy', $tx['id']) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus transaksi {{ addslashes($tx['reference_code']) }}?');">
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
                    <td colspan="8" style="text-align:center;padding:28px 16px;color:var(--text-muted);">
                        Belum ada transaksi keuangan. Pesanan akan masuk otomatis atau klik <strong>Catat Transaksi</strong> untuk menambah arus kas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH / EDIT TRANSAKSI KEUANGAN -->
<div id="financeFormModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="financeModalTitle">
    <div class="admin-modal-box">
        <div class="admin-modal-head">
            <h2 id="financeModalTitle" class="admin-modal-title">Catat Transaksi Keuangan</h2>
            <button type="button" id="closeFinanceModal" class="admin-modal-close" aria-label="Tutup">&times;</button>
        </div>

        <form id="financeFormElement" action="{{ route('admin.keuangan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="financeFormMethod" value="POST">

            <div style="display:grid;gap:12px;">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Jenis Arus Kas</label>
                        <select name="type" id="finTypeInput"
                                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                            <option value="expense">Pengeluaran</option>
                            <option value="income">Pemasukan</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Kategori</label>
                        <input type="text" name="category" id="finCategoryInput" value="Bahan Baku & Stok" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Status</label>
                        <select name="status" id="finStatusInput"
                                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;background:#fff;">
                            <option value="Lunas">Lunas</option>
                            <option value="Tertunda">Tertunda</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Uraian / Judul Transaksi</label>
                    <input type="text" name="title" id="finTitleInput" placeholder="Contoh: Belanja beras & bumbu dapur" required
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Nominal (Rp)</label>
                        <input type="number" name="amount" id="finAmountInput" min="0" step="100" value="0" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Tanggal</label>
                        <input type="date" name="transaction_date" id="finDateInput" value="{{ now()->toDateString() }}" required
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Metode Pembayaran</label>
                        <input type="text" name="payment_method" id="finPaymentInput" value="Transfer Bank"
                               style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Catatan (Opsional)</label>
                    <textarea name="notes" id="finNotesInput" rows="2"
                              style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;"></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
                <button type="button" id="cancelFinanceModalBtn" class="admin-btn-sm">Batal</button>
                <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);">
                    <i class="fa-solid fa-check"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let activeFilter = 'Semua';
    const subnavTabs = document.querySelectorAll('.admin-subnav .admin-subnav-tab');
    const searchInput = document.getElementById('financeSearchInput');
    const dateFilter = document.getElementById('financeDateFilter');
    const rows = Array.from(document.querySelectorAll('#financeTable tbody tr[data-id]'));
    const countLabel = document.getElementById('financeCountLabel');
    const csrfToken = '{{ csrf_token() }}';

    function applyFinanceFilters() {
        const kw = (searchInput ? searchInput.value : '').trim().toLowerCase();
        const dt = dateFilter ? dateFilter.value : '';
        let visible = 0;

        rows.forEach(function (row) {
            const typeLbl = row.getAttribute('data-type-label') || '';
            const st = row.getAttribute('data-status') || '';
            const tDate = row.getAttribute('data-date') || '';
            const sr = row.getAttribute('data-search') || '';

            const matchTab = activeFilter === 'Semua'
                || typeLbl === activeFilter
                || st === activeFilter;

            const show = matchTab && (!dt || tDate === dt) && (!kw || sr.includes(kw));
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countLabel) {
            countLabel.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' transaksi';
        }
    }

    subnavTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            subnavTabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeFilter = tab.getAttribute('data-filter') || 'Semua';
            applyFinanceFilters();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyFinanceFilters);
    if (dateFilter) dateFilter.addEventListener('change', applyFinanceFilters);

    document.querySelectorAll('.btn-toggle-finance-status').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');

            fetch('/admin/keuangan/' + id + '/toggle-status', {
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
                btn.textContent = data.status;
                btn.className = 'admin-badge-status ' + (data.status === 'Lunas' ? 'is-done' : 'is-pending') + ' btn-toggle-finance-status';
                applyFinanceFilters();
            })
            .catch(function (err) {
                console.error('Gagal ubah status transaksi:', err);
            });
        });
    });

    const modal = document.getElementById('financeFormModal');
    const form = document.getElementById('financeFormElement');
    const methodInput = document.getElementById('financeFormMethod');
    const titleEl = document.getElementById('financeModalTitle');

    function openModal() {
        if (modal) modal.classList.add('is-open');
    }

    function closeModal() {
        if (modal) modal.classList.remove('is-open');
    }

    const addBtn = document.getElementById('openAddFinanceModal');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            titleEl.textContent = 'Catat Transaksi Keuangan';
            form.action = "{{ route('admin.keuangan.store') }}";
            methodInput.value = 'POST';
            form.reset();
            document.getElementById('finTypeInput').value = 'expense';
            document.getElementById('finCategoryInput').value = 'Bahan Baku & Stok';
            document.getElementById('finStatusInput').value = 'Lunas';
            document.getElementById('finAmountInput').value = '0';
            document.getElementById('finDateInput').value = '{{ now()->toDateString() }}';
            document.getElementById('finPaymentInput').value = 'Transfer Bank';
            openModal();
        });
    }

    document.querySelectorAll('.btn-edit-finance').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            const id = row.getAttribute('data-id');

            titleEl.textContent = 'Edit Transaksi Keuangan';
            form.action = '/admin/keuangan/' + id;
            methodInput.value = 'PUT';

            document.getElementById('finTypeInput').value = row.getAttribute('data-type') || 'expense';
            document.getElementById('finCategoryInput').value = row.getAttribute('data-category') || 'Operasional';
            document.getElementById('finStatusInput').value = row.getAttribute('data-status') || 'Lunas';
            document.getElementById('finTitleInput').value = row.getAttribute('data-title') || '';
            document.getElementById('finAmountInput').value = row.getAttribute('data-amount') || 0;
            document.getElementById('finDateInput').value = row.getAttribute('data-transaction-date') || '{{ now()->toDateString() }}';
            document.getElementById('finPaymentInput').value = row.getAttribute('data-payment-method') || 'Transfer Bank';
            document.getElementById('finNotesInput').value = row.getAttribute('data-notes') || '';

            openModal();
        });
    });

    const closeBtn = document.getElementById('closeFinanceModal');
    const cancelBtn = document.getElementById('cancelFinanceModalBtn');
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