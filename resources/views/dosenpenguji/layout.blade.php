{{-- resources/views/dosenpenguji/layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Dosen Penguji')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px;
      --blue:#2f73ff; --green:#00b167; --orange:#ff8c00; --red:#e80000;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh; color:#233042;
    }
    .sidebar{
      background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column
    }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px }
    .brand-badge{ width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center; font-weight:700 }
    .brand-title{ line-height:1.1 }
    .brand-title strong{ font-size:18px }
    .brand-title small{ display:block; font-size:12px; opacity:.85 }
    .nav-title{ font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px }
    .menu a{
      display:flex; align-items:center; gap:12px; text-decoration:none; color:#e9edf7;
      padding:10px 12px; border-radius:12px; margin:4px 6px; transition:background .18s, transform .18s;
    }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }

    .logout{ margin-top:auto }
    .logout a{
      color:#ffb2b2; display:block; padding:10px 12px; border-radius:12px; text-decoration:none
    }
    .logout a:hover{ background:#5c1020 }

    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px; display:flex;
      align-items:center; justify-content:space-between; position:sticky; top:0; z-index:3;
      box-shadow:var(--shadow);
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .userbox{ display:flex; align-items:center; gap:14px }
    .page{ padding:26px; display:grid; gap:24px }

    .card{
      background:var(--card); border-radius:var(--radius); border:1px solid var(--ring); box-shadow:var(--shadow)
    }
    .card-hd{
      padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex;
      align-items:center; justify-content:space-between; font-weight:700; color:var(--navy-2)
    }
    .card-bd{ padding:16px 18px; }

    .btn{
      border:0; padding:8px 16px; border-radius:8px; font-size:14px; font-weight:700;
      cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px;
    }
    .btn-primary{ background:var(--navy-2); color:#fff; }
    .btn-primary:hover{ background:var(--navy); }
    .btn-secondary{ background:#e3eaf5; color:var(--navy-2); }
    .btn-secondary:hover{ background:#d8e0ec; }

    .form-group{ margin-bottom:16px; }
    .form-group label{ display:block; margin-bottom:6px; font-weight:700; font-size:14px; }
    .form-control{
      width:100%; padding:10px 12px; border:1px solid #d8dfeb; border-radius:8px;
      background:#fff; font-size:14px;
    }

    /* ===== Notifications (bell) ===== */
    .top-actions{display:flex;align-items:center;gap:16px;position:relative}
    .bell{
      position:relative;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;
      width:36px;height:36px;border-radius:999px;background:#0e257a
    }
    .bell i{color:#fff;font-size:16px}
    .bell .dot{
      position:absolute;top:-4px;right:-4px;
      background:#ff3b3b;color:#fff;min-width:18px;height:18px;border-radius:999px;
      font-size:12px;line-height:18px;text-align:center;padding:0 4px;border:2px solid #0a1a54;
    }
    .notif-dd{
      position:absolute;right:0;top:52px;width:360px;max-height:420px;overflow:auto;
      background:#fff;border:1px solid rgba(13,23,84,.12);border-radius:14px;
      box-shadow:0 10px 28px rgba(13,23,84,.15);
      display:none; z-index:20;
    }
    .notif-dd.active{display:block}
    .notif-hd{
      padding:10px 12px;border-bottom:1px solid #eef1f6;font-weight:700;
      color:#0e257a;display:flex;justify-content:space-between;align-items:center
    }
    .notif-list{display:grid}
    .notif-item{
      display:flex;gap:10px;padding:12px;border-bottom:1px solid #f3f5fb;
      background:#fff;cursor:pointer
    }
    .notif-item:hover{background:#f7f9ff}
    .notif-icon{
      width:28px;height:28px;border-radius:8px;display:grid;place-items:center;
      background:#e9efff;color:#1d4ed8
    }
    .notif-meta{font-size:12px;color:#6c7a8a;margin-top:2px}
    .notif-empty{padding:20px;text-align:center;color:#6c7a8a}
    .notif-ft{padding:10px;border-top:1px solid #eef1f6;text-align:center}
    .notif-ft a{color:#0e257a;text-decoration:none;font-weight:700}

    .notif-item.unread{background:#f7f9ff;}
    .notif-item-link{text-decoration:none;color:inherit;}

    /* --- Robust click & layering for notifications --- */
    header.topbar{ z-index: 5000; }
    #bellBtn{ position: relative; z-index: 7000; pointer-events:auto; }
    .notif-dd{ z-index: 6000; pointer-events:auto; }

    /* ===== User menu (avatar + dropdown) ===== */
    .userbox { position: relative; }
    .userbtn{
      display:flex; align-items:center; gap:10px; cursor:pointer;
      background:transparent; border:0; color:#fff; font-weight:700;
    }
    .userbtn .ava{
      width:32px;height:32px;border-radius:50%; display:grid; place-items:center;
      background:#e3e9ff; color:#31408a; font-weight:700; font-size:12px;
      overflow:hidden;
    }
    .userbtn i{ opacity:.8; transition:transform .15s }
    .userbtn[aria-expanded="true"] i{ transform:rotate(180deg) }

    .user-dd{
      position:absolute; top:44px; right:0; width:260px;
      background:#fff; border:1px solid #e7ecf6; border-radius:14px;
      box-shadow:0 12px 30px rgba(13,23,84,.18); padding:10px; display:none;
      z-index:6500;
    }
    .user-dd.active{ display:block }
    .user-dd .hd{
      display:flex; align-items:center; gap:10px; padding:10px 8px 12px;
      border-bottom:1px dashed #eef1f6;
    }
    .user-dd .hd .bigava{
      width:40px;height:40px;border-radius:50%; background:#e3e9ff; color:#31408a;
      display:grid; place-items:center; font-weight:800;
      overflow:hidden;
    }
    .user-dd .item{
      display:flex; align-items:center; gap:10px; padding:10px 8px; border-radius:10px;
      color:#233042; text-decoration:none;
    }
    .user-dd .item:hover{ background:#f4f7ff }
    .user-dd .item i{ width:18px; text-align:center; color:#0e257a }
    .user-dd .logout{ color:#b42318 }
  </style>
</head>
<body>
 @php
    // jumlah notif belum dibaca (dari AppServiceProvider)
    $notifCount = $unreadCount ?? 0;
 @endphp

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
      <a href="{{ url('/dosenpenguji/dashboard') }}" class="{{ request()->is('dosenpenguji/dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>Dashboard
      </a>
      <a href="{{ url('/dosenpenguji/mahasiswa') }}" class="{{ request()->is('dosenpenguji/mahasiswa*') ? 'active' : '' }}">
        <i class="fa-solid fa-user-graduate"></i>Mahasiswa
      </a>
      <a href="{{ url('/dosenpenguji/kelompok')}}" class="{{ request()->is('dosenpenguji/kelompok*') ? 'active' : '' }}">
        <i class="fa-solid fa-users"></i> Kelompok
      </a>
      <a href="{{ url('/dosenpenguji/penilaian') }}" class="{{ request()->is('dosenpenguji/penilaian*') ? 'active' : '' }}">
        <i class="fa-solid fa-clipboard-check"></i> Penilaian
      </a>
      <a href="{{ route('dosenpenguji.rubrik.index') }}" class="{{ request()->routeIs('dosenpenguji.rubrik.*') ? 'active' : '' }}">
        <i class="fa-solid fa-table-list"></i> Rubrik
      </a>
      <a href="{{ route('dosenpenguji.cpmk.index') }}" class="{{ request()->routeIs('dosenpenguji.cpmk.index') ? 'active' : '' }}">
        <i class="fa-solid fa-bullseye"></i> CPMK
      </a>

      <div class="nav-title">Akun</div>
      <a href="{{ route('dosenpenguji.profile') }}" class="{{ request()->routeIs('dosenpenguji.profile') ? 'active' : '' }}">
        <i class="fa-solid fa-id-badge"></i>Profil
      </a>
    </div>

    {{-- Tombol Logout (pakai POST biar sesuai route Laravel) --}}
    <div class="logout">
      <form action="{{ route('logout') }}" method="POST" style="margin:0">
        @csrf
        <button type="submit" style="
          width:100%;background:none;border:0;cursor:pointer;
          color:#ffb2b2; display:flex; align-items:center; gap:8px;
          padding:10px 12px; border-radius:12px; text-align:left;
        ">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>
      </form>
    </div>
  </aside>

  <main>
    <header class="topbar">
      <div class="welcome">
        <h1>@yield('header', 'Penilaian')</h1>
      </div>

      <div class="top-actions" id="topActions">
        {{-- 🔔 Bell --}}
        <div class="bell" id="bellBtn" aria-label="Notifikasi">
          <i class="fa-solid fa-bell"></i>
          @if($notifCount > 0)
            <span class="dot" id="notifDot">{{ $notifCount }}</span>
          @endif
        </div>

        {{-- 🔔 Dropdown Notifikasi (dari DB) --}}
        <div class="notif-dd" id="notifDd" role="menu" aria-hidden="true">
          <div class="notif-hd">
            <span>Notifikasi</span>
            <small style="color:#6c7a8a">{{ $notifCount }} baru</small>
          </div>

          @forelse($notifications as $n)
    <div class="notif-item {{ $n->is_read ? '' : 'unread' }}">
      <div class="notif-icon">
        <i class="fa-solid fa-bell"></i>
      </div>

      <div>
        <div style="font-weight:700;color:#0e257a">
          {{ $n->judul }}
        </div>

        @if($n->pesan)
          <div class="notif-meta">{{ $n->pesan }}</div>
        @endif

        <div class="notif-meta">
          {{ $n->created_at?->diffForHumans() }}
        </div>
      </div>
    </div>
@empty
            <div class="notif-empty">Belum ada notifikasi.</div>
          @endforelse

          <div class="notif-ft">
            <form action="{{ route('notif.readAll') }}" method="POST" style="margin:0">
              @csrf
              <button type="submit" class="btn btn-secondary" style="font-size:12px;padding:6px 12px">
                Tandai semua sudah dibaca
              </button>
            </form>
          </div>
        </div>

        {{-- User Menu (pakai foto profil) --}}
        <div class="userbox">
          @php
            $u = auth()->user();
            $displayName = $u->nama ?? $u->name ?? 'Nama Dosen Penguji';
            $initial = strtoupper(substr($displayName,0,1) . (preg_replace('/.*\s/','',$displayName)[0] ?? ''));
          @endphp

          <button id="userMenuBtn" class="userbtn" type="button" aria-expanded="false" aria-controls="userMenuDd">
            <span class="ava">
              @if($u && $u->foto)
                <img src="{{ asset('storage/'.$u->foto) }}"
                     alt="Avatar"
                     style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
              @else
                {{ $initial }}
              @endif
            </span>
            <span>{{ $displayName }}</span>
            <i class="fa-solid fa-chevron-down"></i>
          </button>

          <div id="userMenuDd" class="user-dd" role="menu" aria-labelledby="userMenuBtn">
            <div class="hd">
              <div class="bigava">
                @if($u && $u->foto)
                  <img src="{{ asset('storage/'.$u->foto) }}"
                       alt="Avatar"
                       style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                @else
                  {{ $initial }}
                @endif
              </div>
              <div style="min-width:0">
                <div style="font-weight:800;color:#0e257a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                  {{ $displayName }}
                </div>
                <div style="font-size:12px;color:#6c7a8a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                  {{ $u->email ?? 'email@example.com' }}
                </div>
              </div>
            </div>

            <a class="item" href="{{ route('dosenpenguji.profile') }}"><i class="fa-solid fa-id-badge"></i> Lihat Profil</a>
            <a class="item" href="{{ route('dosenpenguji.profile.edit') }}"><i class="fa-solid fa-user-gear"></i> Edit Profil</a>
            <a class="item" href="{{ route('help.index') }}"><i class="fa-solid fa-circle-question"></i> Bantuan</a>


            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="item logout" style="width:100%;background:none;border:0;cursor:pointer">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>

    <div class="page">
      @yield('content')
    </div>
  </main>

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
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const btn = document.getElementById('userMenuBtn');
  const dd  = document.getElementById('userMenuDd');
  if(!btn || !dd) return;

  const open = () => { dd.classList.add('active'); btn.setAttribute('aria-expanded','true'); };
  const close = () => { dd.classList.remove('active'); btn.setAttribute('aria-expanded','false'); };

  btn.addEventListener('click', function(e){
    e.stopPropagation();
    dd.classList.contains('active') ? close() : open();
  });
  dd.addEventListener('click', e => e.stopPropagation());
  document.addEventListener('click', close);
  document.addEventListener('keydown', e => { if(e.key === 'Escape') close(); });
});
</script>
</body>
</html>
