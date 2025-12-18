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
      background:var(--navy); color:#e9edf7; padding:18px 16px;
      display:flex; flex-direction:column;
    }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px; }
    .brand-badge{
      width:36px;height:36px;border-radius:10px;background:#1a2a6b;
      display:grid;place-items:center;font-weight:700;
    }
    .brand-title strong{font-size:18px}
    .brand-title small{display:block;font-size:12px;opacity:.85}

    .nav-title{font-size:12px;text-transform:uppercase;opacity:.7;margin:16px 10px 6px}

    .menu{
      display:flex; align-items:center; gap:12px;
      color:#e9edf7; padding:10px 12px; border-radius:12px;
      margin:4px 6px; text-decoration:none;
      transition:background .18s, transform .18s;
    }
    .menu:hover{ background:#11245f; transform:translateX(2px) }
    .menu.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }

    /* LOGOUT */
    .logout{ margin-top:auto }
    .logout .menu{ color:#ffb2b2 }
    .logout .menu:hover{ background:#5c1020 }

    /* ========== MAIN ========== */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px;
      display:flex; justify-content:space-between; align-items:center;
      position:sticky; top:0; z-index:3;
    }
    .welcome h1{ margin:0; font-size:18px }
    .page{ padding:26px }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; width:240px; transform:translateX(-102%); transition:.2s }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }
    .topbar-btn{ display:none; background:none; border:0; color:#fff; font-size:20px }
  </style>

  @stack('styles')
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="brand-badge">SI</div>
      <div class="brand-title">
        <strong>SIMAP</strong>
        <small>Politala</small>
      </div>
    </div>

    <div class="menu-wrap">
      <div class="nav-title">Menu</div>

      <a href="{{ url('/koordinator/dashboard') }}"
         class="menu {{ request()->is('koordinator/dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i> Dashboard
      </a>

      <a href="{{ url('/koordinator/kelompok') }}"
         class="menu {{ request()->is('koordinator/kelompok*') ? 'active' : '' }}">
        <i class="fa-solid fa-user-graduate"></i> Kelompok
      </a>

      <a href="{{ url('/koordinator/mahasiswa') }}"
         class="menu {{ request()->is('koordinator/mahasiswa*') ? 'active' : '' }}">
        <i class="fa-solid fa-users"></i> Mahasiswa
      </a>

      <a href="{{ url('/koordinator/cpmk') }}"
         class="menu {{ request()->is('koordinator/cpmk*') ? 'active' : '' }}">
        <i class="fa-solid fa-list-check"></i> CPMK
      </a>

      <a href="{{ url('/koordinator/penilaian') }}"
         class="menu {{ request()->is('koordinator/penilaian*') ? 'active' : '' }}">
        <i class="fa-solid fa-clipboard-list"></i> Penilaian
      </a>

       <a href="{{ route('koordinator.peringkat.index') }}"
         class="menu {{ request()->routeIs('peringkat.index*') ? 'active' : '' }}">
        <i class="fa-solid fa-ranking-star"></i> Peringkat
      </a>

      <a href="{{ route('koordinator.proyek-pbl.index') }}"
         class="menu {{ request()->routeIs('koordinator.proyek-pbl*') ? 'active' : '' }}">
        <i class="fa-solid fa-diagram-project"></i> Proyek PBL
      </a>

      <div class="nav-title">Akun</div>

      <a href="{{ url('/koordinator/profile') }}"
         class="menu {{ request()->is('koordinator/profile*') ? 'active' : '' }}">
        <i class="fa-solid fa-id-badge"></i> Profil
      </a>
    </div>
    <div class="logout">
      <form action="{{ route('logout') }}" method="POST" style="margin:0;">
        @csrf
        <button type="submit"
                class="menu"
                style="width:100%;border:0;background:transparent;text-align:left;cursor:pointer;">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>
      </form>
    </div>
  </aside>

  <!-- MAIN -->
  <main>
    <header class="topbar">
      <button class="topbar-btn"
              onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="welcome">
        <h1>@yield('page_title','Dashboard Koordinator')</h1>
      </div>

      <strong>{{ auth()->user()->name ?? 'User' }}</strong>
    </header>

    <div class="page">
      @yield('content')
    </div>
  </main>

  <script>
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      if(!e.target.closest('#sidebar') && !e.target.closest('.topbar-btn')){
        sb.classList.remove('show');
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
