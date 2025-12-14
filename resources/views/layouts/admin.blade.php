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
      z-index:5000; /* penting: dropdown ga ketutup */
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
      position:relative; /* dropdown nempel ke kanan */
    }

    /* ==========================
       ✅ NOTIF DROPDOWN (CUSTOM)
       ========================== */
    .top-actions{display:flex;align-items:center;gap:14px;position:relative}

    .bell-btn{
      position:relative;
      cursor:pointer;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      width:36px;height:36px;
      border-radius:999px;
      background: rgba(255,255,255,.10);
      border:1px solid rgba(255,255,255,.14);
      transition: background .15s ease;
      z-index:7000;
    }
    .bell-btn:hover{ background: rgba(255,255,255,.16); }
    .bell-btn i{color:#fff;font-size:16px}

    .bell-btn .badge{
      position:absolute; top:-6px; right:-6px;
      background:#e53935; color:#fff;
      border-radius:999px;
      font-size:11px;
      padding:2px 6px;
      min-width:18px;
      height:18px;
      line-height:14px;
      text-align:center;
      border:2px solid #0a1a54;
      font-weight:800;
    }

    .notif-dd{
      position:absolute;
      right:0;
      top:46px;
      width:360px;
      max-height:420px;
      overflow:auto;
      background:#fff;
      border:1px solid rgba(13,23,84,.12);
      border-radius:14px;
      box-shadow:0 12px 30px rgba(13,23,84,.18);
      display:none;
      z-index:9999;
      pointer-events:auto;
    }
    .notif-dd.active{display:block}

    .notif-hd{
      padding:10px 12px;
      border-bottom:1px solid #eef1f6;
      font-weight:900;
      color:#0e257a;
      display:flex;
      justify-content:space-between;
      align-items:center
    }
    .notif-item-link{display:block;text-decoration:none;color:inherit}
    .notif-item{
      display:flex;
      gap:10px;
      padding:12px;
      border-bottom:1px solid #f3f5fb;
      background:#fff;
    }
    .notif-item:hover{background:#f7f9ff}
    .notif-item.unread{background:#f7f9ff}
    .notif-icon{
      width:28px;height:28px;
      border-radius:8px;
      display:grid;
      place-items:center;
      background:#e9efff;
      color:#1d4ed8;
      flex:0 0 auto;
    }
    .notif-title{font-weight:900;color:#0e257a;font-size:13px;line-height:1.2}
    .notif-meta{font-size:12px;color:#6c7a8a;margin-top:2px}
    .notif-empty{padding:18px;text-align:center;color:#6c7a8a}
    .notif-ft{
      padding:10px;
      border-top:1px solid #eef1f6;
      text-align:center;
      background:#fff;
    }
    .notif-ft a{
      color:#0e257a;
      text-decoration:none;
      font-weight:900;
    }

    /* ✅ Profil jadi link */
    .profile-box{
      display:flex;
      align-items:center;
      gap:8px;
      text-decoration:none;
      color:inherit;
      cursor:pointer;
      padding:6px 8px;
      border-radius:12px;
      transition:background .18s;
    }
    .profile-box:hover{
      background: rgba(255,255,255,.08);
    }

    /* ✅ avatar bisa isi IMG */
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
      overflow:hidden;
      border:1px solid rgba(255,255,255,.35);
    }
    .profile-avatar img{
      width:100%;
      height:100%;
      object-fit:cover;
      display:block;
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
    use Illuminate\Support\Facades\Storage;

    $user = auth()->user();
    $userName = $user->nama ?? $user->name ?? 'Administrator';
    $parts = preg_split('/\s+/', trim($userName));
    $initial = strtoupper(
        mb_substr($parts[0] ?? 'A', 0, 1) .
        mb_substr($parts[1] ?? '', 0, 1)
    );
    $userRole = $user->role ?? 'Admin';
    $userRoleLabel = ucwords(str_replace('_',' ',$userRole));

    /* ✅ Foto topbar */
    $userPhoto = ($user && $user->profile_photo_path)
        ? Storage::url($user->profile_photo_path)
        : null;

    /* ✅ URL profil */
    $profileUrl = url('/admins/profile');

    /* ✅ data notif (kalau view composer belum ada, aman: default kosong) */
    $notifCount = $unreadCount ?? 0;
    $notifList  = $notifications ?? collect();
  @endphp

  <main>
    {{-- TOPBAR --}}
    <header class="topbar">
      <div class="topbar-left">
        <button class="topbar-btn" onclick="toggleSidebar()">
          <i class="fa-solid fa-bars"></i>
        </button>
        <div class="welcome">
          <h1>@yield('page_title', 'Manajemen Kelompok')</h1>
        </div>
      </div>

      <div class="userbox">
        {{-- ✅ Lonceng Notifikasi (Dropdown Custom) --}}
        <div class="top-actions" id="topActions">
          <div class="bell-btn" id="bellBtn" aria-label="Notifikasi">
            <i class="fa-solid fa-bell"></i>
            @if($notifCount > 0)
              <span class="badge" id="notifDot">{{ $notifCount }}</span>
            @endif
          </div>

          <div class="notif-dd" id="notifDd" role="menu" aria-hidden="true">
            <div class="notif-hd">
              <span>Notifikasi</span>
              <small style="color:#6c7a8a;font-weight:800">{{ $notifCount }} baru</small>
            </div>

            @forelse($notifList as $n)
              <a class="notif-item-link" href="{{ $n->link_url ?? url('/admins/notifikasi') }}">
                <div class="notif-item {{ ($n->pivot->is_read ?? 0) ? '' : 'unread' }}">
                  <div class="notif-icon">
                    <i class="fa-solid fa-bell"></i>
                  </div>
                  <div style="min-width:0">
                    <div class="notif-title">{{ $n->judul ?? '-' }}</div>
                    @if(!empty($n->pesan))
                      <div class="notif-meta" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $n->pesan }}
                      </div>
                    @endif
                    <div class="notif-meta">{{ $n->created_at?->diffForHumans() }}</div>
                  </div>
                </div>
              </a>
            @empty
              <div class="notif-empty">Belum ada notifikasi.</div>
            @endforelse

            <div class="notif-ft">
              <a href="{{ url('/admins/notifikasi') }}">Lihat Semua Notifikasi</a>
            </div>
          </div>
        </div>

        {{-- ✅ Profil user --}}
        <a href="{{ $profileUrl }}" class="profile-box">
          <div class="profile-avatar">
            @if($userPhoto)
              <img
                src="{{ $userPhoto }}"
                alt="Foto Profil"
                data-fallback="{{ asset('images/default-profile.png') }}"
                onerror="this.onerror=null;this.src=this.dataset.fallback;"
              >
            @else
              {{ $initial }}
            @endif
          </div>

          <div class="profile-meta">
            <span class="profile-name">{{ $userName }}</span>
            <span class="profile-role">{{ $userRoleLabel }}</span>
          </div>
        </a>
      </div>
    </header>

    {{-- ISI HALAMAN --}}
    <div class="page">
      @yield('content')
    </div>

    {{-- FOOTER --}}
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

  {{-- ✅ Script dropdown notif --}}
  <script>
  document.addEventListener('DOMContentLoaded', function(){
    const bell = document.getElementById('bellBtn');
    const dd   = document.getElementById('notifDd');
    const dot  = document.getElementById('notifDot');

    if(!bell || !dd) return;

    bell.addEventListener('click', function(e){
      e.stopPropagation();
      dd.classList.toggle('active');
      if (dd.classList.contains('active') && dot) dot.style.display = 'none';
    });

    dd.addEventListener('click', function(e){
      e.stopPropagation();
    });

    document.addEventListener('click', function(){
      dd.classList.remove('active');
    });

    document.addEventListener('keydown', function(e){
      if(e.key === 'Escape') dd.classList.remove('active');
    });
  });
  </script>

  @stack('scripts')
</body>
</html>
