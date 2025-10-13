{{-- resources/views/dosen/logbook/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Logbook — Dosen Pembimbing</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px; --line:#e8edf6; --head:#eef3fa;
      --belum:#e7eefc;   --belum-text:#1d3b8d;
      --pending:#f7e3a1; --pending-text:#7a5a00;
      --selesai:#e6f6ed; --selesai-text:#0b7a45;
    }
    *{box-sizing:border-box;font-family:Arial,Helvetica,sans-serif}
    body{margin:0;background:var(--bg);color:#101828;display:grid;grid-template-columns:260px 1fr;min-height:100vh}

    /* ===== Sidebar & Header (sama dengan milestone) ===== */
    .sidebar{ background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px }
    .brand-badge{ width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center; font-weight:700 }
    .brand-title strong{ font-size:18px }
    .brand-title small{ display:block; font-size:12px; opacity:.85; line-height:1.1 }
    .nav-title{ font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px }
    .menu a{ display:flex; gap:12px; align-items:center; color:#e9edf7; text-decoration:none; padding:10px 12px; border-radius:12px; margin:4px 6px; transition:.18s }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .logout{ margin-top:auto }
    .logout a{ color:#ffb2b2; text-decoration:none; padding:10px 12px; border-radius:12px; display:flex; gap:12px; align-items:center }
    .logout a:hover{ background:#5c1020 }

    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{ background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:3; box-shadow:var(--shadow) }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px }
    .userbox{ display:flex; gap:14px; align-items:center }
    .notif{ position:relative } .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }

    @media(max-width:980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }

    /* ===== Konten ===== */
    .page{ padding:22px; display:grid; gap:16px }
    .sheet{ background:var(--card); border:1px solid var(--line); border-radius:14px; box-shadow:0 10px 28px var(--ring); overflow:hidden }
    .toolbar{ display:flex; gap:12px; align-items:center; justify-content:flex-start; padding:12px; background:#fff }
    .toolbar select, .toolbar input{ padding:8px 10px; border:1px solid #cfd6e3; border-radius:8px; background:#fff }

    .thead{
      display:grid; grid-template-columns:60px 110px 80px 160px 1fr 160px 110px 110px 150px;
      gap:0; background:#f7f9ff; color:#334155; font-weight:700; border-top:1px solid var(--line); border-bottom:1px solid var(--line)
    }
    .thead > div{ padding:12px }

    .row{
      display:grid; grid-template-columns:60px 110px 80px 160px 1fr 160px 110px 110px 150px;
      padding:12px; background:#fff; border-bottom:1px solid var(--line); align-items:center
    }
    .row:nth-child(even){ background:#fbfdff }
    .cell{ padding:4px 8px }
    .cell img{ width:120px; height:70px; object-fit:cover; border-radius:8px; border:1px solid var(--line) }

    .status-btns{ display:flex; gap:8px; flex-wrap:wrap }
    .sbtn{ border:1px solid var(--line); background:#fff; padding:6px 10px; border-radius:20px; font-size:12px; cursor:pointer; font-weight:700 }
    .sbtn.active.belum{   background:var(--belum);   color:var(--belum-text) }
    .sbtn.active.pending{ background:var(--pending); color:var(--pending-text) }
    .sbtn.active.selesai{ background:var(--selesai); color:var(--selesai-text) }
    /* mode selesai: sembunyikan yang lain, tampilkan hanya tombol selesai */
    .status-btns.done .sbtn{ display:none }
    .status-btns.done .sbtn.selesai{ display:inline-flex }

    @media(max-width:1000px){
      .thead,.row{ grid-template-columns:60px 110px 80px 160px 1fr 140px 90px 90px 150px }
    }
    @media(max-width:760px){
      .thead{ display:none }
      .row{ grid-template-columns:1fr; gap:6px }
      .cell[data-th]::before{ content:attr(data-th) ": "; display:block; font-size:12px; color:#64748b; margin-bottom:2px }
    }
  </style>
</head>
<body>

  {{-- ===== SIDEBAR ===== --}}
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="brand-badge">SI</div>
      <div class="brand-title"><strong>SIMAP</strong><small>Politala</small></div>
    </div>

    <div class="menu">
      <div class="nav-title">Menu</div>
      <a href="{{ url('/dosen/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/dosen/mahasiswa') }}"><i class="fa-solid fa-user-graduate"></i>Mahasiswa</a>
      <a href="{{ url('/dosen/kelompok') }}"><i class="fa-solid fa-users"></i>Kelompok</a>
      <a href="{{ url('/dosen/milestone') }}"><i class="fa-solid fa-flag-checkered"></i>Milestone</a>
      <a href="{{ url('/dosen/logbook') }}" class="active"><i class="fa-solid fa-book"></i>Logbook</a>
      
      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>

    <div class="logout">
      <a href="{{ url('/logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  {{-- ===== MAIN ===== --}}
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome"><h1>Logbook — Dosen Pembimbing</h1></div>
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
      <div class="sheet">

        {{-- Toolbar filter --}}
        <div class="toolbar">
          <label>Kelas:
            <select id="fKelas">
              <option value="all">Semua</option>
              <optgroup label="Tingkat 3">
                <option>3A</option><option>3B</option><option>3C</option><option>3D</option><option>3E</option>
              </optgroup>
              <optgroup label="Tingkat 4">
                <option>4A</option><option>4B</option><option>4C</option><option>4D</option><option>4E</option>
              </optgroup>
            </select>
          </label>
          <label>Cari:
            <input id="q" type="text" placeholder="Nama/NIM/Aktivitas...">
          </label>
        </div>

        {{-- Header kolom --}}
        <div class="thead">
          <div>NO</div>
          <div>Tanggal</div>
          <div>Minggu</div>
          <div>Aktivitas</div>
          <div>Rincian Kegiatan</div>
          <div>Gambar Dokumentasi</div>
          <div>Kelompok</div>
          <div>Kelas</div>
          <div>Status</div>
        </div>

        {{-- Data dummy --}}
        <div id="rows">
          <div class="row" data-kelas="3A" data-search="rina 220101001 membuat laporan kelompok 1 3a">
            <div class="cell" data-th="NO">1</div>
            <div class="cell" data-th="Tanggal">2025-10-11</div>
            <div class="cell" data-th="Minggu">6</div>
            <div class="cell" data-th="Aktivitas">Membuat Laporan</div>
            <div class="cell" data-th="Rincian Kegiatan">Melanjutkan laporan sampai ke bab rancangan</div>
            <div class="cell" data-th="Gambar Dokumentasi"><img src="https://picsum.photos/seed/a/160/95" alt="doc"></div>
            <div class="cell" data-th="Kelompok">1</div>
            <div class="cell" data-th="Kelas">3A</div>
            <div class="cell" data-th="Status">
              <div class="status-btns" data-row="1">
                <button class="sbtn belum active">Belum</button>
                <button class="sbtn pending">Pending</button>
                <button class="sbtn selesai">Selesai</button>
              </div>
            </div>
          </div>

          <div class="row" data-kelas="4C" data-search="andi 220101002 riset pustaka kelompok 5 4c">
            <div class="cell" data-th="NO">2</div>
            <div class="cell" data-th="Tanggal">2025-10-12</div>
            <div class="cell" data-th="Minggu">6</div>
            <div class="cell" data-th="Aktivitas">Riset Pustaka</div>
            <div class="cell" data-th="Rincian Kegiatan">Kumpulkan 5 referensi jurnal terbaru</div>
            <div class="cell" data-th="Gambar Dokumentasi"><img src="https://picsum.photos/seed/b/160/95" alt="doc"></div>
            <div class="cell" data-th="Kelompok">5</div>
            <div class="cell" data-th="Kelas">4C</div>
            <div class="cell" data-th="Status">
              <div class="status-btns" data-row="2">
                <button class="sbtn belum">Belum</button>
                <button class="sbtn pending active">Pending</button>
                <button class="sbtn selesai">Selesai</button>
              </div>
            </div>
          </div>

          <div class="row" data-kelas="3E" data-search="gina 220101010 uji coba aplikasi kelompok 2 3e">
            <div class="cell" data-th="NO">3</div>
            <div class="cell" data-th="Tanggal">2025-10-13</div>
            <div class="cell" data-th="Minggu">7</div>
            <div class="cell" data-th="Aktivitas">Uji Coba Aplikasi</div>
            <div class="cell" data-th="Rincian Kegiatan">Testing modul login & dashboard</div>
            <div class="cell" data-th="Gambar Dokumentasi"><img src="https://picsum.photos/seed/c/160/95" alt="doc"></div>
            <div class="cell" data-th="Kelompok">2</div>
            <div class="cell" data-th="Kelas">3E</div>
            <div class="cell" data-th="Status">
              <div class="status-btns done" data-row="3">
                <button class="sbtn belum">Belum</button>
                <button class="sbtn pending">Pending</button>
                <button class="sbtn selesai active">Selesai</button>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /sheet -->
    </div><!-- /page -->
  </main>

  <script>
    // ==== Filter kelas + cari
    const fKelas = document.getElementById('fKelas');
    const q = document.getElementById('q');
    const rows = [...document.querySelectorAll('#rows .row')];

    function applyFilter(){
      const kelas = fKelas.value; // "3A"..."4E" atau "all"
      const term  = q.value.trim().toLowerCase();
      rows.forEach(r=>{
        const okKelas = (kelas === 'all') || (r.dataset.kelas === kelas);
        const okCari  = !term || (r.dataset.search || '').includes(term);
        r.style.display = (okKelas && okCari) ? '' : 'none';
      });
    }
    fKelas.addEventListener('change', applyFilter);
    q.addEventListener('input', applyFilter);

    // ==== Status button behaviour
    document.querySelectorAll('.status-btns').forEach(group=>{
      group.addEventListener('click', (e)=>{
        const btn = e.target.closest('.sbtn');
        if(!btn) return;

        // Toggle selection
        group.querySelectorAll('.sbtn').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');

        // Mode selesai: tampilkan hanya tombol selesai; klik ulang untuk membuka opsi
        if(btn.classList.contains('selesai')){
          if(group.classList.contains('done')){
            // buka kembali opsi
            group.classList.remove('done');
          }else{
            // kunci sebagai selesai
            group.classList.add('done');
          }
        }else{
          group.classList.remove('done');
        }

        // TODO: panggil AJAX untuk simpan status jika sudah ada backend
        // fetch(`/dosen/logbook/${group.dataset.row}/status`, {method:'POST', body:...})
      });
    });

    // Tutup sidebar saat klik di luar (mobile)
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });
  </script>
</body>
</html>
