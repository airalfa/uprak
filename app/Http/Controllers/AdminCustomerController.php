<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminCustomerController extends Controller
{
    public static function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $phone);
        if ($digits === '') {
            return '';
        }
        if (str_starts_with($digits, '62')) {
            $digits = '0' . substr($digits, 2);
        }
        return $digits;
    }

    public static function resolveTable(): ?string
    {
        if (Schema::hasTable('customers')) {
            return 'customers';
        }
        if (Schema::hasTable('pelanggan')) {
            return 'pelanggan';
        }

        try {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('phone', 40)->unique();
                $table->string('email', 150)->nullable();
                $table->text('address')->nullable();
                $table->integer('total_orders')->default(0);
                $table->bigInteger('total_spent')->default(0);
                $table->timestamp('last_order_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            return 'customers';
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Sinkronisasi pelanggan berdasarkan nomor telepon.
     * Jika nomor telepon sudah ada, tidak membuat baris baru; hanya memperbarui statistik/info.
     */
    public static function syncFromActivity(
        string $name,
        ?string $phone,
        int $amount = 0,
        bool $incrementOrder = true,
        ?string $email = null,
        ?string $address = null,
        ?string $note = null
    ): void {
        $normalizedPhone = self::normalizePhone($phone);
        if ($normalizedPhone === '') {
            return;
        }

        $table = self::resolveTable();
        if (!$table) {
            return;
        }

        $phoneCol = Schema::hasColumn($table, 'phone') ? 'phone' : (Schema::hasColumn($table, 'no_hp') ? 'no_hp' : 'telepon');
        $nameCol = Schema::hasColumn($table, 'name') ? 'name' : 'nama';

        $existing = DB::table($table)->where($phoneCol, $normalizedPhone)->first();
        if (!$existing && $phone) {
            $existing = DB::table($table)->where($phoneCol, trim($phone))->first();
        }

        $now = now();

        if ($existing) {
            $update = [];

            if (Schema::hasColumn($table, $phoneCol)) {
                $update[$phoneCol] = $normalizedPhone;
            }
            if (trim($name) !== '' && Schema::hasColumn($table, $nameCol)) {
                $update[$nameCol] = trim($name);
            }
            if ($incrementOrder) {
                if (Schema::hasColumn($table, 'total_orders')) {
                    $update['total_orders'] = (int) ($existing->total_orders ?? 0) + 1;
                } elseif (Schema::hasColumn($table, 'jumlah_pesanan')) {
                    $update['jumlah_pesanan'] = (int) ($existing->jumlah_pesanan ?? 0) + 1;
                }
            }
            if ($amount > 0) {
                if (Schema::hasColumn($table, 'total_spent')) {
                    $update['total_spent'] = (int) ($existing->total_spent ?? 0) + $amount;
                } elseif (Schema::hasColumn($table, 'total_belanja')) {
                    $update['total_belanja'] = (int) ($existing->total_belanja ?? 0) + $amount;
                }
            }
            if ($email && trim($email) !== '' && Schema::hasColumn($table, 'email')) {
                $update['email'] = trim($email);
            }
            if ($address && trim($address) !== '' && Schema::hasColumn($table, 'address')) {
                $update['address'] = trim($address);
            } elseif ($address && trim($address) !== '' && Schema::hasColumn($table, 'alamat')) {
                $update['alamat'] = trim($address);
            }
            if ($note && trim($note) !== '') {
                $noteCol = Schema::hasColumn($table, 'notes') ? 'notes' : (Schema::hasColumn($table, 'catatan') ? 'catatan' : null);
                if ($noteCol) {
                    $prevNote = trim((string) ($existing->{$noteCol} ?? ''));
                    $cleanNote = trim($note);
                    if ($prevNote === '') {
                        $update[$noteCol] = $cleanNote;
                    } elseif (!str_contains($prevNote, $cleanNote)) {
                        $update[$noteCol] = $prevNote . ' | ' . $cleanNote;
                    }
                }
            }
            if (Schema::hasColumn($table, 'last_order_at')) {
                $update['last_order_at'] = $now;
            } elseif (Schema::hasColumn($table, 'terakhir_order')) {
                $update['terakhir_order'] = $now;
            }
            if (Schema::hasColumn($table, 'updated_at')) {
                $update['updated_at'] = $now;
            }

            if (!empty($update)) {
                DB::table($table)->where('id', $existing->id)->update($update);
            }
            return;
        }

        // Pelanggan baru (nomor telepon belum pernah terdaftar)
        $insert = [];
        if (Schema::hasColumn($table, $nameCol)) {
            $insert[$nameCol] = trim($name) !== '' ? trim($name) : 'Pelanggan';
        }
        if (Schema::hasColumn($table, $phoneCol)) {
            $insert[$phoneCol] = $normalizedPhone;
        }
        if ($email && Schema::hasColumn($table, 'email')) {
            $insert['email'] = trim($email);
        }
        if ($address && Schema::hasColumn($table, 'address')) {
            $insert['address'] = trim($address);
        } elseif ($address && Schema::hasColumn($table, 'alamat')) {
            $insert['alamat'] = trim($address);
        }
        if (Schema::hasColumn($table, 'total_orders')) {
            $insert['total_orders'] = $incrementOrder ? 1 : 0;
        } elseif (Schema::hasColumn($table, 'jumlah_pesanan')) {
            $insert['jumlah_pesanan'] = $incrementOrder ? 1 : 0;
        }
        if (Schema::hasColumn($table, 'total_spent')) {
            $insert['total_spent'] = max(0, $amount);
        } elseif (Schema::hasColumn($table, 'total_belanja')) {
            $insert['total_belanja'] = max(0, $amount);
        }
        if (Schema::hasColumn($table, 'last_order_at')) {
            $insert['last_order_at'] = $now;
        } elseif (Schema::hasColumn($table, 'terakhir_order')) {
            $insert['terakhir_order'] = $now;
        }
        $noteCol = Schema::hasColumn($table, 'notes') ? 'notes' : (Schema::hasColumn($table, 'catatan') ? 'catatan' : null);
        if ($noteCol && $note && trim($note) !== '') {
            $insert[$noteCol] = trim($note);
        }
        if (Schema::hasColumn($table, 'created_at')) {
            $insert['created_at'] = $now;
        }
        if (Schema::hasColumn($table, 'updated_at')) {
            $insert['updated_at'] = $now;
        }

        if (!empty($insert)) {
            DB::table($table)->insert($insert);
        }
    }

    /**
     * Sinkronisasi otomatis dari data orders & reservations yang sudah ada
     * agar tidak ada duplikasi berdasarkan nomor telepon.
     */
 private function backfillExistingTransactions(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        $table = self::resolveTable();
        if (!$table) {
            return;
        }

        $orders = DB::table('orders')->orderBy('id')->get();
        if ($orders->isEmpty()) {
            return;
        }

        $phoneCol = Schema::hasColumn($table, 'phone')
            ? 'phone'
            : (Schema::hasColumn($table, 'no_hp') ? 'no_hp' : 'telepon');
        $nameCol = Schema::hasColumn($table, 'name') ? 'name' : 'nama';

        // Kelompokkan pesanan berdasarkan nomor telepon yang dinormalisasi
        $groupedByPhone = [];
        foreach ($orders as $o) {
            $rawPhone = $o->phone
                ?? $o->no_hp
                ?? $o->telepon
                ?? $o->customer_phone
                ?? $o->whatsapp
                ?? null;

            $norm = self::normalizePhone($rawPhone);
            if ($norm === '') {
                continue;
            }

            $rawTotal = $o->total
                ?? $o->total_price
                ?? $o->grand_total
                ?? $o->amount
                ?? $o->subtotal
                ?? 0;

            $numericTotal = (int) preg_replace('/[^0-9]/', '', (string) $rawTotal);

            $customerName = trim((string) (
                $o->customer
                ?? $o->customer_name
                ?? $o->nama_pelanggan
                ?? $o->name
                ?? $o->nama
                ?? 'Pelanggan'
            ));

            $address = $o->address ?? $o->alamat ?? null;
            $note = $o->note ?? $o->notes ?? $o->catatan ?? null;

            if (!isset($groupedByPhone[$norm])) {
                $groupedByPhone[$norm] = [
                    'name'         => $customerName !== '' ? $customerName : 'Pelanggan',
                    'phone'        => $norm,
                    'order_count'  => 0,
                    'total_spent'  => 0,
                    'address'      => $address,
                    'notes'        => [],
                ];
            } elseif ($customerName !== '' && $customerName !== 'Pelanggan') {
                $groupedByPhone[$norm]['name'] = $customerName;
            }

            $groupedByPhone[$norm]['order_count'] += 1;
            $groupedByPhone[$norm]['total_spent'] += max(0, $numericTotal);

            if ($address && trim((string) $address) !== '') {
                $groupedByPhone[$norm]['address'] = trim((string) $address);
            }
            if ($note && trim((string) $note) !== '' && !in_array(trim((string) $note), $groupedByPhone[$norm]['notes'], true)) {
                $groupedByPhone[$norm]['notes'][] = trim((string) $note);
            }
        }

        $now = now();

        foreach ($groupedByPhone as $norm => $info) {
            $existing = DB::table($table)->where($phoneCol, $norm)->first();
            $mergedNote = !empty($info['notes']) ? implode(' | ', $info['notes']) : null;

            if ($existing) {
                $update = [];
                if (Schema::hasColumn($table, $nameCol) && $info['name'] !== '') {
                    $update[$nameCol] = $info['name'];
                }
                if (Schema::hasColumn($table, 'total_orders')) {
                    $update['total_orders'] = $info['order_count'];
                } elseif (Schema::hasColumn($table, 'jumlah_pesanan')) {
                    $update['jumlah_pesanan'] = $info['order_count'];
                }
                if (Schema::hasColumn($table, 'total_spent')) {
                    $update['total_spent'] = $info['total_spent'];
                } elseif (Schema::hasColumn($table, 'total_belanja')) {
                    $update['total_belanja'] = $info['total_spent'];
                }
                if ($info['address']) {
                    if (Schema::hasColumn($table, 'address')) {
                        $update['address'] = $info['address'];
                    } elseif (Schema::hasColumn($table, 'alamat')) {
                        $update['alamat'] = $info['address'];
                    }
                }
                if ($mergedNote) {
                    $noteCol = Schema::hasColumn($table, 'notes') ? 'notes' : (Schema::hasColumn($table, 'catatan') ? 'catatan' : null);
                    if ($noteCol) {
                        $prev = trim((string) ($existing->{$noteCol} ?? ''));
                        if ($prev === '') {
                            $update[$noteCol] = $mergedNote;
                        } elseif (!str_contains($prev, $mergedNote)) {
                            $update[$noteCol] = $prev . ' | ' . $mergedNote;
                        }
                    }
                }
                if (Schema::hasColumn($table, 'last_order_at')) {
                    $update['last_order_at'] = $now;
                }
                if (Schema::hasColumn($table, 'updated_at')) {
                    $update['updated_at'] = $now;
                }

                if (!empty($update)) {
                    DB::table($table)->where('id', $existing->id)->update($update);
                }
            } else {
                $insert = [];
                if (Schema::hasColumn($table, $nameCol)) {
                    $insert[$nameCol] = $info['name'];
                }
                if (Schema::hasColumn($table, $phoneCol)) {
                    $insert[$phoneCol] = $norm;
                }
                if ($info['address']) {
                    if (Schema::hasColumn($table, 'address')) {
                        $insert['address'] = $info['address'];
                    } elseif (Schema::hasColumn($table, 'alamat')) {
                        $insert['alamat'] = $info['address'];
                    }
                }
                if (Schema::hasColumn($table, 'total_orders')) {
                    $insert['total_orders'] = $info['order_count'];
                } elseif (Schema::hasColumn($table, 'jumlah_pesanan')) {
                    $insert['jumlah_pesanan'] = $info['order_count'];
                }
                if (Schema::hasColumn($table, 'total_spent')) {
                    $insert['total_spent'] = $info['total_spent'];
                } elseif (Schema::hasColumn($table, 'total_belanja')) {
                    $insert['total_belanja'] = $info['total_spent'];
                }
                if ($mergedNote) {
                    $noteCol = Schema::hasColumn($table, 'notes') ? 'notes' : (Schema::hasColumn($table, 'catatan') ? 'catatan' : null);
                    if ($noteCol) {
                        $insert[$noteCol] = $mergedNote;
                    }
                }
                if (Schema::hasColumn($table, 'last_order_at')) {
                    $insert['last_order_at'] = $now;
                }
                if (Schema::hasColumn($table, 'created_at')) {
                    $insert['created_at'] = $now;
                }
                if (Schema::hasColumn($table, 'updated_at')) {
                    $insert['updated_at'] = $now;
                }

                if (!empty($insert)) {
                    DB::table($table)->insert($insert);
                }
            }
        }
    }

    public function index()
    {
        $this->backfillExistingTransactions();

        $customers = [];
        $table = self::resolveTable();

        if ($table) {
            $rows = DB::table($table)->orderByDesc('total_spent')->orderBy('id')->get();
            foreach ($rows as $r) {
                $rawPhone = (string) ($r->phone ?? $r->no_hp ?? $r->telepon ?? '');
                $normPhone = self::normalizePhone($rawPhone);
                $ordersCount = (int) ($r->total_orders ?? $r->jumlah_pesanan ?? 0);
                $spent = (int) ($r->total_spent ?? $r->total_belanja ?? 0);

                $customers[] = [
                    'id'            => $r->id,
                    'name'          => $r->name ?? $r->nama ?? 'Pelanggan',
                    'phone'         => $normPhone !== '' ? $normPhone : $rawPhone,
                    'email'         => $r->email ?? '',
                    'address'       => $r->address ?? $r->alamat ?? '',
                    'total_orders'  => $ordersCount,
                    'total_spent'   => $spent,
                    'last_order_at' => $r->last_order_at ?? $r->terakhir_order ?? $r->updated_at ?? '-',
                    'notes'         => $r->notes ?? $r->catatan ?? '',
                    'tier'          => $spent >= 200000 || $ordersCount >= 5 ? 'Setia' : 'Baru',
                ];
            }
        }

        return view('admin.pelanggan', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'phone'   => ['required', 'string', 'max:40'],
            'email'   => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string'],
            'notes'   => ['nullable', 'string'],
        ]);

        $normPhone = self::normalizePhone($data['phone']);
        if ($normPhone === '') {
            return back()->with('error', 'Nomor telepon tidak valid.');
        }

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel pelanggan belum tersedia.');
        }

        $phoneCol = Schema::hasColumn($table, 'phone') ? 'phone' : (Schema::hasColumn($table, 'no_hp') ? 'no_hp' : 'telepon');
        $alreadyExists = DB::table($table)->where($phoneCol, $normPhone)->exists();

        self::syncFromActivity(
            $data['name'],
            $normPhone,
            0,
            false,
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['notes'] ?? null
        );

        if ($alreadyExists) {
            return redirect()->route('admin.pelanggan')
                ->with('success', 'Nomor ' . $normPhone . ' sudah terdaftar — data pelanggan diperbarui tanpa membuat baris ganda.');
        }

        return redirect()->route('admin.pelanggan')
            ->with('success', 'Pelanggan "' . $data['name'] . '" (' . $normPhone . ') berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'phone'   => ['required', 'string', 'max:40'],
            'email'   => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string'],
            'notes'   => ['nullable', 'string'],
        ]);

        $normPhone = self::normalizePhone($data['phone']);
        if ($normPhone === '') {
            return back()->with('error', 'Nomor telepon tidak valid.');
        }

        $table = self::resolveTable();
        if ($table) {
            $phoneCol = Schema::hasColumn($table, 'phone') ? 'phone' : (Schema::hasColumn($table, 'no_hp') ? 'no_hp' : 'telepon');
            $nameCol = Schema::hasColumn($table, 'name') ? 'name' : 'nama';

            // Cek apakah nomor telepon baru sudah dipakai record pelanggan lain
            $duplicate = DB::table($table)
                ->where($phoneCol, $normPhone)
                ->where('id', '!=', $id)
                ->first();

            if ($duplicate) {
                return back()->with('error', 'Nomor telepon ' . $normPhone . ' sudah dipakai oleh pelanggan lain ("' . ($duplicate->{$nameCol} ?? '') . '").');
            }

            $update = [];
            if (Schema::hasColumn($table, $nameCol)) {
                $update[$nameCol] = trim($data['name']);
            }
            if (Schema::hasColumn($table, $phoneCol)) {
                $update[$phoneCol] = $normPhone;
            }
            if (Schema::hasColumn($table, 'email')) {
                $update['email'] = $data['email'] ?? '';
            }
            if (Schema::hasColumn($table, 'address')) {
                $update['address'] = $data['address'] ?? '';
            } elseif (Schema::hasColumn($table, 'alamat')) {
                $update['alamat'] = $data['address'] ?? '';
            }
            if (Schema::hasColumn($table, 'notes')) {
                $update['notes'] = $data['notes'] ?? '';
            } elseif (Schema::hasColumn($table, 'catatan')) {
                $update['catatan'] = $data['notes'] ?? '';
            }
            if (Schema::hasColumn($table, 'updated_at')) {
                $update['updated_at'] = now();
            }

            if (!empty($update)) {
                DB::table($table)->where('id', $id)->update($update);
            }
        }

        return redirect()->route('admin.pelanggan')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $table = self::resolveTable();
        if ($table) {
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.pelanggan')->with('success', 'Data pelanggan berhasil dihapus.');
    }
}