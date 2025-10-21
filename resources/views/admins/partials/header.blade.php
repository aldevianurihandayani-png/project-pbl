<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $title ?? 'Dashboard' }} — Admin SIMAP</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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
    /* Sidebar */
    .sidebar{ background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column; }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px; }
    .brand-badge{ width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center; font-weight:700; letter-spacing:.5px; }
    .brand-title{ line-height:1.1 }
    .brand-title strong{ font-size:18px }
    .brand-title small{ display:block; font-size:12px; opacity:.85 }
    .nav-title{ font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px }
    .menu a{
      display:flex; align-items:center; gap:12px; text-decoration:none;
      color:#e9edf7; padding:10px 12px; border-radius:12px; margin:4px 6px;
      transition:background .18s, transform .18s;
    }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }
    .logout{ margin-top:auto }
    .logout button{ color:#ffb2b2 }
    .logout button:hover{ background:#5c1020; color:#fff }

    /* Main / header */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:3; box-shadow:var(--shadow);
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative; color:#fff }
    .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }

    .page{ padding:26px; }
    .card{ background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring); }
    .card .card-header{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; gap:10px; color:var(--navy-2); font-weight:700 }
    .card .card-body{ padding:16px 18px; color:#233042 }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }
  </style>
</head>
<body>

  <!-- ===== SIDEBAR ===== -->
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

      {{-- Gunakan url() supaya tidak error jika nama route belum dibuat --}}
      <a href="{{ url('/admins/dashboard') }}"
         class="{{ request()->is('admins/dashboard') ? 'active' : '' }}">
         <i class="fa-solid fa-house"></i>Dashboard
      </a>

      <a href="{{ url('/admins/matakuliah') }}"
         class="{{ request()->is('admins/matakuliah*') ? 'active' : '' }}">
         <i class="fa-solid fa-book"></i>Mata Kuliah
      </a>

      <a href="{{ url('/admins/mahasiswa') }}"
         class="{{ request()->is('admins/mahasiswa*') ? 'active' : '' }}">
         <i class="fa-solid fa-user-graduate"></i>Mahasiswa
      </a>

      <a href="{{ url('/admins/kelompok') }}"
         class="{{ request()->is('admins/kelompok*') ? 'active' : '' }}">
         <i class="fa-solid fa-users"></i>Kelompok
      </a>

      <a href="{{ url('/admins/feedback') }}"
         class="{{ request()->is('admins/feedback') ? 'active' : '' }}">
         <i class="fa-solid fa-comment"></i>Feedback
      </a>

      <div class="nav-title">Akun</div>
      {{-- Pakai url fallback agar aman bila route('profile.index') belum ada --}}
      <a href="{{ url('/profile') }}"
         class="{{ request()->is('profile') ? 'active' : '' }}">
         <i class="fa-solid fa-id-badge"></i>Profil
      </a>
    </div>

    <form action="{{ route('logout') }}" method="POST" class="logout mt-2">
      @csrf
      <button type="submit" class="btn btn-link" style="text-decoration:none; display:block; text-align:left; padding:10px 12px;">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </button>
    </form>
  </aside>

  <!-- ===== MAIN ===== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="welcome">
        <h1>{{ $title ?? 'Dashboard' }}</h1>
      </div>

      <div class="userbox">
        <a href="{{ url('/admins/notifikasi') }}" class="notif">
          <i class="fa-regular fa-bell"></i>
          <span class="badge">3</span>
        </a>
        <a href="{{ url('/profile') }}" style="text-decoration:none; color:inherit">
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
              {{ strtoupper(substr(auth()->user()->name ?? 'PU',0,2)) }}
            </div>
            <strong>{{ auth()->user()->name ?? 'Nama Pengguna' }}</strong>
          </div>
        </a>
      </div>
    </header>

    <div class="page">
      @yield('content')
    </div>
  </main>

</body>
</html>
