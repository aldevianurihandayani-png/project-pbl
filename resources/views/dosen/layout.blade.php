<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>@yield('title','Dosen Pembimbing')</title>
  <style>
    :root{--navy:#0b1d54;--bg:#eef3fa;}
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);
         display:grid;grid-template-columns:240px 1fr;min-height:100vh}
    .sidebar{background:var(--navy);color:#e9edf7;padding:18px}
    .menu a{display:block;color:#e9edf7;text-decoration:none;padding:10px 12px;border-radius:10px;margin-bottom:8px}
    .menu a:hover{background:#12306d}.menu a.active{background:#1c3d86}
    .content{padding:28px}
    .card{background:#fff;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08)}
    h1{margin:0 0 16px;color:var(--navy)}
  </style>
</head>
<body>
  <!-- SIDEBAR DITEMPEL DI LAYOUT -->
  <aside class="sidebar">
    <div style="font-weight:bold;margin-bottom:12px;">
      SIMAP POLITALA<br><span style="font-size:12px;opacity:.8;">Dosen Pembimbing</span>
    </div>
    <nav class="menu">
      <a href="{{ route('dosen.dashboard') }}"
         class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">Dashboard</a>

      <a href="#"
         class="{{ request()->routeIs('dosen.mahasiswa*') ? 'active' : '' }}">Mahasiswa</a>

      <a href="#"
         class="{{ request()->routeIs('dosen.kelompok.index') ? 'active' : '' }}">Kelompok</a>

      <a href="#"
         class="{{ request()->routeIs('dosen.milestone.index') ? 'active' : '' }}">Milestone</a>

      <a href="{{ route('dosen.logbook.index') }}"
         class="{{ request()->routeIs('dosen.logbook.index') ? 'active' : '' }}">Logbook</a>

      <a href="#"
         class="{{ request()->routeIs('dosen.cpmk*') ? 'active' : '' }}">CPMK</a>
    </nav>
  </aside>

  <!-- KONTEN HALAMAN -->
  <main class="content">
    @yield('content')
  </main>
</body>
</html>
