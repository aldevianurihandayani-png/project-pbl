<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Mahasiswa</title>
  <style>
    :root {
      --navy: #0b1d54;
      --light-bg: #f6f8fc;
      --white: #fff;
      --shadow: rgba(0,0,0,.05);
    }

    * { box-sizing: border-box; font-family: Arial, sans-serif; }

    body {
      margin: 0;
      display: grid;
      grid-template-columns: 250px 1fr;
      min-height: 100vh;
      background: var(--light-bg);
    }

    /* Sidebar */
    .sidebar {
      background: var(--navy);
      color: #e9edf7;
      padding: 20px;
      display: flex;
      flex-direction: column;
    }

    .sidebar h2 {
      font-size: 20px;
      margin-bottom: 20px;
    }

    .menu a {
      display: block;
      color: #e9edf7;
      text-decoration: none;
      padding: 10px 14px;
      border-radius: 10px;
      margin-bottom: 5px;
    }
    .menu a:hover, .menu a.active {
      background: #1a2e66;
    }

    /* Topbar */
    .topbar {
      background: var(--navy);
      color: #fff;
      padding: 12px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Content */
    .content {
      padding: 25px;
    }

    .welcome {
      margin-bottom: 25px;
    }
    .welcome h1 {
      margin: 0;
      font-size: 22px;
      color: var(--navy);
    }
    .welcome p {
      margin: 4px 0 0;
      color: #555;
    }

    .cards {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
    }
    .card {
      background: var(--white);
      padding: 18px;
      border-radius: 12px;
      flex: 1;
      box-shadow: 0 2px 6px var(--shadow);
      text-align: center;
    }
    .card h2 { margin: 0; color: var(--navy); }
    .card p { margin: 8px 0 0; color: #666; }

    .group-box {
      background: var(--white);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 6px var(--shadow);
    }
    .group-box h3 {
      margin-top: 0;
      color: var(--navy);
    }
    .group-box .buttons {
      margin-top: 10px;
      display: flex;
      gap: 10px;
    }
    .group-box button {
      padding: 8px 14px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    .btn-primary { background: #0059ff; color: #fff; }
    .btn-secondary { background: #e0e0e0; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>SIMAP<br>Politala</h2>
    <div class="menu">
      <a href="#" class="active">Beranda</a>
      <a href="#">Catatan Kegiatan (Logbook)</a>
      <a href="#">Peringkat</a>
      <a href="#">Konsultasi</a>
      <a href="#">Laporan Penilaian</a>
      <a href="#">Data Kelompok</a>
      <a href="#">Kelola Feedback</a>
      <a href="#">Notifikasi</a>
      <a href="#">Settings</a>
      <a href="{{ route('logout') }}">Logout</a>
    </div>
  </div>

  <div>
    <div class="topbar">
      <input type="text" placeholder="Cari materi, notifikasi, nilai..." style="width:300px;padding:6px;border-radius:6px;border:none;">
      <div>{{ auth()->user()->nama }}</div>
    </div>

    <div class="content">
      <div class="welcome">
        <h1>Halo, {{ auth()->user()->nama }} 👋</h1>
        <p>Selamat datang di sistem PBL</p>
      </div>

      <div class="cards">
        <div class="card">
          <h2>{{ $logbook_terkirim }}</h2>
          <p>Logbook Terkirim</p>
        </div>
        <div class="card">
          <h2>{{ $notifikasi }}</h2>
          <p>Notifikasi</p>
        </div>
        <div class="card">
          <h2>{{ $feedback }}</h2>
          <p>Feedback</p>
        </div>
        <div class="card">
          <h2>{{ $nilai }}</h2>
          <p>Entri Nilai</p>
        </div>
      </div>

      <div class="group-box">
        <h3>Kelompok 1 PBL</h3>
        <p>Dosen Pembimbing: —</p>
        <div class="buttons">
          <button class="btn-primary">Isi Logbook</button>
          <button class="btn-secondary">Lihat Kelompok</button>
          <button class="btn-secondary">Lihat Nilai</button>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
