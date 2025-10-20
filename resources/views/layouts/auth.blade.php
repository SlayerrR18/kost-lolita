<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kost Lolita')</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('css')
</head>
<body style="background-color: #f8fafc;"> {{-- Warna latar belakang dasar --}}
    <main>
        {{-- Konten dari login.blade.php akan masuk ke sini --}}
        @yield('content')
    </main>

    {{-- 'Stack' untuk JS khusus per halaman --}}
    @stack('js')
</body>
</html>
