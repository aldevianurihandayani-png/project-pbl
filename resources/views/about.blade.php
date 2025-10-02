<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tentang – Sistem PBL</title>

  <style>
    :root{
      --navy:#001744;
      --bg:#f4f7fb;
      --text:#0f172a;
      --muted:#475569;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family: Arial, Helvetica, sans-serif;
      background:var(--bg); color:var(--text);
      min-height:100vh; display:flex; flex-direction:column;
    }

    /* Section wrapper */
    .about-wrap{
      max-width: 920px;
      width: 100%;
      margin: 42px auto 28px auto;
      padding: 0 20px;
    }

    .about-title{
      margin:0 0 18px 0;
      font-size: 32px;
      line-height: 1.25;
      color: var(--navy);
      font-weight: 800;
      text-align:left;
    }

    .about-card{
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius: 14px;
      box-shadow: 0 10px 24px rgba(0,0,0,.06);
      padding: 26px 24px;
    }

    .about-card p{
      margin: 0 0 14px 0;
      font-size: 16px;
      line-height: 1.8;
      color: var(--muted);
    }

    .about-card b{
      color: var(--text);
    }

    footer{
      margin-top:auto;
      background:var(--navy);
      color:#fff;
      text-align:center;
      padding:14px;
      font-size:.95rem;
    }
  </style>
</head>
<body>

  {{-- Navbar / header yang sudah kamu punya --}}
  @include('header')

  <main class="about-wrap">
    <h1 class="about-title">Tentang Sistem PBL</h1>

    <div class="about-card">
      <p>
        <b>Sistem Informasi Manajemen Project Based Learning (PBL)</b> adalah platform
        digital yang mendukung proses pembelajaran berbasis proyek di Jurusan Teknologi Informasi.
      </p>
      <p>
        Sistem ini dirancang untuk mempermudah <b>mahasiswa</b>, <b>dosen pembimbing</b>,
        <b>dosen penguji</b>, serta <b>koordinator</b> dalam mengelola kegiatan PBL,
        mulai dari pembentukan kelompok, pencatatan logbook, pengaturan milestone,
        hingga penilaian akhir.
      </p>
      <p>
        Dengan adanya sistem ini, proses monitoring, evaluasi, dan penjaminan mutu
        dapat dilakukan lebih <b>transparan</b>, <b>terstruktur</b>, dan <b>efisien</b>.
      </p>
    </div>
  </main>

  <footer>
    © 2025 Sistem PBL - Jurusan Teknologi Informasi. All rights reserved.
  </footer>
</body>
</html>
