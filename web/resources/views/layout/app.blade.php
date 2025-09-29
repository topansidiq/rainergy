<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/image/logo.png" type="image/svg+xml">
</head>

<body class="bg-emerald-50">
    @include('components.logo')
    @yield('content', 'Tidak ada konten!')
</body>

</html>
