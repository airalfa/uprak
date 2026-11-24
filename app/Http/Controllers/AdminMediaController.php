<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminMediaController extends Controller
{
    public const PAGES = [
            'Tentang',
            'Katalog',
            'Blog',
            'Kontak',
            'Beranda & Umum',
        ];

    public static function resolveTable(): ?string
    {
        if (Schema::hasTable('media')) {
            return 'media';
        }
        if (Schema::hasTable('media_items')) {
            return 'media_items';
        }

        try {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('title', 180);
                $table->string('page_location', 80)->default('Beranda & Umum');
                $table->string('file_path', 500);
                $table->string('mime_type', 100)->default('image/jpeg');
                $table->unsignedBigInteger('file_size')->default(0);
                $table->string('storage_disk', 30)->default('public');
                $table->string('alt_text', 255)->nullable();
                $table->timestamps();
            });

            return 'media';
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function guessPageFromFile(string $relativePath): string
    {
        $lower = strtolower(str_replace('\\', '/', $relativePath));
        $clean = preg_replace('#^images/#', '', $lower);

        if (str_starts_with($clean, 'blog/')) {
            return 'Blog';
        }
        if (str_starts_with($clean, 'katalog/')) {
            return 'Katalog';
        }
        if (str_starts_with($clean, 'tentang/')) {
            return 'Tentang';
        }
        if (str_starts_with($clean, 'kontak/')) {
            return 'Kontak';
        }

        return 'Beranda & Umum';
    }

    private function humanizeTitle(string $filePath): string
    {
        $name = pathinfo($filePath, PATHINFO_FILENAME);
        $clean = preg_replace('/[-_]+/', ' ', $name);
        return Str::title(trim((string) $clean)) ?: 'Media';
    }

    private function scanPublicImages(): array
    {
        $baseDir = public_path('images');
        if (!File::isDirectory($baseDir)) {
            return [];
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif'];
        $items = [];
        $idx = 1;

        foreach (File::allFiles($baseDir) as $file) {
            $ext = strtolower($file->getExtension());
            if (!in_array($ext, $allowedExt, true)) {
                continue;
            }

            $rel = str_replace('\\', '/', $file->getRelativePathname());
            $webPath = 'images/' . ltrim($rel, '/');
            $size = (int) $file->getSize();
            $mtime = $file->getMTime();

            $items[] = [
                'id'            => 'file-' . ($idx++),
                'title'         => $this->humanizeTitle($webPath),
                'page_location' => $this->guessPageFromFile($webPath),
                'file_path'     => $webPath,
                'url'           => asset($webPath),
                'is_image'      => true,
                'size_label'    => $this->formatBytes($size),
                'storage_disk'  => 'public_images',
                'alt_text'      => $webPath,
                'created_at'    => $mtime ? date('Y-m-d', $mtime) : now()->toDateString(),
                'can_delete'    => false,
            ];
        }

        return $items;
    }

    private function normalizePage(string $raw): string
    {
        $clean = trim($raw);
        foreach (self::PAGES as $page) {
            if (strcasecmp($clean, $page) === 0) {
                return $page;
            }
        }

        $lower = strtolower($clean);
        if (str_contains($lower, 'tentang') || str_contains($lower, 'about')) {
            return 'Tentang';
        }
        if (str_contains($lower, 'katalog') || str_contains($lower, 'menu')) {
            return 'Katalog';
        }
        if (str_contains($lower, 'blog') || str_contains($lower, 'artikel') || str_contains($lower, 'tokoh')) {
            return 'Blog';
        }

        return 'Beranda & Umum';
    }

    private function buildUrl(string $path, string $disk): string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return '';
        }
        if (Str::startsWith($trimmed, ['http://', 'https://'])) {
            return $trimmed;
        }
        if (str_starts_with($trimmed, 'images/')) {
            return asset($trimmed);
        }
        if ($disk === 'public') {
            return asset('storage/' . ltrim($trimmed, '/'));
        }
        return asset(ltrim($trimmed, '/'));
    }

    private function isImageMime(string $mime, string $path): bool
    {
        if (Str::startsWith(strtolower($mime), 'image/')) {
            return true;
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif'], true);
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return 'File Lokal';
        }
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        if ($bytes < 1048576) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return round($bytes / 1048576, 2) . ' MB';
    }

    public function index()
    {
        $mediaItems = [];
        $seenPaths = [];

        $table = self::resolveTable();
        if ($table) {
            $rows = DB::table($table)->orderByDesc('id')->get();
            foreach ($rows as $r) {
                $path = (string) ($r->file_path ?? $r->path ?? $r->url ?? '');
                $disk = (string) ($r->storage_disk ?? $r->disk ?? 'public');
                $mime = (string) ($r->mime_type ?? $r->type ?? 'image/jpeg');
                $size = (int) ($r->file_size ?? $r->size ?? 0);
                $rawPage = (string) ($r->page_location ?? $r->page ?? $r->category ?? $r->kategori ?? 'Beranda & Umum');

                if ($path !== '') {
                    $seenPaths[strtolower($path)] = true;
                }

                $mediaItems[] = [
                    'id'            => $r->id,
                    'title'         => (string) ($r->title ?? $r->name ?? $r->nama ?? 'Media #' . $r->id),
                    'page_location' => $this->normalizePage($rawPage),
                    'file_path'     => $path,
                    'url'           => $this->buildUrl($path, $disk),
                    'is_image'      => $this->isImageMime($mime, $path),
                    'size_label'    => $this->formatBytes($size),
                    'storage_disk'  => $disk,
                    'alt_text'      => (string) ($r->alt_text ?? $r->alt ?? ''),
                    'created_at'    => !empty($r->created_at) ? substr((string) $r->created_at, 0, 10) : now()->toDateString(),
                    'can_delete'    => true,
                ];
            }
        }

        foreach ($this->scanPublicImages() as $fileItem) {
            if (!empty($seenPaths[strtolower($fileItem['file_path'])])) {
                continue;
            }
            $mediaItems[] = $fileItem;
        }

        $pages = self::PAGES;
        return view('admin.media', compact('mediaItems', 'pages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => ['nullable', 'string', 'max:180'],
            'page_location' => ['nullable', 'string', 'max:80'],
            'file'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp,svg,pdf', 'max:5120'],
            'media_url'     => ['nullable', 'string', 'max:500'],
            'alt_text'      => ['nullable', 'string', 'max:255'],
        ]);

        if (!$request->hasFile('file') && empty(trim((string) ($data['media_url'] ?? '')))) {
            return back()->withErrors(['file' => 'Pilih file gambar/dokumen atau masukkan URL gambar terlebih dahulu.'])->withInput();
        }

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel media belum dapat diakses.');
        }

        $now = now();
        $pageLocation = $this->normalizePage((string) ($data['page_location'] ?? 'Beranda & Umum'));
        $altText = trim((string) ($data['alt_text'] ?? '')) ?: null;

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $originalName = pathinfo($uploaded->getClientOriginalName(), PATHINFO_FILENAME);
            $title = trim((string) ($data['title'] ?? '')) ?: ($originalName ?: 'Media Baru');
            $storedPath = $uploaded->store('media', 'public');
            $mime = (string) ($uploaded->getClientMimeType() ?? 'application/octet-stream');
            $size = (int) ($uploaded->getSize() ?? 0);
            $disk = 'public';
        } else {
            $rawUrl = trim((string) $data['media_url']);
            $title = trim((string) ($data['title'] ?? '')) ?: 'Media Eksternal';
            $storedPath = $rawUrl;
            $mime = 'image/jpeg';
            $size = 0;
            $disk = 'url';
        }

        $row = [];
        if (Schema::hasColumn($table, 'title')) {
            $row['title'] = $title;
        } elseif (Schema::hasColumn($table, 'name')) {
            $row['name'] = $title;
        }

        if (Schema::hasColumn($table, 'page_location')) {
            $row['page_location'] = $pageLocation;
        } elseif (Schema::hasColumn($table, 'page')) {
            $row['page'] = $pageLocation;
        } elseif (Schema::hasColumn($table, 'category')) {
            $row['category'] = $pageLocation;
        } elseif (Schema::hasColumn($table, 'kategori')) {
            $row['kategori'] = $pageLocation;
        }

        if (Schema::hasColumn($table, 'file_path')) {
            $row['file_path'] = $storedPath;
        } elseif (Schema::hasColumn($table, 'path')) {
            $row['path'] = $storedPath;
        } elseif (Schema::hasColumn($table, 'url')) {
            $row['url'] = $storedPath;
        }

        if (Schema::hasColumn($table, 'mime_type')) {
            $row['mime_type'] = $mime;
        }
        if (Schema::hasColumn($table, 'file_size')) {
            $row['file_size'] = $size;
        }
        if (Schema::hasColumn($table, 'storage_disk')) {
            $row['storage_disk'] = $disk;
        }
        if (Schema::hasColumn($table, 'alt_text')) {
            $row['alt_text'] = $altText;
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

        return redirect()->route('admin.media')->with('success', "Media berhasil ditambahkan ke kelompok halaman {$pageLocation}.");
    }

    public function destroy(string $id)
    {
        if (str_starts_with($id, 'file-')) {
            return redirect()->route('admin.media')->with('error', 'File bawaan folder public/images tidak dihapus dari pustaka.');
        }

        $table = self::resolveTable();
        if ($table && ctype_digit($id)) {
            $item = DB::table($table)->where('id', (int) $id)->first();
            if ($item) {
                $path = (string) ($item->file_path ?? $item->path ?? '');
                $disk = (string) ($item->storage_disk ?? $item->disk ?? 'public');
                if ($disk === 'public' && $path !== '' && !Str::startsWith($path, ['http://', 'https://'])) {
                    Storage::disk('public')->delete($path);
                }
            }
            DB::table($table)->where('id', (int) $id)->delete();
        }

        return redirect()->route('admin.media')->with('success', 'Media berhasil dihapus.');
    }
}