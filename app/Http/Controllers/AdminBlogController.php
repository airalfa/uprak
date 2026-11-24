<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminBlogController extends Controller
{
    public static function resolveTable(): ?string
    {
        if (Schema::hasTable('posts')) {
            return 'posts';
        }
        if (Schema::hasTable('blog_posts')) {
            return 'blog_posts';
        }

        try {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('title', 180);
                $table->string('slug', 200)->unique();
                $table->string('category', 80)->default('Kuliner Nusantara');
                $table->string('author', 100)->default('Tim Kusantara');
                $table->text('excerpt')->nullable();
                $table->longText('content');
                $table->string('status', 30)->default('Terbit'); // Terbit | Draft
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
            });

            $now = now();
            DB::table('posts')->insert([
                [
                    'title' => 'Filosofi Sajian Halalan Thayyiban di Dapur Kusantara',
                    'slug' => 'filosofi-sajian-halalan-thayyiban-di-dapur-kusantara',
                    'category' => 'Prinsip & Syariah',
                    'author' => 'Tim Kusantara',
                    'excerpt' => 'Bagaimana PT. Kusantara menjaga kualitas bahan baku, kebersihan proses, serta kejujuran takaran di setiap hidangan.',
                    'content' => "Setiap sajian di PT. Kusantara disiapkan dengan prinsip halalan thayyiban—tidak hanya halal secara bahan dan proses, tetapi juga baik, bersih, dan bernutrisi.\n\nKami memilih bahan segar dari pemasok terpercaya dan memastikan setiap proses pengolahan hingga pengemasan berjalan transparan.",
                    'status' => 'Terbit',
                    'published_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title' => 'Tips Memilih Paket Katering Acara Kantor & Keluarga',
                    'slug' => 'tips-memilih-paket-katering-acara-kantor-dan-keluarga',
                    'category' => 'Katering & Layanan',
                    'author' => 'Tim Kusantara',
                    'excerpt' => 'Panduan praktis menyesuaikan jumlah porsi, variasi menu Nusantara, dan jadwal pengiriman agar acara berjalan lancar.',
                    'content' => "Menyiapkan konsumsi untuk rapat kantor, syukuran, atau acara keluarga membutuhkan perencanaan porsi dan waktu tiba yang tepat.\n\nKombinasikan lauk utama bercita rasa khas dengan pelengkap yang seimbang, serta konfirmasi jadwal pengiriman minimal H-1 agar tim dapur dapat menyiapkan pesanan secara optimal.",
                    'status' => 'Terbit',
                    'published_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            return 'posts';
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeStatus(string $raw): string
    {
        $val = strtolower(trim($raw));
        if (in_array($val, ['draft', 'konsep', 'nonaktif'], true)) {
            return 'Draft';
        }
        return 'Terbit';
    }

    private function makeUniqueSlug(string $table, string $title, ?int $ignoreId = null): string
    {
        $slugCol = Schema::hasColumn($table, 'slug') ? 'slug' : null;
        $base = Str::slug($title) ?: 'artikel-kusantara';
        if (!$slugCol) {
            return $base;
        }

        $candidate = $base;
        $counter = 2;
        while (true) {
            $q = DB::table($table)->where($slugCol, $candidate);
            if ($ignoreId !== null) {
                $q->where('id', '!=', $ignoreId);
            }
            if (!$q->exists()) {
                return $candidate;
            }
            $candidate = $base . '-' . $counter;
            $counter++;
        }
    }

    public function index()
    {
        $posts = [];
        $table = self::resolveTable();

        if ($table) {
            $rows = DB::table($table)->orderByDesc('id')->get();
            foreach ($rows as $r) {
                $status = $this->normalizeStatus((string) ($r->status ?? 'Terbit'));
                $title = (string) ($r->title ?? $r->judul ?? 'Artikel');
                $content = (string) ($r->content ?? $r->isi ?? $r->body ?? '');
                $excerpt = (string) ($r->excerpt ?? $r->ringkasan ?? Str::limit(strip_tags($content), 120));

                $posts[] = [
                    'id' => $r->id,
                    'title' => $title,
                    'slug' => (string) ($r->slug ?? Str::slug($title)),
                    'category' => (string) ($r->category ?? $r->kategori ?? 'Umum'),
                    'author' => (string) ($r->author ?? $r->penulis ?? 'Tim Kusantara'),
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'status' => $status,
                    'published_at' => !empty($r->published_at)
                        ? substr((string) $r->published_at, 0, 10)
                        : (!empty($r->created_at) ? substr((string) $r->created_at, 0, 10) : now()->toDateString()),
                ];
            }
        }

        return view('admin.blog', compact('posts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:80'],
            'author' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'status' => ['required', 'string', 'max:30'],
        ]);

        $table = self::resolveTable();
        if (!$table) {
            return back()->with('error', 'Tabel blog belum dapat diakses.');
        }

        $status = $this->normalizeStatus($data['status']);
        $slug = $this->makeUniqueSlug($table, $data['title']);
        $now = now();

        $row = [];
        if (Schema::hasColumn($table, 'title')) {
            $row['title'] = trim($data['title']);
        } elseif (Schema::hasColumn($table, 'judul')) {
            $row['judul'] = trim($data['title']);
        }

        if (Schema::hasColumn($table, 'slug')) {
            $row['slug'] = $slug;
        }

        if (Schema::hasColumn($table, 'category')) {
            $row['category'] = trim($data['category']);
        } elseif (Schema::hasColumn($table, 'kategori')) {
            $row['kategori'] = trim($data['category']);
        }

        if (Schema::hasColumn($table, 'author')) {
            $row['author'] = trim((string) ($data['author'] ?? 'Tim Kusantara')) ?: 'Tim Kusantara';
        } elseif (Schema::hasColumn($table, 'penulis')) {
            $row['penulis'] = trim((string) ($data['author'] ?? 'Tim Kusantara')) ?: 'Tim Kusantara';
        }

        $cleanExcerpt = trim((string) ($data['excerpt'] ?? ''));
        if ($cleanExcerpt === '') {
            $cleanExcerpt = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($data['content']))), 130);
        }

        if (Schema::hasColumn($table, 'excerpt')) {
            $row['excerpt'] = $cleanExcerpt;
        } elseif (Schema::hasColumn($table, 'ringkasan')) {
            $row['ringkasan'] = $cleanExcerpt;
        }

        if (Schema::hasColumn($table, 'content')) {
            $row['content'] = $data['content'];
        } elseif (Schema::hasColumn($table, 'isi')) {
            $row['isi'] = $data['content'];
        }

        if (Schema::hasColumn($table, 'status')) {
            $row['status'] = $status;
        }

        if (Schema::hasColumn($table, 'published_at')) {
            $row['published_at'] = $status === 'Terbit' ? $now : null;
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

        $backToBlog = $request->input('redirect_to') === 'blog';
        $targetRoute = $backToBlog ? route('blog.index') : route('admin.blog');

        return redirect($targetRoute)->with('success', 'Artikel "' . $data['title'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:80'],
            'author' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'status' => ['required', 'string', 'max:30'],
        ]);

        $table = self::resolveTable();
        if ($table) {
            $status = $this->normalizeStatus($data['status']);
            $slug = $this->makeUniqueSlug($table, $data['title'], $id);
            $now = now();

            $cleanExcerpt = trim((string) ($data['excerpt'] ?? ''));
            if ($cleanExcerpt === '') {
                $cleanExcerpt = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($data['content']))), 130);
            }

            $row = [];
            if (Schema::hasColumn($table, 'title')) {
                $row['title'] = trim($data['title']);
            } elseif (Schema::hasColumn($table, 'judul')) {
                $row['judul'] = trim($data['title']);
            }

            if (Schema::hasColumn($table, 'slug')) {
                $row['slug'] = $slug;
            }

            if (Schema::hasColumn($table, 'category')) {
                $row['category'] = trim($data['category']);
            } elseif (Schema::hasColumn($table, 'kategori')) {
                $row['kategori'] = trim($data['category']);
            }

            if (Schema::hasColumn($table, 'author')) {
                $row['author'] = trim((string) ($data['author'] ?? 'Tim Kusantara')) ?: 'Tim Kusantara';
            } elseif (Schema::hasColumn($table, 'penulis')) {
                $row['penulis'] = trim((string) ($data['author'] ?? 'Tim Kusantara')) ?: 'Tim Kusantara';
            }

            if (Schema::hasColumn($table, 'excerpt')) {
                $row['excerpt'] = $cleanExcerpt;
            } elseif (Schema::hasColumn($table, 'ringkasan')) {
                $row['ringkasan'] = $cleanExcerpt;
            }

            if (Schema::hasColumn($table, 'content')) {
                $row['content'] = $data['content'];
            } elseif (Schema::hasColumn($table, 'isi')) {
                $row['isi'] = $data['content'];
            }

            if (Schema::hasColumn($table, 'status')) {
                $row['status'] = $status;
            }

            if (Schema::hasColumn($table, 'published_at') && $status === 'Terbit') {
                $existing = DB::table($table)->where('id', $id)->first();
                if (empty($existing->published_at ?? null)) {
                    $row['published_at'] = $now;
                }
            }

            if (Schema::hasColumn($table, 'updated_at')) {
                $row['updated_at'] = $now;
            }

            if (!empty($row)) {
                DB::table($table)->where('id', $id)->update($row);
            }
        }

        return redirect()->route('admin.blog')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function toggleStatus(int $id)
    {
        $table = self::resolveTable();
        if ($table) {
            $row = DB::table($table)->where('id', $id)->first();
            if ($row) {
                $current = $this->normalizeStatus((string) ($row->status ?? 'Terbit'));
                $next = $current === 'Terbit' ? 'Draft' : 'Terbit';

                $update = [];
                if (Schema::hasColumn($table, 'status')) {
                    $update['status'] = $next;
                }
                if (Schema::hasColumn($table, 'published_at') && $next === 'Terbit' && empty($row->published_at ?? null)) {
                    $update['published_at'] = now();
                }
                if (Schema::hasColumn($table, 'updated_at')) {
                    $update['updated_at'] = now();
                }

                if (!empty($update)) {
                    DB::table($table)->where('id', $id)->update($update);
                }

                return response()->json(['ok' => true, 'status' => $next]);
            }
        }

        return response()->json(['ok' => false], 404);
    }

    public function destroy(int $id)
    {
        $table = self::resolveTable();
        if ($table) {
            DB::table($table)->where('id', $id)->delete();
        }

        return redirect()->route('admin.blog')->with('success', 'Artikel berhasil dihapus.');
    }
}