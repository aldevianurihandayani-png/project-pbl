{{-- resources/views/dosen/kelompok/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kelompok — Dosen Pembimbing</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px; --blue:#2f73ff; --line:#e8edf6; --head:#eef3fa;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh; color:#233042;
    }

    /* ===== SIDEBAR (same as Mahasiswa) ===== */
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

    /* ===== HEADER (same as Mahasiswa) ===== */
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
    @media (max-width:980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }

    /* ===== CONTENT: sheet non-table ===== */
    .page{ padding:26px; display:grid; gap:18px }
    .toolbar{ display:flex; gap:12px; align-items:center; justify-content:space-between; flex-wrap:wrap }
    .filters{ display:flex; gap:10px; align-items:center; flex-wrap:wrap }
    .filters select{ padding:8px 10px; border:1px solid #cfd6e3; border-radius:8px; background:#fff }
    .btn{ border:none; border-radius:8px; padding:9px 14px; cursor:pointer; font-weight:700; font-size:14px; background:var(--navy); color:#fff }
    .btn:hover{ background:#142f85 }

    .sheet{ background:var(--card); border:1px solid var(--ring); border-radius:12px; box-shadow:var(--shadow); overflow:hidden }
    .sheet-head{ background:var(--head); font-weight:700; color:#283a5a }
    .row{ display:grid; grid-template-columns:100px 140px 1fr 120px 90px 140px; padding:10px 12px }
    .rows{ position:relative; }
    /* garis horizontal tipis */
    .rows::before{
      content:""; position:absolute; inset:0;
      background: repeating-linear-gradient(to bottom, transparent 0 42px, var(--line) 42px 43px);
      pointer-events:none;
    }
    .row > div{ padding:4px 6px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis }
    .title{ font-weight:700; color:#0b1d54; margin:0 0 8px 0; }

    @media(max-width:860px){
      .row{ grid-template-columns:80px 120px 1fr 100px 80px 120px }
    }
    @media(max-width:680px){
      .sheet-head{ display:none }
      .row{ grid-template-columns:1fr 1fr; padding:12px }
      .row > div{ display:flex; gap:6px }
      .row > div::before{ content:attr(data-label) ":"; min-width:100px; color:#6b7a93 }
      .rows::before{ background:none }
    }
  </style>
</head>
<body>

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="brand-badge">SI</div>
      <div class="brand-title"><strong>SIMAP</strong><small>Politala</small></div>
    </div>

    <div class="menu">
      <div class="nav-title">Menu</div>
      <a href="{{ url('/dosenpenguji/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/dosenpenguji/mahasiswa') }}"><i class="fa-solid fa-user-graduate"></i>Mahasiswa</a>
      <a href="{{ url('/dosenpenguji/kelompok') }}" class="active"><i class="fa-solid fa-users"></i>Kelompok</a>
      <a href="{{ url('/dosenpenguji/penilaian') }}"><i class="fa-solid fa-flag-checkered"></i>Milestone</a>
      <a href="{{ url('/dosenpenguji/cpmk') }}"><i class="fa-solid fa-book"></i>Logbook</a>
         <a href="{{ url('/dosenpenguji/rubrik') }}"><i class="fa-solid fa-book"></i>Rubrik</a>

      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>

    <div class="logout">
      <a href="{{ url('/logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  <!-- ===== MAIN ===== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome"><h1>Kelompok — Dosen Pembimbing</h1></div>
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
      <div class="toolbar">
        <h2 class="title">Data Kelompok</h2>

        <div class="filters">
          <label>Kelas:
            <select id="fKelas">
              <option value="all">Semua</option>
              <option>A</option><option>B</option><option>C</option><option>D</option><option>E</option>
            </select>
          </label>
          <label>Semester:
            <select id="fSemester">
              <option value="all">Semua</option>
              <option>1</option><option>2</option><option>3</option>
              <option>4</option><option>5</option><option>6</option>
            </select>
          </label>
          <button class="btn" onclick="tambahKelompok()">+ Tambah Kelompok</button>
        </div>
      </div>

      <div class="sheet" id="sheet">
        <!-- header kolom -->
        <div class="row sheet-head">
          <div>Kelompok</div><div>Nim</div><div>Nama</div><div>Angkatan</div><div>Kelas</div><div>Klien</div>
        </div>

        <!-- baris data (dummy) -->
        <div class="rows" id="rows">
          <div class="row" data-kelas="E" data-semester="3">
            <div data-label="Kelompok">1</div>
            <div data-label="Nim">2403101026</div>
            <div data-label="Nama">Aldevianuri Handayani</div>
            <div data-label="Angkatan">2024</div>
            <div data-label="Kelas">3E</div>
            <div data-label="Klien">Oky</div>
          </div>

          <div class="row" data-kelas="A" data-semester="2">
            <div data-label="Kelompok">2</div>
            <div data-label="Nim">2403101101</div>
            <div data-label="Nama">Bima Saputra</div>
            <div data-label="Angkatan">2024</div>
            <div data-label="Kelas">2A</div>
            <div data-label="Klien">UPT Perpus</div>
          </div>

          <div class="row" data-kelas="C" data-semester="5">
            <div data-label="Kelompok">3</div>
            <div data-label="Nim">2403101202</div>
            <div data-label="Nama">Citra Lestari</div>
            <div data-label="Angkatan">2023</div>
            <div data-label="Kelas">5C</div>
            <div data-label="Klien">KMM Lab</div>
          </div>

          <div class="row" data-kelas="B" data-semester="1">
            <div data-label="Kelompok">4</div>
            <div data-label="Nim">2403101303</div>
            <div data-label="Nama">Dhiya Putra</div>
            <div data-label="Angkatan">2025</div>
            <div data-label="Kelas">1B</div>
            <div data-label="Klien">UMKM RBJ</div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    const fKelas = document.getElementById('fKelas');
    const fSemester = document.getElementById('fSemester');
    const rows = document.querySelectorAll('#rows .row');

    function applyFilter(){
      const k = fKelas.value;       // A..E or all
      const s = fSemester.value;    // "1".. "6" or all
      rows.forEach(r=>{
        const kelas = r.dataset.kelas;      // A/B/C/D/E
        const sem   = r.dataset.semester;   // 1..6
        const okKelas = (k==='all') || (kelas===k);
        const okSem   = (s==='all') || (sem===s);
        r.style.display = (okKelas && okSem) ? '' : 'none';
      });
    }
    fKelas.addEventListener('change', applyFilter);
    fSemester.addEventListener('change', applyFilter);

    // Tambah baris dummy (pakai prompt)
    function tambahKelompok(){
      const idx = document.querySelectorAll('#rows .row').length + 1;
      const nim = prompt('NIM ketua:'); if(!nim) return;
      const nama = prompt('Nama ketua:') || '-';
      const angkatan = prompt('Angkatan (YYYY):') || '-';
      const kelas = prompt('Kelas (A/B/C/D/E):'); if(!kelas) return;
      const semester = prompt('Semester (1-6):'); if(!semester) return;
      const klien = prompt('Klien:') || '-';

      const wrap = document.getElementById('rows');
      const item = document.createElement('div');
      item.className = 'row';
      item.dataset.kelas = kelas.toUpperCase();
      item.dataset.semester = String(semester);
      item.innerHTML = `
        <div data-label="Kelompok">${idx}</div>
        <div data-label="Nim">${nim}</div>
        <div data-label="Nama">${nama}</div>
        <div data-label="Angkatan">${angkatan}</div>
        <div data-label="Kelas">${semester}${kelas.toUpperCase()}</div>
        <div data-label="Klien">${klien}</div>`;
      wrap.appendChild(item);
      applyFilter();
    }

    // close sidebar on outside click (mobile)
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });
  </script>
</body>
</html>
