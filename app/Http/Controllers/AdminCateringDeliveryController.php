<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminCateringDeliveryController extends Controller
{
    private function resolveTable(): ?string
    {
        if (Schema::hasTable('catering_deliveries')) {
            return 'catering_deliveries';
        }

        try {
            Schema::create('catering_deliveries', function (Blueprint $table) {
                $table->id();
                $table->string('code', 40)->nullable();
                $table->string('service_type', 30)->default('Delivery'); // Catering | Delivery
                $table->string('customer_name', 150);
                $table->string('phone', 40)->nullable();
                $table->text('items_summary');
                $table->date('delivery_date');
                $table->string('delivery_time', 20)->default('11:00');
                $table->string('courier_name', 100)->default('Tim Kusantara');
                $table->string('total_label', 60)->default('Rp0');
                $table->integer('total_amount')->default(0);
                $table->string('status', 30)->default('Menunggu'); // Menunggu | Diproses | Dikirim | Selesai | Dibatalkan
                $table->text('address_notes')->nullable();
                $table->timestamps();
            });

            return 'catering_deliveries';
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeServiceType(string $raw): string
    {
        $val = strtolower(trim($raw));
        if (str_contains($val, 'cater') || str_contains($val, 'kater')) {
            return 'Catering';
        }
        return 'Delivery';
    }

    private function normalizeStatus(string $raw): string
    {
        $val = strtolower(trim($raw));
        return match ($val) {
            'menunggu', 'pending' => 'Menunggu',
            'diproses', 'proses', 'processing', 'disiapkan' => 'Diproses',
            'dikirim', 'dalam pengiriman', 'shipping', 'on_delivery', 'otw' => 'Dikirim',
            'selesai', 'terkirim', 'completed', 'done' => 'Selesai',
            'dibatalkan', 'batal', 'cancelled', 'canceled' => 'Dibatalkan',
            default => in_array($raw, ['Menunggu', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'], true) ? $raw : 'Menunggu',
        };
    }

    /**
     * Sinkronkan pesanan Catering & Delivery dari tabel orders ke catering_deliveries
     * agar pesanan yang masuk lewat /pesanan langsung muncul di halaman ini.
     */
    private function syncOrdersToDeliveries(string $table): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        $orders = DB::table('orders')->orderBy('id')->get();
        foreach ($orders as $o) {
            $svcRaw = strtolower(trim((string) ($o->service ?? $o->layanan ?? '')));
            if (!str_contains($svcRaw, 'cater') && !str_contains($svcRaw, 'kater') && !str_contains($svcRaw, 'deliver')) {
                continue;
            }

            $code = (string) ($o->code ?? ('#ORD-' . $o->id));
            $exists = DB::table($table)->where('code', $code)->exists();
            if ($exists) {
                continue;
            }

            $serviceType = $this->normalizeServiceType((string) ($o->service ?? $o->layanan ?? 'Delivery'));
            $customerName = (string) ($o->customer ?? $o->customer_name ?? $o->nama_pelanggan ?? 'Pelanggan');
            $phone = (string) ($o->phone ?? $o->no_hp ?? '-');
            $itemsSummary = (string) ($o->items ?? 'Paket Pesanan');
            $rawTotal = (string) ($o->total ?? $o->total_price ?? 'Rp0');
            $numericTotal = (int) preg_replace('/[^0-9]/', '', $rawTotal);
            $formattedTotal = str_starts_with(trim($rawTotal), 'Rp')
                ? trim($rawTotal)
                : 'Rp' . number_format($numericTotal, 0, ',', '.');

            $createdDate = !empty($o->created_at) ? substr((string) $o->created_at, 0, 10) : now()->toDateString();
            $createdTime = !empty($o->created_at) && strlen((string) $o->created_at) >= 16
                ? substr((string) $o->created_at, 11, 5)
                : '11:00';

            $note = (string) ($o->note ?? $o->notes ?? '');
            $orderStatus = $this->normalizeStatus((string) ($o->status ?? 'Menunggu'));

            $now = now();
            DB::table($table)->insert([
                'code'          => $code,
                'service_type'  => $serviceType,
                'customer_name' => $customerName,
                'phone'         => $phone !== '' ? $phone : '-',
                'items_summary' => $itemsSummary,
                'delivery_date' => $createdDate,
                'delivery_time' => $createdTime,
                'courier_name'  => $serviceType === 'Catering' ? 'Tim Katering Kusantara' : 'Kurir Kusantara',
                'total_label'   => $formattedTotal,
                'total_amount'  => $numericTotal,
                'status'        => $orderStatus,
                'address_notes' => $note,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }

    public function index()
    {
        $deliveries = [];
        $table = $this->resolveTable();

        if ($table) {
            $this->syncOrdersToDeliveries($table);

            $rows = DB::table($table)
                ->orderByDesc('delivery_date')
                ->orderByDesc('id')
                ->get();

            foreach ($rows as $r) {
                $amount = (int) ($r->total_amount ?? preg_replace('/[^0-9]/', '', (string) ($r->total_label ?? '0')));
                $label = !empty($r->total_label) ? $r->total_label : ('Rp' . number_format($amount, 0, ',', '.'));

                $deliveries[] = [
                    'id'            => $r->id,
                    'code'          => $r->code ?? ('#CD-' . str_pad((string) $r->id, 3, '0', STR_PAD_LEFT)),
                    'service_type'  => $this->normalizeServiceType((string) ($r->service_type ?? 'Delivery')),
                    'customer_name' => $r->customer_name ?? 'Pelanggan',
                    'phone'         => $r->phone ?? '-',
                    'items_summary' => $r->items_summary ?? '-',
                    'delivery_date' => (string) ($r->delivery_date ?? now()->toDateString()),
                    'delivery_time' => (string) ($r->delivery_time ?? '11:00'),
                    'courier_name'  => $r->courier_name ?? 'Tim Kusantara',
                    'total_label'   => $label,
                    'total_amount'  => $amount,
                    'status'        => $this->normalizeStatus((string) ($r->status ?? 'Menunggu')),
                    'address_notes' => $r->address_notes ?? '',
                ];
            }
        }

        return view('admin.catering', compact('deliveries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_type'  => ['required', 'string', 'max:30'],
            'customer_name' => ['required', 'string', 'max:150'],
            'phone'         => ['nullable', 'string', 'max:40'],
            'items_summary' => ['required', 'string'],
            'delivery_date' => ['required', 'date'],
            'delivery_time' => ['required', 'string', 'max:20'],
            'courier_name'  => ['nullable', 'string', 'max:100'],
            'total_amount'  => ['required', 'numeric', 'min:0'],
            'status'        => ['required', 'string', 'max:30'],
            'address_notes' => ['nullable', 'string'],
        ]);

        $table = $this->resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel catering & delivery belum dapat diakses.');
        }

        $serviceType = $this->normalizeServiceType($data['service_type']);
        $nextNum = (DB::table($table)->max('id') ?? 0) + 1;
        $code = ($serviceType === 'Catering' ? '#CTR-' : '#DLV-') . str_pad((string) $nextNum, 3, '0', STR_PAD_LEFT);
        $amount = (int) $data['total_amount'];
        $now = now();

        DB::table($table)->insert([
            'code'          => $code,
            'service_type'  => $serviceType,
            'customer_name' => trim($data['customer_name']),
            'phone'         => trim((string) ($data['phone'] ?? '-')) ?: '-',
            'items_summary' => trim($data['items_summary']),
            'delivery_date' => $data['delivery_date'],
            'delivery_time' => $data['delivery_time'],
            'courier_name'  => trim((string) ($data['courier_name'] ?? 'Tim Kusantara')) ?: 'Tim Kusantara',
            'total_label'   => 'Rp' . number_format($amount, 0, ',', '.'),
            'total_amount'  => $amount,
            'status'        => $this->normalizeStatus($data['status']),
            'address_notes' => trim((string) ($data['address_notes'] ?? '')),
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        return redirect()->route('admin.catering')->with('success', 'Jadwal ' . $serviceType . ' (' . $code . ') berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'service_type'  => ['required', 'string', 'max:30'],
            'customer_name' => ['required', 'string', 'max:150'],
            'phone'         => ['nullable', 'string', 'max:40'],
            'items_summary' => ['required', 'string'],
            'delivery_date' => ['required', 'date'],
            'delivery_time' => ['required', 'string', 'max:20'],
            'courier_name'  => ['nullable', 'string', 'max:100'],
            'total_amount'  => ['required', 'numeric', 'min:0'],
            'status'        => ['required', 'string', 'max:30'],
            'address_notes' => ['nullable', 'string'],
        ]);

        $table = $this->resolveTable();
        if ($table) {
            $serviceType = $this->normalizeServiceType($data['service_type']);
            $amount = (int) $data['total_amount'];

            DB::table($table)->where('id', $id)->update([
                'service_type'  => $serviceType,
                'customer_name' => trim($data['customer_name']),
                'phone'         => trim((string) ($data['phone'] ?? '-')) ?: '-',
                'items_summary' => trim($data['items_summary']),
                'delivery_date' => $data['delivery_date'],
                'delivery_time' => $data['delivery_time'],
                'courier_name'  => trim((string) ($data['courier_name'] ?? 'Tim Kusantara')) ?: 'Tim Kusantara',
                'total_label'   => 'Rp' . number_format($amount, 0, ',', '.'),
                'total_amount'  => $amount,
                'status'        => $this->normalizeStatus($data['status']),
                'address_notes' => trim((string) ($data['address_notes'] ?? '')),
                'updated_at'    => now(),
            ]);
        }

        return redirect()->route('admin.catering')->with('success', 'Data pengiriman/katering berhasil diperbarui.');
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:30'],
        ]);

        $normalized = $this->normalizeStatus($data['status']);
        $table = $this->resolveTable();

        if ($table) {
            $row = DB::table($table)->where('id', $id)->first();
            DB::table($table)->where('id', $id)->update([
                'status'     => $normalized,
                'updated_at' => now(),
            ]);

            // Sinkronkan juga ke tabel orders jika berasal dari kode order
            if ($row && !empty($row->code) && Schema::hasTable('orders')) {
                DB::table('orders')->where('code', $row->code)->update([
                    'status'     => $normalized,
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json([
            'ok'     => true,
            'status' => $normalized,
        ]);
    }

    public function destroy(int $id)
    {
        $table = $this->resolveTable();
        if ($table) {
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.catering')->with('success', 'Data jadwal pengiriman berhasil dihapus.');
    }
}