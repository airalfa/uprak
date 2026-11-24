<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminSettingController extends Controller
{
    public const GROUPS = [
        'Umum',
        'Operasional',
        'Pesanan & Pembayaran',
        'Keamanan & Sistem',
    ];

    public static function defaults(): array
    {
        return [
            // Umum
            'brand_name' => [
                'group' => 'Umum',
                'label' => 'Nama Brand / Perusahaan',
                'type'  => 'text',
                'value' => 'PT. Kusantara Kita',
                'hint'  => 'Ditampilkan pada identitas sistem dan halaman utama.',
            ],
            'tagline' => [
                'group' => 'Umum',
                'label' => 'Tagline / Slogan',
                'type'  => 'text',
                'value' => 'Cita Rasa Nusantara Halalan Thayyiban',
                'hint'  => 'Kalimat singkat yang menggambarkan nilai utama brand.',
            ],
            'contact_email' => [
                'group' => 'Umum',
                'label' => 'Email Resmi',
                'type'  => 'text',
                'value' => 'halo@kusantara.id',
                'hint'  => 'Alamat email kontak dan informasi resmi pelanggan.',
            ],
            'contact_phone' => [
                'group' => 'Umum',
                'label' => 'No. Telepon / WhatsApp',
                'type'  => 'text',
                'value' => '0812-2026-0000',
                'hint'  => 'Nomor layanan pelanggan, reservasi, dan katering.',
            ],
            'office_address' => [
                'group' => 'Umum',
                'label' => 'Alamat Utama / Dapur Pusat',
                'type'  => 'text',
                'value' => 'Jl. Karangsetra No. 24, Bandung, Jawa Barat',
                'hint'  => 'Lokasi operasional utama dan titik pengiriman.',
            ],

            // Operasional
            'operating_hours' => [
                'group' => 'Operasional',
                'label' => 'Jam Operasional',
                'type'  => 'text',
                'value' => 'Senin – Minggu, 08.00 – 21.30 WIB',
                'hint'  => 'Jam layanan makan di tempat, antar, dan pemesanan.',
            ],
            'timezone' => [
                'group' => 'Operasional',
                'label' => 'Zona Waktu',
                'type'  => 'text',
                'value' => 'Asia/Jakarta (WIB)',
                'hint'  => 'Acuan waktu operasional dan pencatatan transaksi.',
            ],
            'auto_accept_orders' => [
                'group' => 'Operasional',
                'label' => 'Terima Pesanan Otomatis',
                'type'  => 'toggle',
                'value' => '1',
                'hint'  => 'Pesanan baru otomatis masuk antrean proses dapur/kasir.',
            ],
            'enable_catering' => [
                'group' => 'Operasional',
                'label' => 'Aktifkan Layanan Katering',
                'type'  => 'toggle',
                'value' => '1',
                'hint'  => 'Membuka layanan pesanan acara dan katering khusus.',
            ],
            'enable_delivery' => [
                'group' => 'Operasional',
                'label' => 'Aktifkan Layanan Delivery',
                'type'  => 'toggle',
                'value' => '1',
                'hint'  => 'Membuka opsi pengantaran pesanan ke alamat pelanggan.',
            ],

            // Pesanan & Pembayaran
            'currency_symbol' => [
                'group' => 'Pesanan & Pembayaran',
                'label' => 'Simbol Mata Uang',
                'type'  => 'text',
                'value' => 'Rp',
                'hint'  => 'Simbol mata uang yang digunakan pada katalog dan laporan.',
            ],
            'min_order_amount' => [
                'group' => 'Pesanan & Pembayaran',
                'label' => 'Minimal Pesanan (Rp)',
                'type'  => 'number',
                'value' => '25000',
                'hint'  => 'Batas minimal nominal pesanan dalam rupiah.',
            ],
            'delivery_fee' => [
                'group' => 'Pesanan & Pembayaran',
                'label' => 'Tarif Ongkir Default (Rp)',
                'type'  => 'number',
                'value' => '12000',
                'hint'  => 'Biaya pengantaran standar jika belum diatur per zona.',
            ],
            'tax_percentage' => [
                'group' => 'Pesanan & Pembayaran',
                'label' => 'Pajak / Layanan (%)',
                'type'  => 'number',
                'value' => '0',
                'hint'  => 'Persentase tambahan biaya layanan/pajak (0 jika inklusif).',
            ],
            'payment_notes' => [
                'group' => 'Pesanan & Pembayaran',
                'label' => 'Catatan Pembayaran Transparan',
                'type'  => 'text',
                'value' => 'Pembayaran jelas, transparan, dan bebas riba sesuai prinsip syariat.',
                'hint'  => 'Pesan informasi pembayaran yang tampil di halaman checkout.',
            ],

            // Keamanan & Sistem
            'maintenance_mode' => [
                'group' => 'Keamanan & Sistem',
                'label' => 'Mode Pemeliharaan (Maintenance)',
                'type'  => 'toggle',
                'value' => '0',
                'hint'  => 'Tandai jika sedang dilakukan perawatan berkala pada sistem.',
            ],
            'review_moderation' => [
                'group' => 'Keamanan & Sistem',
                'label' => 'Moderasi Ulasan Pelanggan',
                'type'  => 'toggle',
                'value' => '1',
                'hint'  => 'Ulasan baru perlu persetujuan admin sebelum tampil publik.',
            ],
            'session_timeout_minutes' => [
                'group' => 'Keamanan & Sistem',
                'label' => 'Batas Waktu Sesi Admin (Menit)',
                'type'  => 'number',
                'value' => '120',
                'hint'  => 'Durasi sesi admin sebelum diminta login ulang.',
            ],
            'pagination_limit' => [
                'group' => 'Keamanan & Sistem',
                'label' => 'Jumlah Baris Default per Tabel',
                'type'  => 'number',
                'value' => '25',
                'hint'  => 'Jumlah data standar yang ditampilkan di tabel admin.',
            ],
        ];
    }

    public static function resolveTable(): ?string
    {
        foreach (['site_settings', 'settings', 'pengaturan'] as $candidate) {
            if (Schema::hasTable($candidate)) {
                return $candidate;
            }
        }

        try {
            Schema::create('site_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 120)->unique();
                $table->string('group_name', 80)->default('Umum');
                $table->text('value')->nullable();
                $table->timestamps();
            });

            return 'site_settings';
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function upsertSetting(string $table, string $key, string $group, ?string $value): void
    {
        $keyCol = Schema::hasColumn($table, 'key')
            ? 'key'
            : (Schema::hasColumn($table, 'setting_key')
                ? 'setting_key'
                : (Schema::hasColumn($table, 'name') ? 'name' : null));

        $valCol = Schema::hasColumn($table, 'value')
            ? 'value'
            : (Schema::hasColumn($table, 'setting_value') ? 'setting_value' : null);

        if (!$keyCol || !$valCol) {
            return;
        }

        $groupCol = Schema::hasColumn($table, 'group_name')
            ? 'group_name'
            : (Schema::hasColumn($table, 'group') ? 'group' : null);

        $now = now();
        $exists = DB::table($table)->where($keyCol, $key)->first();

        if ($exists) {
            $update = [$valCol => $value];
            if ($groupCol) {
                $update[$groupCol] = $group;
            }
            if (Schema::hasColumn($table, 'updated_at')) {
                $update['updated_at'] = $now;
            }
            DB::table($table)->where($keyCol, $key)->update($update);
        } else {
            $insert = [
                $keyCol => $key,
                $valCol => $value,
            ];
            if ($groupCol) {
                $insert[$groupCol] = $group;
            }
            if (Schema::hasColumn($table, 'created_at')) {
                $insert['created_at'] = $now;
            }
            if (Schema::hasColumn($table, 'updated_at')) {
                $insert['updated_at'] = $now;
            }
            DB::table($table)->insert($insert);
        }
    }

    public function index()
    {
        $defaults = self::defaults();
        $groups = self::GROUPS;
        $values = [];

        foreach ($defaults as $key => $meta) {
            $values[$key] = $meta['value'];
        }

        $table = self::resolveTable();
        if ($table) {
            $keyCol = Schema::hasColumn($table, 'key')
                ? 'key'
                : (Schema::hasColumn($table, 'setting_key')
                    ? 'setting_key'
                    : (Schema::hasColumn($table, 'name') ? 'name' : null));

            $valCol = Schema::hasColumn($table, 'value')
                ? 'value'
                : (Schema::hasColumn($table, 'setting_value') ? 'setting_value' : null);

            if ($keyCol && $valCol) {
                $rows = DB::table($table)->get();
                foreach ($rows as $r) {
                    $k = (string) ($r->{$keyCol} ?? '');
                    if ($k !== '' && array_key_exists($k, $values)) {
                        $values[$k] = (string) ($r->{$valCol} ?? '');
                    }
                }
            }
        }

        return view('admin.pengaturan', compact('groups', 'defaults', 'values'));
    }

    public function update(Request $request)
    {
        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel pengaturan belum dapat diakses.');
        }

        $defaults = self::defaults();
        $inputSettings = $request->input('settings', []);

        foreach ($defaults as $key => $meta) {
            if ($meta['type'] === 'toggle') {
                $val = (!empty($inputSettings[$key]) && (string) $inputSettings[$key] !== '0') ? '1' : '0';
            } else {
                $raw = $inputSettings[$key] ?? $meta['value'];
                $val = is_scalar($raw) ? trim((string) $raw) : $meta['value'];
            }

            $this->upsertSetting($table, $key, $meta['group'], $val);
        }

        return redirect()->route('admin.pengaturan')->with('success', 'Pengaturan sistem berhasil disimpan.');
    }

    public function reset()
    {
        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel pengaturan belum dapat diakses.');
        }

        foreach (self::defaults() as $key => $meta) {
            $this->upsertSetting($table, $key, $meta['group'], $meta['value']);
        }

        return redirect()->route('admin.pengaturan')->with('success', 'Pengaturan berhasil dikembalikan ke nilai bawaan.');
    }
}