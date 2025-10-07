<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Mahasiswa — SIMAP Politala</title>
  <style>
    :root {
      --navy: #0b1d54;
      --light: #eef3fa;
      --white: #fff;
      --ring: rgba(13, 23, 84, .12);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; }
    body { background: var(--light); display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }

    /* Sidebar */
    .sidebar {
      background: var(--navy);
      color: #e9edf7;
      padding: 18px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .brand { font-size: 18px; font-weight: bold; margin-bottom: 24px; }
    .menu a {
      display: block;
      color: #e9edf7;
      text-decoration: none;
      padding: 10px 12px;
      border-radius: 10px;
      margin-bottom: 8px;
      transition: .3s;
    }
    .menu a:hover { background: #12306d; }
    .menu a.active { background: #1c3d86; }

    /* Akun section */
    .account {
      border-top: 1px solid rgba(255,255,255,.3);
      padding-top: 12px;
      text-align: center;
    }

    /* Header */
    .header {
      background: var(--navy);
      color: var(--white);
      padding: 14px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Content */
    .content { padding: 28px; }
    h1 { color: var(--navy); margin-bottom: 16px; }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 14px;
      margin-bottom: 24px;
    }
    .card {
      background: var(--white);
      border-radius: 14px;
      box-shadow: 0 2px 8px var(--ring);
      padding: 16px;
    }
    .card h2 { color: var(--navy); font-size: 15px; }
    .card p { font-size: 28px; font-weight: bold; color: #1c3d86; margin-top: 8px; }

    .section {
      background: var(--white);
      border-radius: 14px;
      box-shadow: 0 2px 8px var(--ring);
      padding: 16px;
      margin-bottom: 16px;
    }
    .section h3 { color: var(--navy); font-size: 16px; margin-bottom: 6px; }

    ul { margin-left: 16px; }
    li { margin-bottom: 6px; }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <div class="brand">SIMAP<br>Politala</div>
      <nav class="menu">
        <a href="#" class="active">Dashboard</a>
        <a href="#">Kelompok</a>
        <a href="#">Logbook</a>
        <a href="#">Peringkat</a>
        <a href="#">Laporan Penilaian</a>
        <a href="#">Feedback</a>
      </nav>
    </div>

    <div class="account">
      <span>Nama Mahasiswa</span>
    </div>
  </aside>

  <!-- Main -->
  <main>
    <div class="header">
      <h2>Dashboard Mahasiswa</h2>
      <span>SIMAP Politala</span>
    </div>

    <div class="content">
      <h1>Selamat Datang di Dashboard Mahasiswa</h1>

      <div class="cards">
        <div class="card">
          <h2>Jumlah Logbook</h2>
          <p>5</p>
        </div>
        <div class="card">
          <h2>Nilai Tertinggi</h2>
          <p>95</p>
        </div>
        <div class="card">
          <h2>Peringkat</h2>
          <p>Top 3</p>
        </div>
      </div>

      <div class="section">
        <h3>Status Logbook</h3>
        <p>Logbook Minggu ke-5 telah disetujui oleh dosen pembimbing.</p>
      </div>

      <div class="section">
        <h3>Milestone</h3>
        <p>Deadline milestone berikutnya: <b>15 Oktober 2025</b></p>
      </div>

      <div class="section">
        <h3>Notifikasi</h3>
        <ul>
          <li>Logbook minggu 5 disetujui.</li>
          <li>Peringkat Anda meningkat ke posisi Top 3.</li>
          <li>Milestone final presentasi akan dimulai minggu depan.</li>
        </ul>
      </div>
    </div>
  </main>

</body>
</html>
