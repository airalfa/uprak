
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;

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
Route::resource('cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);

Route::get('/', function () {
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

    $argumentsFor = [
        'Kemudahan akses dana cepat bagi pelanggan.',
        'Mempercepat arus kas dan perputaran transaksi.',
        'Kompensasi risiko inflasi bagi penyedia dana.',
    ];

    $argumentsAgainst = [
        'Terindikasi praktik riba yang dilarang agama.',
        'Potensi membebani pelanggan dengan bunga berbunga.',
        'Adanya alternatif akad syariah (seperti Murabahah) yang lebih adil.',
    ];

    return view('landing', compact(
        'dalilList',
        'penerapanList',
        'argumentsFor',
        'argumentsAgainst'
    ));
})->name('dashboard');

Route::get('/blog', function () {
    return view('blog');
});

require __DIR__.'/auth.php';
