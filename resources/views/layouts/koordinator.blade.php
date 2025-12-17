{{-- resources/views/layouts/koordinator.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Dashboard — Koordinator')</title>
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
    .welcome p{ margin:2px 0 0; font-size:12px; opacity:.8 }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative; cursor:pointer; }
    .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }

    .page{ padding:26px; display:grid; gap:18px }

    /* Card & util bawaan dashboard */
    .card{
      background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring);
    }
    .card .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; justify-content:space-between; gap:10px; color:var(--navy-2); font-weight:700 }
    .card .card-hd span.small{ font-size:11px; font-weight:400; color:var(--muted); }
    .card .card-bd{ padding:16px 18px; color:#233042 }
    .muted{ color:var(--muted) }

    .grid-2{
      display:grid; grid-template-columns:2fr 1.1fr; gap:18px;
    }
    .grid-bottom{
      display:grid; grid-template-columns:1.5fr 1.2fr; gap:18px;
    }

    .table-mini{ width:100%; border-collapse:collapse; font-size:12px; }
    .table-mini th, .table-mini td{ padding:6px 4px; text-align:left; }
    .table-mini th{
      font-size:11px; text-transform:uppercase; letter-spacing:.06em;
      color:#9ca3af; border-bottom:1px solid #e3e7f2;
    }
    .table-mini tr + tr td{ border-top:1px solid #f0f2f8; }
    .table-mini tbody tr:hover{ background:#f7f8fe; }

    .tag{
      font-size:11px; padding:3px 7px; border-radius:999px; background:#e4ebff; color:#273b90;
      white-space:nowrap;
    }
    .tag-ok{ background:#dcfce7; color:#166534; }
    .tag-warn{ background:#fef9c3; color:#854d0e; }
    .tag-bad{ background:#fee2e2; color:#b91c1c; }

    .list{ list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:6px; }
    .list-item{
      font-size:13px; padding:6px 0; display:flex; justify-content:space-between; gap:10px; align-items:flex-start;
      border-bottom:1px dashed #eef1f6;
    }
    .list-text{ max-width:80%; }
    .list-sub{ font-size:11px; color:var(--muted); }
    .badge-pill{
      font-size:11px; padding:3px 7px; border-radius:999px; background:#eef3ff; color:#273b90;
      white-space:nowrap;
    }

    .progress-wrap{ margin-top:6px; }
    .progress{
      height:6px; border-radius:999px; background:#e5e7f3; overflow:hidden;
    }
    .progress > div{
      height:100%; background:linear-gradient(90deg,#2563eb,#4f46e5);
    }

    @media (max-width: 1100px){
      .grid-2, .grid-bottom{ grid-template-columns:1fr; }
    }
    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }
  </style>

  @stack('styles')
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
      <a href="{{ url('/koordinator/dashboard') }}"
         class="{{ request()->is('koordinator/dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>Dashboard
      </a>
      <a href="{{ url('/koordinator/kelompok') }}"
         class="{{ request()->is('koordinator/kelompok*') ? 'active' : '' }}">
        <i class="fa-solid fa-user-graduate"></i>Kelompok
      </a>
      <a href="{{ url('/koordinator/mahasiswa') }}"
         class="{{ request()->is('koordinator/mahasiswa*') ? 'active' : '' }}">
        <i class="fa-solid fa-users"></i>Mahasiswa
      </a>
      <a href="{{ url('/koordinator/cpmk') }}"
         class="{{ request()->is('koordinator/cpmk*') ? 'active' : '' }}">
        <i class="fa-solid fa-list-check"></i>Cpmk
      </a>
      <a href="{{ url('/koordinator/penilaian') }}"
         class="{{ request()->is('koordinator/penilaian*') ? 'active' : '' }}">
        <i class="fa-solid fa-clipboard-list"></i>Penilaian
      </a>
      <a href="{{ url('/koordinator/peringkat') }}"
         class="{{ request()->is('koordinator/peringkat*') ? 'active' : '' }}">
        <i class="fa-solid fa-ranking-star"></i>Kelola Peringkat
      </a>

      <a href="{{ route('koordinator.proyek-pbl.index') }}"
       class="{{ request()->routeIs('koordinator.proyek-pbl*') ? 'active' : '' }}">
      <i class="fa-solid fa-diagram-project"></i>Proyek PBL
      </a>

      <div class="nav-title">Akun</div>
      <a href="{{ url('/koordinator/profile') }}"class="{{ request()->is('koordinator/profile*') ? 'active' : '' }}">
        <i class="fa-solid fa-id-badge"></i>Profil
      </a>
    </div>

    <div class="logout">
      <a href="{{ url('/logout') }}" class="menu" style="display:block">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </aside>

  <!-- ========== MAIN ========== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome">
        <h1>@yield('page_title', 'Dashboard Koordinator')</h1>
      </div>
      <div class="userbox">
        <div class="notif">
          <i class="fa-regular fa-bell"></i>
          <span class="badge">{{ $jumlahNotifBaru ?? 3 }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
            {{ strtoupper(substr(auth()->user()->name ?? 'NU',0,2)) }}
          </div>
          <strong>{{ auth()->user()->name ?? 'Nama User' }}</strong>
        </div>
      </div>
    </header>

    <div class="page">
      @yield('content')
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

  @stack('scripts')
</body>
</html>
