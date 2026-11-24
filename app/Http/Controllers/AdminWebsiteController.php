<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminWebsiteController extends Controller
{
    public static function defaults(): array
    {
        return [
            'brand_name'      => 'PT. Kusantara Kita',
            'tagline'         => 'Cita Rasa Nusantara Halalan Thayyiban & Bebas Riba',
            'hero_badge'      => 'Kuliner Khas Nusantara',
            'announcement'    => 'Gunakan kode promo KUSANTARA10 atau HEMAT15RB saat checkout!',
            'phone'           => '081234567890',
            'whatsapp'        => '6281234567890',
            'email'           => 'halo@kusantara.id',
            'address'         => 'Jl. Nusantara No. 123, Bandung, Jawa Barat',
            'operating_hours' => 'Setiap Hari, 09.00 – 22.00 WIB',
            'about_summary'   => 'PT. Kusantara Kita menghadirkan sajian kuliner Nusantara pilihan dengan standar kehalalan, transparansi harga, dan layanan pemesanan yang praktis.',
            'delivery_info'   => 'Tersedia layanan Dine-in, Delivery, Katering, serta simulasi cicilan bebas riba.',
            'is_maintenance'  => '0',
        ];
    }

    public static function resolveTable(): ?string
    {
        if (Schema::hasTable('site_settings')) {
            return 'site_settings';
        }
        if (Schema::hasTable('website_settings')) {
            return 'website_settings';
        }

        try {
            Schema::create('site_settings', function (Blueprint $table) {
                $table->id();
                $table->string('setting_key', 100)->unique();
                $table->text('setting_value')->nullable();
                $table->timestamps();
            });

            $now = now();
            foreach (self::defaults() as $key => $val) {
                DB::table('site_settings')->insert([
                    'setting_key'   => $key,
                    'setting_value' => $val,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }

            return 'site_settings';
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function getSettings(): array
    {
        $settings = self::defaults();
        $table = self::resolveTable();

        if ($table) {
            $keyCol = Schema::hasColumn($table, 'setting_key')
                ? 'setting_key'
                : (Schema::hasColumn($table, 'key') ? 'key' : null);
            $valCol = Schema::hasColumn($table, 'setting_value')
                ? 'setting_value'
                : (Schema::hasColumn($table, 'value') ? 'value' : null);

            if ($keyCol && $valCol) {
                $rows = DB::table($table)->get();
                foreach ($rows as $r) {
                    $k = (string) ($r->{$keyCol} ?? '');
                    if ($k !== '') {
                        $settings[$k] = (string) ($r->{$valCol} ?? '');
                    }
                }
            }
        }

        return $settings;
    }

    public function index()
    {
        $settings = self::getSettings();
        return view('admin.website', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'brand_name'      => ['required', 'string', 'max:150'],
            'tagline'         => ['nullable', 'string', 'max:255'],
            'hero_badge'      => ['nullable', 'string', 'max:100'],
            'announcement'    => ['nullable', 'string', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:40'],
            'whatsapp'        => ['nullable', 'string', 'max:40'],
            'email'           => ['nullable', 'email', 'max:150'],
            'address'         => ['nullable', 'string', 'max:255'],
            'operating_hours' => ['nullable', 'string', 'max:150'],
            'about_summary'   => ['nullable', 'string'],
            'delivery_info'   => ['nullable', 'string'],
            'is_maintenance'  => ['nullable'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel pengaturan website belum dapat diakses.');
        }

        $keyCol = Schema::hasColumn($table, 'setting_key')
            ? 'setting_key'
            : (Schema::hasColumn($table, 'key') ? 'key' : null);
        $valCol = Schema::hasColumn($table, 'setting_value')
            ? 'setting_value'
            : (Schema::hasColumn($table, 'value') ? 'value' : null);

        if (!$keyCol || !$valCol) {
            return back()->with('error', 'Struktur kolom tabel pengaturan tidak sesuai.');
        }

        $payload = [
            'brand_name'      => trim($data['brand_name']),
            'tagline'         => trim((string) ($data['tagline'] ?? '')),
            'hero_badge'      => trim((string) ($data['hero_badge'] ?? '')),
            'announcement'    => trim((string) ($data['announcement'] ?? '')),
            'phone'           => trim((string) ($data['phone'] ?? '')),
            'whatsapp'        => preg_replace('/[^0-9]/', '', (string) ($data['whatsapp'] ?? '')),
            'email'           => trim((string) ($data['email'] ?? '')),
            'address'         => trim((string) ($data['address'] ?? '')),
            'operating_hours' => trim((string) ($data['operating_hours'] ?? '')),
            'about_summary'   => trim((string) ($data['about_summary'] ?? '')),
            'delivery_info'   => trim((string) ($data['delivery_info'] ?? '')),
            'is_maintenance'  => !empty($data['is_maintenance']) ? '1' : '0',
        ];

        $now = now();
        foreach ($payload as $key => $val) {
            $exists = DB::table($table)->where($keyCol, $key)->exists();
            if ($exists) {
                $upd = [$valCol => $val];
                if (Schema::hasColumn($table, 'updated_at')) {
                    $upd['updated_at'] = $now;
                }
                DB::table($table)->where($keyCol, $key)->update($upd);
            } else {
                $ins = [
                    $keyCol => $key,
                    $valCol => $val,
                ];
                if (Schema::hasColumn($table, 'created_at')) {
                    $ins['created_at'] = $now;
                }
                if (Schema::hasColumn($table, 'updated_at')) {
                    $ins['updated_at'] = $now;
                }
                DB::table($table)->insert($ins);
            }
        }

        return redirect()->route('admin.website')->with('success', 'Pengaturan konten & informasi website berhasil disimpan.');
    }
}