<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem PBL</title>
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
        .navbar h2 { margin: 0; font-size: 20px; font-weight: bold; }
        .navbar ul { list-style: none; display: flex; gap: 20px; }
        .navbar ul li { display: inline; }
        .navbar ul li a { color: #fff; text-decoration: none; font-weight: bold; }
        .navbar ul li a:hover { text-decoration: underline; }

        /* Container Tengah */
        .container {
            flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px 20px;
        }

        /* Login Card */
        .login-box {
            background: #fff; padding: 40px 30px; border-radius: 12px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.2); width: 380px; text-align: center;
        }
        .login-box img { max-width: 80px; margin-bottom: 15px; }
        .login-box h2 { text-align: center; color: #002366; margin-bottom: 25px; }

        /* Error Box */
        .error-box {
            background: #ffe6e6; border: 1px solid #ff4d4d; color: #b30000;
            padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; text-align: left;
        }

        .login-box select,
        .login-box input {
            width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 8px; font-size: 15px;
        }
        .login-box select:focus,
        .login-box input:focus { border-color: #002366; outline: none; }

        .login-box button {
            width: 100%; padding: 12px; background: #002366; border: none; border-radius: 8px;
            color: white; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px;
        }
        .login-box button:hover { background: #001744; }

        /* Divider “OR” */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 18px 0 10px;
            font-size: 12px;
            color: #999;
        }
        .divider-line {
            flex: 1;
            height: 1px;
            background: #ddd;
        }

        /* Tombol Google style putih */
        .google-btn {
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            color: #444;
            text-decoration: none;
            cursor: pointer;
        }
        .google-btn img {
            width: 18px;
            height: 18px;
        }
        .google-btn:hover {
            background: #f3f4f6;
        }

        .extra-links { text-align: center; margin-top: 15px; font-size: 14px; }
        .extra-links a { color: #002366; text-decoration: none; font-weight: bold; }
        .extra-links a:hover { text-decoration: underline; }

        /* Footer */
        footer { background-color: #001744; color: #fff; text-align: center; padding: 15px; }
    </style>
</head>
<body>
    @include('header')

    <!-- Konten Tengah -->
    <div class="container">
        <div class="login-box">
            <!-- Logo -->
            <img src="{{ asset('assets/PBL.png') }}" alt="Logo PBL">

            <h2>Sistem Informasi Manajemen PBL</h2>

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.authenticate') }}" method="POST">
                @csrf

                <!-- Pilih Role (tanpa tulisan opsional, admin boleh tidak memilih) -->
                <select name="role">
                    <option value="" disabled selected hidden>Pilih Jenis User</option>

                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>
                        Mahasiswa
                    </option>

                    <option value="dosen_pembimbing" {{ old('role') == 'dosen_pembimbing' ? 'selected' : '' }}>
                        Dosen Pembimbing
                    </option>

                    <option value="dosen_penguji" {{ old('role') == 'dosen_penguji' ? 'selected' : '' }}>
                        Dosen Penguji
                    </option>

                    <option value="koordinator" {{ old('role') == 'koordinator' ? 'selected' : '' }}>
                        Koordinator PBL
                    </option>

                    <option value="jaminan_mutu" {{ old('role') == 'jaminan_mutu' ? 'selected' : '' }}>
                        Jaminan Mutu
                    </option>

                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>

                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit">Login</button>
            </form>

            <!-- Divider OR -->
            <div class="divider">
                <span class="divider-line"></span>
                <span>atau</span>
                <span class="divider-line"></span>
            </div>

            <!-- Tombol Login Dengan Google -->
            <a href="{{ route('google.redirect') }}" class="google-btn">
                <img src="{{ asset('assets/google-logo.png') }}" alt="Google Logo">
                <span>Login dengan Akun Politala (Google)</span>
            </a>

            <div class="extra-links">
                <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        © 2025 Sistem PBL - Jurusan Teknologi Informasi. All rights reserved.
    </footer>
</body>
</html>
