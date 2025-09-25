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
            background-color: #002366;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .navbar h2 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .navbar ul li {
            display: inline;
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar ul li a:hover {
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
        }

        .register-card h2 {
            text-align: center;
            color: #002366;
            margin-bottom: 20px;
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
            background-color: #002366;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }

        .register-card button:hover {
            background-color: #001744;
        }

        .register-card p {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .register-card p a {
            color: #002366;
            text-decoration: none;
            font-weight: bold;
        }

        .register-card p a:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            background-color: #002366;
            color: #fff;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
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

    <!-- Konten Tengah -->
    <div class="container">
        <div class="register-card">
            <h2>Register Sistem PBL</h2>

            {{-- Pesan sukses --}}
            @if(session('success'))
                <p style="color: green; text-align:center;">{{ session('success') }}</p>
            @endif

            {{-- Error validasi --}}
            @if ($errors->any())
                <div style="color: red; margin-bottom:15px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                <input type="text" name="nim" placeholder="NIM / NIP" value="{{ old('nim') }}" required>
                <input type="text" name="prodi" placeholder="Program Studi" value="{{ old('prodi') }}" required>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <select name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen Pembimbing</option>
                    <option value="admin">Admin</option>
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
