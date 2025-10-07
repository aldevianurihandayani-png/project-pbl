<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Dosen Penguji - SIMAP Politala</title>
  <style>
    :root {
      --navy: #0b1d54;
      --dark-blue: #081b45;
      --light-bg: #f5f7fb;
      --white: #ffffff;
      --shadow: rgba(0, 0, 0, 0.08);
      --hover: #1b2d70;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: "Segoe UI", Arial, sans-serif;
    }

    body {
      display: grid;
      grid-template-columns: 240px 1fr;
      min-height: 100vh;
      background: var(--light-bg);
    }

    /* SIDEBAR */
    .sidebar {
      background: var(--navy);
      color: #fff;
      padding: 22px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .brand {
      display: flex;
      align-items: center;
      margin-bottom: 32px;
    }

    .brand img {
      width: 36px;
      height: 36px;
      margin-right: 10px;
    }

    .brand h2 {
      font-size: 18px;
      font-weight: bold;
      line-height: 1.2;
    }

    .brand small {
      font-size: 12px;
      color: #cdd5f7;
    }

    .menu a {
      display: block;
      text-decoration: none;
      color: #e9ecf9;
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 8px;
      transition: background 0.2s;
    }

    .menu a:hover, .menu a.active {
      background: var(--hover);
    }

    .menu hr {
      border: 0;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
      margin: 14px 0;
    }

    .bottom-menu a {
      display: block;
      text-decoration: none;
      color: #b8c2e1;
      margin-top: 10px;
      font-size: 14px;
    }

    /* TOPBAR */
    .topbar {
      background: var(--dark-blue);
      color: #fff;
      padding: 14px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .search-bar {
      background: #1c2f70;
      border-radius: 10px;
      padding: 7px 14px;
      width: 350px;
      border: none;
      color: #fff;
      outline: none;
    }

    .search-bar::placeholder {
      color: #cbd3ff;
    }

    .profile {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .notif {
      font-size: 20px;
      color: #00d2ff;
    }

    .username {
      background: #142a6e;
      padding: 8px 12px;
      border-radius: 20px;
      font-size: 14px;
    }

    /* CONTENT */
    .content {
      padding: 32px;
    }

    .content h2 {
      color: var(--navy);
      margin-bottom: 4px;
      font-weight: 600;
    }

    .content p {
      margin-bottom: 18px;
      color: #555;
    }

    .cards {
      display: flex;
      gap: 16px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }

    .card {
      background: var(--white);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 6px var(--shadow);
      text-align: center;
      flex: 1;
      min-width: 150px;
    }

    .card h3 {
      color: var(--navy);
      font-size: 14px;
      margin-bottom: 6px;
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
      color: var(--navy);
    }

    .kelompok-card {
      background: var(--white);
      border-radius: 12px;
      box-shadow: 0 2px 6px var(--shadow);
      padding: 22px;
      width: 400px;
    }

    .kelompok-card h4 {
      color: var(--navy);
      margin-bottom: 16px;
      font-size: 17px;
    }

    .kelompok-card .btn {
      border: none;
      border-radius: 8px;
      padding: 8px 14px;
      margin-right: 6px;
      cursor: pointer;
      color: #fff;
      font-size: 13px;
      transition: 0.2s;
    }

    .btn-logbook { background: #1e88e5; }
    .btn-kelompok { background: #007bff; }
    .btn-nilai { background: #0043c4; }
    .btn:hover { opacity: 0.9; }

  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div>
      <div class="brand">
        <img src="{{ asset('images/simap_logo.png') }}" alt="Logo SIMAP">
        <h2>SIMAP<br><small>Politala</small></h2>
      </div>

      <nav class="menu">
        <a href="#" class="active">🏠 Beranda</a>
        <a href="#">📊 Evaluasi Mahasiswa</a>
        <a href="#">📝 Kelola Penilaian</a>
        <a href="#">📚 Kelola Rubrik</a>
        <hr>
        <a href="#">💬 Kelola Feedback</a>
        <a href="#">🔔 Notifikasi</a>
      </nav>
    </div>

    <div class="bottom-menu">
      <a href="#">⚙️ Settings</a>
      <a href="#">🚪 Logout</a>
    </div>
  </aside>

  <!-- MAIN AREA -->
  <main>
    <div class="topbar">
      <input type="text" class="search-bar" placeholder="Cari kelompok/mahasiswa...">
      <div class="profile">
        <span class="notif">🔔</span>
        <span class="username">aldevianuri handayani ⬇️</span>
      </div>
    </div>

    <div class="content">
      <h2>Halo, aldevianuri handayani 👋</h2>
      <p>Selamat datang di sistem PBL</p>

      <div class="cards">
        <div class="card">
          <h3>Kelompok Uji</h3>
          <p>1</p>
        </div>
        <div class="card">
          <h3>Feedback Masuk</h3>
          <p>2</p>
        </div>
        <div class="card">
          <h3>Rubrik</h3>
          <p>0</p>
        </div>
      </div>

      <div class="kelompok-card">
        <h4>Kelompok 1 PBL</h4>
        <button class="btn btn-logbook">Lihat Logbook</button>
        <button class="btn btn-kelompok">Lihat Kelompok</button>
        <button class="btn btn-nilai">Lihat Nilai</button>
      </div>
    </div>
  </main>

</body>
</html>
