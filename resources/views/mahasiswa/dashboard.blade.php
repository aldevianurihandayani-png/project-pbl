{{-- resources/views/mahasiswa/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard — Mahasiswa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px; --ok:#16a34a; --warn:#f59e0b; --danger:#ef4444;
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
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px; }
    .brand-badge{
      width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center;
      font-weight:700; letter-spacing:.5px; color:#fff;
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
    .kpi{ display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:16px }
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

    table{ width:100%; border-collapse:collapse }
    th,td{ padding:10px 12px; border-bottom:1px solid #eef2f7; text-align:left }
    th{ font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.06em }
    .pill{ padding:6px 10px; border-radius:999px; font-size:12px; border:1px solid }
    .pill.ok{ color:#166534;border-color:#86efac;background:#ecfdf5 }
    .pill.warn{ color:#92400e;border-color:#fcd34d;background:#fffbeb }
    .pill.danger{ color:#991b1b;border-color:#fecaca;background:#fef2f2 }

    .progress{height:10px;background:#eef2ff;border-radius:999px;overflow:hidden}
    .progress > span{display:block;height:100%;background:#22c55e;width:64%}

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
      .kpi{ grid-template-columns:repeat(2, minmax(0,1fr)); }
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
        <small>Mahasiswa</small>
      </div>
    </div>

    <div class="menu">
      <div class="nav-title">Menu</div>
      <a href="{{ url('/mahasiswa/dashboard') }}" class="active"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/mahasiswa/kelompok') }}"><i class="fa-solid fa-users"></i>Kelompok</a>
      <a href="{{ url('/mahasiswa/milestone') }}"><i class="fa-solid fa-flag-checkered"></i>Milestone</a>
      <a href="{{ url('/mahasiswa/logbook') }}"><i class="fa-regular fa-clipboard"></i>Logbook</a>
      <a href="{{ url('/mahasiswa/laporan-penilaian') }}"><i class="fa-solid fa-file-lines"></i>Laporan Penilaian</a>

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
        <h1>Dashboard Mahasiswa</h1>
      </div>
      <div class="userbox">
        <div class="notif">
          <i class="fa-regular fa-bell"></i>
          <span class="badge">3</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
            {{ strtoupper(substr(($nama ?? (auth()->user()?->name ?? 'MS')),0,2)) }}
          </div>
          <strong>{{ $nama ?? (auth()->user()?->name ?? 'Mahasiswa') }}</strong>
        </div>
      </div>
    </header>

    <div class="page">
      <!-- KPI -->
      <section class="kpi">
        <div class="card">
          <div class="icon"><i class="fa-solid fa-users"></i></div>
          <div class="meta"><small>Anggota Kelompok</small><br><b>{{ $anggotaKelompok ?? 5 }}</b></div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-regular fa-clipboard"></i></div>
          <div class="meta"><small>Logbook Terkumpul</small><br><b>{{ $jumlahLogbook ?? 12 }}</b></div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-solid fa-flag-checkered"></i></div>
          <div class="meta"><small>Milestone Selesai</small><br><b>{{ ($milestoneSelesai ?? 3).'/'.($totalMilestone ?? 5) }}</b></div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-solid fa-star-half-stroke"></i></div>
          <div class="meta"><small>Nilai Sementara</small><br><b>{{ $nilaiAkhir ?? 86 }}</b></div>
        </div>
      </section>

      <!-- Jadwal Milestone + Progress -->
      <section class="card">
        <div class="card-hd"><i class="fa-solid fa-flag"></i> Milestone & Progress</div>
        <div class="card-bd">
          <div style="display:grid;grid-template-columns:1.2fr .8fr;gap:16px">
            <div>
              <table>
                <thead><tr><th>Tanggal</th><th>Milestone</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                  <tr>
                    <td>20 Okt 2025</td>
                    <td>Proposal</td>
                    <td><span class="pill ok">Selesai</span></td>
                    <td><button class="pill" style="border-color:#cbd5e1;background:#fff">Lihat</button></td>
                  </tr>
                  <tr>
                    <td>27 Okt 2025</td>
                    <td>Desain Sistem</td>
                    <td><span class="pill warn">Progres 60%</span></td>
                    <td><button class="pill" style="border-color:#cbd5e1;background:#fff">Detail</button></td>
                  </tr>
                  <tr>
                    <td>03 Nov 2025</td>
                    <td>Implementasi</td>
                    <td><span class="pill danger">Belum Mulai</span></td>
                    <td><button class="pill" style="border-color:#cbd5e1;background:#fff">Detail</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div>
              <div class="muted" style="margin-bottom:6px">Progress Kelompok</div>
              <div class="progress"><span style="width: {}{ isset($progress) && is_numeric($progress) ? $progress : 64 }}%;"></span></div>
              <div class="muted" style="margin-top:6px">{{ $progress ?? 64 }}%</div>

              <div class="muted" style="margin:14px 0 6px">Status Logbook</div>
              <ul class="clean">
                <li>Minggu 5: <strong>Disetujui</strong></li>
                <li>Minggu 6: <strong>Menunggu Review</strong></li>
                <li>Minggu 7: <strong>Belum Submit</strong></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- Logbook Ringkas -->
      <section class="card">
        <div class="card-hd"><i class="fa-regular fa-clipboard"></i> Logbook Terakhir</div>
        <div class="card-bd">
          <table>
            <thead><tr><th>Minggu</th><th>Ringkasan</th><th>Reviewer</th><th>Status</th></tr></thead>
            <tbody>
              <tr>
                <td>5</td><td>Integrasi autentikasi & role</td><td>Dosen Pembimbing</td><td><span class="pill ok">Disetujui</span></td>
              </tr>
              <tr>
                <td>6</td><td>Rancang tabel rubrik & relasi</td><td>Dosen Pembimbing</td><td><span class="pill warn">Menunggu</span></td>
              </tr>
              <tr>
                <td>7</td><td>Implementasi fitur notifikasi</td><td>-</td><td><span class="pill danger">Belum</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Laporan Penilaian -->
      <section class="card" style="margin-bottom:28px">
        <div class="card-hd"><i class="fa-solid fa-file-lines"></i> Laporan Penilaian</div>
        <div class="card-bd">
          <table>
            <thead><tr><th>Komponen</th><th>Bobot</th><th>Skor</th><th>Nilai Akhir</th></tr></thead>
            <tbody>
              <tr><td>Kedisiplinan</td><td>10%</td><td>85</td><td>8.5</td></tr>
              <tr><td>Logbook</td><td>25%</td><td>90</td><td>22.5</td></tr>
              <tr><td>Milestone</td><td>35%</td><td>88</td><td>30.8</td></tr>
              <tr><td>Presentasi</td><td>30%</td><td>84</td><td>25.2</td></tr>
              <tr><th colspan="3" style="text-align:right">Total</th><th>{{ $nilaiAkhir ?? 86 }}</th></tr>
            </tbody>
          </table>
          <p class="muted" style="margin-top:8px">Unduh versi PDF dari Laporan Penilaian tersedia pada menu <em>Laporan Penilaian</em>.</p>
        </div>
      </section>
    </div>
  </main>

  <script>
    // Toggle sidebar (mobile) & tutup saat klik di luar
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      const btn = e.target.closest('.topbar-btn');
      if(btn){ sb.classList.toggle('show'); return; }
      if(!sb.classList.contains('show')) return;
      if(!e.target.closest('#sidebar')) sb.classList.remove('show');
    });
  </script>
</body>
</html>
