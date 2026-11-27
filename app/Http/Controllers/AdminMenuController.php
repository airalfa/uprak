<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminMenuController extends Controller
{
    public const MENU_FILE_MAP = [
        'nasi liwet komplit'     => 'nasi liwet.jpg',
        'sate ayam madura'       => 'sate ayam madura.jpg',
        'rendang daging sapi'    => 'rendang daging sapi.jpg',
        'ayam bakar taliwang'    => 'ayam bakar taliwang.jpg',
        'gado gado siram'        => 'gado gado siram.jpg',
        'gado-gado siram'        => 'gado gado siram.jpg',
        'soto betawi daging'     => 'Soto Betawi daging.jpg',
        'es teh manis nusantara' => 'es teh manis nusantara.jpg',
        'es cendol dawet'        => 'es cendol dawet.jpg',
        'wedang jahe hangat'     => 'wedang jahe hangat.jpg',
        'es kelapa muda jeruk'   => 'es kelapa muda jeruk.jpg',
    ];

    public static function resolveTable(): ?string
    {
        foreach (['menus', 'menu', 'products', 'produk'] as $candidate) {
            if (Schema::hasTable($candidate)) {
                return $candidate;
            }
        }

        try {
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->string('name', 160);
                $table->string('category', 80)->default('Makanan');
                $table->unsignedBigInteger('price')->default(0);
                $table->integer('stock')->default(0);
                $table->text('description')->nullable();
                $table->string('image', 500)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            return 'menus';
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function buildEncodedAssetUrl(string $relativePath): string
    {
        $clean = ltrim(str_replace('\\', '/', trim($relativePath)), '/');
        if ($clean === '') {
            return '';
        }

        $segments = explode('/', $clean);
        $encoded = array_map(fn ($seg) => rawurlencode($seg), $segments);
        return asset(implode('/', $encoded));
    }

    private function resolveImageUrl(object $row): string
    {
        $rawImage = trim((string) ($row->image ?? $row->image_url ?? $row->gambar ?? $row->foto ?? ''));
        $menuKey = strtolower(trim((string) ($row->name ?? $row->nama ?? '')));
        $mappedFile = self::MENU_FILE_MAP[$menuKey] ?? null;

        if ($rawImage !== '' && Str::startsWith($rawImage, ['http://', 'https://'])) {
            return $rawImage;
        }

        $baseName = $rawImage !== '' ? basename(str_replace('\\', '/', $rawImage)) : '';

        if ($rawImage !== '' && file_exists(public_path(ltrim(str_replace('\\', '/', $rawImage), '/')))) {
            return $this->buildEncodedAssetUrl($rawImage);
        }

        if ($mappedFile && file_exists(public_path('images/katalog/' . $mappedFile))) {
            return $this->buildEncodedAssetUrl('images/katalog/' . $mappedFile);
        }

        if ($baseName !== '' && file_exists(public_path('images/katalog/' . $baseName))) {
            return $this->buildEncodedAssetUrl('images/katalog/' . $baseName);
        }

        if ($baseName !== '' && file_exists(public_path('images/' . $baseName))) {
            return $this->buildEncodedAssetUrl('images/' . $baseName);
        }

        if ($mappedFile && file_exists(public_path('images/' . $mappedFile))) {
            return $this->buildEncodedAssetUrl('images/' . $mappedFile);
        }

        if (file_exists(public_path('images/backgroud.jpg'))) {
            return $this->buildEncodedAssetUrl('images/backgroud.jpg');
        }

        return '';
    }

    private function storeMenuImage(Request $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $targetDir = public_path('images/katalog');
        if (!File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'menu';
        $fileName = $base . '-' . time() . '-' . Str::random(4) . '.' . $ext;

        $file->move($targetDir, $fileName);

        return 'images/katalog/' . $fileName;
    }

    public function index()
    {
        $menus = [];
        $table = self::resolveTable();

        if ($table) {
            $rows = DB::table($table)->orderBy('id')->get();
            foreach ($rows as $r) {
                $rawActive = $r->is_active ?? $r->active ?? $r->status ?? 1;
                if (is_string($rawActive)) {
                    $isActive = !in_array(strtolower(trim($rawActive)), ['0', 'false', 'nonaktif', 'inactive', 'habis'], true);
                } else {
                    $isActive = (bool) $rawActive;
                }

                $menus[] = [
                    'id'          => $r->id,
                    'name'        => (string) ($r->name ?? $r->nama ?? 'Menu #' . $r->id),
                    'category'    => (string) ($r->category ?? $r->kategori ?? 'Makanan'),
                    'price'       => (int) ($r->price ?? $r->harga ?? 0),
                    'stock'       => (int) ($r->stock ?? $r->stok ?? 0),
                    'description' => (string) ($r->description ?? $r->deskripsi ?? ''),
                    'image_url'   => $this->resolveImageUrl($r),
                    'is_active'   => $isActive,
                ];
            }
        }

        return view('admin.menu', compact('menus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:160'],
            'category'    => ['required', 'string', 'max:80'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel menu belum dapat diakses.');
        }

        $imagePath = $this->storeMenuImage($request);
        $now = now();
        $row = [];

        if (Schema::hasColumn($table, 'name')) {
            $row['name'] = trim($data['name']);
        } elseif (Schema::hasColumn($table, 'nama')) {
            $row['nama'] = trim($data['name']);
        }

        if (Schema::hasColumn($table, 'category')) {
            $row['category'] = trim($data['category']);
        } elseif (Schema::hasColumn($table, 'kategori')) {
            $row['kategori'] = trim($data['category']);
        }

        if (Schema::hasColumn($table, 'price')) {
            $row['price'] = (int) $data['price'];
        } elseif (Schema::hasColumn($table, 'harga')) {
            $row['harga'] = (int) $data['price'];
        }

        if (Schema::hasColumn($table, 'stock')) {
            $row['stock'] = (int) $data['stock'];
        } elseif (Schema::hasColumn($table, 'stok')) {
            $row['stok'] = (int) $data['stock'];
        }

        $desc = trim((string) ($data['description'] ?? '')) ?: null;
        if (Schema::hasColumn($table, 'description')) {
            $row['description'] = $desc;
        } elseif (Schema::hasColumn($table, 'deskripsi')) {
            $row['deskripsi'] = $desc;
        }

        if ($imagePath) {
            if (Schema::hasColumn($table, 'image')) {
                $row['image'] = $imagePath;
            } elseif (Schema::hasColumn($table, 'image_url')) {
                $row['image_url'] = $imagePath;
            } elseif (Schema::hasColumn($table, 'gambar')) {
                $row['gambar'] = $imagePath;
            }
        }

        if (Schema::hasColumn($table, 'is_active')) {
            $row['is_active'] = 1;
        } elseif (Schema::hasColumn($table, 'active')) {
            $row['active'] = 1;
        } elseif (Schema::hasColumn($table, 'status')) {
            $row['status'] = 'Tersedia';
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

        return redirect()->route('admin.menu')->with('success', 'Menu baru berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:160'],
            'category'    => ['required', 'string', 'max:80'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'is_active'   => ['nullable'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel menu belum dapat diakses.');
        }

        $isActive = $request->boolean('is_active');
        $imagePath = $this->storeMenuImage($request);
        $row = [];

        if (Schema::hasColumn($table, 'name')) {
            $row['name'] = trim($data['name']);
        } elseif (Schema::hasColumn($table, 'nama')) {
            $row['nama'] = trim($data['name']);
        }

        if (Schema::hasColumn($table, 'category')) {
            $row['category'] = trim($data['category']);
        } elseif (Schema::hasColumn($table, 'kategori')) {
            $row['kategori'] = trim($data['category']);
        }

        if (Schema::hasColumn($table, 'price')) {
            $row['price'] = (int) $data['price'];
        } elseif (Schema::hasColumn($table, 'harga')) {
            $row['harga'] = (int) $data['price'];
        }

        if (Schema::hasColumn($table, 'stock')) {
            $row['stock'] = (int) $data['stock'];
        } elseif (Schema::hasColumn($table, 'stok')) {
            $row['stok'] = (int) $data['stock'];
        }

        $desc = trim((string) ($data['description'] ?? '')) ?: null;
        if (Schema::hasColumn($table, 'description')) {
            $row['description'] = $desc;
        } elseif (Schema::hasColumn($table, 'deskripsi')) {
            $row['deskripsi'] = $desc;
        }

        if ($imagePath) {
            if (Schema::hasColumn($table, 'image')) {
                $row['image'] = $imagePath;
            } elseif (Schema::hasColumn($table, 'image_url')) {
                $row['image_url'] = $imagePath;
            } elseif (Schema::hasColumn($table, 'gambar')) {
                $row['gambar'] = $imagePath;
            }
        }

        if (Schema::hasColumn($table, 'is_active')) {
            $row['is_active'] = $isActive ? 1 : 0;
        } elseif (Schema::hasColumn($table, 'active')) {
            $row['active'] = $isActive ? 1 : 0;
        } elseif (Schema::hasColumn($table, 'status')) {
            $row['status'] = $isActive ? 'Tersedia' : 'Nonaktif';
        }

        if (Schema::hasColumn($table, 'updated_at')) {
            $row['updated_at'] = now();
        }

        if (!empty($row)) {
            DB::table($table)->where('id', $id)->update($row);
        }

        return redirect()->route('admin.menu')->with('success', 'Data menu berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $table = self::resolveTable();
        if ($table) {
            $item = DB::table($table)->where('id', $id)->first();
            if ($item) {
                $rawImg = trim((string) ($item->image ?? $item->image_url ?? $item->gambar ?? ''));
                $clean = ltrim(str_replace('\\', '/', $rawImg), '/');
                if (str_starts_with($clean, 'images/katalog/')) {
                    $fullPath = public_path($clean);
                    if (file_exists($fullPath) && !array_key_exists(basename($clean), array_flip(self::MENU_FILE_MAP))) {
                        @unlink($fullPath);
                    }
                }
            }
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.menu')->with('success', 'Menu berhasil dihapus.');
    }
}