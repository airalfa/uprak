<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\AdminInventoryController;
use App\Http\Controllers\AdminReservationController;
use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\AdminPromoController;
use App\Http\Controllers\AdminCateringDeliveryController;
use App\Http\Controllers\AdminFinanceController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminWebsiteController;
use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\AdminMediaController;
use App\Http\Controllers\AdminEmployeeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('partials.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/blog', function () {
    $table = AdminBlogController::resolveTable();
    $posts = [];

    if ($table) {
        $rows = DB::table($table)
            ->orderByDesc('id')
            ->get();

        foreach ($rows as $r) {
            $status = strtolower(trim((string) ($r->status ?? 'Terbit')));
            if (in_array($status, ['draft', 'konsep', 'nonaktif'], true)) {
                continue;
            }

            $title = (string) ($r->title ?? $r->judul ?? 'Artikel');
            $content = (string) ($r->content ?? $r->isi ?? $r->body ?? '');
            $excerpt = (string) ($r->excerpt ?? $r->ringkasan ?? Str::limit(strip_tags($content), 140));

            $posts[] = [
                'id' => $r->id,
                'title' => $title,
                'slug' => (string) ($r->slug ?? Str::slug($title)),
                'category' => (string) ($r->category ?? $r->kategori ?? 'Kuliner Nusantara'),
                'author' => (string) ($r->author ?? $r->penulis ?? 'Tim Kusantara'),
                'excerpt' => $excerpt,
                'content' => $content,
                'published_at' => !empty($r->published_at)
                    ? substr((string) $r->published_at, 0, 10)
                    : (!empty($r->created_at) ? substr((string) $r->created_at, 0, 10) : now()->toDateString()),
            ];
        }
    }

    return view('blog', compact('posts'));
})->name('blog.index');

Route::get('/katalog', [MenuController::class, 'index'])->name('katalog.index');

Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::put('/cart/{menuId}', [CartController::class, 'update'])->name('cart.update');

Route::get('/bukti-beriman', function () {
    $dalilList = [
        [
            'icon' => 'fa-book-open',
            'title' => "Al-Qur'an",
            'quote' => '"Hai orang-orang yang beriman, janganlah kamu saling memakan harta sesamamu dengan jalan yang batil..."',
            'source' => '(QS. An-Nisa: 29)',
        ],
        [
            'icon' => 'fa-kaaba',
            'title' => 'Hadis',
            'quote' => '"Pedagang yang jujur dan terpercaya (akan bersama) para nabi, orang-orang yang shiddiq, dan para syuhada."',
            'source' => '(HR. Tirmidzi)',
        ],
    ];

    $penerapanList = [
        'Jaminan kehalalan produk, bahan baku, dan proses di seluruh lini bisnis.',
        'Transparansi saluran dan harga yang adil kepada seluruh pelanggan.',
        'Solusi transaksi bebas riba yang jelas dan sesuai syariat pada sistem pembayaran.',
    ];

    return view('bukti-beriman', compact('dalilList', 'penerapanList'));
})->name('bukti-beriman');

Route::view('/checkout', 'checkout')->name('checkout.index');

Route::get('/tentang', function () {
    $table = AdminReviewController::resolveTable();
    $publishedReviews = [];

    if ($table) {
        $rows = DB::table($table)
            ->orderByDesc('id')
            ->get();

        foreach ($rows as $r) {
            $status = strtolower(trim((string) ($r->status ?? 'Menunggu')));
            if (!in_array($status, ['terbit', 'published', 'approved', 'aktif', 'tampil'], true)) {
                continue;
            }

            $publishedReviews[] = [
                'customer_name' => (string) ($r->customer_name ?? $r->name ?? $r->nama ?? 'Pelanggan'),
                'service_or_menu' => (string) ($r->service_or_menu ?? $r->service ?? 'Delivery'),
                'rating' => max(1, min(5, (int) ($r->rating ?? 5))),
                'comment' => (string) ($r->comment ?? $r->review ?? $r->ulasan ?? ''),
                'admin_reply' => (string) ($r->admin_reply ?? ''),
                'created_at' => !empty($r->created_at) ? substr((string) $r->created_at, 0, 10) : now()->toDateString(),
            ];
        }
    }

    return view('tentang', compact('publishedReviews'));
})->name('tentang.index');

Route::view('/kontak', 'kontak')->name('kontak.index');

// Route publik checkout pelanggan (tanpa login)
Route::post('/pesanan', [OrderController::class, 'store'])->name('pesanan.store');

// Route autentikasi khusus admin
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::post('/promo/validate', [AdminPromoController::class, 'validateCode'])->name('promo.validate');

Route::post('/ulasan', [AdminReviewController::class, 'storePublic'])->name('ulasan.store');

// Route area admin (terproteksi middleware admin.auth & izin fitur per role)
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->middleware('admin.auth:dashboard')->name('dashboard');

    Route::get('/pesanan', [OrderController::class, 'index'])
        ->middleware('admin.auth:pesanan')
        ->name('pesanan');

    Route::patch('/pesanan/{id}/status', [OrderController::class, 'updateStatus'])
        ->middleware('admin.auth:pesanan')
        ->name('pesanan.status');

    Route::get('/menu', [AdminMenuController::class, 'index'])
        ->middleware('admin.auth:menu')
        ->name('menu');

    Route::post('/menu', [AdminMenuController::class, 'store'])
        ->middleware('admin.auth:menu')
        ->name('menu.store');

    Route::put('/menu/{id}', [AdminMenuController::class, 'update'])
        ->middleware('admin.auth:menu')
        ->name('menu.update');

    Route::delete('/menu/{id}', [AdminMenuController::class, 'destroy'])
        ->middleware('admin.auth:menu')
        ->name('menu.destroy');

    Route::get('/inventaris', [AdminInventoryController::class, 'index'])
        ->middleware('admin.auth:inventaris')
        ->name('inventaris');

    Route::post('/inventaris', [AdminInventoryController::class, 'store'])
        ->middleware('admin.auth:inventaris')
        ->name('inventaris.store');

    Route::put('/inventaris/{id}', [AdminInventoryController::class, 'update'])
        ->middleware('admin.auth:inventaris')
        ->name('inventaris.update');

    Route::patch('/inventaris/{id}/adjust-stock', [AdminInventoryController::class, 'adjustStock'])
        ->middleware('admin.auth:inventaris')
        ->name('inventaris.adjust-stock');

    Route::delete('/inventaris/{id}', [AdminInventoryController::class, 'destroy'])
        ->middleware('admin.auth:inventaris')
        ->name('inventaris.destroy');

    Route::get('/reservasi', [AdminReservationController::class, 'index'])
        ->middleware('admin.auth:reservasi')
        ->name('reservasi');

    Route::post('/reservasi', [AdminReservationController::class, 'store'])
        ->middleware('admin.auth:reservasi')
        ->name('reservasi.store');

    Route::put('/reservasi/{id}', [AdminReservationController::class, 'update'])
        ->middleware('admin.auth:reservasi')
        ->name('reservasi.update');

    Route::patch('/reservasi/{id}/status', [AdminReservationController::class, 'updateStatus'])
        ->middleware('admin.auth:reservasi')
        ->name('reservasi.status');

    Route::delete('/reservasi/{id}', [AdminReservationController::class, 'destroy'])
        ->middleware('admin.auth:reservasi')
        ->name('reservasi.destroy');

    Route::get('/pelanggan', [AdminCustomerController::class, 'index'])
        ->middleware('admin.auth:pelanggan')
        ->name('pelanggan');

    Route::post('/pelanggan', [AdminCustomerController::class, 'store'])
        ->middleware('admin.auth:pelanggan')
        ->name('pelanggan.store');

    Route::put('/pelanggan/{id}', [AdminCustomerController::class, 'update'])
        ->middleware('admin.auth:pelanggan')
        ->name('pelanggan.update');

    Route::delete('/pelanggan/{id}', [AdminCustomerController::class, 'destroy'])
        ->middleware('admin.auth:pelanggan')
        ->name('pelanggan.destroy');

    Route::get('/promo', [AdminPromoController::class, 'index'])
        ->middleware('admin.auth:promo')
        ->name('promo');

    Route::post('/promo', [AdminPromoController::class, 'store'])
        ->middleware('admin.auth:promo')
        ->name('promo.store');

    Route::put('/promo/{id}', [AdminPromoController::class, 'update'])
        ->middleware('admin.auth:promo')
        ->name('promo.update');

    Route::patch('/promo/{id}/toggle', [AdminPromoController::class, 'toggleActive'])
        ->middleware('admin.auth:promo')
        ->name('promo.toggle');

    Route::delete('/promo/{id}', [AdminPromoController::class, 'destroy'])
        ->middleware('admin.auth:promo')
        ->name('promo.destroy');

    Route::get('/catering-delivery', [AdminCateringDeliveryController::class, 'index'])
        ->middleware('admin.auth:catering')
        ->name('catering');

    Route::post('/catering-delivery', [AdminCateringDeliveryController::class, 'store'])
        ->middleware('admin.auth:catering')
        ->name('catering.store');

    Route::put('/catering-delivery/{id}', [AdminCateringDeliveryController::class, 'update'])
        ->middleware('admin.auth:catering')
        ->name('catering.update');

    Route::patch('/catering-delivery/{id}/status', [AdminCateringDeliveryController::class, 'updateStatus'])
        ->middleware('admin.auth:catering')
        ->name('catering.status');

    Route::delete('/catering-delivery/{id}', [AdminCateringDeliveryController::class, 'destroy'])
        ->middleware('admin.auth:catering')
        ->name('catering.destroy');

    Route::get('/keuangan', [AdminFinanceController::class, 'index'])
        ->middleware('admin.auth:keuangan')
        ->name('keuangan');

    Route::post('/keuangan', [AdminFinanceController::class, 'store'])
        ->middleware('admin.auth:keuangan')
        ->name('keuangan.store');

    Route::put('/keuangan/{id}', [AdminFinanceController::class, 'update'])
        ->middleware('admin.auth:keuangan')
        ->name('keuangan.update');

    Route::patch('/keuangan/{id}/toggle-status', [AdminFinanceController::class, 'toggleStatus'])
        ->middleware('admin.auth:keuangan')
        ->name('keuangan.toggle-status');

    Route::delete('/keuangan/{id}', [AdminFinanceController::class, 'destroy'])
        ->middleware('admin.auth:keuangan')
        ->name('keuangan.destroy');

    Route::get('/laporan', [AdminReportController::class, 'index'])
        ->middleware('admin.auth:laporan')
        ->name('laporan');

    Route::get('/kelola-website', [AdminWebsiteController::class, 'index'])
        ->middleware('admin.auth:website')
        ->name('website');

    Route::put('/kelola-website', [AdminWebsiteController::class, 'update'])
        ->middleware('admin.auth:website')
        ->name('website.update');

    Route::get('/blog', [AdminBlogController::class, 'index'])
        ->middleware('admin.auth:blog')
        ->name('blog');

    Route::post('/blog', [AdminBlogController::class, 'store'])
        ->middleware('admin.auth:blog')
        ->name('blog.store');

    Route::put('/blog/{id}', [AdminBlogController::class, 'update'])
        ->middleware('admin.auth:blog')
        ->name('blog.update');

    Route::patch('/blog/{id}/toggle-status', [AdminBlogController::class, 'toggleStatus'])
        ->middleware('admin.auth:blog')
        ->name('blog.toggle-status');

    Route::delete('/blog/{id}', [AdminBlogController::class, 'destroy'])
        ->middleware('admin.auth:blog')
        ->name('blog.destroy');

    Route::get('/ulasan', [AdminReviewController::class, 'index'])
        ->middleware('admin.auth:ulasan')
        ->name('ulasan');

    Route::patch('/ulasan/{id}/reply', [AdminReviewController::class, 'reply'])
        ->middleware('admin.auth:ulasan')
        ->name('ulasan.reply');

    Route::patch('/ulasan/{id}/status', [AdminReviewController::class, 'updateStatus'])
        ->middleware('admin.auth:ulasan')
        ->name('ulasan.status');

    Route::delete('/ulasan/{id}', [AdminReviewController::class, 'destroy'])
        ->middleware('admin.auth:ulasan')
        ->name('ulasan.destroy');

    Route::get('/media', [AdminMediaController::class, 'index'])
        ->middleware('admin.auth:media')
        ->name('media');

    Route::post('/media', [AdminMediaController::class, 'store'])
        ->middleware('admin.auth:media')
        ->name('media.store');

    Route::delete('/media/{id}', [AdminMediaController::class, 'destroy'])
        ->middleware('admin.auth:media')
        ->name('media.destroy');
    Route::get('/karyawan', [AdminEmployeeController::class, 'index'])
        ->middleware('admin.auth:karyawan')
        ->name('karyawan');

    Route::post('/karyawan', [AdminEmployeeController::class, 'store'])
        ->middleware('admin.auth:karyawan')
        ->name('karyawan.store');

    Route::put('/karyawan/{id}', [AdminEmployeeController::class, 'update'])
        ->middleware('admin.auth:karyawan')
        ->name('karyawan.update');

    Route::patch('/karyawan/{id}/status', [AdminEmployeeController::class, 'updateStatus'])
        ->middleware('admin.auth:karyawan')
        ->name('karyawan.status');

    Route::delete('/karyawan/{id}', [AdminEmployeeController::class, 'destroy'])
        ->middleware('admin.auth:karyawan')
        ->name('karyawan.destroy');
    Route::view('/hak-akses', 'admin.hak-akses')->middleware('admin.auth:hak_akses')->name('hak_akses');
    Route::view('/pengaturan', 'admin.pengaturan')->middleware('admin.auth:pengaturan')->name('pengaturan');
});

require __DIR__ . '/auth.php';