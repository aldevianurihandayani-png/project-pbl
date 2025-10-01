<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen PBL</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
</head>
<body>

<header>
    <!-- Bar Judul -->
    <div class="header-top">
        <div class="brand">SISTEM INFORMASI MANAJEMEN PBL</div>
    </div>

    <!-- Bar Menu -->
    <div class="header-menu">
        <nav>
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/about') }}">Tentang</a>
            <a href="{{ url('/logbook') }}">Catatan</a>
            <a href="{{ url('/group') }}">Kelompok</a>
            <a href="{{ url('/login') }}">Login</a>
        </nav>
    </div>
</header>

</body>
</html>
