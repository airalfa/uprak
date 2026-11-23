<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Daftar Role & Hak Akses Fitur (Sesuai Dokumen Admin PT. Kusantara)
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'super_admin' => [
            'label'       => 'Super Admin',
            'description' => 'Akses penuh ke seluruh fitur operasional, bisnis, konten, dan administrasi.',
            'permissions' => ['*'], // Semua fitur
        ],
        'ceo' => [
            'label'       => 'CEO',
            'description' => 'Memantau ringkasan dashboard dan laporan keseluruhan perusahaan.',
            'permissions' => ['dashboard', 'laporan'],
        ],
        'operational_manager' => [
            'label'       => 'Operational Manager',
            'description' => 'Mengelola pesanan, menu & produk, inventaris stok, reservasi, dan operasional catering.',
            'permissions' => ['dashboard', 'pesanan', 'menu', 'inventaris', 'reservasi', 'catering'],
        ],
        'marketing' => [
            'label'       => 'Marketing',
            'description' => 'Mengelola promo & diskon, data pelanggan, blog, ulasan, media, dan konten website.',
            'permissions' => ['dashboard', 'promo', 'pelanggan', 'blog', 'website', 'ulasan', 'media'],
        ],
        'finance' => [
            'label'       => 'Finance',
            'description' => 'Mengelola pencatatan keuangan, transaksi, dan laporan keuangan.',
            'permissions' => ['dashboard', 'keuangan', 'laporan'],
        ],
        'customer_service' => [
            'label'       => 'Customer Service',
            'description' => 'Menangani pesanan pelanggan, data pelanggan, reservasi, dan ulasan.',
            'permissions' => ['dashboard', 'pesanan', 'pelanggan', 'ulasan', 'reservasi'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Daftar Akun Resmi per Role (Bisa dipakai langsung & di-seed ke DB)
    |--------------------------------------------------------------------------
    */
    'accounts' => [
        [
            'name'      => 'Super Admin Kusantara',
            'email'     => 'superadmin@kusantara.id',
            'password'  => 'SuperAdmin2026!',
            'role'      => 'super_admin',
            'is_active' => true,
        ],
        [
            'name'      => 'M. Airlangga Alfarisi (CEO)',
            'email'     => 'ceo@kusantara.id',
            'password'  => 'CeoKusantara2026!',
            'role'      => 'ceo',
            'is_active' => true,
        ],
        [
            'name'      => 'Radhythia Ardian (Operational Manager)',
            'email'     => 'operasional@kusantara.id',
            'password'  => 'OpsKusantara2026!',
            'role'      => 'operational_manager',
            'is_active' => true,
        ],
        [
            'name'      => 'Adly Radithya (Marketing)',
            'email'     => 'marketing@kusantara.id',
            'password'  => 'MktKusantara2026!',
            'role'      => 'marketing',
            'is_active' => true,
        ],
        [
            'name'      => 'Irfan Ainul Yaqin (Finance)',
            'email'     => 'finance@kusantara.id',
            'password'  => 'FinKusantara2026!',
            'role'      => 'finance',
            'is_active' => true,
        ],
        [
            'name'      => 'Gilang Aditya (Customer Service)',
            'email'     => 'cs@kusantara.id',
            'password'  => 'CsKusantara2026!',
            'role'      => 'customer_service',
            'is_active' => true,
        ],
    ],
];