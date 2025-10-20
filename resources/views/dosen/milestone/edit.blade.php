{{-- resources/views/dosen/milestone/edit.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Edit Milestone — Dosen Pembimbing</title>
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

    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; }
    .form-group input, .form-group select { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; }
    .btn{border:0;background:var(--navy);color:#fff;border-radius:10px;padding:9px 14px;font-weight:700;cursor:pointer}
    .btn:hover{background:var(--navy-2)}

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
      <div class="welcome"><h1>Edit Milestone — Dosen Pembimbing</h1></div>
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
      <h2 class="page-title">Edit Milestone</h2>

      <div class="card">
        <form action="{{ route('dosen.milestone.update', $milestone->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="form-group">
            <label for="minggu">Minggu</label>
            <input type="text" id="minggu" name="minggu" value="{{ $milestone->minggu }}">
          </div>

          <div class="form-group">
            <label for="kegiatan">Kegiatan</label>
            <input type="text" id="kegiatan" name="kegiatan" value="{{ $milestone->kegiatan }}">
          </div>

          <div class="form-group">
            <label for="deadline">Deadline</label>
            <input type="date" id="deadline" name="deadline" value="{{ $milestone->deadline }}">
          </div>

          <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
              <option value="belum" {{ $milestone->status == 'belum' ? 'selected' : '' }}>Belum</option>
              <option value="pending" {{ $milestone->status == 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="selesai" {{ $milestone->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
          </div>

          <button type="submit" class="btn">Update</button>
        </form>
      </div>
    </div>
  </main>

  <script>
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
