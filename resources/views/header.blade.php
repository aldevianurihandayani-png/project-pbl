<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen PBL</title>
    @vite(['resources/css/header.css'])
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">SISTEM INFORMASI MANAJEMEN PBL</div>
            <nav>
                <ul>
                    <li><a href="{{ url('home') }}">Home</a></li>
                    <li><a href="{{ url('about') }}">Tentang</a></li>
                    <li><a href="{{ url('logbook') }}">Catatan</a></li>
                    <li><a href="{{ url('group') }}">Kelompok</a></li>
                    <li><a href="{{ url('login') }}">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>
</body>
</html>
