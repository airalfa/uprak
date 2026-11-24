<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminRolePermissionController extends Controller
{
    public const MODULES = [
        'dashboard'  => 'Dashboard',
        'pesanan'    => 'Pesanan',
        'menu'       => 'Katalog Menu',
        'inventaris' => 'Inventaris',
        'reservasi'  => 'Reservasi',
        'pelanggan'  => 'Pelanggan',
        'promo'      => 'Promo',
        'catering'   => 'Catering & Delivery',
        'keuangan'   => 'Keuangan',
        'laporan'    => 'Laporan',
        'website'    => 'Kelola Website',
        'blog'       => 'Blog',
        'ulasan'     => 'Ulasan',
        'media'      => 'Media',
        'karyawan'   => 'Karyawan',
        'hak_akses'  => 'Hak Akses',
        'pengaturan' => 'Pengaturan',
    ];

    public static function resolveTable(): ?string
    {
        if (Schema::hasTable('role_permissions')) {
            return 'role_permissions';
        }
        if (Schema::hasTable('hak_akses')) {
            return 'hak_akses';
        }

        try {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->string('role_name', 100);
                $table->string('role_slug', 100)->nullable();
                $table->string('description', 255)->nullable();
                $table->json('permissions')->nullable();
                $table->string('status', 30)->default('Aktif'); // Aktif | Nonaktif
                $table->timestamps();
            });

            $allKeys = array_keys(self::MODULES);
            $now = now();

            DB::table('role_permissions')->insert([
                [
                    'role_name'   => 'Super Admin',
                    'role_slug'   => 'super-admin',
                    'description' => 'Akses penuh ke seluruh modul operasional, keuangan, dan pengaturan sistem.',
                    'permissions' => json_encode($allKeys),
                    'status'      => 'Aktif',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'role_name'   => 'Manajer Operasional',
                    'role_slug'   => 'manajer-operasional',
                    'description' => 'Mengelola pesanan, menu, inventaris, reservasi, pelanggan, promo, katering, dan laporan.',
                    'permissions' => json_encode([
                        'dashboard', 'pesanan', 'menu', 'inventaris',
                        'reservasi', 'pelanggan', 'promo', 'catering', 'laporan',
                    ]),
                    'status'      => 'Aktif',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'role_name'   => 'Staf Operasional & Katering',
                    'role_slug'   => 'staf-operasional',
                    'description' => 'Fokus memproses pesanan harian, reservasi, stok bahan, dan pengiriman katering.',
                    'permissions' => json_encode([
                        'dashboard', 'pesanan', 'inventaris', 'reservasi', 'catering',
                    ]),
                    'status'      => 'Aktif',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
                [
                    'role_name'   => 'Admin Konten & Keuangan',
                    'role_slug'   => 'admin-konten-keuangan',
                    'description' => 'Mengelola keuangan, laporan, website, blog, ulasan pelanggan, dan pustaka media.',
                    'permissions' => json_encode([
                        'dashboard', 'keuangan', 'laporan', 'website', 'blog', 'ulasan', 'media',
                    ]),
                    'status'      => 'Aktif',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ],
            ]);

            return 'role_permissions';
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function parsePermissions(mixed $raw): array
    {
        $validKeys = array_keys(self::MODULES);

        if (is_array($raw)) {
            $decoded = $raw;
        } elseif (is_string($raw) && trim($raw) !== '') {
            $tryJson = json_decode($raw, true);
            if (is_array($tryJson)) {
                $decoded = $tryJson;
            } else {
                $decoded = array_map('trim', explode(',', $raw));
            }
        } else {
            $decoded = [];
        }

        $result = [];
        foreach ($decoded as $key => $val) {
            // Mendukung format ['menu', 'blog'] maupun ['menu' => true, 'blog' => false]
            if (is_string($key) && is_bool($val)) {
                if ($val && in_array($key, $validKeys, true)) {
                    $result[] = $key;
                }
            } elseif (is_string($val) && in_array($val, $validKeys, true)) {
                $result[] = $val;
            }
        }

        return array_values(array_unique($result));
    }

    private function normalizeStatus(string $raw): string
    {
        $lower = strtolower(trim($raw));
        if (in_array($lower, ['nonaktif', 'inactive', 'non-aktif', 'off', 'disabled'], true)) {
            return 'Nonaktif';
        }
        return 'Aktif';
    }

    public function index()
    {
        $roles = [];
        $table = self::resolveTable();

        if ($table) {
            $rows = DB::table($table)->orderBy('id')->get();
            foreach ($rows as $r) {
                $rawPerms = $r->permissions ?? $r->akses ?? $r->modules ?? null;
                $perms = $this->parsePermissions($rawPerms);

                $roles[] = [
                    'id'          => $r->id,
                    'role_name'   => (string) ($r->role_name ?? $r->name ?? $r->nama_role ?? 'Role #' . $r->id),
                    'description' => (string) ($r->description ?? $r->deskripsi ?? ''),
                    'permissions' => $perms,
                    'status'      => $this->normalizeStatus((string) ($r->status ?? 'Aktif')),
                ];
            }
        }

        $modules = self::MODULES;
        return view('admin.hak-akses', compact('roles', 'modules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role_name'     => ['required', 'string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'string', 'max:30'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel hak akses belum dapat diakses.');
        }

        $now = now();
        $roleName = trim($data['role_name']);
        $perms = $this->parsePermissions($data['permissions'] ?? []);
        $status = $this->normalizeStatus((string) ($data['status'] ?? 'Aktif'));

        $row = [];
        if (Schema::hasColumn($table, 'role_name')) {
            $row['role_name'] = $roleName;
        } elseif (Schema::hasColumn($table, 'name')) {
            $row['name'] = $roleName;
        } elseif (Schema::hasColumn($table, 'nama_role')) {
            $row['nama_role'] = $roleName;
        }

        if (Schema::hasColumn($table, 'role_slug')) {
            $row['role_slug'] = \Illuminate\Support\Str::slug($roleName);
        }

        $desc = trim((string) ($data['description'] ?? '')) ?: null;
        if (Schema::hasColumn($table, 'description')) {
            $row['description'] = $desc;
        } elseif (Schema::hasColumn($table, 'deskripsi')) {
            $row['deskripsi'] = $desc;
        }

        if (Schema::hasColumn($table, 'permissions')) {
            $row['permissions'] = json_encode($perms);
        } elseif (Schema::hasColumn($table, 'akses')) {
            $row['akses'] = json_encode($perms);
        }

        if (Schema::hasColumn($table, 'status')) {
            $row['status'] = $status;
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

        return redirect()->route('admin.hak_akses')->with('success', "Role \"{$roleName}\" berhasil ditambahkan.");
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'role_name'     => ['nullable', 'string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'string', 'max:30'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel hak akses belum dapat diakses.');
        }

        $perms = $this->parsePermissions($data['permissions'] ?? []);
        $row = [];

        if (!empty($data['role_name'])) {
            $roleName = trim($data['role_name']);
            if (Schema::hasColumn($table, 'role_name')) {
                $row['role_name'] = $roleName;
            } elseif (Schema::hasColumn($table, 'name')) {
                $row['name'] = $roleName;
            } elseif (Schema::hasColumn($table, 'nama_role')) {
                $row['nama_role'] = $roleName;
            }
        }

        if (array_key_exists('description', $data)) {
            $desc = trim((string) ($data['description'] ?? '')) ?: null;
            if (Schema::hasColumn($table, 'description')) {
                $row['description'] = $desc;
            } elseif (Schema::hasColumn($table, 'deskripsi')) {
                $row['deskripsi'] = $desc;
            }
        }

        if (!empty($data['status']) && Schema::hasColumn($table, 'status')) {
            $row['status'] = $this->normalizeStatus($data['status']);
        }

        if (Schema::hasColumn($table, 'permissions')) {
            $row['permissions'] = json_encode($perms);
        } elseif (Schema::hasColumn($table, 'akses')) {
            $row['akses'] = json_encode($perms);
        }

        if (Schema::hasColumn($table, 'updated_at')) {
            $row['updated_at'] = now();
        }

        if (!empty($row)) {
            DB::table($table)->where('id', $id)->update($row);
        }

        return redirect()->route('admin.hak_akses')->with('success', 'Hak akses role berhasil diperbarui.');
    }

    public function toggleStatus(int $id)
    {
        $table = self::resolveTable();
        if ($table && Schema::hasColumn($table, 'status')) {
            $item = DB::table($table)->where('id', $id)->first();
            if ($item) {
                $current = $this->normalizeStatus((string) ($item->status ?? 'Aktif'));
                $next = $current === 'Aktif' ? 'Nonaktif' : 'Aktif';
                $update = ['status' => $next];
                if (Schema::hasColumn($table, 'updated_at')) {
                    $update['updated_at'] = now();
                }
                DB::table($table)->where('id', $id)->update($update);
            }
        }

        return redirect()->route('admin.hak_akses')->with('success', 'Status role berhasil diubah.');
    }

    public function destroy(int $id)
    {
        $table = self::resolveTable();
        if ($table) {
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.hak_akses')->with('success', 'Role berhasil dihapus.');
    }
}