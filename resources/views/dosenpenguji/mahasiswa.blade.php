{{-- resources/views/dosenpenguji/mahasiswa/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mahasiswa — Dosen Pembimbing</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px;
      --blue:#2f73ff; --yellow:#ffcc00; --red:#e80000; --gray:#e8edf6;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh;
      color:#233042;
    }

    /* ========== SIDEBAR ========== */
    .sidebar{ background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px }
    .brand-badge{ width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center; font-weight:700 }
    .brand-title{ line-height:1.1 }
    .brand-title strong{ font-size:18px }
    .brand-title small{ display:block; font-size:12px; opacity:.85 }
    .nav-title{ font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px }
    .menu a{
      display:flex; align-items:center; gap:12px; text-decoration:none; color:#e9edf7;
      padding:10px 12px; border-radius:12px; margin:4px 6px; transition:background .18s, transform .18s;
    }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }
    .logout{ margin-top:auto }
    .logout a{ color:#ffb2b2; display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; text-decoration:none }
    .logout a:hover{ background:#5c1020 }

    /* ========== MAIN ========== */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:3; box-shadow:var(--shadow);
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative } .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }

    .page{ padding:26px; display:grid; gap:18px }

    /* ========== PAGE: MAHASISWA ========== */
    h2.page-title{ color:var(--navy); font-size:14px; margin:0 0 8px 0; letter-spacing:.3px }

    .toolbar{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:8px }
    .filters{ display:flex; align-items:center; gap:10px }
    .filters label{ font-size:14px; color:var(--navy); font-weight:700 }
    .filters select{ padding:6px 10px; border:1px solid #d8dfeb; border-radius:8px; background:#fff }

    .card{ background:var(--card); border-radius:var(--radius); border:1px solid var(--ring); box-shadow:var(--shadow) }
    .table-wrap{ overflow:auto; border-radius:12px; border:1px solid var(--ring); background:#fff }
    table{ width:100%; border-collapse:collapse; min-width:860px }
    th,td{ padding:12px 12px; font-size:14px; border-bottom:1px solid #eef1f6 }
    thead th{ background:#eef3fa; color:var(--navy); text-align:left }
    tbody tr:hover td{ background:#f9fbff }

    .actions{ display:flex; gap:8px }
    .pill{ border:0; padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; color:#fff; text-decoration:none; display:inline-block }
    .pill.view{ background:var(--blue) } .pill.view:hover{ background:#0045c5 }
    .pill.edit{ background:var(--yellow); color:#000 } .pill.edit:hover{ background:#e0b000 }
    .pill.delete{ background:var(--red) } .pill.delete:hover{ background:#c50000 }

    /* status badges */
    .status{ display:inline-flex; align-items:center; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:700 }
    .status.aktif{ background:var(--blue); color:#fff }
    .status.mengulang{ background:var(--yellow); color:#000 }
    .status.keluar{ background:var(--red); color:#fff }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
      .toolbar{ flex-direction:column; align-items:stretch }
    }
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
    </div>

    <div class="menu">
      <div class="nav-title">Menu</div>
      <a href="{{ url('/dosenpenguji/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/dosenpenguji/mahasiswa') }}" class="active"><i class="fa-solid fa-user-graduate"></i>Mahasiswa</a>
      <a href="{{ url('/dosenpenguji/kelompok') }}"><i class="fa-solid fa-users"></i>Kelompok</a>
      <a href="{{ url('/dosenpenguji/penilaian') }}"><i class="fa-solid fa-flag-checkered"></i>Milestone</a>
      <a href="{{ url('/dosenpenguji/rubrik') }}"><i class="fa-solid fa-book"></i>Logbook</a>
      <a href="{{ url('/dosenpenguji/cpmk') }}"><i class="fa-solid fa-list-check"></i>CPMK</a>

      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>

    <div class="logout">
      <a href="{{ url('/logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  <!-- ========== MAIN ========== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome"><h1>Mahasiswa — Dosen Pembimbing</h1></div>
      <div class="userbox">
        <div class="notif"><i class="fa-regular fa-bell"></i><span class="badge">3</span></div>
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
            {{ strtoupper(substr(auth()->user()->name ?? 'NU',0,2)) }}
          </div>
          <strong>{{ auth()->user()->name ?? 'Nama User' }}</strong>
        </div>
      </div>
    </header>

    <div class="page">
      <h2 class="page-title">DAFTAR MAHASISWA</h2>

      <div class="toolbar">
        <div class="filters">
          <label for="filter-kelas">Filter Kelas:</label>
          <select id="filter-kelas" onchange="filterKelas()">
            <option value="all">Semua</option>
            <option value="A">Kelas A</option>
            <option value="B">Kelas B</option>
            <option value="C">Kelas C</option>
            <option value="D">Kelas D</option>
            <option value="E">Kelas E</option>
          </select>
        </div>
        {{-- tombol tambah bisa ditaruh di sini saat CRUD siap --}}
      </div>

      <div class="table-wrap card">
        <table id="tabelMahasiswa">
          <thead>
            <tr>
              <th style="width:60px">NO</th>
              <th style="width:140px">NIM</th>
              <th>NAMA</th>
              <th style="width:120px">ANGKATAN</th>
              <th style="width:160px">NO HP</th>
              <th style="width:90px">KELAS</th>
              <th style="width:140px">STATUS</th>
            </tr>
          </thead>
          <tbody>
            <tr data-kelas="A">
              <td>1</td>
              <td>220101001</td>
              <td>Rina Saputri</td>
              <td>2022</td>
              <td>08123456789</td>
              <td>A</td>
              <td><span class="status aktif">Aktif</span></td>
            </tr>
            <tr data-kelas="B">
              <td>2</td>
              <td>220101002</td>
              <td>Andi Pratama</td>
              <td>2022</td>
              <td>08129876543</td>
              <td>B</td>
              <td><span class="status mengulang">Mengulang</span></td>
            </tr>
            <tr data-kelas="A">
              <td>3</td>
              <td>220101003</td>
              <td>Dewi Lestari</td>
              <td>2023</td>
              <td>08213344556</td>
              <td>A</td>
              <td><span class="status keluar">Keluar</span></td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </main>

  <script>
    // Filter kelas (dummy)
    function filterKelas() {
      const selected = document.getElementById('filter-kelas').value;
      const rows = document.querySelectorAll('#tabelMahasiswa tbody tr');
      rows.forEach(row => {
        row.style.display = (selected === 'all' || row.dataset.kelas === selected) ? '' : 'none';
      });
    }

    // Tutup sidebar ketika klik di luar (mobile)
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });
  </script>
</body>
</html>
