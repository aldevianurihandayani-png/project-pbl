<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard Dosen Pembimbing')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  @php
    // ✅ DATA ASLI NOTIFIKASI (GLOBAL DI LAYOUT)
    $notifBaru = \App\Models\Notification::getUnreadCount();
    $notifs    = \App\Models\Notification::getListForTopbar(5);

    $user = auth()->user();
    $namaUser = $user?->nama ?? $user?->name ?? 'Nama User';
  @endphp

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
      position:relative;
      z-index: 5;
      overflow: visible; /* 🔥 biar dropdown gak kepotong */
    }
    .user-info {
      display:flex;
      align-items:center;
      gap:12px;
      overflow: visible; /* 🔥 biar dropdown gak kepotong */
    }

    /* ===== NOTIF ICON (tetap gaya kamu, tapi jadi button) ===== */
    .notif {
      position:relative;
      margin-right:10px;
      cursor:pointer;
      background: transparent;
      border: 0;
      padding: 0;
      line-height: 1;
      z-index: 10001;        /* 🔥 biar bisa diklik */
      pointer-events: auto;  /* 🔥 biar gak ketutup layer */
    }
    .notif i {font-size:18px;color:var(--navy);}
    .notif span.badge {
      position:absolute;
      top:-6px;right:-6px;
      background:red;color:#fff;
      font-size:10px;
      width:14px;height:14px;
      border-radius:50%;
      display:flex;justify-content:center;align-items:center;
    }

    /* ===== DROPDOWN NOTIF (box seperti mahasiswa) ===== */
    .notif-dropdown{
      position:absolute;
      top:42px;
      right:0;
      width:320px;
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:14px;
      box-shadow:0 12px 30px rgba(0,0,0,.18);
      overflow:hidden;
      display:none;
      z-index:10002; /* 🔥 di atas semuanya */
    }
    .notif-dropdown.show{ display:block; }

    .notif-dd-head{
      background:var(--navy);
      color:#fff;
      padding:12px 14px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      font-weight:700;
    }
    .notif-dd-head small{ font-weight:600; opacity:.9; }

    .notif-dd-item{
      display:block;
      padding:12px 14px;
      text-decoration:none;
      color:#111827;
      border-bottom:1px solid #eef2f7;
    }
    .notif-dd-item:hover{ background:#f3f6ff; }

    .notif-dd-item strong{ display:block; font-size:14px; }
    .notif-dd-item em{
      display:block;
      font-style:normal;
      color:#6b7280;
      font-size:12px;
      margin-top:2px;
    }

    .notif-dd-foot{
      background:#f9fafb;
      padding:10px;
      text-align:center;
    }
    .notif-dd-foot a{
      color:#1e3a8a;
      text-decoration:none;
      font-weight:700;
    }
    .notif-dd-foot a:hover{ text-decoration:underline; }

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
        {{-- 🔔 NOTIFIKASI (klik -> dropdown, data asli) --}}
        <div style="position:relative;">
          <button type="button" class="notif" id="notifBtn" aria-label="Notifikasi">
            <i class="fa-solid fa-bell"></i>
            @if($notifBaru > 0)
              <span class="badge">{{ $notifBaru }}</span>
            @endif
          </button>

          <div class="notif-dropdown" id="notifBox">
            <div class="notif-dd-head">
              <div>Notifikasi</div>
              <small>{{ $notifBaru }} baru</small>
            </div>

            @forelse($notifs as $n)
              <a class="notif-dd-item" href="{{ route('admins.notifikasi.read', $n->id) }}">
                <strong>{{ $n->judul }}</strong>
                <em>{{ \Illuminate\Support\Str::limit($n->pesan, 60) }}</em>
              </a>
            @empty
              <div class="notif-dd-item" style="border-bottom:0;color:#6b7280;">
                Tidak ada notifikasi
              </div>
            @endforelse

            <div class="notif-dd-foot">
              <a href="{{ route('admins.notifikasi.index') }}">Lihat semua</a>
            </div>
          </div>
        </div>

        <div><strong>{{ $namaUser }}</strong></div>
      </div>
    </header>

    <div class="content">
      @yield('content')
    </div>
  </main>

  {{-- ✅ SCRIPT toggle di LAYOUT (biar jalan di semua halaman) --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const btn = document.getElementById('notifBtn');
      const box = document.getElementById('notifBox');
      if (!btn || !box) return;

      btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        box.classList.toggle('show');
      });

      document.addEventListener('click', function (e) {
        if (!box.contains(e.target) && !btn.contains(e.target)) {
          box.classList.remove('show');
        }
      });
    });
  </script>
</body>
</html>
