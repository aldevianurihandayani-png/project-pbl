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
            <a href="{{ route('register') }}">Register Eksternal</a>
            <a href="{{ url('/contact') }}">Contact</a>
            <a href="{{ route('login') }}">Login</a>
        </nav>
    </div>
</header>

</body>
</html>
