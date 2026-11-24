<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminMenuController extends Controller
{
    public function index()
    {
        $menus = [];

        if (Schema::hasTable('menus')) {
            $rows = DB::table('menus')->orderBy('id')->get();
            foreach ($rows as $r) {
                $rawImg = $r->image ?? $r->gambar ?? '';
                $menus[] = [
                    'id'          => $r->id,
                    'name'        => $r->name ?? $r->nama ?? 'Menu Tanpa Nama',
                    'category'    => $r->category ?? $r->kategori ?? 'Makanan Utama',
                    'price'       => (int) ($r->price ?? $r->harga ?? 0),
                    'stock'       => (int) ($r->stock ?? $r->stok ?? 20),
                    'description' => $r->description ?? $r->deskripsi ?? '',
                    'image'       => $rawImg,
                    'image_url' => $this->resolveImageUrl($rawImg, (string) ($r->name ?? $r->nama ?? '')),
                    'is_active'   => isset($r->is_active) ? (bool) $r->is_active : (isset($r->status) ? ($r->status !== 'nonaktif') : true),
                ];
            }
        }

        return view('admin.menu', compact('menus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'category'    => ['required', 'string', 'max:80'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'image.required' => 'Gambar menu wajib diupload saat menambah menu baru.',
            'image.image'    => 'File yang diupload harus berupa gambar.',
            'image.mimes'    => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'image.max'      => 'Ukuran gambar maksimal 2 MB.',
        ]);

        if (!Schema::hasTable('menus')) {
            return back()->with('error', 'Tabel menus belum tersedia di database.');
        }

        $imagePath = $this->uploadMenuImage($request);
        $data['image'] = $imagePath;

        $payload = $this->mapMenuPayload($data, true);
        DB::table('menus')->insert($payload);

        return redirect()->route('admin.menu')->with('success', 'Menu "' . $data['name'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'category'    => ['required', 'string', 'max:80'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'   => ['nullable'],
        ], [
            'image.image' => 'File yang diupload harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'image.max'   => 'Ukuran gambar maksimal 2 MB.',
        ]);

        if (Schema::hasTable('menus')) {
            $existing = DB::table('menus')->where('id', $id)->first();
            $currentImg = $existing->image ?? $existing->gambar ?? '';

            if ($request->hasFile('image')) {
                $this->deleteOldImage($currentImg);
                $data['image'] = $this->uploadMenuImage($request);
            } else {
                $data['image'] = $currentImg;
            }

            $active = $request->has('is_active') ? (bool) $request->input('is_active') : true;
            $payload = $this->mapMenuPayload($data, false, $active);
            DB::table('menus')->where('id', $id)->update($payload);
        }

        return redirect()->route('admin.menu')->with('success', 'Data menu "' . $data['name'] . '" berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        if (Schema::hasTable('menus')) {
            $existing = DB::table('menus')->where('id', $id)->first();
            if ($existing) {
                $this->deleteOldImage($existing->image ?? $existing->gambar ?? '');
            }
            DB::table('menus')->where('id', $id)->delete();
        }

        return redirect()->route('admin.menu')->with('success', 'Menu berhasil dihapus.');
    }

    private function uploadMenuImage(Request $request): string
    {
        $folder = public_path('images/menus');
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $file = $request->file('image');
        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($folder, $filename);

        return 'images/menus/' . $filename;
    }

    private function deleteOldImage(string $path): void
    {
        if ($path === '') {
            return;
        }
        $clean = ltrim($path, '/');
        if (Str::startsWith($clean, 'images/menus/')) {
            $fullPath = public_path($clean);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }

private function resolveImageUrl(string $path, string $menuName = ''): string
    {
        $menuKey = strtolower(trim($menuName));

        $fotoMenu = [
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

        $fallbackImg = File::exists(public_path('images/backgroud.jpg'))
            ? asset('images/' . rawurlencode('backgroud.jpg'))
            : (File::exists(public_path('images/nasi liwet.jpg'))
                ? asset('images/' . rawurlencode('nasi liwet.jpg'))
                : '');

        $mappedFile = $fotoMenu[$menuKey] ?? null;
        if ($mappedFile && File::exists(public_path('images/' . $mappedFile))) {
            return asset('images/' . rawurlencode($mappedFile));
        }

        if ($path !== '') {
            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            $clean = ltrim($path, '/');
            if (File::exists(public_path($clean))) {
                $parts = explode('/', $clean);
                $encodedParts = array_map('rawurlencode', $parts);
                return asset(implode('/', $encodedParts));
            }

            $baseName = basename($clean);
            if ($baseName !== '' && File::exists(public_path('images/' . $baseName))) {
                return asset('images/' . rawurlencode($baseName));
            }
        }

        return $fallbackImg;
    }

private function mapMenuPayload(array $data, bool $isInsert, bool $isActive = true): array
    {
        $row = [];

        if (Schema::hasColumn('menus', 'name')) {
            $row['name'] = $data['name'];
        } elseif (Schema::hasColumn('menus', 'nama')) {
            $row['nama'] = $data['name'];
        }

        $category = $data['category'];
        if ($category === 'Makanan Utama') {
            $category = 'Makanan';
        } elseif ($category === 'Paket Catering') {
            $category = 'Catering';
        }

        if (Schema::hasColumn('menus', 'category')) {
            $row['category'] = $category;
        } elseif (Schema::hasColumn('menus', 'kategori')) {
            $row['kategori'] = $category;
        }

        if (Schema::hasColumn('menus', 'price')) {
            $row['price'] = (int) $data['price'];
        } elseif (Schema::hasColumn('menus', 'harga')) {
            $row['harga'] = (int) $data['price'];
        }

        if (Schema::hasColumn('menus', 'stock')) {
            $row['stock'] = (int) $data['stock'];
        } elseif (Schema::hasColumn('menus', 'stok')) {
            $row['stok'] = (int) $data['stock'];
        }

        if (Schema::hasColumn('menus', 'description')) {
            $row['description'] = $data['description'] ?? '';
        } elseif (Schema::hasColumn('menus', 'deskripsi')) {
            $row['deskripsi'] = $data['description'] ?? '';
        }

        if (Schema::hasColumn('menus', 'image')) {
            $row['image'] = $data['image'] ?? '';
        } elseif (Schema::hasColumn('menus', 'gambar')) {
            $row['gambar'] = $data['image'] ?? '';
        }

        if (Schema::hasColumn('menus', 'is_active')) {
            $row['is_active'] = $isActive ? 1 : 0;
        } elseif (Schema::hasColumn('menus', 'status')) {
            $row['status'] = $isActive ? 'aktif' : 'nonaktif';
        }

        if (Schema::hasColumn('menus', 'updated_at')) {
            $row['updated_at'] = now();
        }
        if ($isInsert && Schema::hasColumn('menus', 'created_at')) {
            $row['created_at'] = now();
        }

        return $row;
    }
}