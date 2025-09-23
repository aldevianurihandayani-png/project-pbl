<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem PBL</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f5f7fa; }
        header { background: #002366; color: white; padding: 15px; text-align: center; font-size: 22px; font-weight: bold; }
        nav { background: #003399; text-align: center; padding: 10px; }
        nav a { color: white; text-decoration: none; margin: 0 15px; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
        .container { display: flex; justify-content: center; align-items: flex-start; margin: 40px auto; max-width: 1000px; gap: 40px; }
        .hero { flex: 1; }
        .hero h2 { color: #002366; }
        .hero p { color: #333; }
        .hero button { margin: 10px 5px; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-blue { background: #002366; color: white; }
        .btn-green { background: #28a745; color: white; }
        .card { flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card h3 { text-align: center; color: #002366; }
        .card input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        .card button { width: 100%; padding: 10px; background: #002366; color: white; border: none; border-radius: 5px; cursor: pointer; }
        footer { background: #002366; color: white; text-align: center; padding: 15px; margin-top: 40px; font-size: 14px; }
    </style>
</head>
<body>
    <header>SISTEM INFORMASI MANAJEMEN PBL</header>

    <nav>
        <a href="/">Home</a>
        <a href="/about">About</a>
        <a href="/login">Login</a>
        <a href="/register">Register</a>
    </nav>

    <div class="logo">
        <img src="{{ asset('image/logo.png') }}" alt="Logo">
    <div class="container">
        <div class="hero">
            <h2>Selamat Datang di Sistem Project Based Learning</h2>
            <p>
                Sistem ini dirancang untuk mendukung pengelolaan kegiatan Project Based Learning (PBL) 
                pada Jurusan Teknologi Informasi, mulai dari pengelolaan kelompok, dosen pembimbing, 
                hingga penilaian akhir mahasiswa.
            </p>
            <button class="btn-blue">Masuk ke Sistem</button>
            <button class="btn-green">Daftar Akun Baru</button>
        </div>

        <div class="card">
            <h3>Register</h3>
            <form action="/register" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Nama Lengkap" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>

    <footer>
        © 2025 Sistem PBL - Jurusan Teknologi Informasi. All rights reserved.
    </footer>
</body>
</html>
