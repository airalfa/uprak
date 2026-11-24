@extends('layouts.admin')

@section('title', 'Kelola Website - PT. Kusantara')

@section('subnav')
    <a href="{{ route('admin.website') }}" class="admin-subnav-tab is-active">Profil &amp; Konten</a>
    <a href="{{ url('/') }}" target="_blank" rel="noopener" class="admin-subnav-tab">Lihat Beranda</a>
    <a href="{{ route('katalog.index') }}" target="_blank" rel="noopener" class="admin-subnav-tab">Lihat Katalog</a>
@endsection

@section('content')
@php
    $s = array_merge(\App\Http\Controllers\AdminWebsiteController::defaults(), $settings ?? []);
@endphp

<div class="admin-page-head">
    <div>
        <h1 class="admin-page-title">Kelola Website</h1>
        <p class="admin-page-sub">Atur identitas brand, pengumuman beranda, kontak resmi, jam operasional, dan ringkasan profil PT. Kusantara.</p>
    </div>
    <a href="{{ url('/') }}" target="_blank" rel="noopener" class="admin-btn-sm">
        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Website Publik
    </a>
</div>

@if (session('success'))
    <div style="margin-bottom:14px;padding:12px 16px;border-radius:10px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;font-size:0.86rem;font-weight:600;">
        {{ session('success') }}
    </div>
@endif

@if (session('error') || $errors->any())
    <div style="margin-bottom:14px;padding:12px 16px;border-radius:10px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-size:0.86rem;font-weight:600;">
        {{ session('error') ?? $errors->first() }}
    </div>
@endif

<form action="{{ route('admin.website.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;">
        <!-- IDENTITAS & BERANDA -->
        <div style="padding:18px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;display:grid;gap:12px;align-content:start;">
            <h3 style="font-size:0.96rem;font-weight:800;color:var(--navy-dark);margin-bottom:2px;">Identitas &amp; Tampilan Beranda</h3>

            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Nama Brand / Perusahaan</label>
                <input type="text" name="brand_name" value="{{ old('brand_name', $s['brand_name']) }}" required
                       style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
            </div>

            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Tagline Utama</label>
                <input type="text" name="tagline" value="{{ old('tagline', $s['tagline']) }}"
                       style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
            </div>

            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Label / Badge Hero</label>
                <input type="text" name="hero_badge" value="{{ old('hero_badge', $s['hero_badge']) }}"
                       style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
            </div>

            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Banner / Info Pengumuman</label>
                <input type="text" name="announcement" value="{{ old('announcement', $s['announcement']) }}"
                       style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
            </div>

            <label style="display:inline-flex;align-items:center;gap:8px;font-size:0.84rem;font-weight:600;margin-top:4px;">
                <input type="checkbox" name="is_maintenance" value="1"
                       {{ old('is_maintenance', $s['is_maintenance']) === '1' ? 'checked' : '' }}>
                Mode Pemberitahuan Pemeliharaan (Maintenance Notice)
            </label>
        </div>

        <!-- KONTAK, LOKASI & JAM OPERASIONAL -->
        <div style="padding:18px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;display:grid;gap:12px;align-content:start;">
            <h3 style="font-size:0.96rem;font-weight:800;color:var(--navy-dark);margin-bottom:2px;">Kontak, Lokasi &amp; Operasional</h3>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $s['phone']) }}"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">No. WhatsApp (62...)</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $s['whatsapp']) }}"
                           style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
                </div>
            </div>

            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Email Resmi</label>
                <input type="email" name="email" value="{{ old('email', $s['email']) }}"
                       style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
            </div>

            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Alamat Outlet / Kantor</label>
                <input type="text" name="address" value="{{ old('address', $s['address']) }}"
                       style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
            </div>

            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Jam Operasional</label>
                <input type="text" name="operating_hours" value="{{ old('operating_hours', $s['operating_hours']) }}"
                       style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">
            </div>
        </div>
    </div>

    <!-- DESKRIPSI PROFIL & LAYANAN -->
    <div style="margin-top:16px;padding:18px;border-radius:12px;background:#fff;border:1px solid #e6dfd1;display:grid;gap:12px;">
        <h3 style="font-size:0.96rem;font-weight:800;color:var(--navy-dark);margin-bottom:2px;">Ringkasan Profil &amp; Layanan</h3>

        <div>
            <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Tentang PT. Kusantara</label>
            <textarea name="about_summary" rows="3"
                      style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">{{ old('about_summary', $s['about_summary']) }}</textarea>
        </div>

        <div>
            <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Informasi Layanan &amp; Pembayaran</label>
            <textarea name="delivery_info" rows="2"
                      style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #d9ceba;">{{ old('delivery_info', $s['delivery_info']) }}</textarea>
        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:6px;">
            <button type="submit" class="admin-btn-sm" style="background:var(--gold);border-color:var(--gold);padding:10px 18px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Website
            </button>
        </div>
    </div>
</form>
@endsection