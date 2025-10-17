<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen PBL</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        /* Navbar */
        .navbar {
            background:#001744; /* biru tua */
            color:white;
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:15px 40px;
        }
        .navbar .brand {
            font-weight:bold;
            font-size:18px;
            color:white;
        }
        .navbar nav {
            display:flex;
            gap:20px;
        }
        .navbar nav a {
            color:white;
            text-decoration:none;
            font-weight:bold;
        }
        .navbar nav a:hover {
            text-decoration:underline;
        }
    </style>
</head>
<body>

<header>
    <div class="navbar">
        <div class="brand">SIMAP Politala</div>
        <nav>
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/about') }}">Tentang</a>

            {{-- Amankan register supaya tidak error walau route register belum ada --}}
            @if (Route::has('register'))
                <a href="{{ route('register') }}">Register Eksternal</a>
            @else
                <a href="{{ url('/register') }}">Register Eksternal</a>
            @endif

            <a href="{{ url('/contact') }}">Contact</a>

            {{-- Amankan login juga --}}
            @if (Route::has('login'))
                <a href="{{ route('login') }}">Login</a>
            @else
                <a href="{{ url('/login') }}">Login</a>
            @endif
        </nav>
    </div>
</header>

</body>
</html>
