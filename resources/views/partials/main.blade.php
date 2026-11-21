<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT. Kusantara - Rasa Nusantara dalam Setiap Suapan')</title>
    
    <!-- Google Fonts: Playfair Display & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/kusantara.css') }}">
    @stack('styles')
</head>
<body class="antialiased">
    <div class="page-wrapper">
        @include('partials.navbar')

        <main>
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        @include('partials.footer')
    </div>

    <script src="{{ asset('js/kusantara.js') }}"></script>
</body>
</html>