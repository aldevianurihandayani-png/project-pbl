{{-- resources/views/dosen/milestone.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Milestone — Dosen Pembimbing</title>

  <!-- Font Awesome untuk ikon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0a1a4b; --light:#eef3fa; --card:#ffffff;
      --ring:rgba(13,23,84,.10); --text:#101828; --muted:#667085; --line:#e7ebf3;
      --pending:#f7e3a1; --pending-text:#7a5a00; --belum:#e7eefc; --belum-text:#1d3b8d;
      --selesai:#e6f6ed; --selesai-text:#0b7a45;
    }
    *{box-sizing:border-box;font-family:Arial,Helvetica,sans-serif}
    html,body{height:100%}
    body{margin:0;background:var(--light);color:var(--text);}

    /* Shell */
    .app{display:grid;grid-template-columns:240px 1fr;min-height:100vh}
    .sidebar{background:var(--navy);color:#e9edf7;padding:20px 16px}
    .brand{display:flex;gap:10px;align-items:center;margin-bottom:24px}
    .brand-badge{width:36px;height:36px;border-radius:8px;background:#22356f;display:grid;place-items:center;font-weight:800}
    .brand .title{line-height:1}
    .brand .title b{display:block;font-size:14px}
    .brand .title small{opacity:.8}

    .menu a{display:flex;align-items:center;gap:10px;text-decoration:none;color:#e9edf7;
            padding:10px 12px;border-radius:10px;margin-bottom:8px;font-size:14px}
    .menu a:hover{background:#12306d}
    .menu a.active{background:#1c3d86}
    .menu .section{margin:18px 0 8px;font-size:12px;opacity:.7;text-transform:uppercase;letter-spacing:.04em}

    /* ukuran & alignment ikon agar rapi seperti gambar 1 */
    .menu a i{width:18px;text-align:center;font-size:14px}

    .main{display:grid;grid-template-rows:64px 1fr;min-width:0}
    .topbar{background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:space-between;padding:0 20px}
    .topbar h1{font-size:18px;margin:0;font-weight:800}
    .user{display:flex;align-items:center;gap:12px}
    .user .bell{cursor:pointer}
    .user .bubble{width:34px;height:34px;border-radius:50%;background:#334a93;display:grid;place-items:center;font-weight:800}

    /* Content area */
    .content{padding:22px}
    .page-title{margin:2px 0 14px;font-size:22px;font-weight:800;color:var(--navy)}
    .card{background:var(--card);border:1px solid var(--line);border-radius:14px;
          box-shadow:0 8px 30px var(--ring);padding:16px}

    /* Toolbar pencarian */
    .toolbar{display:flex;gap:10px;justify-content:flex-end;margin-bottom:10px}
    .search{display:flex;gap:8px;align-items:center;background:#f8fafc;border:1px solid var(--line);
            padding:8px 10px;border-radius:10px;min-width:320px}
    .search input{border:0;outline:0;background:transparent;width:100%}

    .btn{border:0;background:var(--navy);color:#fff;border-radius:10px;padding:9px 14px;font-weight:700;cursor:pointer}
    .btn:hover{background:var(--navy-2)}

    /* Tabel Milestone */
    .table{width:100%;border-collapse:separate;border-spacing:0 10px}
    .thead{display:grid;grid-template-columns:70px 100px 1fr 180px 260px 160px;padding:10px 12px;
           background:#f6f8fe;border:1px solid var(--line);border-radius:12px;color:#475467;font-size:13px}
    .row{display:grid;grid-template-columns:70px 100px 1fr 180px 260px 160px;align-items:center;
         padding:12px;border:1px solid var(--line);border-radius:12px;background:#fff}
    .cell{padding:0 2px}
    .badge{display:inline-block;padding:6px 10px;border-radius:20px;font-size:12px;font-weight:700}
    .badge.belum{background:var(--belum);color:var(--belum-text)}
    .badge.pending{background:var(--pending);color:var(--pending-text)}
    .badge.selesai{background:var(--selesai);color:var(--selesai-text)}

    .aksi-note{font-size:12px;color:#7f8aa3}
    .status-group{display:flex;gap:8px;flex-wrap:wrap}
    .status-toggle{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:20px;
                   border:1px dashed var(--line);cursor:pointer;font-size:12px}
    .status-toggle input{accent-color:var(--navy)}

    /* Responsive */
    @media (max-width: 980px){
      .app{grid-template-columns:84px 1fr}
      .brand .title{display:none}
      .menu a{justify-content:center}
      .menu a span.text{display:none}
      .thead,.row{grid-template-columns:60px 80px 1fr 140px 220px 140px}
    }
    @media (max-width: 720px){
      .toolbar{justify-content:stretch}
      .thead{display:none}
      .row{grid-template-columns:1fr;gap:8px}
      .cell[data-th]::before{content:attr(data-th) " ";display:block;font-size:12px;color:#667085}
      .status-group{justify-content:flex-start}
    }
  </style>
</head>
<body>
<div class="app">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-badge">SI</div>
      <div class="title">
        <b>SIMAP POLITALA</b>
        <small>Dosen Pembimbing</small>
      </div>
    </div>

    <nav class="menu">
      <a href="{{ url('dosen/dashboard') }}">
        <i class="fa-solid fa-house"></i><span class="text">Dashboard</span>
      </a>
      <a href="{{ url('dosen/mahasiswa') }}">
        <i class="fa-solid fa-user-graduate"></i><span class="text">Mahasiswa</span>
      </a>
      <a href="{{ url('dosen/kelompok') }}">
        <i class="fa-solid fa-users"></i><span class="text">Kelompok</span>
      </a>
      <a class="active" href="{{ url('dosen/milestone') }}">
        <i class="fa-solid fa-flag"></i><span class="text">Milestone</span>
      </a>
      <a href="{{ url('dosen/logbook') }}">
        <i class="fa-solid fa-book"></i><span class="text">Logbook</span>
      </a>
      <a href="{{ url('dosen/cpmk') }}">
        <i class="fa-solid fa-sliders"></i><span class="text">CPMK</span>
      </a>

      <div class="section">AKUN</div>
      <a href="{{ url('profil') }}">
        <i class="fa-solid fa-id-card"></i><span class="text">Profil</span>
      </a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <!-- TOPBAR -->
    <header class="topbar">
      <h1>Dashboard Dosen Pembimbing</h1>
      <div class="user">
        <i class="fa-regular fa-bell bell" title="Notifikasi"></i>
        <div class="bubble">NU</div>
        <div>Nama User</div>
      </div>
    </header>

    <!-- PAGE CONTENT -->
    <div class="content">
      <h2 class="page-title">Milestone</h2>

      <div class="card">
        <!-- Toolbar/search -->
        <div class="toolbar">
          <div class="search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Cari minggu/kegiatan/deadline/status" />
          </div>
          <button class="btn">Cari</button>
        </div>

        <!-- Header kolom -->
        <div class="thead">
          <div>No</div>
          <div>Minggu</div>
          <div>Kegiatan</div>
          <div>Deadline</div>
          <div>Status</div>
          <div>Aksi</div>
        </div>

        <!-- Row contoh (dummy) -->
        <div class="table">
          <div class="row">
            <div class="cell" data-th="No">1</div>
            <div class="cell" data-th="Minggu">1</div>
            <div class="cell" data-th="Kegiatan">Menghitung manual TPK</div>
            <div class="cell" data-th="Deadline">2025-10-09</div>

            <div class="cell" data-th="Status">
              <div class="status-group">
                <label class="status-toggle">
                  <input type="radio" name="status-1" value="belum" checked>
                  <span class="badge belum">Belum</span>
                </label>
                <label class="status-toggle">
                  <input type="radio" name="status-1" value="pending">
                  <span class="badge pending">Pending</span>
                </label>
                <label class="status-toggle">
                  <input type="radio" name="status-1" value="selesai">
                  <span class="badge selesai">Selesai</span>
                </label>
              </div>
            </div>

            <div class="cell" data-th="Aksi">
              <div class="aksi-note">Klik salah satu status untuk mengubah</div>
            </div>
          </div>
          <!-- duplikasi row sesuai kebutuhan -->
        </div>
      </div>
    </div>
  </main>
</div>

<script>
  // Demo: ubah warna badge saat radio dipilih (tanpa backend)
  document.querySelectorAll('.status-group').forEach(group => {
    group.addEventListener('change', (e) => {
      if (e.target.name) {
        // panggil route update status di sini jika diperlukan
        // fetch('/dosen/milestone/{id}/status', {method:'POST', body:...})
      }
    });
  });
</script>
</body>
</html>
