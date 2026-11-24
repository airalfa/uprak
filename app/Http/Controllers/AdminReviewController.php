<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminReviewController extends Controller
{
    public static function resolveTable(): ?string
    {
        if (Schema::hasTable('reviews')) {
            return 'reviews';
        }
        if (Schema::hasTable('ulasan')) {
            return 'ulasan';
        }

        try {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name', 150);
                $table->string('phone_or_order', 60)->nullable();
                $table->string('service_or_menu', 120)->default('Delivery');
                $table->tinyInteger('rating')->default(5); // 1 - 5
                $table->text('comment');
                $table->text('admin_reply')->nullable();
                $table->string('status', 30)->default('Menunggu'); // Menunggu | Terbit | Disembunyikan
                $table->timestamps();
            });

            $now = now();
            DB::table('reviews')->insert([
                [
                    'customer_name'   => 'Rina Kusuma',
                    'phone_or_order'  => '#KS001',
                    'service_or_menu' => 'Delivery',
                    'rating'          => 5,
                    'comment'         => 'Bumbu khas Nusantara terasa pas, pengiriman tepat waktu, dan kemasannya rapi.',
                    'admin_reply'     => 'Terima kasih Kak Rina atas ulasan dan kepercayaannya kepada PT. Kusantara!',
                    'status'          => 'Terbit',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'customer_name'   => 'Fajar Nugraha',
                    'phone_or_order'  => '081298765432',
                    'service_or_menu' => 'Catering',
                    'rating'          => 5,
                    'comment'         => 'Paket katering untuk acara keluarga aman dan disukai semua tamu.',
                    'admin_reply'     => null,
                    'status'          => 'Menunggu',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
            ]);

            return 'reviews';
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeStatus(string $raw): string
    {
        $val = strtolower(trim($raw));
        return match ($val) {
            'terbit', 'published', 'approved', 'aktif', 'tampil' => 'Terbit',
            'disembunyikan', 'hidden', 'rejected', 'ditolak' => 'Disembunyikan',
            default => 'Menunggu',
        };
    }

    /**
     * Endpoint publik: pelanggan mengirim ulasan baru dari halaman web.
     */
    public function storePublic(Request $request)
    {
        $data = $request->validate([
            'customer_name'   => ['required', 'string', 'max:150'],
            'phone_or_order'  => ['nullable', 'string', 'max:60'],
            'service_or_menu' => ['nullable', 'string', 'max:120'],
            'rating'          => ['required', 'integer', 'min:1', 'max:5'],
            'comment'         => ['required', 'string', 'max:2000'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return response()->json([
                'ok'      => false,
                'message' => 'Tabel ulasan belum dapat diakses.',
            ], 500);
        }

        $now = now();
        $row = [];

        if (Schema::hasColumn($table, 'customer_name')) {
            $row['customer_name'] = trim($data['customer_name']);
        } elseif (Schema::hasColumn($table, 'name')) {
            $row['name'] = trim($data['customer_name']);
        } elseif (Schema::hasColumn($table, 'nama')) {
            $row['nama'] = trim($data['customer_name']);
        }

        if (Schema::hasColumn($table, 'phone_or_order')) {
            $row['phone_or_order'] = trim((string) ($data['phone_or_order'] ?? ''));
        } elseif (Schema::hasColumn($table, 'phone')) {
            $row['phone'] = trim((string) ($data['phone_or_order'] ?? ''));
        }

        $svc = trim((string) ($data['service_or_menu'] ?? 'Delivery')) ?: 'Delivery';
        if (Schema::hasColumn($table, 'service_or_menu')) {
            $row['service_or_menu'] = $svc;
        } elseif (Schema::hasColumn($table, 'service')) {
            $row['service'] = $svc;
        }

        if (Schema::hasColumn($table, 'rating')) {
            $row['rating'] = (int) $data['rating'];
        }

        if (Schema::hasColumn($table, 'comment')) {
            $row['comment'] = trim($data['comment']);
        } elseif (Schema::hasColumn($table, 'review')) {
            $row['review'] = trim($data['comment']);
        } elseif (Schema::hasColumn($table, 'ulasan')) {
            $row['ulasan'] = trim($data['comment']);
        }

        if (Schema::hasColumn($table, 'status')) {
            $row['status'] = 'Menunggu';
        }

        if (Schema::hasColumn($table, 'created_at')) {
            $row['created_at'] = $now;
        }
        if (Schema::hasColumn($table, 'updated_at')) {
            $row['updated_at'] = $now;
        }

        if (!empty($row)) {
            DB::table($table)->insert($row);
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'ok'      => true,
                'message' => 'Terima kasih! Ulasan Anda berhasil dikirim dan menunggu persetujuan admin.',
            ]);
        }

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function index()
    {
        $reviews = [];
        $table = self::resolveTable();

        if ($table) {
            $rows = DB::table($table)->orderByDesc('id')->get();
            foreach ($rows as $r) {
                $rating = max(1, min(5, (int) ($r->rating ?? 5)));
                $reviews[] = [
                    'id'              => $r->id,
                    'customer_name'   => (string) ($r->customer_name ?? $r->name ?? $r->nama ?? 'Pelanggan'),
                    'phone_or_order'  => (string) ($r->phone_or_order ?? $r->phone ?? '-'),
                    'service_or_menu' => (string) ($r->service_or_menu ?? $r->service ?? 'Delivery'),
                    'rating'          => $rating,
                    'comment'         => (string) ($r->comment ?? $r->review ?? $r->ulasan ?? ''),
                    'admin_reply'     => (string) ($r->admin_reply ?? ''),
                    'status'          => $this->normalizeStatus((string) ($r->status ?? 'Menunggu')),
                    'created_at'      => !empty($r->created_at) ? substr((string) $r->created_at, 0, 10) : now()->toDateString(),
                ];
            }
        }

        return view('admin.ulasan', compact('reviews'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'   => ['required', 'string', 'max:150'],
            'phone_or_order'  => ['nullable', 'string', 'max:60'],
            'service_or_menu' => ['nullable', 'string', 'max:120'],
            'rating'          => ['required', 'integer', 'min:1', 'max:5'],
            'comment'         => ['required', 'string', 'max:2000'],
            'admin_reply'     => ['nullable', 'string', 'max:2000'],
            'status'          => ['required', 'string', 'max:30'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel ulasan belum dapat diakses.');
        }

        $now = now();
        $status = $this->normalizeStatus($data['status']);

        DB::table($table)->insert([
            'customer_name'   => trim($data['customer_name']),
            'phone_or_order'  => trim((string) ($data['phone_or_order'] ?? '-')) ?: '-',
            'service_or_menu' => trim((string) ($data['service_or_menu'] ?? 'Delivery')) ?: 'Delivery',
            'rating'          => (int) $data['rating'],
            'comment'         => trim($data['comment']),
            'admin_reply'     => trim((string) ($data['admin_reply'] ?? '')) ?: null,
            'status'          => $status,
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);

        return redirect()->route('admin.ulasan')->with('success', 'Ulasan berhasil ditambahkan.');
    }

    public function reply(Request $request, int $id)
    {
        $data = $request->validate([
            'admin_reply' => ['nullable', 'string', 'max:2000'],
            'status'      => ['nullable', 'string', 'max:30'],
        ]);

        $table = self::resolveTable();
        if ($table) {
            $update = [
                'updated_at' => now(),
            ];

            if (Schema::hasColumn($table, 'admin_reply')) {
                $cleanReply = trim((string) ($data['admin_reply'] ?? ''));
                $update['admin_reply'] = $cleanReply !== '' ? $cleanReply : null;
            }

            if (!empty($data['status']) && Schema::hasColumn($table, 'status')) {
                $update['status'] = $this->normalizeStatus($data['status']);
            }

            DB::table($table)->where('id', $id)->update($update);
        }

        return redirect()->route('admin.ulasan')->with('success', 'Balasan ulasan berhasil disimpan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'customer_name'   => ['required', 'string', 'max:150'],
            'phone_or_order'  => ['nullable', 'string', 'max:60'],
            'service_or_menu' => ['nullable', 'string', 'max:120'],
            'rating'          => ['required', 'integer', 'min:1', 'max:5'],
            'comment'         => ['required', 'string', 'max:2000'],
            'admin_reply'     => ['nullable', 'string', 'max:2000'],
            'status'          => ['required', 'string', 'max:30'],
        ]);

        $table = self::resolveTable();
        if ($table) {
            $status = $this->normalizeStatus($data['status']);
            $update = [
                'updated_at' => now(),
            ];

            if (Schema::hasColumn($table, 'customer_name')) {
                $update['customer_name'] = trim($data['customer_name']);
            }
            if (Schema::hasColumn($table, 'phone_or_order')) {
                $update['phone_or_order'] = trim((string) ($data['phone_or_order'] ?? '-')) ?: '-';
            }
            if (Schema::hasColumn($table, 'service_or_menu')) {
                $update['service_or_menu'] = trim((string) ($data['service_or_menu'] ?? 'Delivery')) ?: 'Delivery';
            }
            if (Schema::hasColumn($table, 'rating')) {
                $update['rating'] = (int) $data['rating'];
            }
            if (Schema::hasColumn($table, 'comment')) {
                $update['comment'] = trim($data['comment']);
            }
            if (Schema::hasColumn($table, 'admin_reply')) {
                $update['admin_reply'] = trim((string) ($data['admin_reply'] ?? '')) ?: null;
            }
            if (Schema::hasColumn($table, 'status')) {
                $update['status'] = $status;
            }

            DB::table($table)->where('id', $id)->update($update);
        }

        return redirect()->route('admin.ulasan')->with('success', 'Ulasan dan balasan berhasil diperbarui.');
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:30'],
        ]);

        $status = $this->normalizeStatus($data['status']);
        $table = self::resolveTable();

        if ($table) {
            DB::table($table)->where('id', $id)->update([
                'status'     => $status,
                'updated_at' => now(),
            ]);
        }

        return response()->json(['ok' => true, 'status' => $status]);
    }

    public function destroy(int $id)
    {
        $table = self::resolveTable();
        if ($table) {
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.ulasan')->with('success', 'Ulasan berhasil dihapus.');
    }
}