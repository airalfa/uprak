<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Menu - PT. Kusantara Kita</title>
</head>
<body>
    <nav>
        <a href="/">Beranda</a> |
        <a href="{{route('katalog.index')}}">Katalog Menu</a> |
        <a href="/bukti-beriman">Bukti Beriman & Isu Bunga</a> |
        <a href="/sejarah">Sejarah & Tim Kami</a> |
        <a href="/checkout">Checkout</a>
    </nav>

    <hr>
    <section>
        <h1>Katalog Menu</h1>

        <form action="{{ route('katalog.index') }}" method="GET">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu...">
            <button type="submit">Cari</button>
            @if(request('search') || request('category'))
                <a href="{{ route('katalog.index') }}">Reset</a>
            @endif
        </form>

        <br>
        <div>
            <a href="{{ route('katalog.index', ['category' => 'Semua', 'search' => request('search')]) }}">Semua Menu</a> |
            <a href="{{ route('katalog.index', ['category' => 'Makanan', 'search' => request('search')]) }}">Makanan Menu</a> |
            <a href="{{ route('katalog.index', ['category' => 'Minuman', 'search' => request('search')]) }}">Minuman</a>
        </div>

        <br>
        <div>
            @forelse ($menus as $menu)
                <div style="border: 1px solid #000; padding: 10px; margin-bottom: 10px;">
                    <span>[{{ $menu->category }}]</span>
                    <h3>{{ $menu->name }}</h3>
                    <p>Harga: Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                    <p>{{ $menu->description }}</p>
                    <small>File gambar: {{ $menu->image }}</small>
                </div>
            @empty
                <p>Menu tidak ditemukan.</p>
            @endforelse
        </div>
    </section>

</body>
</html>