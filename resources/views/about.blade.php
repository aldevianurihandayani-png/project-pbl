<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang PBL</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* biar footer selalu di bawah */
        }

        header {
            background-color: #003399;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        header h2 {
            margin: 0;
            font-size: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            flex: 1; /* isi penuh biar footer turun */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 50px 20px;
        }

        .content {
            max-width: 700px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .content h1 {
            color: #003399;
            margin-bottom: 15px;
        }

        .content p {
            line-height: 1.6;
            margin-bottom: 15px;
            color: #333;
            text-align: justify;
        }

        footer {
            background-color: #003399;
            color: white;
            text-align: center;
            padding: 25px 15px; /* lebih lega */
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <h2>SISTEM INFORMASI MANAJEMEN PBL</h2>
        <nav>
            <a href="/home">Home</a>
            <a href="/about">About</a>
            <a href="/login">Login</a>
            <a href="/register">Register</a>
        </nav>
    </header>

    <div class="container">
        <div class="content">
            <h1>Tentang Project Based Learning</h1>
            <p>
                Sistem Informasi Manajemen Project Based Learning (PBL) dirancang untuk mendukung
                kegiatan pembelajaran berbasis proyek di Jurusan Teknologi Informasi.
            </p>
            <p>
                Melalui sistem ini, mahasiswa dapat lebih mudah dalam mengelola kelompok,
                mendokumentasikan progres proyek, serta berkomunikasi dengan dosen pembimbing.
            </p>
            <p>
                Selain itu, sistem ini juga memfasilitasi penilaian akhir mahasiswa secara lebih
                transparan, terstruktur, dan terintegrasi dengan data akademik yang ada.
            </p>
            <p>
                Dengan adanya sistem PBL, diharapkan proses pembelajaran menjadi lebih efektif,
                kolaboratif, dan menghasilkan output yang bermanfaat bagi mahasiswa maupun institusi.
            </p>
        </div>
    </div>

    <footer>
        © 2025 Sistem PBL - Jurusan Teknologi Informasi. All rights reserved.
    </footer>
</body>
</html>
