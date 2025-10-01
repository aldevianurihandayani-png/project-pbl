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
            <a href="{{ url('/') }}">HOME</a>
            <a href="{{ url('/about') }}">TENTANG</a>
            <a href="{{ url('logbook/index.blade.php') }}">LOGBOOK</a>
            <a href="{{ url('/group') }}">KELOMPOK</a>
            <a href="{{ url('/login') }}">LOGIN</a>
        </nav>
    </div>
</header>

</body>
</html>
