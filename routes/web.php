<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
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

require __DIR__.'/auth.php';
