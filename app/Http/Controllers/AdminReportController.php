<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminReportController extends Controller
{
    private function parseAmount(mixed $raw): int
    {
        return (int) preg_replace('/[^0-9]/', '', (string) ($raw ?? '0'));
    }

    private function buildReportData(string $startDate, string $endDate): array
    {
        $ordersSummary = [
            'total'     => 0,
            'completed' => 0,
            'active'    => 0,
            'cancelled' => 0,
            'by_service'=> [],
            'rows'      => [],
        ];

        if (Schema::hasTable('orders')) {
            $query = DB::table('orders')->orderByDesc('id');
            if (Schema::hasColumn('orders', 'created_at')) {
                $query->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            }
            $rows = $query->get();

            $serviceMap = [];
            foreach ($rows as $r) {
                $status = strtolower(trim((string) ($r->status ?? 'Menunggu')));
                $service = trim((string) ($r->service ?? $r->layanan ?? 'Umum')) ?: 'Umum';
                $amount = $this->parseAmount($r->total ?? $r->total_price ?? 0);
                $dateStr = !empty($r->created_at) ? substr((string) $r->created_at, 0, 10) : $startDate;

                $ordersSummary['total']++;
                if (in_array($status, ['selesai', 'completed', 'done'], true)) {
                    $ordersSummary['completed']++;
                } elseif (in_array($status, ['dibatalkan', 'batal', 'cancelled', 'canceled'], true)) {
                    $ordersSummary['cancelled']++;
                } else {
                    $ordersSummary['active']++;
                }

                if (!isset($serviceMap[$service])) {
                    $serviceMap[$service] = ['service' => $service, 'count' => 0, 'amount' => 0];
                }
                $serviceMap[$service]['count']++;
                if (!in_array($status, ['dibatalkan', 'batal', 'cancelled', 'canceled'], true)) {
                    $serviceMap[$service]['amount'] += $amount;
                }

                $ordersSummary['rows'][] = [
                    'date'     => $dateStr,
                    'code'     => (string) ($r->code ?? ('#ORD-' . $r->id)),
                    'service'  => $service,
                    'customer' => (string) ($r->customer ?? $r->customer_name ?? 'Pelanggan'),
                    'payment'  => (string) ($r->payment ?? 'QRIS'),
                    'status'   => (string) ($r->status ?? 'Menunggu'),
                    'amount'   => $amount,
                ];
            }

            $ordersSummary['by_service'] = array_values($serviceMap);
        }

        $financeSummary = [
            'income'  => 0,
            'expense' => 0,
            'net'     => 0,
        ];

        $finTable = Schema::hasTable('financial_transactions')
            ? 'financial_transactions'
            : (Schema::hasTable('keuangan') ? 'keuangan' : null);

        if ($finTable) {
            $dateCol = Schema::hasColumn($finTable, 'transaction_date')
                ? 'transaction_date'
                : (Schema::hasColumn($finTable, 'tanggal') ? 'tanggal' : null);
            $typeCol = Schema::hasColumn($finTable, 'type')
                ? 'type'
                : (Schema::hasColumn($finTable, 'jenis') ? 'jenis' : null);
            $amtCol = Schema::hasColumn($finTable, 'amount')
                ? 'amount'
                : (Schema::hasColumn($finTable, 'nominal') ? 'nominal' : 'jumlah');

            $q = DB::table($finTable);
            if ($dateCol) {
                $q->whereDate($dateCol, '>=', $startDate)->whereDate($dateCol, '<=', $endDate);
            }
            $finRows = $q->get();

            foreach ($finRows as $fr) {
                $rawType = strtolower(trim((string) ($typeCol ? ($fr->{$typeCol} ?? 'income') : 'income')));
                $amt = $this->parseAmount($fr->{$amtCol} ?? 0);
                if (in_array($rawType, ['expense', 'pengeluaran', 'keluar', 'out', 'debit'], true)) {
                    $financeSummary['expense'] += $amt;
                } else {
                    $financeSummary['income'] += $amt;
                }
            }
        } else {
            foreach ($ordersSummary['rows'] as $oRow) {
                if (strtolower($oRow['status']) !== 'dibatalkan') {
                    $financeSummary['income'] += $oRow['amount'];
                }
            }
        }
        $financeSummary['net'] = $financeSummary['income'] - $financeSummary['expense'];

        $resTable = Schema::hasTable('reservations')
            ? 'reservations'
            : (Schema::hasTable('reservasi') ? 'reservasi' : null);

        $reservationsSummary = [
            'total'        => 0,
            'confirmed'    => 0,
            'active_guests'=> 0,
        ];

        if ($resTable) {
            $dateCol = Schema::hasColumn($resTable, 'reservation_date')
                ? 'reservation_date'
                : (Schema::hasColumn($resTable, 'tanggal') ? 'tanggal' : null);
            $q = DB::table($resTable);
            if ($dateCol) {
                $q->whereDate($dateCol, '>=', $startDate)->whereDate($dateCol, '<=', $endDate);
            }
            $resRows = $q->get();
            foreach ($resRows as $rr) {
                $st = strtolower(trim((string) ($rr->status ?? $rr->status_reservasi ?? 'Menunggu')));
                $guests = (int) ($rr->guests ?? $rr->jumlah_tamu ?? $rr->orang ?? 0);
                $reservationsSummary['total']++;
                if (in_array($st, ['dikonfirmasi', 'confirmed', 'selesai', 'completed'], true)) {
                    $reservationsSummary['confirmed']++;
                }
                if (!in_array($st, ['dibatalkan', 'batal', 'cancelled'], true)) {
                    $reservationsSummary['active_guests'] += max(0, $guests);
                }
            }
        }

        $custTable = Schema::hasTable('customers')
            ? 'customers'
            : (Schema::hasTable('pelanggan') ? 'pelanggan' : null);

        $customersSummary = [
            'total' => $custTable ? DB::table($custTable)->count() : 0,
        ];

        $promoTable = Schema::hasTable('promos')
            ? 'promos'
            : (Schema::hasTable('promo') ? 'promo' : null);

        $promosSummary = [
            'total' => $promoTable ? DB::table($promoTable)->count() : 0,
            'used'  => 0,
        ];
        if ($promoTable) {
            $usedCol = Schema::hasColumn($promoTable, 'used_count')
                ? 'used_count'
                : (Schema::hasColumn($promoTable, 'terpakai') ? 'terpakai' : null);
            if ($usedCol) {
                $promosSummary['used'] = (int) DB::table($promoTable)->sum($usedCol);
            }
        }

        $invTable = Schema::hasTable('inventories')
            ? 'inventories'
            : (Schema::hasTable('inventaris') ? 'inventaris' : null);

        $lowStockItems = [];
        if ($invTable) {
            $invRows = DB::table($invTable)->orderBy('id')->get();
            foreach ($invRows as $ir) {
                $stock = (int) ($ir->stock ?? $ir->stok ?? 0);
                $minStock = (int) ($ir->min_stock ?? $ir->stok_minimum ?? $ir->minimum_stock ?? 5);
                if ($stock <= $minStock) {
                    $lowStockItems[] = [
                        'name'     => (string) ($ir->name ?? $ir->nama_bahan ?? $ir->nama ?? 'Bahan'),
                        'stock'    => $stock,
                        'min_stock'=> $minStock,
                        'unit'     => (string) ($ir->unit ?? $ir->satuan ?? 'pcs'),
                    ];
                }
            }
        }

        return [
            'start_date'   => $startDate,
            'end_date'     => $endDate,
            'orders'       => $ordersSummary,
            'finance'      => $financeSummary,
            'reservations' => $reservationsSummary,
            'customers'    => $customersSummary,
            'promos'       => $promosSummary,
            'low_stock'    => $lowStockItems,
        ];
    }

    public function index(Request $request)
    {
        $startDate = $request->input('tanggal_mulai') ?: now()->startOfMonth()->toDateString();
        $endDate = $request->input('tanggal_selesai') ?: now()->toDateString();

        if ($startDate > $endDate) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $report = $this->buildReportData($startDate, $endDate);

        if ($request->input('export') === 'csv') {
            $filename = 'laporan-kusantara-' . $startDate . '-sd-' . $endDate . '.csv';
            $headers = [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($report) {
                $out = fopen('php://output', 'w');
                fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

                fputcsv($out, ['LAPORAN OPERASIONAL PT. KUSANTARA']);
                fputcsv($out, ['Periode', $report['start_date'] . ' s/d ' . $report['end_date']]);
                fputcsv($out, []);
                fputcsv($out, ['RINGKASAN KEUANGAN']);
                fputcsv($out, ['Total Pemasukan', $report['finance']['income']]);
                fputcsv($out, ['Total Pengeluaran', $report['finance']['expense']]);
                fputcsv($out, ['Saldo Bersih', $report['finance']['net']]);
                fputcsv($out, []);
                fputcsv($out, ['RINCIAN PESANAN']);
                fputcsv($out, ['Tanggal', 'Kode', 'Layanan', 'Pelanggan', 'Pembayaran', 'Status', 'Nominal']);

                foreach ($report['orders']['rows'] as $row) {
                    fputcsv($out, [
                        $row['date'],
                        $row['code'],
                        $row['service'],
                        $row['customer'],
                        $row['payment'],
                        $row['status'],
                        $row['amount'],
                    ]);
                }

                fclose($out);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('admin.laporan', compact('report'));
    }
}