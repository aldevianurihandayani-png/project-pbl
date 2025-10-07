<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard Dosen Pembimbing')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --navy: #0b1d54;
      --bg: #f5f7fb;
      --white: #fff;
      --shadow: 0 2px 8px rgba(0,0,0,.05);
      --radius: 14px;
    }

    * {box-sizing:border-box;margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;}
    body {
      display:grid;
      grid-template-columns:240px 1fr;
      min-height:100vh;
      background:var(--bg);
    }

    /* Sidebar */
    .sidebar {
      background:var(--navy);
      color:#e9edf7;
      padding:20px;
      display:flex;
      flex-direction:column;
    }
    .brand {
      font-weight:bold;
      font-size:20px;
      margin-bottom:30px;
    }
    .menu a {
      display:flex;
      align-items:center;
      gap:10px;
      color:#e9edf7;
      text-decoration:none;
      padding:10px 12px;
      border-radius:10px;
      margin-bottom:8px;
      transition:.2s;
    }
    .menu a:hover {background:#12306d;}
    .menu a.active {background:#1c3d86;}

    /* Header */
    header {
      background:#fff;
      box-shadow:var(--shadow);
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:12px 28px;
    }
    .user-info {
      display:flex;
      align-items:center;
      gap:12px;
    }
    .notif {
      position:relative;
      margin-right:10px;
    }
    .notif i {font-size:18px;color:var(--navy);}
    .notif span {
      position:absolute;
      top:-6px;right:-6px;
      background:red;color:#fff;
      font-size:10px;
      width:14px;height:14px;
      border-radius:50%;
      display:flex;justify-content:center;align-items:center;
    }

    /* Content */
    .content {padding:28px;}
    h1 {color:var(--navy);margin-bottom:10px;}
    .card {
      background:var(--white);
      border-radius:var(--radius);
      padding:18px;
      box-shadow:var(--shadow);
      margin-bottom:20px;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="brand">SIMAP <br><small>Politala</small></div>
    <div class="menu">
      <a href="{{ route('dosen.dashboard') }}" class="active"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="#"><i class="fa-solid fa-users"></i>Kelompok</a>
      <a href="#"><i class="fa-solid fa-flag-checkered"></i>Milestone</a>
      <a href="#" style="color:#f66;margin-top:auto;"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
    </div>
  </aside>

  <!-- Main -->
  <main>
    <header>
      <div><strong>Dashboard Dosen Pembimbing</strong></div>
      <div class="user-info">
        <div class="notif">
          <i class="fa-solid fa-bell"></i>
          <span>2</span>
        </div>
        <div><strong>Nama User</strong></div>
      </div>
    </header>

    <div class="content">
      @yield('content')
    </div>
  </main>
</body>
</html>
