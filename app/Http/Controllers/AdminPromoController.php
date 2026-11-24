<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminPromoController extends Controller
{
    public static function resolveTable(): ?string
    {
        if (Schema::hasTable('promos')) {
            return 'promos';
        }
        if (Schema::hasTable('promo')) {
            return 'promo';
        }

        try {
            Schema::create('promos', function (Blueprint $table) {
                $table->id();
                $table->string('code', 40)->unique();
                $table->string('title', 150);
                $table->string('discount_type', 20)->default('percent'); // percent | fixed
                $table->integer('discount_value')->default(10);
                $table->integer('min_order')->default(0);
                $table->integer('max_discount')->default(0); // 0 = tanpa batas
                $table->integer('quota')->default(100); // 0 = unlimited
                $table->integer('used_count')->default(0);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->text('description')->nullable();
                $table->timestamps();
            });

            $today = now()->toDateString();
            $nextMonth = now()->addMonth()->toDateString();
            $now = now();

            DB::table('promos')->insert([
                [
                    'code'           => 'KUSANTARA10',
                    'title'          => 'Diskon 10% Semua Menu',
                    'discount_type'  => 'percent',
                    'discount_value' => 10,
                    'min_order'      => 30000,
                    'max_discount'   => 25000,
                    'quota'          => 100,
                    'used_count'     => 0,
                    'start_date'     => $today,
                    'end_date'       => $nextMonth,
                    'is_active'      => 1,
                    'description'    => 'Potongan 10% maksimal Rp25.000 untuk minimal belanja Rp30.000.',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ],
                [
                    'code'           => 'HEMAT15RB',
                    'title'          => 'Potongan Langsung Rp15.000',
                    'discount_type'  => 'fixed',
                    'discount_value' => 15000,
                    'min_order'      => 75000,
                    'max_discount'   => 0,
                    'quota'          => 50,
                    'used_count'     => 0,
                    'start_date'     => $today,
                    'end_date'       => $nextMonth,
                    'is_active'      => 1,
                    'description'    => 'Potongan langsung Rp15.000 untuk transaksi minimal Rp75.000.',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ],
            ]);

            return 'promos';
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Hitung detail diskon berdasarkan kode promo dan subtotal pesanan.
     * Mengembalikan array ['valid' => bool, 'message' => string, 'discount' => int, 'final_total' => int, 'promo' => ?object]
     */
    public static function evaluatePromo(?string $rawCode, int $subtotal): array
    {
        $code = strtoupper(trim((string) $rawCode));
        $subtotal = max(0, $subtotal);

        if ($code === '') {
            return [
                'valid'       => false,
                'message'     => 'Masukkan kode promo terlebih dahulu.',
                'discount'    => 0,
                'final_total' => $subtotal,
                'promo'       => null,
            ];
        }

        $table = self::resolveTable();
        if (!$table) {
            return [
                'valid'       => false,
                'message'     => 'Tabel promo belum dapat diakses.',
                'discount'    => 0,
                'final_total' => $subtotal,
                'promo'       => null,
            ];
        }

        $codeCol = Schema::hasColumn($table, 'code') ? 'code' : 'kode';
        $promo = DB::table($table)->whereRaw("UPPER({$codeCol}) = ?", [$code])->first();

        if (!$promo) {
            return [
                'valid'       => false,
                'message'     => 'Kode promo "' . $code . '" tidak ditemukan.',
                'discount'    => 0,
                'final_total' => $subtotal,
                'promo'       => null,
            ];
        }

        $isActive = isset($promo->is_active)
            ? (bool) $promo->is_active
            : (strtolower((string) ($promo->status ?? 'aktif')) === 'aktif');

        if (!$isActive) {
            return [
                'valid'       => false,
                'message'     => 'Kode promo "' . $code . '" sedang tidak aktif.',
                'discount'    => 0,
                'final_total' => $subtotal,
                'promo'       => $promo,
            ];
        }

        $today = now()->toDateString();
        $startDate = $promo->start_date ?? $promo->tanggal_mulai ?? null;
        $endDate = $promo->end_date ?? $promo->tanggal_berakhir ?? null;

        if ($startDate && $today < substr((string) $startDate, 0, 10)) {
            return [
                'valid'       => false,
                'message'     => 'Promo "' . $code . '" baru berlaku mulai ' . substr((string) $startDate, 0, 10) . '.',
                'discount'    => 0,
                'final_total' => $subtotal,
                'promo'       => $promo,
            ];
        }

        if ($endDate && $today > substr((string) $endDate, 0, 10)) {
            return [
                'valid'       => false,
                'message'     => 'Promo "' . $code . '" sudah berakhir.',
                'discount'    => 0,
                'final_total' => $subtotal,
                'promo'       => $promo,
            ];
        }

        $quota = (int) ($promo->quota ?? $promo->kuota ?? 0);
        $usedCount = (int) ($promo->used_count ?? $promo->terpakai ?? 0);
        if ($quota > 0 && $usedCount >= $quota) {
            return [
                'valid'       => false,
                'message'     => 'Kuota promo "' . $code . '" sudah habis.',
                'discount'    => 0,
                'final_total' => $subtotal,
                'promo'       => $promo,
            ];
        }

        $minOrder = (int) ($promo->min_order ?? $promo->minimal_belanja ?? 0);
        if ($minOrder > 0 && $subtotal < $minOrder) {
            return [
                'valid'       => false,
                'message'     => 'Minimal belanja untuk promo "' . $code . '" adalah Rp' . number_format($minOrder, 0, ',', '.') . '.',
                'discount'    => 0,
                'final_total' => $subtotal,
                'promo'       => $promo,
            ];
        }

        $type = strtolower((string) ($promo->discount_type ?? $promo->tipe_diskon ?? 'percent'));
        $value = (int) ($promo->discount_value ?? $promo->nilai_diskon ?? 0);
        $maxDiscount = (int) ($promo->max_discount ?? $promo->maks_diskon ?? 0);

        if ($type === 'fixed' || $type === 'nominal') {
            $discount = max(0, $value);
        } else {
            $discount = (int) round(($subtotal * max(0, $value)) / 100);
            if ($maxDiscount > 0) {
                $discount = min($discount, $maxDiscount);
            }
        }

        $discount = min($discount, $subtotal);
        $finalTotal = max(0, $subtotal - $discount);

        return [
            'valid'       => true,
            'message'     => 'Promo "' . $code . '" berhasil diterapkan (hemat Rp' . number_format($discount, 0, ',', '.') . ').',
            'discount'    => $discount,
            'final_total' => $finalTotal,
            'promo'       => $promo,
        ];
    }

    /**
     * Catat pemakaian promo saat pesanan resmi dibuat.
     */
    public static function redeemPromo(?string $rawCode, int $subtotal): array
    {
        $eval = self::evaluatePromo($rawCode, $subtotal);
        if (!$eval['valid'] || !$eval['promo']) {
            return $eval;
        }

        $table = self::resolveTable();
        if ($table) {
            $usedCol = Schema::hasColumn($table, 'used_count')
                ? 'used_count'
                : (Schema::hasColumn($table, 'terpakai') ? 'terpakai' : null);

            if ($usedCol) {
                $currentUsed = (int) ($eval['promo']->{$usedCol} ?? 0);
                $update = [$usedCol => $currentUsed + 1];
                if (Schema::hasColumn($table, 'updated_at')) {
                    $update['updated_at'] = now();
                }
                DB::table($table)->where('id', $eval['promo']->id)->update($update);
            }
        }

        return $eval;
    }

    /**
     * Endpoint publik AJAX untuk mengecek kode promo di halaman menu / checkout.
     */
    public function validateCode(Request $request)
    {
        $data = $request->validate([
            'code'     => ['required', 'string', 'max:40'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $result = self::evaluatePromo($data['code'], (int) $data['subtotal']);

        return response()->json([
            'ok'          => $result['valid'],
            'code'        => strtoupper(trim($data['code'])),
            'message'     => $result['message'],
            'discount'    => $result['discount'],
            'final_total' => $result['final_total'],
        ], $result['valid'] ? 200 : 422);
    }

    public function index()
    {
        $promos = [];
        $table = self::resolveTable();

        if ($table) {
            $rows = DB::table($table)->orderByDesc('id')->get();
            foreach ($rows as $r) {
                $type = strtolower((string) ($r->discount_type ?? $r->tipe_diskon ?? 'percent'));
                $promos[] = [
                    'id'             => $r->id,
                    'code'           => strtoupper((string) ($r->code ?? $r->kode ?? '')),
                    'title'          => $r->title ?? $r->judul ?? $r->nama ?? 'Promo',
                    'discount_type'  => in_array($type, ['fixed', 'nominal'], true) ? 'fixed' : 'percent',
                    'discount_value' => (int) ($r->discount_value ?? $r->nilai_diskon ?? 0),
                    'min_order'      => (int) ($r->min_order ?? $r->minimal_belanja ?? 0),
                    'max_discount'   => (int) ($r->max_discount ?? $r->maks_diskon ?? 0),
                    'quota'          => (int) ($r->quota ?? $r->kuota ?? 0),
                    'used_count'     => (int) ($r->used_count ?? $r->terpakai ?? 0),
                    'start_date'     => substr((string) ($r->start_date ?? $r->tanggal_mulai ?? now()->toDateString()), 0, 10),
                    'end_date'       => substr((string) ($r->end_date ?? $r->tanggal_berakhir ?? now()->addMonth()->toDateString()), 0, 10),
                    'is_active'      => isset($r->is_active)
                        ? (bool) $r->is_active
                        : (strtolower((string) ($r->status ?? 'aktif')) === 'aktif'),
                    'description'    => $r->description ?? $r->deskripsi ?? '',
                ];
            }
        }

        return view('admin.promo', compact('promos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'           => ['required', 'string', 'max:40'],
            'title'          => ['required', 'string', 'max:150'],
            'discount_type'  => ['required', 'in:percent,fixed'],
            'discount_value' => ['required', 'integer', 'min:1'],
            'min_order'      => ['required', 'integer', 'min:0'],
            'max_discount'   => ['nullable', 'integer', 'min:0'],
            'quota'          => ['required', 'integer', 'min:0'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date'],
            'is_active'      => ['nullable'],
            'description'    => ['nullable', 'string'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel promo belum dapat diakses.');
        }

        $code = strtoupper(trim($data['code']));
        $codeCol = Schema::hasColumn($table, 'code') ? 'code' : 'kode';
        if (DB::table($table)->whereRaw("UPPER({$codeCol}) = ?", [$code])->exists()) {
            return back()->with('error', 'Kode promo "' . $code . '" sudah digunakan.');
        }

        $payload = $this->mapPayload($table, $data, $code, true);
        DB::table($table)->insert($payload);

        return redirect()->route('admin.promo')->with('success', 'Promo "' . $code . '" berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'code'           => ['required', 'string', 'max:40'],
            'title'          => ['required', 'string', 'max:150'],
            'discount_type'  => ['required', 'in:percent,fixed'],
            'discount_value' => ['required', 'integer', 'min:1'],
            'min_order'      => ['required', 'integer', 'min:0'],
            'max_discount'   => ['nullable', 'integer', 'min:0'],
            'quota'          => ['required', 'integer', 'min:0'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date'],
            'is_active'      => ['nullable'],
            'description'    => ['nullable', 'string'],
        ]);

        $table = self::resolveTable();
        if ($table) {
            $code = strtoupper(trim($data['code']));
            $codeCol = Schema::hasColumn($table, 'code') ? 'code' : 'kode';
            $dup = DB::table($table)
                ->whereRaw("UPPER({$codeCol}) = ?", [$code])
                ->where('id', '!=', $id)
                ->first();

            if ($dup) {
                return back()->with('error', 'Kode promo "' . $code . '" sudah dipakai promo lain.');
            }

            $payload = $this->mapPayload($table, $data, $code, false);
            DB::table($table)->where('id', $id)->update($payload);
        }

        return redirect()->route('admin.promo')->with('success', 'Data promo berhasil diperbarui.');
    }

    public function toggleActive(int $id)
    {
        $table = self::resolveTable();
        if ($table) {
            $row = DB::table($table)->where('id', $id)->first();
            if ($row) {
                $current = isset($row->is_active)
                    ? (bool) $row->is_active
                    : (strtolower((string) ($row->status ?? 'aktif')) === 'aktif');
                $next = !$current;

                $update = [];
                if (Schema::hasColumn($table, 'is_active')) {
                    $update['is_active'] = $next ? 1 : 0;
                } elseif (Schema::hasColumn($table, 'status')) {
                    $update['status'] = $next ? 'aktif' : 'nonaktif';
                }
                if (Schema::hasColumn($table, 'updated_at')) {
                    $update['updated_at'] = now();
                }

                if (!empty($update)) {
                    DB::table($table)->where('id', $id)->update($update);
                }

                return response()->json(['ok' => true, 'is_active' => $next]);
            }
        }

        return response()->json(['ok' => false], 404);
    }

    public function destroy(int $id)
    {
        $table = self::resolveTable();
        if ($table) {
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.promo')->with('success', 'Promo berhasil dihapus.');
    }

    private function mapPayload(string $table, array $data, string $code, bool $isInsert): array
    {
        $row = [];
        $activeBool = !empty($data['is_active']);

        if (Schema::hasColumn($table, 'code')) {
            $row['code'] = $code;
        } elseif (Schema::hasColumn($table, 'kode')) {
            $row['kode'] = $code;
        }

        if (Schema::hasColumn($table, 'title')) {
            $row['title'] = $data['title'];
        } elseif (Schema::hasColumn($table, 'judul')) {
            $row['judul'] = $data['title'];
        } elseif (Schema::hasColumn($table, 'nama')) {
            $row['nama'] = $data['title'];
        }

        if (Schema::hasColumn($table, 'discount_type')) {
            $row['discount_type'] = $data['discount_type'];
        } elseif (Schema::hasColumn($table, 'tipe_diskon')) {
            $row['tipe_diskon'] = $data['discount_type'];
        }

        if (Schema::hasColumn($table, 'discount_value')) {
            $row['discount_value'] = (int) $data['discount_value'];
        } elseif (Schema::hasColumn($table, 'nilai_diskon')) {
            $row['nilai_diskon'] = (int) $data['discount_value'];
        }

        if (Schema::hasColumn($table, 'min_order')) {
            $row['min_order'] = (int) $data['min_order'];
        } elseif (Schema::hasColumn($table, 'minimal_belanja')) {
            $row['minimal_belanja'] = (int) $data['min_order'];
        }

        if (Schema::hasColumn($table, 'max_discount')) {
            $row['max_discount'] = (int) ($data['max_discount'] ?? 0);
        } elseif (Schema::hasColumn($table, 'maks_diskon')) {
            $row['maks_diskon'] = (int) ($data['max_discount'] ?? 0);
        }

        if (Schema::hasColumn($table, 'quota')) {
            $row['quota'] = (int) $data['quota'];
        } elseif (Schema::hasColumn($table, 'kuota')) {
            $row['kuota'] = (int) $data['quota'];
        }

        if (Schema::hasColumn($table, 'start_date')) {
            $row['start_date'] = $data['start_date'] ?: now()->toDateString();
        } elseif (Schema::hasColumn($table, 'tanggal_mulai')) {
            $row['tanggal_mulai'] = $data['start_date'] ?: now()->toDateString();
        }

        if (Schema::hasColumn($table, 'end_date')) {
            $row['end_date'] = $data['end_date'] ?: now()->addMonth()->toDateString();
        } elseif (Schema::hasColumn($table, 'tanggal_berakhir')) {
            $row['tanggal_berakhir'] = $data['end_date'] ?: now()->addMonth()->toDateString();
        }

        if (Schema::hasColumn($table, 'is_active')) {
            $row['is_active'] = $activeBool ? 1 : 0;
        } elseif (Schema::hasColumn($table, 'status')) {
            $row['status'] = $activeBool ? 'aktif' : 'nonaktif';
        }

        if (Schema::hasColumn($table, 'description')) {
            $row['description'] = $data['description'] ?? '';
        } elseif (Schema::hasColumn($table, 'deskripsi')) {
            $row['deskripsi'] = $data['description'] ?? '';
        }

        if ($isInsert && Schema::hasColumn($table, 'used_count')) {
            $row['used_count'] = 0;
        }

        if (Schema::hasColumn($table, 'updated_at')) {
            $row['updated_at'] = now();
        }
        if ($isInsert && Schema::hasColumn($table, 'created_at')) {
            $row['created_at'] = now();
        }

        return $row;
    }
}