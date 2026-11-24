<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminEmployeeController extends Controller
{
    public const STATUSES = ['Aktif', 'Cuti', 'Nonaktif'];

    public static function resolveTable(): ?string
    {
        if (Schema::hasTable('employees')) {
            return 'employees';
        }
        if (Schema::hasTable('karyawan')) {
            return 'karyawan';
        }

        try {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('employee_code', 40)->nullable();
                $table->string('name', 150);
                $table->string('role_title', 120);
                $table->string('department', 100)->default('Operasional');
                $table->string('shift', 60)->default('Pagi');
                $table->string('phone', 40)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('status', 40)->default('Aktif'); // Aktif | Cuti | Nonaktif
                $table->date('joined_at')->nullable();
                $table->timestamps();
            });

            $now = now();
            DB::table('employees')->insert([
                [
                    'employee_code' => 'KRY-001',
                    'name'          => 'Raka Pratama',
                    'role_title'    => 'Manajer Operasional',
                    'department'    => 'Operasional',
                    'shift'         => 'Penuh Waktu',
                    'phone'         => '0812-2001-1101',
                    'email'         => 'raka@kusantara.id',
                    'status'        => 'Aktif',
                    'joined_at'     => '2025-02-10',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ],
                [
                    'employee_code' => 'KRY-002',
                    'name'          => 'Siti Rahmawati',
                    'role_title'    => 'Kepala Dapur Nusantara',
                    'department'    => 'Dapur & Produksi',
                    'shift'         => 'Pagi',
                    'phone'         => '0813-2002-2202',
                    'email'         => 'siti.dapur@kusantara.id',
                    'status'        => 'Aktif',
                    'joined_at'     => '2025-03-01',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ],
                [
                    'employee_code' => 'KRY-003',
                    'name'          => 'Dimas Nugroho',
                    'role_title'    => 'Koordinator Katering & Delivery',
                    'department'    => 'Logistik & Katering',
                    'shift'         => 'Siang',
                    'phone'         => '0857-2003-3303',
                    'email'         => 'dimas.logistik@kusantara.id',
                    'status'        => 'Aktif',
                    'joined_at'     => '2025-05-15',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ],
                [
                    'employee_code' => 'KRY-004',
                    'name'          => 'Aulia Putri',
                    'role_title'    => 'Admin Keuangan & Kasir',
                    'department'    => 'Keuangan',
                    'shift'         => 'Pagi',
                    'phone'         => '0819-2004-4404',
                    'email'         => 'aulia.keuangan@kusantara.id',
                    'status'        => 'Cuti',
                    'joined_at'     => '2025-07-08',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ],
            ]);

            return 'employees';
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeStatus(string $raw): string
    {
        $clean = trim($raw);
        foreach (self::STATUSES as $st) {
            if (strcasecmp($clean, $st) === 0) {
                return $st;
            }
        }

        $lower = strtolower($clean);
        if (str_contains($lower, 'cuti') || str_contains($lower, 'izin')) {
            return 'Cuti';
        }
        if (str_contains($lower, 'non') || str_contains($lower, 'keluar') || str_contains($lower, 'resign') || str_contains($lower, 'nonaktif')) {
            return 'Nonaktif';
        }

        return 'Aktif';
    }

    public function index()
    {
        $employees = [];
        $table = self::resolveTable();

        if ($table) {
            $rows = DB::table($table)->orderByDesc('id')->get();
            foreach ($rows as $r) {
                $rawStatus = (string) ($r->status ?? 'Aktif');
                $joined = (string) ($r->joined_at ?? $r->join_date ?? $r->tanggal_masuk ?? '');

                $employees[] = [
                    'id'            => $r->id,
                    'employee_code' => (string) ($r->employee_code ?? $r->code ?? $r->nip ?? ('KRY-' . str_pad((string) $r->id, 3, '0', STR_PAD_LEFT))),
                    'name'          => (string) ($r->name ?? $r->nama ?? $r->full_name ?? 'Karyawan #' . $r->id),
                    'role_title'    => (string) ($r->role_title ?? $r->position ?? $r->jabatan ?? 'Staf'),
                    'department'    => (string) ($r->department ?? $r->departemen ?? $r->divisi ?? 'Operasional'),
                    'shift'         => (string) ($r->shift ?? 'Pagi'),
                    'phone'         => (string) ($r->phone ?? $r->telepon ?? $r->no_hp ?? '-'),
                    'email'         => (string) ($r->email ?? '-'),
                    'status'        => $this->normalizeStatus($rawStatus),
                    'joined_at'     => $joined !== '' ? substr($joined, 0, 10) : (!empty($r->created_at) ? substr((string) $r->created_at, 0, 10) : '-'),
                ];
            }
        }

        $statuses = self::STATUSES;
        return view('admin.karyawan', compact('employees', 'statuses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_code' => ['nullable', 'string', 'max:40'],
            'name'          => ['required', 'string', 'max:150'],
            'role_title'    => ['required', 'string', 'max:120'],
            'department'    => ['nullable', 'string', 'max:100'],
            'shift'         => ['nullable', 'string', 'max:60'],
            'phone'         => ['nullable', 'string', 'max:40'],
            'email'         => ['nullable', 'string', 'max:150'],
            'status'        => ['nullable', 'string', 'max:40'],
            'joined_at'     => ['nullable', 'date'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel karyawan belum dapat diakses.');
        }

        $now = now();
        $code = trim((string) ($data['employee_code'] ?? ''));
        if ($code === '') {
            $nextNum = ((int) DB::table($table)->max('id')) + 1;
            $code = 'KRY-' . str_pad((string) $nextNum, 3, '0', STR_PAD_LEFT);
        }

        $row = [];
        if (Schema::hasColumn($table, 'employee_code')) {
            $row['employee_code'] = $code;
        } elseif (Schema::hasColumn($table, 'code')) {
            $row['code'] = $code;
        }

        if (Schema::hasColumn($table, 'name')) {
            $row['name'] = trim($data['name']);
        } elseif (Schema::hasColumn($table, 'nama')) {
            $row['nama'] = trim($data['name']);
        }

        if (Schema::hasColumn($table, 'role_title')) {
            $row['role_title'] = trim($data['role_title']);
        } elseif (Schema::hasColumn($table, 'position')) {
            $row['position'] = trim($data['role_title']);
        } elseif (Schema::hasColumn($table, 'jabatan')) {
            $row['jabatan'] = trim($data['role_title']);
        }

        $dept = trim((string) ($data['department'] ?? 'Operasional')) ?: 'Operasional';
        if (Schema::hasColumn($table, 'department')) {
            $row['department'] = $dept;
        } elseif (Schema::hasColumn($table, 'departemen')) {
            $row['departemen'] = $dept;
        }

        $shift = trim((string) ($data['shift'] ?? 'Pagi')) ?: 'Pagi';
        if (Schema::hasColumn($table, 'shift')) {
            $row['shift'] = $shift;
        }

        $phone = trim((string) ($data['phone'] ?? '')) ?: null;
        if (Schema::hasColumn($table, 'phone')) {
            $row['phone'] = $phone;
        } elseif (Schema::hasColumn($table, 'no_hp')) {
            $row['no_hp'] = $phone;
        }

        $email = trim((string) ($data['email'] ?? '')) ?: null;
        if (Schema::hasColumn($table, 'email')) {
            $row['email'] = $email;
        }

        $status = $this->normalizeStatus((string) ($data['status'] ?? 'Aktif'));
        if (Schema::hasColumn($table, 'status')) {
            $row['status'] = $status;
        }

        $joinedAt = !empty($data['joined_at']) ? $data['joined_at'] : $now->toDateString();
        if (Schema::hasColumn($table, 'joined_at')) {
            $row['joined_at'] = $joinedAt;
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

        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'employee_code' => ['nullable', 'string', 'max:40'],
            'name'          => ['required', 'string', 'max:150'],
            'role_title'    => ['required', 'string', 'max:120'],
            'department'    => ['nullable', 'string', 'max:100'],
            'shift'         => ['nullable', 'string', 'max:60'],
            'phone'         => ['nullable', 'string', 'max:40'],
            'email'         => ['nullable', 'string', 'max:150'],
            'status'        => ['nullable', 'string', 'max:40'],
            'joined_at'     => ['nullable', 'date'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel karyawan belum dapat diakses.');
        }

        $row = [];
        if (!empty($data['employee_code'])) {
            if (Schema::hasColumn($table, 'employee_code')) {
                $row['employee_code'] = trim($data['employee_code']);
            } elseif (Schema::hasColumn($table, 'code')) {
                $row['code'] = trim($data['employee_code']);
            }
        }

        if (Schema::hasColumn($table, 'name')) {
            $row['name'] = trim($data['name']);
        } elseif (Schema::hasColumn($table, 'nama')) {
            $row['nama'] = trim($data['name']);
        }

        if (Schema::hasColumn($table, 'role_title')) {
            $row['role_title'] = trim($data['role_title']);
        } elseif (Schema::hasColumn($table, 'position')) {
            $row['position'] = trim($data['role_title']);
        } elseif (Schema::hasColumn($table, 'jabatan')) {
            $row['jabatan'] = trim($data['role_title']);
        }

        $dept = trim((string) ($data['department'] ?? 'Operasional')) ?: 'Operasional';
        if (Schema::hasColumn($table, 'department')) {
            $row['department'] = $dept;
        } elseif (Schema::hasColumn($table, 'departemen')) {
            $row['departemen'] = $dept;
        }

        $shift = trim((string) ($data['shift'] ?? 'Pagi')) ?: 'Pagi';
        if (Schema::hasColumn($table, 'shift')) {
            $row['shift'] = $shift;
        }

        $phone = trim((string) ($data['phone'] ?? '')) ?: null;
        if (Schema::hasColumn($table, 'phone')) {
            $row['phone'] = $phone;
        } elseif (Schema::hasColumn($table, 'no_hp')) {
            $row['no_hp'] = $phone;
        }

        $email = trim((string) ($data['email'] ?? '')) ?: null;
        if (Schema::hasColumn($table, 'email')) {
            $row['email'] = $email;
        }

        if (!empty($data['status']) && Schema::hasColumn($table, 'status')) {
            $row['status'] = $this->normalizeStatus($data['status']);
        }

        if (!empty($data['joined_at']) && Schema::hasColumn($table, 'joined_at')) {
            $row['joined_at'] = $data['joined_at'];
        }

        if (Schema::hasColumn($table, 'updated_at')) {
            $row['updated_at'] = now();
        }

        if (!empty($row)) {
            DB::table($table)->where('id', $id)->update($row);
        }

        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:40'],
        ]);

        $table = self::resolveTable();
        if ($table && Schema::hasColumn($table, 'status')) {
            $update = ['status' => $this->normalizeStatus($data['status'])];
            if (Schema::hasColumn($table, 'updated_at')) {
                $update['updated_at'] = now();
            }
            DB::table($table)->where('id', $id)->update($update);
        }

        return redirect()->route('admin.karyawan')->with('success', 'Status karyawan diperbarui.');
    }

    public function destroy(int $id)
    {
        $table = self::resolveTable();
        if ($table) {
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil dihapus.');
    }
}