<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem PBL</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <h2>SISTEM INFORMASI MANAJEMEN PBL</h2>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/about') }}">About</a></li>
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
                <li><a href="{{ url('/logbook') }}">Logbook</a></li>
            </ul>
        </div>
    </header>

    <!-- Konten Dinamis -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        © 2025 Sistem PBL - Jurusan Teknologi Informasi. All rights reserved.
    </footer>
</body>
</html>
