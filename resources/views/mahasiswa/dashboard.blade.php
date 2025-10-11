{{-- resources/views/mahasiswa/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<<<<<<< HEAD
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard — Mahasiswa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh;
    }

    /* ========== SIDEBAR ========== */
    .sidebar{
      background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column;
    }
    .brand{
      display:flex; align-items:center; gap:10px; margin-bottom:22px;
    }
    .brand-badge{
      width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center;
      font-weight:700; letter-spacing:.5px;
    }
    .brand-title{line-height:1.1}
    .brand-title strong{font-size:18px}
    .brand-title small{display:block; font-size:12px; opacity:.85}

    .nav-title{font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px}
    .menu a{
      display:flex; align-items:center; gap:12px; text-decoration:none;
      color:#e9edf7; padding:10px 12px; border-radius:12px; margin:4px 6px;
      transition:background .18s, transform .18s;
    }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }

    .logout{ margin-top:auto }
    .logout a{ color:#ffb2b2 }
    .logout a:hover{ background:#5c1020 }

    /* ========== MAIN ========== */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:3; box-shadow:var(--shadow);
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative; }
    .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }

    .page{ padding:26px; display:grid; gap:18px }

    /* KPI cards */
    .kpi{ display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:16px }
    .kpi .card{
      background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); padding:16px 18px;
      display:flex; align-items:center; gap:12px; border:1px solid var(--ring);
    }
    .kpi .icon{ width:36px; height:36px; border-radius:10px; background:#eef3ff; display:grid; place-items:center; color:var(--navy-2) }
    .kpi .meta small{ color:var(--muted) }
    .kpi .meta b{ font-size:22px; color:var(--navy-2) }

    /* Section cards */
    .card{
      background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring);
    }
    .card .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; gap:10px; color:var(--navy-2); font-weight:700 }
    .card .card-bd{ padding:16px 18px; color:#233042 }
    .muted{ color:var(--muted) }
    ul.clean{ margin:8px 0 0 18px }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }
  </style>
</head>
<body>

  <!-- ========== SIDEBAR ========== -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="brand-badge">SI</div>
      <div class="brand-title">
        <strong>SIMAP</strong>
        <small>Politala</small>
      </div>
=======
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
>>>>>>> 9042b67c88cefd3ab08cebdcd6251739418194c3
    </div>
  </div>

<<<<<<< HEAD
    <div class="menu">
      <div class="nav-title">Menu</div>
      <a href="{{ url('/dosenpenguji/dashboard') }}" class="active"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/dosenpenguji/mahasiswa') }}"><i class="fa-solid fa-user-graduate"></i>Mahasiswa</a>
      <a href="{{ url('/dosenpenguji/penilaian') }}"><i class="fa-solid fa-users"></i>Penilaian</a>
      <a href="{{ url('/dosenpenguji/rubrik') }}"><i class="fa-solid fa-flag-checkered"></i>Rubrik</a>
      
      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>

    <div class="logout">
      <a href="{{ url('/logout') }}" class="menu" style="display:block"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  <!-- ========== MAIN ========== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome">
        <h1>Dashboard Dosen Penguji</h1>
      </div>
      <div class="userbox">
        <div class="notif">
          <i class="fa-regular fa-bell"></i>
          <span class="badge">3</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
            {{ strtoupper(substr(auth()->user()->name ?? 'NU',0,2)) }}
          </div>
          <strong>{{ auth()->user()->name ?? 'Nama User' }}</strong>
=======
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
>>>>>>> 9042b67c88cefd3ab08cebdcd6251739418194c3
        </div>
      </div>
    </header>

<<<<<<< HEAD
    <div class="page">
      <!-- KPI -->
      <section class="kpi">
        <div class="card">
          <div class="icon"><i class="fa-solid fa-users"></i></div>
          <div class="meta"><small>Jumlah Kelompok</small><br><b>{{ $jumlahKelompok ?? 4 }}</b></div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-solid fa-book"></i></div>
          <div class="meta"><small>Jumlah Kelas</small><br><b>{{ $jumlahKelas ?? 5 }}</b></div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-solid fa-user-graduate"></i></div>
          <div class="meta"><small>Mahasiswa</small><br><b>{{ $jumlahMahasiswa ?? 140 }}</b></div>
        </div>
      </section>

      <!-- Status Logbook -->
      <section class="card">
        <div class="card-hd"><i class="fa-solid fa-clipboard-check"></i> Status Logbook</div>
        <div class="card-bd">
          Beri nilai mahasiswa <strong>Disetujui</strong>.<br>
          <span class="muted">Terakhir diperbarui: 2 Oktober 2025</span>
        </div>
      </section>

      <!-- Milestone -->
      <section class="card">
        <div class="card-hd"><i class="fa-solid fa-flag"></i> Milestone</div>
        <div class="card-bd">
          Deadline milestone berikutnya: <strong>10 Oktober 2025</strong>.
        </div>
      </section>

      <!-- Nilai & Peringkat -->
      <section class="card">
        <div class="card-hd"><i class="fa-solid fa-star"></i> Nilai & Peringkat</div>
        <div class="card-bd">
          Nilai TPK: 85, Pemweb Lanjut: 90, Integrasi Sistem: 88, Sistem Operasi: 80. <br/>
          Peringkat: <strong>Top 5</strong> dalam kelas.
        </div>
      </section>

      <!-- Notifikasi -->
      <section class="card" style="margin-bottom:28px">
        <div class="card-hd"><i class="fa-regular fa-bell"></i> Notifikasi</div>
        <div class="card-bd">
          <ul class="clean">
            <li>Logbook Minggu 3 disetujui</li>
            <li>Milestone Presentasi Final 7 hari lagi</li>
            <li>Dosen pembimbing menambahkan nilai baru</li>
          </ul>
        </div>
      </section>
    </div>
  </main>

  <script>
    // Tutup sidebar ketika klik di luar (mobile)
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });
  </script>
=======
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
>>>>>>> 9042b67c88cefd3ab08cebdcd6251739418194c3
</body>
</html>

