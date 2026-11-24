<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminReservationController extends Controller
{
    private function resolveTable(): ?string
    {
        if (Schema::hasTable('reservations')) {
            return 'reservations';
        }
        if (Schema::hasTable('reservasi')) {
            return 'reservasi';
        }

        try {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name', 150);
                $table->string('phone', 40)->nullable();
                $table->date('reservation_date');
                $table->string('reservation_time', 20)->default('18:30');
                $table->integer('guests')->default(4);
                $table->string('table_number', 50)->default('Meja 01');
                $table->string('status', 30)->default('Menunggu');
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            $today = now()->toDateString();
            $now = now();

            DB::table('reservations')->insert([
                [
                    'customer_name'    => 'Raden Wijaya',
                    'phone'            => '081223344556',
                    'reservation_date' => $today,
                    'reservation_time' => '12:30',
                    'guests'           => 6,
                    'table_number'     => 'VIP-01',
                    'status'           => 'Dikonfirmasi',
                    'notes'            => 'Keluarga besar, minta area dekat taman.',
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ],
                [
                    'customer_name'    => 'Nadia Kusuma',
                    'phone'            => '085711223344',
                    'reservation_date' => $today,
                    'reservation_time' => '19:00',
                    'guests'           => 4,
                    'table_number'     => 'Meja 05',
                    'status'           => 'Menunggu',
                    'notes'            => 'Ada 1 anak kecil, siapkan kursi anak.',
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ],
                [
                    'customer_name'    => 'Budi Santoso',
                    'phone'            => '081398765432',
                    'reservation_date' => now()->addDay()->toDateString(),
                    'reservation_time' => '18:00',
                    'guests'           => 10,
                    'table_number'     => 'Ruang Lesehan A',
                    'status'           => 'Menunggu',
                    'notes'            => 'Rapat santai tim kantor.',
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ],
            ]);

            return 'reservations';
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeStatus(string $raw): string
    {
        $key = strtolower(trim($raw));
        return match ($key) {
            'menunggu', 'pending', 'waiting' => 'Menunggu',
            'dikonfirmasi', 'confirmed', 'confirm', 'disetujui' => 'Dikonfirmasi',
            'selesai', 'completed', 'done', 'hadir' => 'Selesai',
            'dibatalkan', 'cancelled', 'canceled', 'batal' => 'Dibatalkan',
            default => in_array($raw, ['Menunggu', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'], true) ? $raw : 'Menunggu',
        };
    }

    public function index()
    {
        $reservations = [];
        $table = $this->resolveTable();

        if ($table) {
            $rows = DB::table($table)
                ->orderByDesc('reservation_date')
                ->orderBy('id')
                ->get();

            foreach ($rows as $r) {
                $rawStatus = (string) ($r->status ?? $r->status_reservasi ?? 'Menunggu');
                $reservations[] = [
                    'id'               => $r->id,
                    'customer_name'    => $r->customer_name ?? $r->nama_pelanggan ?? $r->name ?? 'Tamu',
                    'phone'            => $r->phone ?? $r->no_hp ?? $r->telepon ?? '-',
                    'reservation_date' => (string) ($r->reservation_date ?? $r->tanggal ?? now()->toDateString()),
                    'reservation_time' => (string) ($r->reservation_time ?? $r->jam ?? $r->waktu ?? '18:00'),
                    'guests'           => (int) ($r->guests ?? $r->jumlah_tamu ?? $r->orang ?? 2),
                    'table_number'     => $r->table_number ?? $r->no_meja ?? $r->meja ?? 'Meja 01',
                    'status'           => $this->normalizeStatus($rawStatus),
                    'notes'            => $r->notes ?? $r->catatan ?? '',
                ];
            }
        }

        return view('admin.reservasi', compact('reservations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'    => ['required', 'string', 'max:150'],
            'phone'            => ['nullable', 'string', 'max:40'],
            'reservation_date' => ['required', 'date'],
            'reservation_time' => ['required', 'string', 'max:20'],
            'guests'           => ['required', 'integer', 'min:1', 'max:200'],
            'table_number'     => ['required', 'string', 'max:50'],
            'status'           => ['required', 'string', 'max:30'],
            'notes'            => ['nullable', 'string'],
        ]);

        $table = $this->resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel reservasi belum dapat diakses.');
        }

        $data['status'] = $this->normalizeStatus($data['status']);
        $payload = $this->mapPayload($table, $data, true);
        DB::table($table)->insert($payload);

        return redirect()->route('admin.reservasi')->with('success', 'Reservasi atas nama "' . $data['customer_name'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'customer_name'    => ['required', 'string', 'max:150'],
            'phone'            => ['nullable', 'string', 'max:40'],
            'reservation_date' => ['required', 'date'],
            'reservation_time' => ['required', 'string', 'max:20'],
            'guests'           => ['required', 'integer', 'min:1', 'max:200'],
            'table_number'     => ['required', 'string', 'max:50'],
            'status'           => ['required', 'string', 'max:30'],
            'notes'            => ['nullable', 'string'],
        ]);

        $table = $this->resolveTable();
        if ($table) {
            $data['status'] = $this->normalizeStatus($data['status']);
            $payload = $this->mapPayload($table, $data, false);
            DB::table($table)->where('id', $id)->update($payload);
        }

        return redirect()->route('admin.reservasi')->with('success', 'Data reservasi "' . $data['customer_name'] . '" berhasil diperbarui.');
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:30'],
        ]);

        $table = $this->resolveTable();
        $normalized = $this->normalizeStatus($data['status']);

        if ($table) {
            $update = [];
            if (Schema::hasColumn($table, 'status')) {
                $update['status'] = $normalized;
            } elseif (Schema::hasColumn($table, 'status_reservasi')) {
                $update['status_reservasi'] = $normalized;
            }
            if (Schema::hasColumn($table, 'updated_at')) {
                $update['updated_at'] = now();
            }

            if (!empty($update)) {
                DB::table($table)->where('id', $id)->update($update);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok'     => true,
                'status' => $normalized,
            ]);
        }

        return redirect()->route('admin.reservasi')->with('success', 'Status reservasi diubah menjadi ' . $normalized . '.');
    }

    public function destroy(int $id)
    {
        $table = $this->resolveTable();
        if ($table) {
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.reservasi')->with('success', 'Data reservasi berhasil dihapus.');
    }

    private function mapPayload(string $table, array $data, bool $isInsert): array
    {
        $row = [];

        if (Schema::hasColumn($table, 'customer_name')) {
            $row['customer_name'] = $data['customer_name'];
        } elseif (Schema::hasColumn($table, 'nama_pelanggan')) {
            $row['nama_pelanggan'] = $data['customer_name'];
        } elseif (Schema::hasColumn($table, 'name')) {
            $row['name'] = $data['customer_name'];
        }

        if (Schema::hasColumn($table, 'phone')) {
            $row['phone'] = $data['phone'] ?? '';
        } elseif (Schema::hasColumn($table, 'no_hp')) {
            $row['no_hp'] = $data['phone'] ?? '';
        } elseif (Schema::hasColumn($table, 'telepon')) {
            $row['telepon'] = $data['phone'] ?? '';
        }

        if (Schema::hasColumn($table, 'reservation_date')) {
            $row['reservation_date'] = $data['reservation_date'];
        } elseif (Schema::hasColumn($table, 'tanggal')) {
            $row['tanggal'] = $data['reservation_date'];
        }

        if (Schema::hasColumn($table, 'reservation_time')) {
            $row['reservation_time'] = $data['reservation_time'];
        } elseif (Schema::hasColumn($table, 'jam')) {
            $row['jam'] = $data['reservation_time'];
        } elseif (Schema::hasColumn($table, 'waktu')) {
            $row['waktu'] = $data['reservation_time'];
        }

        if (Schema::hasColumn($table, 'guests')) {
            $row['guests'] = (int) $data['guests'];
        } elseif (Schema::hasColumn($table, 'jumlah_tamu')) {
            $row['jumlah_tamu'] = (int) $data['guests'];
        } elseif (Schema::hasColumn($table, 'orang')) {
            $row['orang'] = (int) $data['guests'];
        }

        if (Schema::hasColumn($table, 'table_number')) {
            $row['table_number'] = $data['table_number'];
        } elseif (Schema::hasColumn($table, 'no_meja')) {
            $row['no_meja'] = $data['table_number'];
        } elseif (Schema::hasColumn($table, 'meja')) {
            $row['meja'] = $data['table_number'];
        }

        if (Schema::hasColumn($table, 'status')) {
            $row['status'] = $data['status'];
        } elseif (Schema::hasColumn($table, 'status_reservasi')) {
            $row['status_reservasi'] = $data['status'];
        }

        if (Schema::hasColumn($table, 'notes')) {
            $row['notes'] = $data['notes'] ?? '';
        } elseif (Schema::hasColumn($table, 'catatan')) {
            $row['catatan'] = $data['notes'] ?? '';
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