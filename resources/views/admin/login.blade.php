<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PT. Kusantara</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/kusantara.css') }}">
</head>
<body>
<div class="admin-login-page">
    <div class="admin-login-card">
        <div class="admin-login-brand">
            <div class="admin-login-badge">
                <i class="fa-solid fa-crown"></i>
            </div>
            <h1 class="admin-login-title">PT. KUSANTARA</h1>
            <p class="admin-login-sub">Portal Login Khusus Administrator</p>
        </div>

        <div class="admin-login-notice">
            <i class="fa-solid fa-shield-halved"></i>
            Akses ini hanya untuk <strong>Admin terdaftar</strong>. Pelanggan/tamu tidak perlu login untuk berbelanja atau memesan menu, dan tidak diperkenankan masuk ke panel admin.
        </div>

        @if (session('error'))
            <div class="admin-login-alert is-error">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="admin-login-alert is-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="admin-login-alert is-error">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="admin-login-field">
                <label for="adminEmail" class="admin-login-label">Email Admin</label>
                <input type="email"
                       id="adminEmail"
                       name="email"
                       value="{{ old('email') }}"
                       class="admin-login-input"
                       placeholder="admin@kusantara.id"
                       required
                       autofocus>
            </div>

            <div class="admin-login-field">
                <label for="adminPassword" class="admin-login-label">Kata Sandi</label>
                <input type="password"
                       id="adminPassword"
                       name="password"
                       class="admin-login-input"
                       placeholder="Masukkan kata sandi admin"
                       required>
            </div>

            <button type="submit" class="admin-login-btn">
                Masuk ke Dashboard Admin <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="admin-login-footer">
            <a href="{{ url('/') }}">&larr; Kembali ke Halaman Utama (Pelanggan)</a>
        </div>
    </div>
</div>
</body>
</html>