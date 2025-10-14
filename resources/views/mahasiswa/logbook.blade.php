{{-- resources/views/mahasiswa/logbook.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Logbook — Mahasiswa</title>
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

    /* ===== SIDEBAR (sama dengan dashboard mhs) ===== */
    .sidebar{ background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column; }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px }
    .brand-badge{ width:36px;height:36px;border-radius:10px;background:#1a2a6b;display:grid;place-items:center;color:#fff;font-weight:700 }
    .brand-title{ line-height:1.1 }
    .brand-title strong{ font-size:18px }
    .brand-title small{ display:block; font-size:12px; opacity:.85 }
    .nav-title{ font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px }
    .menu a{ display:flex; align-items:center; gap:12px; text-decoration:none; color:#e9edf7; padding:10px 12px; border-radius:12px; margin:4px 6px; transition:background .18s, transform .18s; }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }
    .logout{ margin-top:auto }
    .logout a{ color:#ffb2b2 }
    .logout a:hover{ background:#5c1020 }

    /* ===== HEADER ===== */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:3; box-shadow:var(--shadow);
    }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative } .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }

    /* ===== PAGE ===== */
    .page{ padding:26px; display:grid; gap:18px }
    .heading h2{ margin:0 0 4px } .heading small{ color:var(--muted) }

    /* ===== KARTU ===== */
    .card{ background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring); }
    .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; gap:10px; color:var(--navy-2); font-weight:700 }
    .card-bd{ padding:16px 18px }

    /* ===== FORM ===== */
    .form-grid{ display:grid; grid-template-columns:1fr 1fr; gap:16px }
    .input{ display:flex; flex-direction:column; gap:6px }
    .input label{ font-size:13px; color:#1f2b43 }
    .input input[type="text"], .input input[type="date"], .input input[type="number"], .input textarea, .input input[type="file"]{
      width:100%; padding:10px 12px; border-radius:10px; border:1px solid #cfd7e6; outline:none; background:#fff;
    }
    .input textarea{ min-height:84px; resize:vertical }
    .btn{ border:0; background:#155eef; color:#fff; padding:10px 14px; border-radius:10px; cursor:pointer; font-weight:600; box-shadow:var(--shadow) }
    .btn:disabled{ opacity:.6; cursor:not-allowed }

    /* ===== LAYOUT DUA KOLOM (FORM + KOMENTAR) ===== */
    .two-col{ display:grid; grid-template-columns:1fr 340px; gap:16px }
    .aside{ background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring) }

    /* ===== TABEL LIST LOGBOOK ===== */
    table{ width:100%; border-collapse:collapse }
    th,td{ padding:10px 12px; border-bottom:1px solid #eef2f7; text-align:left }
    th{ font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.06em }
    .pill{ padding:6px 10px; border-radius:999px; font-size:12px; border:1px solid }
    .pill.ok{ color:#166534;border-color:#86efac;background:#ecfdf5 }
    .pill.warn{ color:#92400e;border-color:#fcd34d;background:#fffbeb }
    .pill.danger{ color:#991b1b;border-color:#fecaca;background:#fef2f2 }
    .act{ display:flex; gap:8px }
    .act a,.act button{ padding:6px 10px; border-radius:8px; border:1px solid #cbd5e1; background:#fff; cursor:pointer }

    @media (max-width:980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
      .two-col{ grid-template-columns:1fr }
    }
  </style>
</head>
<body>
  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="brand-badge">SI</div>
      <div class="brand-title"><strong>SIMAP</strong><small>Mahasiswa</small></div>
    </div>
    <div class="menu">
      <div class="nav-title">Menu</div>
      <a href="{{ url('/mahasiswa/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/mahasiswa/kelompok') }}"><i class="fa-solid fa-users"></i>Kelompok</a>
      <a href="{{ url('/mahasiswa/milestone') }}"><i class="fa-solid fa-flag-checkered"></i>Milestone</a>
      <a href="{{ url('/mahasiswa/logbook') }}" class="active"><i class="fa-regular fa-clipboard"></i>Logbook</a>
      <a href="{{ url('/mahasiswa/laporan-penilaian') }}"><i class="fa-solid fa-file-lines"></i>Laporan Penilaian</a>

      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>
    <div class="logout">
      <a href="{{ url('/logout') }}" class="menu" style="display:block"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  <!-- MAIN -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome"><h1>Logbook</h1><small>Sistem Informasi Manajemen PBL</small></div>
      <div class="userbox">
        <div class="notif"><i class="fa-regular fa-bell"></i><span class="badge">2</span></div>
        <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
          {{ strtoupper(substr(($nama ?? (auth()->user()?->name ?? 'MS')),0,2)) }}
        </div>
      </div>
    </header>

    <div class="page">
      <!-- FORM INPUT + KOMENTAR DOSEN -->
      <div class="two-col">
        <section class="card">
          <div class="card-hd"><i class="fa-regular fa-clipboard"></i> Input Logbook</div>
          <div class="card-bd">
            <form action="{{ route('mhs.logbook.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="input" style="margin-bottom:10px">
                <label>Tanggal / Minggu ke</label>
                <div style="display:grid;grid-template-columns:1fr 150px;gap:10px">
                  <input type="date" name="tanggal" value="{{ old('tanggal') }}">
                  <input type="number" name="minggu" min="1" placeholder="Minggu ke-" value="{{ old('minggu') }}">
                </div>
              </div>
              <div class="input">
                <label>Aktivitas</label>
                <input type="text" name="aktivitas" placeholder="Judul Aktivitas..." value="{{ old('aktivitas') }}">
              </div>
              <div class="input">
                <label>Rincian kegiatan</label>
                <textarea name="rincian" placeholder="Deskripsi singkat milestone...">{{ old('rincian') }}</textarea>
              </div>
              <div class="input" style="margin-bottom:12px">
                <label>Upload Dokumentasi</label>
                <input type="file" name="lampiran">
              </div>
              <button class="btn" type="submit">Simpan Logbook</button>
            </form>
          </div>
        </section>

        <aside class="aside">
          <div class="card-hd"><i class="fa-regular fa-comments"></i> Komentar Dosen</div>
          <div class="card-bd">
            <p style="margin:0" class="muted">{{ $komentarTerbaru ?? 'Belum ada komentar untuk logbook ini' }}</p>
          </div>
        </aside>
      </div>

      <!-- LIST LOGBOOK (ringkas) -->
      <section class="card" style="margin-top:6px">
        <div class="card-hd"><i class="fa-solid fa-list-check"></i> Riwayat Logbook</div>
        <div class="card-bd">
          <table>
            <thead>
              <tr><th>Tanggal</th><th>Minggu</th><th>Aktivitas</th><th>Status</th><th>Lampiran</th><th>Aksi</th></tr>
            </thead>
            <tbody>
              {{-- Contoh statis/dummy. Ganti dengan @foreach ($items as $row) --}}
              <tr>
                <td>2025-10-09</td><td>5</td><td>Integrasi autentikasi</td>
                <td><span class="pill ok">Disetujui</span></td>
                <td><a href="#">download</a></td>
                <td class="act">
                  <a href="#">Lihat</a>
                  <a href="#">Edit</a>
                  <form action="#" method="POST" onsubmit="return confirm('Hapus logbook ini?')" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit">Hapus</button>
                  </form>
                </td>
              </tr>
              <tr>
                <td>2025-10-16</td><td>6</td><td>Desain tabel rubrik</td>
                <td><span class="pill warn">Menunggu</span></td>
                <td><span class="muted">-</span></td>
                <td class="act"><a href="#">Lihat</a><a href="#">Edit</a><button>Hapus</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </main>

  <script>
    // Toggle sidebar mobile
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
