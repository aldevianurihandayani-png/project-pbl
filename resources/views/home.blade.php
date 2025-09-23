<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem PBL</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background: #f5f7fa; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
        }
        header { 
            background: #002366; 
            color: white; 
            padding: 15px 30px; 
            display: flex; 
            align-items: center; 
        }
        header .title { 
            font-size: 20px; 
            font-weight: bold; 
        }
        header img { 
            height: 45px; 
            margin-right: 15px; 
        }
        nav { 
            background: #003399; 
            text-align: center; 
            padding: 12px; 
        }
        nav a { 
            color: white; 
            text-decoration: none; 
            margin: 0 20px; 
            font-weight: bold; 
        }
        nav a:hover { 
            text-decoration: underline; 
        }
        .container { 
            flex: 1; 
            display: flex; 
            justify-content: center; 
            align-items: flex-start; 
            margin: 60px auto; 
            max-width: 1000px; 
            gap: 60px; 
        }
        .hero { 
            flex: 1; 
        }
        .hero h2 { 
            color: #002366; 
            margin-bottom: 20px; 
        }
        .hero p { 
            color: #333; 
            line-height: 1.6; 
        }
        .card { 
            flex: 0.9; 
            background: #fff; 
            padding: 30px 25px; 
            border-radius: 12px; 
            box-shadow: 0 6px 12px rgba(0,0,0,0.1); 
        }
        .card h3 { 
            text-align: center; 
            color: #002366; 
            margin-bottom: 20px; 
            font-size: 18px;
        }
        .card form { 
            display: flex; 
            flex-direction: column; 
            gap: 12px; 
        }
        .card input { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ccc; 
            border-radius: 8px; 
            background: #f8f8f8; 
            font-size: 14px;
        }
        .card input:focus { 
            outline: none; 
            border: 1px solid #002366; 
            background: #fff; 
        }
        .card button { 
            padding: 12px; 
            background: #002366; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-size: 15px; 
            cursor: pointer; 
            margin-top: 5px;
        }
        .card button:hover { 
            background: #001a4d; 
        }
        footer { 
            background: #002366; 
            color: white; 
            text-align: center; 
            padding: 18px; 
            font-size: 14px; 
            margin-top: auto; 
        }
    </style>
</head>
<body>
    <header>
        <img src="PBL.png" alt="Logo PBL"> 
        <span class="title">SISTEM INFORMASI MANAJEMEN PBL</span>
    </header>

    <nav>
        <a href="/">Home</a>
        <a href="/about">About</a>
        <a href="/login">Login</a>
        <a href="/register">Register</a>
    </nav>

    <div class="container">
        <div class="hero">
            <h2>Selamat Datang di Sistem Project Based Learning</h2>
            <p>
                Sistem ini dirancang untuk mendukung pengelolaan kegiatan Project Based Learning (PBL) 
                pada Jurusan Teknologi Informasi, mulai dari pengelolaan kelompok, dosen pembimbing, 
                hingga penilaian akhir mahasiswa.
            </p>
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
