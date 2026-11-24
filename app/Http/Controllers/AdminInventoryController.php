<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminInventoryController extends Controller
{
    private function resolveTable(): ?string
    {
        if (Schema::hasTable('inventories')) {
            return 'inventories';
        }
        if (Schema::hasTable('inventaris')) {
            return 'inventaris';
        }

        try {
            Schema::create('inventories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('category', 80)->default('Bahan Baku');
                $table->integer('stock')->default(0);
                $table->integer('min_stock')->default(10);
                $table->string('unit', 30)->default('pcs');
                $table->integer('unit_price')->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            $now = now();
            DB::table('inventories')->insert([
                [
                    'name'       => 'Beras Pandan Wangi',
                    'category'   => 'Bahan Baku',
                    'stock'      => 85,
                    'min_stock'  => 25,
                    'unit'       => 'kg',
                    'unit_price' => 14500,
                    'notes'      => 'Stok utama nasi liwet dan paket nasi.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name'       => 'Daging Sapi Has Dalam',
                    'category'   => 'Bahan Baku',
                    'stock'      => 12,
                    'min_stock'  => 15,
                    'unit'       => 'kg',
                    'unit_price' => 125000,
                    'notes'      => 'Perlu restok untuk rendang dan soto betawi.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name'       => 'Gula Aren Asli',
                    'category'   => 'Bahan Baku',
                    'stock'      => 0,
                    'min_stock'  => 10,
                    'unit'       => 'kg',
                    'unit_price' => 28000,
                    'notes'      => 'Habis untuk es cendol dawet dan pemanis.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name'       => 'Box Kemasan Takeaway & Catering',
                    'category'   => 'Kemasan',
                    'stock'      => 240,
                    'min_stock'  => 50,
                    'unit'       => 'pcs',
                    'unit_price' => 1800,
                    'notes'      => 'Kemasan food grade tahan panas.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            return 'inventories';
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function index()
    {
        $items = [];
        $table = $this->resolveTable();

        if ($table) {
            $rows = DB::table($table)->orderBy('id')->get();
            foreach ($rows as $r) {
                $stock = (int) ($r->stock ?? $r->stok ?? 0);
                $minStock = (int) ($r->min_stock ?? $r->stok_minimum ?? 10);

                if ($stock <= 0) {
                    $status = 'Habis';
                } elseif ($stock <= $minStock) {
                    $status = 'Menipis';
                } else {
                    $status = 'Aman';
                }

                $items[] = [
                    'id'         => $r->id,
                    'name'       => $r->name ?? $r->nama ?? 'Item Tanpa Nama',
                    'category'   => $r->category ?? $r->kategori ?? 'Bahan Baku',
                    'stock'      => $stock,
                    'min_stock'  => $minStock,
                    'unit'       => $r->unit ?? $r->satuan ?? 'pcs',
                    'unit_price' => (int) ($r->unit_price ?? $r->harga_satuan ?? $r->harga ?? 0),
                    'notes'      => $r->notes ?? $r->catatan ?? $r->keterangan ?? '',
                    'status'     => $status,
                ];
            }
        }

        return view('admin.inventaris', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:150'],
            'category'   => ['required', 'string', 'max:80'],
            'stock'      => ['required', 'integer', 'min:0'],
            'min_stock'  => ['required', 'integer', 'min:0'],
            'unit'       => ['required', 'string', 'max:30'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'notes'      => ['nullable', 'string'],
        ]);

        $table = $this->resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel inventaris belum dapat diakses.');
        }

        $payload = $this->mapPayload($table, $data, true);
        DB::table($table)->insert($payload);

        return redirect()->route('admin.inventaris')->with('success', 'Item "' . $data['name'] . '" berhasil ditambahkan ke inventaris.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:150'],
            'category'   => ['required', 'string', 'max:80'],
            'stock'      => ['required', 'integer', 'min:0'],
            'min_stock'  => ['required', 'integer', 'min:0'],
            'unit'       => ['required', 'string', 'max:30'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'notes'      => ['nullable', 'string'],
        ]);

        $table = $this->resolveTable();
        if ($table) {
            $payload = $this->mapPayload($table, $data, false);
            DB::table($table)->where('id', $id)->update($payload);
        }

        return redirect()->route('admin.inventaris')->with('success', 'Data inventaris "' . $data['name'] . '" berhasil diperbarui.');
    }

    public function adjustStock(Request $request, int $id)
    {
        $data = $request->validate([
            'delta' => ['required', 'integer'],
        ]);

        $table = $this->resolveTable();
        if ($table) {
            $row = DB::table($table)->where('id', $id)->first();
            if ($row) {
                $currentStock = (int) ($row->stock ?? $row->stok ?? 0);
                $nextStock = max(0, $currentStock + (int) $data['delta']);

                $update = [];
                if (Schema::hasColumn($table, 'stock')) {
                    $update['stock'] = $nextStock;
                } elseif (Schema::hasColumn($table, 'stok')) {
                    $update['stok'] = $nextStock;
                }
                if (Schema::hasColumn($table, 'updated_at')) {
                    $update['updated_at'] = now();
                }

                if (!empty($update)) {
                    DB::table($table)->where('id', $id)->update($update);
                }

                return response()->json([
                    'ok'    => true,
                    'stock' => $nextStock,
                ]);
            }
        }

        return response()->json(['ok' => false], 404);
    }

    public function destroy(int $id)
    {
        $table = $this->resolveTable();
        if ($table) {
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.inventaris')->with('success', 'Item inventaris berhasil dihapus.');
    }

    private function mapPayload(string $table, array $data, bool $isInsert): array
    {
        $row = [];

        if (Schema::hasColumn($table, 'name')) {
            $row['name'] = $data['name'];
        } elseif (Schema::hasColumn($table, 'nama')) {
            $row['nama'] = $data['name'];
        }

        if (Schema::hasColumn($table, 'category')) {
            $row['category'] = $data['category'];
        } elseif (Schema::hasColumn($table, 'kategori')) {
            $row['kategori'] = $data['category'];
        }

        if (Schema::hasColumn($table, 'stock')) {
            $row['stock'] = (int) $data['stock'];
        } elseif (Schema::hasColumn($table, 'stok')) {
            $row['stok'] = (int) $data['stock'];
        }

        if (Schema::hasColumn($table, 'min_stock')) {
            $row['min_stock'] = (int) $data['min_stock'];
        } elseif (Schema::hasColumn($table, 'stok_minimum')) {
            $row['stok_minimum'] = (int) $data['min_stock'];
        }

        if (Schema::hasColumn($table, 'unit')) {
            $row['unit'] = $data['unit'];
        } elseif (Schema::hasColumn($table, 'satuan')) {
            $row['satuan'] = $data['unit'];
        }

        if (Schema::hasColumn($table, 'unit_price')) {
            $row['unit_price'] = (int) $data['unit_price'];
        } elseif (Schema::hasColumn($table, 'harga_satuan')) {
            $row['harga_satuan'] = (int) $data['unit_price'];
        } elseif (Schema::hasColumn($table, 'harga')) {
            $row['harga'] = (int) $data['unit_price'];
        }

        if (Schema::hasColumn($table, 'notes')) {
            $row['notes'] = $data['notes'] ?? '';
        } elseif (Schema::hasColumn($table, 'catatan')) {
            $row['catatan'] = $data['notes'] ?? '';
        } elseif (Schema::hasColumn($table, 'keterangan')) {
            $row['keterangan'] = $data['notes'] ?? '';
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