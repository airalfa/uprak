<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminFinanceController extends Controller
{
    private function resolveTable(): ?string
    {
        if (Schema::hasTable('financial_transactions')) {
            return 'financial_transactions';
        }
        if (Schema::hasTable('keuangan')) {
            return 'keuangan';
        }

        try {
            Schema::create('financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('reference_code', 50)->nullable();
                $table->string('type', 20)->default('income'); // income | expense
                $table->string('category', 80)->default('Operasional');
                $table->string('title', 180);
                $table->string('payment_method', 60)->default('QRIS / Transfer');
                $table->bigInteger('amount')->default(0);
                $table->date('transaction_date');
                $table->string('status', 30)->default('Lunas'); // Lunas | Tertunda
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            return 'financial_transactions';
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeType(string $raw): string
    {
        $val = strtolower(trim($raw));
        if (in_array($val, ['expense', 'pengeluaran', 'keluar', 'out', 'debit'], true)) {
            return 'expense';
        }
        return 'income';
    }

    private function normalizeStatus(string $raw): string
    {
        $val = strtolower(trim($raw));
        if (in_array($val, ['tertunda', 'pending', 'unpaid', 'belum lunas'], true)) {
            return 'Tertunda';
        }
        return 'Lunas';
    }

    /**
     * Sinkronkan pemasukan dari tabel orders ke financial_transactions
     * berdasarkan reference_code (kode order) agar tidak duplikat.
     */
    private function syncOrdersToFinance(string $table): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        $orders = DB::table('orders')->orderBy('id')->get();
        foreach ($orders as $o) {
            $statusOrder = strtolower(trim((string) ($o->status ?? 'Menunggu')));
            if (in_array($statusOrder, ['dibatalkan', 'batal', 'cancelled', 'canceled'], true)) {
                continue;
            }

            $code = trim((string) ($o->code ?? ('#ORD-' . $o->id)));
            if ($code === '') {
                continue;
            }

            $amount = (int) preg_replace('/[^0-9]/', '', (string) ($o->total ?? $o->total_price ?? '0'));
            $txDate = !empty($o->created_at) ? substr((string) $o->created_at, 0, 10) : now()->toDateString();
            $customer = trim((string) ($o->customer ?? $o->customer_name ?? 'Pelanggan'));
            $service = trim((string) ($o->service ?? 'Pesanan'));
            $payment = trim((string) ($o->payment ?? 'QRIS'));
            $financeStatus = in_array($statusOrder, ['selesai', 'completed', 'done'], true) ? 'Lunas' : 'Tertunda';

            $existing = DB::table($table)->where('reference_code', $code)->first();
            if ($existing) {
                DB::table($table)->where('id', $existing->id)->update([
                    'amount'     => $amount,
                    'status'     => $financeStatus,
                    'updated_at' => now(),
                ]);
                continue;
            }

            $now = now();
            DB::table($table)->insert([
                'reference_code'   => $code,
                'type'             => 'income',
                'category'         => 'Penjualan ' . $service,
                'title'            => 'Pesanan ' . $code . ' - ' . $customer,
                'payment_method'   => $payment !== '' ? $payment : 'QRIS',
                'amount'           => $amount,
                'transaction_date' => $txDate,
                'status'           => $financeStatus,
                'notes'            => trim((string) ($o->note ?? '')),
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }
    }

    public function index()
    {
        $transactions = [];
        $table = $this->resolveTable();

        if ($table) {
            $this->syncOrdersToFinance($table);

            $rows = DB::table($table)
                ->orderByDesc('transaction_date')
                ->orderByDesc('id')
                ->get();

            foreach ($rows as $r) {
                $type = $this->normalizeType((string) ($r->type ?? $r->jenis ?? 'income'));
                $amount = (int) ($r->amount ?? $r->nominal ?? $r->jumlah ?? 0);

                $transactions[] = [
                    'id'               => $r->id,
                    'reference_code'   => $r->reference_code ?? ('#FIN-' . str_pad((string) $r->id, 3, '0', STR_PAD_LEFT)),
                    'type'             => $type,
                    'type_label'       => $type === 'expense' ? 'Pengeluaran' : 'Pemasukan',
                    'category'         => $r->category ?? $r->kategori ?? 'Operasional',
                    'title'            => $r->title ?? $r->judul ?? $r->keterangan ?? 'Transaksi',
                    'payment_method'   => $r->payment_method ?? $r->metode_pembayaran ?? 'Kas / Transfer',
                    'amount'           => $amount,
                    'transaction_date' => (string) ($r->transaction_date ?? $r->tanggal ?? now()->toDateString()),
                    'status'           => $this->normalizeStatus((string) ($r->status ?? 'Lunas')),
                    'notes'            => $r->notes ?? $r->catatan ?? '',
                ];
            }
        }

        return view('admin.keuangan', compact('transactions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'             => ['required', 'string', 'max:20'],
            'category'         => ['required', 'string', 'max:80'],
            'title'            => ['required', 'string', 'max:180'],
            'payment_method'   => ['nullable', 'string', 'max:60'],
            'amount'           => ['required', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
            'status'           => ['required', 'string', 'max:30'],
            'notes'            => ['nullable', 'string'],
        ]);

        $table = $this->resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel keuangan belum dapat diakses.');
        }

        $type = $this->normalizeType($data['type']);
        $nextNum = (DB::table($table)->max('id') ?? 0) + 1;
        $refCode = ($type === 'expense' ? '#EXP-' : '#INC-') . str_pad((string) $nextNum, 3, '0', STR_PAD_LEFT);
        $now = now();

        DB::table($table)->insert([
            'reference_code'   => $refCode,
            'type'             => $type,
            'category'         => trim($data['category']),
            'title'            => trim($data['title']),
            'payment_method'   => trim((string) ($data['payment_method'] ?? 'Kas / Transfer')) ?: 'Kas / Transfer',
            'amount'           => (int) $data['amount'],
            'transaction_date' => $data['transaction_date'],
            'status'           => $this->normalizeStatus($data['status']),
            'notes'            => trim((string) ($data['notes'] ?? '')),
            'created_at'       => $now,
            'updated_at'       => $now,
        ]);

        return redirect()->route('admin.keuangan')->with('success', 'Transaksi keuangan (' . $refCode . ') berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'type'             => ['required', 'string', 'max:20'],
            'category'         => ['required', 'string', 'max:80'],
            'title'            => ['required', 'string', 'max:180'],
            'payment_method'   => ['nullable', 'string', 'max:60'],
            'amount'           => ['required', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
            'status'           => ['required', 'string', 'max:30'],
            'notes'            => ['nullable', 'string'],
        ]);

        $table = $this->resolveTable();
        if ($table) {
            $type = $this->normalizeType($data['type']);
            DB::table($table)->where('id', $id)->update([
                'type'             => $type,
                'category'         => trim($data['category']),
                'title'            => trim($data['title']),
                'payment_method'   => trim((string) ($data['payment_method'] ?? 'Kas / Transfer')) ?: 'Kas / Transfer',
                'amount'           => (int) $data['amount'],
                'transaction_date' => $data['transaction_date'],
                'status'           => $this->normalizeStatus($data['status']),
                'notes'            => trim((string) ($data['notes'] ?? '')),
                'updated_at'       => now(),
            ]);
        }

        return redirect()->route('admin.keuangan')->with('success', 'Data transaksi keuangan berhasil diperbarui.');
    }

    public function toggleStatus(int $id)
    {
        $table = $this->resolveTable();
        if ($table) {
            $row = DB::table($table)->where('id', $id)->first();
            if ($row) {
                $current = $this->normalizeStatus((string) ($row->status ?? 'Lunas'));
                $next = $current === 'Lunas' ? 'Tertunda' : 'Lunas';

                DB::table($table)->where('id', $id)->update([
                    'status'     => $next,
                    'updated_at' => now(),
                ]);

                return response()->json(['ok' => true, 'status' => $next]);
            }
        }

        return response()->json(['ok' => false], 404);
    }

    public function destroy(int $id)
    {
        $table = $this->resolveTable();
        if ($table) {
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.keuangan')->with('success', 'Data transaksi keuangan berhasil dihapus.');
    }
}