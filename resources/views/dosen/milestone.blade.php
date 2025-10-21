{{-- resources/views/dosen/milestone.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Milestone — Dosen Pembimbing</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    /* ====== VARS GLOBAL ====== */
    :root{
      /* dari halaman kelompok (untuk sidebar+header) */
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px; --blue:#2f73ff; --line:#e8edf6; --head:#eef3fa;

      /* warna khusus milestone (badge) */
      --pending:#f7e3a1; --pending-text:#7a5a00;
      --belum:#e7eefc;   --belum-text:#1d3b8d;
      --selesai:#e6f6ed; --selesai-text:#0b7a45;
    }

    *{box-sizing:border-box;font-family:Arial,Helvetica,sans-serif}
    html,body{height:100%}
    body{
      margin:0; background:var(--bg); color:#101828;
      /* shell sama seperti halaman kelompok */
      display:grid; grid-template-columns:260px 1fr; min-height:100vh;
    }

    /* ====== SIDEBAR (copas style dari kelompok) ====== */
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

    /* ====== HEADER/TOPBAR (copas style dari kelompok) ====== */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px;
      display:flex; align-items:center; justify-content:space-between;
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

    /* ====== KONTEN MILESTONE (tidak diubah, hanya dirapikan) ====== */
    .page{ padding:22px; display:grid; gap:18px }
    .card{ background:var(--card); border:1px solid var(--line); border-radius:14px; box-shadow:0 8px 30px var(--ring); padding:16px }
    .page-title{ margin:2px 0 6px; font-size:22px; font-weight:800; color:var(--navy) }

    .toolbar{display:flex;gap:10px;justify-content:flex-end;margin-bottom:10px}
    .search{display:flex;gap:8px;align-items:center;background:#f8fafc;border:1px solid var(--line);
            padding:8px 10px;border-radius:10px;min-width:320px}
    .search input{border:0;outline:0;background:transparent;width:100%}
    .btn{border:0;background:var(--navy);color:#fff;border-radius:10px;padding:9px 14px;font-weight:700;cursor:pointer}
    .btn:hover{background:var(--navy-2)}

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

    @media (max-width: 980px){
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

  <!-- ===== SIDEBAR (sama dengan halaman kelompok) ===== -->
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
      <a href="{{ url('/dosen/milestone') }}" class="active"><i class="fa-solid fa-flag-checkered"></i>Milestone</a>
      <a href="{{ url('/dosen/logbook') }}"><i class="fa-solid fa-book"></i>Logbook</a>
      

      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>

    <div class="logout">
      <a href="{{ url('/logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  <!-- ===== MAIN + HEADER (sama dengan halaman kelompok) ===== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome"><h1>Milestone — Dosen Pembimbing</h1></div>
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

    <!-- ====== KONTEN MILESTONE (ASLI, tidak diubah) ====== -->
    <div class="page">
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
<<<<<<< HEAD
          @foreach ($milestones as $milestone)
          <div class="row">
            <div class="cell" data-th="No">{{ $loop->iteration }}</div>
            <div class="cell" data-th="Minggu">{{ $milestone->minggu }}</div>
            <div class="cell" data-th="Kegiatan">{{ $milestone->kegiatan }}</div>
            <div class="cell" data-th="Deadline">{{ $milestone->deadline }}</div>
=======
          <div class="row">
            <div class="cell" data-th="No">1</div>
            <div class="cell" data-th="Minggu">1</div>
            <div class="cell" data-th="Kegiatan">Menghitung manual TPK</div>
            <div class="cell" data-th="Deadline">2025-10-09</div>
>>>>>>> bbcfba2 (commit noorma)

            <div class="cell" data-th="Status">
              <div class="status-group">
                <label class="status-toggle">
<<<<<<< HEAD
                  <input type="radio" name="status-{{ $milestone->id }}" value="belum" {{ $milestone->status == 'belum' ? 'checked' : '' }}>
                  <span class="badge belum">Belum</span>
                </label>
                <label class="status-toggle">
                  <input type="radio" name="status-{{ $milestone->id }}" value="pending" {{ $milestone->status == 'pending' ? 'checked' : '' }}>
                  <span class="badge pending">Pending</span>
                </label>
                <label class="status-toggle">
                  <input type="radio" name="status-{{ $milestone->id }}" value="selesai" {{ $milestone->status == 'selesai' ? 'checked' : '' }}>
=======
                  <input type="radio" name="status-1" value="belum" checked>
                  <span class="badge belum">Belum</span>
                </label>
                <label class="status-toggle">
                  <input type="radio" name="status-1" value="pending">
                  <span class="badge pending">Pending</span>
                </label>
                <label class="status-toggle">
                  <input type="radio" name="status-1" value="selesai">
>>>>>>> bbcfba2 (commit noorma)
                  <span class="badge selesai">Selesai</span>
                </label>
              </div>
            </div>

            <div class="cell" data-th="Aksi">
<<<<<<< HEAD
              <a href="{{ route('dosen.milestone.edit', $milestone->id) }}" class="btn">Edit</a>
            </div>
          </div>
          @endforeach
=======
              <div class="aksi-note">Klik salah satu status untuk mengubah</div>
            </div>
          </div>
>>>>>>> bbcfba2 (commit noorma)
          <!-- duplikasi row sesuai kebutuhan -->
        </div>
      </div>
    </div>
  </main>

  <script>
    // sama seperti sebelumnya: contoh handler radio
    document.querySelectorAll('.status-group').forEach(group => {
      group.addEventListener('change', (e) => {
        if (e.target.name) {
          // panggil route update status di sini jika diperlukan
        }
      });
    });

    // tutup sidebar ketika klik di luar (mobile)
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });
  </script>
</body>
</html>
