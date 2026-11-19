
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PT. Kusantara</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>

    @include('partials.navbar')

    @include('partials.main')

    @include('partials.footer')

    <script src="{{ asset('js/home.js') }}"></script>

</body>
</html>
