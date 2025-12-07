<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title','Dashboard — Admin')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root{
      --navy:#0b1d54;
      --navy-2:#0e257a;
      --bg:#f5f7fb;
      --card:#ffffff;
      --muted:#6c7a8a;
      --ring:rgba(13,23,84,.10);
      --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px;
    }
    *{box-sizing:border-box}

    body{
      margin:0;
      font-family:Arial,Helvetica,sans-serif;
      background:var(--bg);
      display:grid;
      grid-template-columns:260px 1fr;
      min-height:100vh;
    }

    /* SIDEBAR */
    .sidebar{
      background:var(--navy);
      color:#e9edf7;
      padding:18px 16px;
      display:flex;
      flex-direction:column;
    }
    .brand{
      display:flex;
      align-items:center;
      gap:10px;
      margin-bottom:22px;
    }
    .brand-badge{
      width:36px;height:36px;
      border-radius:10px;
      background:#1a2a6b;
      display:grid;
      place-items:center;
      font-weight:700;
      letter-spacing:.5px;
    }
    .brand-title{line-height:1.1}
    .brand-title strong{font-size:18px}
    .brand-title small{display:block;font-size:12px;opacity:.85}
    .nav-title{
      font-size:12px;
      letter-spacing:.6px;
      text-transform:uppercase;
      opacity:.7;
      margin:16px 10px 6px;
    }
    .menu a{
      display:flex;
      align-items:center;
      gap:12px;
      text-decoration:none;
      color:#e9edf7;
      padding:10px 12px;
      border-radius:12px;
      margin:4px 6px;
      transition:background .18s, transform .18s;
    }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }

    .logout{ margin-top:auto }
    .logout a{ color:#ffb2b2 }
    .logout a:hover{ background:#5c1020 }

    /* MAIN & TOPBAR */
    main{
      display:flex;
      flex-direction:column;
      min-width:0;
    }

    header.topbar{
      background:#0a1a54;
      color:#fff;
      padding:12px 22px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      position:sticky;
      top:0;
      z-index:3;
      box-shadow:var(--shadow);
    }
    .topbar-left{
      display:flex;
      align-items:center;
      gap:10px;
    }
    .topbar-btn{
      display:none;
      border:0;
      background:transparent;
      color:#fff;
      font-size:20px;
      cursor:pointer;
    }
    .welcome h1{
      margin:0;
      font-size:18px;
      letter-spacing:.2px;
    }

    .userbox{
      display:flex;
      align-items:center;
      gap:14px;
    }
    .notif{
      position:relative;
      text-decoration:none;
      color:#fff;
    }
    .notif i{ font-size:18px; }
    .notif .badge{
      position:absolute;
      top:-6px;
      right:-6px;
      background:#e53935;
      color:#fff;
      border-radius:10px;
      font-size:10px;
      padding:2px 5px;
      min-width:16px;
      text-align:center;
    }

    .profile-box{
      display:flex;
      align-items:center;
      gap:8px;
    }
    .profile-avatar{
      width:32px;
      height:32px;
      border-radius:999px;
      background:#1a2a6b;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#fff;
      font-weight:700;
      font-size:13px;
    }
    .profile-meta{
      display:flex;
      flex-direction:column;
      line-height:1.1;
    }
    .profile-name{
      font-size:13px;
      font-weight:600;
    }
    .profile-role{
      font-size:11px;
      opacity:.8;
    }

    .page{
      padding:26px;
      display:grid;
      gap:18px;
    }

    /* Card umum, KPI dll – biarkan seperti semula */
    .kpi{
      display:grid;
      grid-template-columns:repeat(3, minmax(0,1fr));
      gap:16px;
    }
    .kpi .card{
      background:var(--card);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      padding:16px 18px;
      display:flex;
      align-items:center;
      gap:12px;
      border:1px solid var(--ring);
    }
    .kpi .icon{
      width:36px;
      height:36px;
      border-radius:10px;
      background:#eef3ff;
      display:grid;
      place-items:center;
      color:var(--navy-2);
    }
    .kpi .meta small{ color:var(--muted) }
    .kpi .meta b{ font-size:22px; color:var(--navy-2) }

    .card{
      background:var(--card);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      border:1px solid var(--ring);
    }
    .card .card-hd{
      padding:14px 18px;
      border-bottom:1px solid #eef1f6;
      display:flex;
      align-items:center;
      gap:10px;
      color:var(--navy-2);
      font-weight:700;
    }
    .card .card-bd{
      padding:16px 18px;
      color:#233042;
    }
    .muted{ color:var(--muted) }
    ul.clean{ margin:8px 0 0 18px }
    a.card-link{text-decoration:none}

    /* RESPONSIVE */
    @media (max-width:980px){
      body{
        grid-template-columns:1fr;
      }
      .sidebar{
        position:fixed;
        inset:0 auto 0 0;
        width:240px;
        transform:translateX(-102%);
        transition:transform .2s;
        z-index:10;
      }
      .sidebar.show{ transform:none; }
      .topbar-btn{ display:inline-flex; }
    }
  </style>

  @stack('styles')
</head>
<body>
  {{-- SIDEBAR --}}
  @include('admins.partials.sidebar')

  @php
    $user = auth()->user();
    $userName = $user->nama ?? $user->name ?? 'Administrator';
    $parts = preg_split('/\s+/', trim($userName));
    $initial = strtoupper(
        mb_substr($parts[0] ?? 'A', 0, 1) .
        mb_substr($parts[1] ?? '', 0, 1)
    );
    $userRole = $user->role ?? 'Admin';
    $userRoleLabel = ucwords(str_replace('_',' ',$userRole));
  @endphp

  <main>
    {{-- TOPBAR BIRU (header global) --}}
    <header class="topbar">
      <div class="topbar-left">
        <button class="topbar-btn" onclick="toggleSidebar()">
          <i class="fa-solid fa-bars"></i>
        </button>
        <div class="welcome">
          {{-- Judul halaman: bisa di-set di setiap view pakai @section('page_title', 'Manajemen Mata Kuliah') --}}
          <h1>@yield('page_title', 'Manajemen Kelompok')</h1>
        </div>
      </div>

      <div class="userbox">
        {{-- Lonceng notifikasi: arahkan ke halaman notifikasi --}}
        <a href="{{ url('/admins/notifikasi') }}" class="notif">
          <i class="fa-solid fa-bell"></i>
          <span class="badge">3</span> {{-- ganti dengan jumlah notif dinamis kalau sudah siap --}}
        </a>

        {{-- Profil user --}}
        <div class="profile-box">
          <div class="profile-avatar">{{ $initial }}</div>
          <div class="profile-meta">
            <span class="profile-name">{{ $userName }}</span>
            <span class="profile-role">{{ $userRoleLabel }}</span>
          </div>
        </div>
      </div>
    </header>

    {{-- ISI HALAMAN --}}
    <div class="page">
      @yield('content')
    </div>

    {{-- FOOTER (opsional) --}}
    @include('admins.partials.footer')
  </main>

  {{-- SCRIPT GLOBAL --}}
  @include('admins.partials.scripts')

  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      if (sidebar) sidebar.classList.toggle('show');
    }
  </script>

  @stack('scripts')
</body>
</html>
