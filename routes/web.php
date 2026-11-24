<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminMenuController;
use Illuminate\Support\Facades\Route;

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
    return view('blog');
})->name('blog.index');

Route::get('/katalog', [MenuController::class, 'index'])->name('katalog.index');

Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::put('/cart/{menuId}', [CartController::class, 'update'])->name('cart.update');

Route::get('/bukti-beriman', function () {
    $dalilList = [
        [
            'icon'   => 'fa-book-open',
            'title'  => "Al-Qur'an",
            'quote'  => '"Hai orang-orang yang beriman, janganlah kamu saling memakan harta sesamamu dengan jalan yang batil..."',
            'source' => '(QS. An-Nisa: 29)',
        ],
        [
            'icon'   => 'fa-kaaba',
            'title'  => 'Hadis',
            'quote'  => '"Pedagang yang jujur dan terpercaya (akan bersama) para nabi, orang-orang yang shiddiq, dan para syuhada."',
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
Route::view('/tentang', 'tentang')->name('tentang.index');
Route::view('/kontak', 'kontak')->name('kontak.index');

// Route publik checkout pelanggan (tanpa login)
Route::post('/pesanan', [OrderController::class, 'store'])->name('pesanan.store');

// Route autentikasi khusus admin
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

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

    Route::view('/inventaris', 'admin.inventaris')->middleware('admin.auth:inventaris')->name('inventaris');
    Route::view('/reservasi', 'admin.reservasi')->middleware('admin.auth:reservasi')->name('reservasi');
    Route::view('/pelanggan', 'admin.pelanggan')->middleware('admin.auth:pelanggan')->name('pelanggan');
    Route::view('/promo', 'admin.promo')->middleware('admin.auth:promo')->name('promo');
    Route::view('/catering-delivery', 'admin.catering')->middleware('admin.auth:catering')->name('catering');
    Route::view('/keuangan', 'admin.keuangan')->middleware('admin.auth:keuangan')->name('keuangan');
    Route::view('/laporan', 'admin.laporan')->middleware('admin.auth:laporan')->name('laporan');
    Route::view('/kelola-website', 'admin.website')->middleware('admin.auth:website')->name('website');
    Route::view('/blog', 'admin.blog')->middleware('admin.auth:blog')->name('blog');
    Route::view('/ulasan', 'admin.ulasan')->middleware('admin.auth:ulasan')->name('ulasan');
    Route::view('/media', 'admin.media')->middleware('admin.auth:media')->name('media');
    Route::view('/karyawan', 'admin.karyawan')->middleware('admin.auth:karyawan')->name('karyawan');
    Route::view('/hak-akses', 'admin.hak-akses')->middleware('admin.auth:hak_akses')->name('hak_akses');
    Route::view('/pengaturan', 'admin.pengaturan')->middleware('admin.auth:pengaturan')->name('pengaturan');
});

require __DIR__.'/auth.php';