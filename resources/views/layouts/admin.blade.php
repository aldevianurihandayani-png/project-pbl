<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title','Dashboard — Admin')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  {{-- CSS INLINE (dipindah dari file kamu) --}}
  <style>
    :root{ --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08); --radius:16px; }
    *{box-sizing:border-box}
    body{ margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh; }

    /* SIDEBAR */
    .sidebar{ background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column; }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px; }
    .brand-badge{ width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center;
      font-weight:700; letter-spacing:.5px; }
    .brand-title{line-height:1.1}
    .brand-title strong{font-size:18px}
    .brand-title small{display:block; font-size:12px; opacity:.85}
    .nav-title{font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px}
    .menu a{ display:flex; align-items:center; gap:12px; text-decoration:none; color:#e9edf7; padding:10px 12px;
      border-radius:12px; margin:4px 6px; transition:background .18s, transform .18s; }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }
    .logout{ margin-top:auto }
    .logout a{ color:#ffb2b2 }
    .logout a:hover{ background:#5c1020 }

    /* MAIN */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{ background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:3; box-shadow:var(--shadow); }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative; }
    .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }
    .page{ padding:26px; display:grid; gap:18px }

    /* KPI */
    .kpi{ display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:16px }
    .kpi .card{ background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); padding:16px 18px;
      display:flex; align-items:center; gap:12px; border:1px solid var(--ring); }
    .kpi .icon{ width:36px; height:36px; border-radius:10px; background:#eef3ff; display:grid; place-items:center; color:var(--navy-2) }
    .kpi .meta small{ color:var(--muted) } .kpi .meta b{ font-size:22px; color:var(--navy-2) }

    /* Card umum */
    .card{ background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring); }
    .card .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; gap:10px; color:var(--navy-2); font-weight:700 }
    .card .card-bd{ padding:16px 18px; color:#233042 }
    .muted{ color:var(--muted) } ul.clean{ margin:8px 0 0 18px }
    a.card-link{text-decoration:none}

    /* Notif dropdown */
    .notif-dropdown{ position:relative; display:inline-block; cursor:pointer; }
    .notif-icon{ position:relative; padding:5px; }
    .notif-icon .badge{ position:absolute; top:-2px; right:-2px; background:#e53935; color:#fff; border-radius:50%; font-size:10px; padding:2px 5px; min-width:18px; text-align:center; line-height:14px; }
    .dropdown-content{ display:none; position:absolute; background:#f9f9f9; min-width:300px; box-shadow:0 8px 16px rgba(0,0,0,.2); z-index:1; right:0; border-radius:8px; overflow:hidden; border:1px solid #ddd; }
    .notif-dropdown:hover .dropdown-content{ display:block }
    .dropdown-content.show-dropdown{ display:block }
    .dropdown-header{ padding:12px 16px; border-bottom:1px solid #eee; font-weight:bold; color:var(--navy-2); background:#f0f2f5; }
    .dropdown-item{ display:flex; padding:10px 16px; color:#333; border-bottom:1px solid #eee; transition:background-color .2s; align-items:center; text-decoration:none }
    .dropdown-item:hover{ background:#f1f1f1 } .dropdown-item:last-of-type{ border-bottom:none }
    .dropdown-item .item-icon{ width:30px; height:30px; border-radius:50%; display:grid; place-items:center; margin-right:10px; color:#fff; font-size:14px; }
    .item-icon.info{ background:#3498db } .item-icon.materi{ background:#2ecc71 } .item-icon.tugas{ background:#e67e22 }
    .dropdown-item .item-content{ flex-grow:1 } .dropdown-item .item-title{ font-size:14px; font-weight:600; line-height:1.3 }
    .dropdown-item .item-time{ font-size:11px; color:#777 }
    .dropdown-item.no-notif{ text-align:center; font-style:italic; color:#777; padding:20px 16px }
    .dropdown-footer{ display:flex; justify-content:space-between; padding:10px 16px; border-top:1px solid #eee; background:#f0f2f5 }
    .mark-all-read-btn,.view-all-btn{ background:none; border:none; color:#1c3d86; cursor:pointer; font-size:13px; text-decoration:none; padding:5px 8px; border-radius:4px; }
    .mark-all-read-btn:hover,.view-all-btn:hover{ background:#e0e0e0 }

    @media (max-width:980px){
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
  {{-- SIDEBAR --}}
  @include('admins.partials.sidebar')

    {{-- ISI HALAMAN --}}
    <div class="page">
      @yield('content')
    </div>

    {{-- FOOTER (opsional, kalau mau) --}}
    @include('admins.partials.footer')
  </main>

  {{-- SCRIPT GLOBAL --}}
  @include('admins.partials.scripts')
  @stack('scripts')
</body>
</html>
