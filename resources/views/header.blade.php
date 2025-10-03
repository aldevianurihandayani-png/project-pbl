<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMAP Politala</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy2:#0e2771; --bg:#f4f6fb; --card:#fff; --ring:#e5e7eb;
      --text:#111827; --muted:#6b7280; --shadow:0 8px 20px rgba(15,23,42,.06); --radius:14px;
      --blue:#3b82f6; --yellow:#f59e0b; --red:#ef4444;
    }
    *{box-sizing:border-box}
    body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;background:var(--bg);color:var(--text);display:flex;min-height:100vh}
    .sidebar{width:240px;background:var(--navy);color:#fff;padding:22px 16px;position:sticky;top:0;height:100vh}
    .brand{font-weight:800;letter-spacing:.2px;margin-bottom:10px}
    .menu{display:flex;flex-direction:column;gap:6px;margin-top:10px}
    .menu a{color:#e6ecff;text-decoration:none;padding:10px 12px;border-radius:10px;font-weight:600}
    .menu a:hover{background:rgba(255,255,255,.08)}
    .menu a.active{background:#fff;color:var(--navy)}
    .content{flex:1;display:flex;flex-direction:column}
    .topbar{background:var(--navy2);color:#dbeafe;padding:10px 18px;display:flex;justify-content:flex-end;align-items:center;gap:20px;position:relative}
    .user-info{display:flex;align-items:center;gap:10px;color:#fff;font-weight:600;cursor:pointer;text-decoration:none}
    .user-info img{width:32px;height:32px;border-radius:50%;object-fit:cover}
    .notif{position:relative;cursor:pointer;font-size:18px;color:#fff}
    .notif .badge{position:absolute;top:-6px;right:-8px;background:red;color:white;font-size:10px;padding:2px 5px;border-radius:50%}
    .notif-dropdown {
      display:none;
      position:absolute;
      top:50px;
      right:20px;
      background:#fff;
      color:#111;
      min-width:280px;
      border-radius:10px;
      box-shadow:0 8px 16px rgba(0,0,0,.2);
      z-index:1000;
      overflow:hidden;
    }
    .notif-dropdown h4{
      margin:0;padding:10px;background:#0b1d54;color:#fff;font-size:14px;
    }
    .notif-dropdown ul{list-style:none;margin:0;padding:0;max-height:300px;overflow-y:auto}
    .notif-dropdown li{padding:10px;border-bottom:1px solid #e5e7eb;font-size:14px}
    .notif-dropdown li:last-child{border-bottom:none}
    .notif-dropdown li:hover{background:#f3f4f6}
    .wrap{max-width:1100px;margin:20px auto;padding:0 18px;width:100%}
    .card{background:var(--card);border:1px solid var(--ring);border-radius:var(--radius);box-shadow:var(--shadow);padding:18px}
    .page-title{margin:0 0 14px;color:var(--navy2)}
    footer{background:#0b1d54;color:#fff;text-align:center;padding:12px;margin-top:26px}
    @media(max-width:900px){.sidebar{display:none}}
  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand">SIMAP POLITALA</div>
    <nav class="menu">
      <a href="{{ url('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
      <a href="{{ url('kelompok') }}" class="{{ request()->is('kelompok*') ? 'active' : '' }}">Kelompok</a>
      <a href="{{ url('logbook') }}" class="{{ request()->is('logbook*') ? 'active' : '' }}">Logbook</a>
      <a href="{{ url('mahasiswa') }}" class="{{ request()->is('mahasiswa*') ? 'active' : '' }}">Mahasiswa</a>
      <a href="{{ url('milestone') }}" class="{{ request()->is('milestone*') ? 'active' : '' }}">Milestone</a>
      <a href="{{ url('nilai') }}" class="{{ request()->is('nilai*') ? 'active' : '' }}">Nilai</a>
      <a href="{{ url('dokumen') }}" class="{{ request()->is('dokumen*') ? 'active' : '' }}">Dokumen</a>
      <a href="{{ url('evaluasi') }}" class="{{ request()->is('evaluasi*') ? 'active' : '' }}">Evaluasi</a>
      <a href="{{ url('feedback') }}" class="{{ request()->is('feedback*') ? 'active' : '' }}">Feedback</a>
      <a href="{{ url('profile') }}" class="{{ request()->is('profile*') ? 'active' : '' }}">Profil</a>
      <a href="{{ url('logout') }}" style="color:#fca5a5">Logout</a>
    </nav>
  </aside>
  <section class="content">
   <div class="topbar">
      <!-- Notifikasi -->
      <div class="notif" onclick="toggleNotif()">
        <i class="fas fa-bell"></i>
        <span class="badge">3</span>
        <div class="notif-dropdown" id="notifDropdown">
          <h4>Notifikasi</h4>
          <ul>
            <li>Logbook Minggu 3 disetujui</li>
            <li>Milestone Presentasi Final 7 hari lagi</li>
            <li>Dosen pembimbing menambahkan nilai baru</li>
          </ul>
        </div>
      </div>
      <!-- Info User -->
      <a href="{{ url('profile') }}" class="user-info">
        <img src="{{ session('user_photo', 'https://ui-avatars.com/api/?name='.urlencode(session('user_name', 'Nama User')).'&background=0D8ABC&color=fff') }}" alt="User">
        <span>{{ session('user_name', 'Nama User') }}</span>
      </a>
   </div>
   <div class="wrap">
