<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
    // --- 6 MAKANAN ---
    [
        'name' => 'Nasi Liwet Komplit',
        'category' => 'Makanan',
        'price' => 25000,
        'description' => 'Nasi gurih, ayam suwir, tahu tempe, sambal terasi',
        'image' => 'nasi-liwet.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Sate Ayam Madura',
        'category' => 'Makanan',
        'price' => 22000,
        'description' => '10 tusuk sate ayam bumbu kacang kental & lontong',
        'image' => 'sate-ayam.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Rendang Daging Sapi',
        'category' => 'Makanan',
        'price' => 32000,
        'description' => 'Daging sapi empuk dimasak dengan rempah Minang',
        'image' => 'rendang.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Soto Betawi Daging',
        'category' => 'Makanan',
        'price' => 28000,
        'description' => 'Kuah santan susu gurih dengan potongan daging sapi dan emping',
        'image' => 'soto-betawi.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Gado-Gado Siram',
        'category' => 'Makanan',
        'price' => 18000,
        'description' => 'Sayuran segar rebus, tahu tempe, telur, dan bumbu kacang medok',
        'image' => 'gado-gado.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Ayam Bakar Taliwang',
        'category' => 'Makanan',
        'price' => 27000,
        'description' => 'Ayam kampung bakar bumbu pedas manis khas Lombok + plecing kangkung',
        'image' => 'ayam-taliwang.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ],

    // --- 4 MINUMAN ---
    [
        'name' => 'Es Teh Manis Nusantara',
        'category' => 'Minuman',
        'price' => 6000,
        'description' => 'Teh melati seduh segar dengan gula tebu asli',
        'image' => 'es-teh.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Es Cendol Dawet',
        'category' => 'Minuman',
        'price' => 12000,
        'description' => 'Cendol pandan, gula aren asli, dan santan segar',
        'image' => 'es-cendol.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Wedang Jahe Hangat',
        'category' => 'Minuman',
        'price' => 10000,
        'description' => 'Jahe merah bakar dengan serai dan madu murni',
        'image' => 'wedang-jahe.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Es Kelapa Muda Jeruk',
        'category' => 'Minuman',
        'price' => 14000,
        'description' => 'Daging kelapa muda segar dengan perasan jeruk nipis dan sirup gula',
        'image' => 'es-kelapa.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
    }
