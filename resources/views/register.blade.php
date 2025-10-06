<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem PBL</title>
    <style>
        body {
            background-color: #f4f7fb;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background-color: #001744;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }
        .navbar .brand {
            font-weight: bold;
            font-size: 18px;
            color: white;
        }
        .navbar nav {
            display: flex;
            gap: 20px;
        }
        .navbar nav a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar nav a:hover {
            text-decoration: underline;
        }

        /* Container Tengah */
        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        /* Register Card */
        .register-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        .register-card img.logo {
            display: block;
            margin: 0 auto 12px;
            width: 55px;
            height: auto;
        }
        .register-card h2 {
            color: #001744;
            margin-bottom: 20px;
            font-size: 18px;
        }
        .register-card input,
        .register-card select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        .register-card button {
            width: 100%;
            padding: 12px;
            background-color: #001744;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }
        .register-card button:hover {
            background-color: #001133;
        }
        .register-card p {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .register-card p a {
            color: #001744;
            text-decoration: none;
            font-weight: bold;
        }
        .register-card p a:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            background-color: #001744;
            color: #fff;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
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

    <!-- Konten Tengah -->
    <div class="container">
        <div class="register-card">

            {{-- Logo kecil --}}
            <img src="{{ asset('assets/PBL.png') }}" alt="Logo PBL" class="logo">

            <h2>Sistem Informasi Manajemen PBL</h2>

            {{-- Pesan sukses --}}
            @if(session('success'))
                <p style="color: green; text-align:center;">{{ session('success') }}</p>
            @endif

            {{-- Error validasi --}}
            @if ($errors->any())
                <div style="color: red; margin-bottom:15px; text-align:left;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                <input type="text" name="nim" placeholder="NIM / NIP" value="{{ old('nim') }}" required>
                <input type="text" name="prodi" placeholder="Program Studi" value="{{ old('prodi') }}" required>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>

                <select name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="mahasiswa"         {{ old('role')=='mahasiswa'?'selected':'' }}>Mahasiswa</option>
                    <option value="dosen_pembimbing"  {{ old('role')=='dosen_pembimbing'?'selected':'' }}>Dosen Pembimbing</option>
                    <option value="dosen_penguji"     {{ old('role')=='dosen_penguji'?'selected':'' }}>Dosen Penguji</option>
                    <option value="koor_pbl"          {{ old('role')=='koor_pbl'?'selected':'' }}>Koordinator PBL</option>
                    <option value="jaminan_mutu"           {{ old('role')=='jaminan_mutu'?'selected':'' }}>Jaminan Mutu</option>
                    <option value="admin"             {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                </select>

                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
                <button type="submit">Register</button>
            </form>

            <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        © 2025 Sistem PBL - Jurusan Teknologi Informasi. All rights reserved.
    </footer>

</body>
</html>
