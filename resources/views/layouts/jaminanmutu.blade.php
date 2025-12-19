{{-- resources/views/layouts/jaminanmutu.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Dashboard — Jaminan Mutu')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  @php
    // ✅ DATA ASLI NOTIFIKASI (dropdown)
    $notifBaru = \App\Models\Notification::getUnreadCount();
    $notifs    = \App\Models\Notification::getListForTopbar(5);
  @endphp

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
      overflow: visible;
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .userbox{ display:flex; align-items:center; gap:14px; overflow: visible; }

    .notif{ position:relative; }
    .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }

    .page{ padding:26px; display:grid; gap:18px }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }

    /* =========================
       DROPDOWN NOTIF
       ========================= */
    .notif-btn{
      background:transparent; border:0; padding:0; color:inherit; cursor:pointer;
      display:inline-block; line-height:1;
    }
    .notif-dropdown{
      position:absolute; top:34px; right:0; width:320px; background:#fff;
      border:1px solid #e5e7eb; border-radius:14px; box-shadow:0 12px 30px rgba(0,0,0,.18);
      overflow:hidden; display:none; z-index:9999;
    }
    .notif-dropdown.show{ display:block; }
    .notif-dd-head{
      background:var(--navy); color:#fff; padding:12px 14px;
      display:flex; justify-content:space-between; align-items:center; font-weight:700;
    }
    .notif-dd-head small{ font-weight:600; opacity:.9; }
    .notif-dd-item{
      display:block; padding:12px 14px; text-decoration:none; color:#111827;
      border-bottom:1px solid #eef2f7;
    }
    .notif-dd-item:hover{ background:#f3f6ff; }
    .notif-dd-item strong{ display:block; font-size:14px; }
    .notif-dd-item em{ display:block; font-style:normal; color:#6b7280; font-size:12px; margin-top:2px; }
    .notif-dd-foot{ background:#f9fafb; padding:10px; text-align:center; }
    .notif-dd-foot a{ color:#1e3a8a; text-decoration:none; font-weight:700; }
    .notif-dd-foot a:hover{ text-decoration:underline; }

    @stack('styles')
  </style>
</head>
<body>

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

      <a href="{{ url('/jaminanmutu/dashboard') }}"
         class="{{ request()->is('jaminanmutu/dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>Dashboard
      </a>

      <a href="{{ url('/jaminanmutu/penilaian') }}"
         class="{{ request()->is('jaminanmutu/penilaian*') ? 'active' : '' }}">
        <i class="fa-solid fa-users"></i>Penilaian
      </a>

      <a href="{{ url('/jaminanmutu/rubrik') }}"
         class="{{ request()->is('jaminanmutu/rubrik*') ? 'active' : '' }}">
        <i class="fa-solid fa-flag-checkered"></i>Rubrik
      </a>

      <div class="nav-title">Akun</div>
      <a href="{{ route('jaminanmutu.profile') }}"
   class="{{ request()->is('jaminanmutu/profile*') ? 'active' : '' }}">
  <i class="fa-solid fa-id-badge"></i>Profil
</a>
    </div>

    <div class="logout" style="margin-top:auto">
  <form action="{{ route('logout') }}" method="POST" style="margin:0;">
    @csrf
    <button type="submit" class="menu"
      style="display:flex;width:100%;border:0;background:transparent;padding:10px 12px;border-radius:12px;margin:4px 6px;color:#ffb2b2;cursor:pointer;">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </button>
  </form>
</div>
  </aside>

  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="welcome">
        <h1>@yield('page_title', 'Jaminan Mutu (PBL)')</h1>
      </div>

      <div class="userbox">
        <div class="notif" id="notifWrapJM">
          <button type="button" class="notif-btn" id="notifBtnJM" aria-label="Notifikasi">
            <i class="fa-solid fa-bell"></i>
            @if($notifBaru > 0)
              <span class="badge">{{ $notifBaru }}</span>
            @endif
          </button>

          <div class="notif-dropdown" id="notifBoxJM">
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
    
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb || !sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });

    // Toggle dropdown notifikasi
    document.addEventListener('click', function(e){
      const btn = document.getElementById('notifBtnJM');
      const box = document.getElementById('notifBoxJM');
      if(!btn || !box) return;

      if(btn.contains(e.target)){
        e.preventDefault();
        e.stopPropagation();
        box.classList.toggle('show');
        return;
      }

      if(!box.contains(e.target)){
        box.classList.remove('show');
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
