{{-- resources/views/dosenpenguji/mahasiswa_detail.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detail Mahasiswa Kelas {{ $kelas }} — Dosen Penguji</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px; --blue:#2f73ff;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh; color:#233042;
    }

    /* SIDEBAR (copy dari mahasiswa.blade) */
    .sidebar{ background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px }
    .brand-badge{ width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center; font-weight:700; color:#fff }
    .brand-title{ line-height:1.1 }
    .brand-title strong{ font-size:18px; color:#fff }
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
    .logout form{ margin:0 }
    .logout button{
      width:100%; text-align:left; border:0; background:transparent; cursor:pointer;
      color:#ffb2b2; padding:10px 12px; border-radius:12px; display:flex; align-items:center; gap:12px;
    }
    .logout button:hover{ background:#5c1020; color:#fff }

    /* MAIN / TOPBAR */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:5000; box-shadow:var(--shadow);
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }

    .page{ padding:26px; display:grid; gap:16px }
    h2.page-title{ color:var(--navy); font-size:14px; margin:0 0 4px 0; letter-spacing:.3px }

    .header-row{
      display:flex;
      flex-wrap:wrap;
      justify-content:space-between;
      align-items:flex-end;
      gap:12px;
    }
    .kelas-title-main{ font-size:20px; font-weight:800; color:var(--navy); }
    .kelas-sub{ font-size:13px; color:var(--muted); }

    .btn-back{
      display:inline-flex; align-items:center; gap:6px;
      padding:7px 12px; border-radius:999px; border:1px solid #d0d7ea;
      background:#fff; color:#233042; font-size:13px; text-decoration:none;
    }
    .btn-back i{ font-size:12px; }

    .toolbar{
      display:flex; justify-content:flex-end; align-items:flex-end; flex-wrap:wrap; gap:10px;
    }
    .search-group{ display:flex; flex-direction:column; gap:4px; min-width:220px; }
    .search-group label{ font-size:12px; font-weight:700; color:var(--navy); }
    .search-group input{
      padding:6px 10px; border-radius:8px; border:1px solid #d8dfeb; font-size:13px;
    }
    .toolbar button{
      padding:7px 14px; border-radius:10px; border:0; background:var(--blue); color:#fff;
      font-size:13px; font-weight:700; cursor:pointer;
    }

    .card{
      background:var(--card); border-radius:var(--radius);
      border:1px solid var(--ring); box-shadow:var(--shadow);
    }
    .card-body{ padding:0; }
    .table-responsive{ border-radius:var(--radius); overflow:auto; }
    table{ width:100%; border-collapse:collapse; min-width:880px; }
    th,td{ padding:10px 12px; font-size:13px; border-bottom:1px solid #eef1f6; }
    thead th{ background:#eef3fa; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:.4px; }
    tbody tr:hover td{ background:#f9fbff; }

    .card-footer{ padding:10px 14px; }

    .empty-row td{
      padding:24px 16px; text-align:center; color:var(--muted);
    }

    /* Notifikasi + User Menu (copy dari mahasiswa.blade) */
    #topActions{ display:flex; align-items:center; gap:14px; }
    .bell{ position:relative; cursor:pointer; } .bell i{ font-size:18px }
    .bell .dot{
      position:absolute; top:-6px; right:-6px; min-width:18px; height:18px; padding:0 4px; border-radius:10px; font-size:10px;
      background:#e53935; color:#fff; display:grid; place-items:center;
    }

    .notif-dd{
      position:absolute; right:0; top:44px; width:320px; background:#fff; color:#233042;
      border:1px solid #e7ecf6; border-radius:14px; box-shadow:0 12px 30px rgba(13,23,84,.18);
      display:none; z-index:6000; overflow:hidden;
    }
    .notif-dd.active{ display:block; }
    .notif-hd{ display:flex; justify-content:space-between; align-items:center; padding:12px 14px; font-weight:700; color:#0e257a; background:#f8fbff }
    .notif-list{ max-height:300px; overflow:auto }
    .notif-item{ display:flex; gap:10px; padding:10px 12px; border-top:1px solid #f0f2f7 }
    .notif-item:hover{ background:#f7f9ff }
    .notif-icon{ width:28px; height:28px; border-radius:8px; background:#eef3ff; display:grid; place-items:center; color:#0e257a }
    .notif-meta{ font-size:12px; color:#6c7a8a }
    .notif-empty{ padding:16px; color:#6c7a8a; text-align:center }
    .notif-ft{ padding:10px 12px; border-top:1px solid #f0f2f7; text-align:center; background:#fafcff }
    .notif-ft a{ color:#0e257a; text-decoration:none; font-weight:700 }

    .userbox{ position:relative; }
    .userbtn{
      display:flex; align-items:center; gap:10px; cursor:pointer; background:transparent; border:0; color:#fff; font-weight:700;
    }
    .userbtn .ava{
      width:32px;height:32px;border-radius:50%; display:grid; place-items:center;
      background:#e3e9ff; color:#31408a; font-weight:700; font-size:12px;
    }
    .userbtn i{ opacity:.85; transition:transform .15s }
    .userbtn[aria-expanded="true"] i{ transform:rotate(180deg) }

    .user-dd{
      position:absolute; top:44px; right:0; width:260px; background:#fff; color:#233042; border:1px solid #e7ecf6; border-radius:14px;
      box-shadow:0 12px 30px rgba(13,23,84,.18); padding:10px; display:none; z-index:6500;
    }
    .user-dd.active{ display:block }
    .user-dd .hd{ display:flex; align-items:center; gap:10px; padding:10px 8px 12px; border-bottom:1px dashed #eef1f6; }
    .user-dd .bigava{ width:40px;height:40px;border-radius:50%; background:#e3e9ff; color:#31408a; display:grid; place-items:center; font-weight:800; }
    .user-dd .item{ display:flex; align-items:center; gap:10px; padding:10px 8px; border-radius:10px; color:#233042; text-decoration:none; }
    .user-dd .item:hover{ background:#f4f7ff }
    .user-dd .item i{ width:18px; text-align:center; color:#0e257a }
    .user-dd .logout{ color:#b42318 }

    /* Mobile */
    @media (max-width:980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
      .page{ padding:18px; }
      .header-row{ align-items:flex-start; }
    }
  </style>
</head>
<body>

  @php
    $notifications = $notifications ?? [];
    $notifCount = count($notifications);
  @endphp

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="brand-badge">SI</div>
      <div class="brand-title"><strong>SIMAP</strong><small>Politala</small></div>
    </div>

    <div class="menu">
      <div class="nav-title">Menu</div>
      <a href="{{ url('/dosenpenguji/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/dosenpenguji/mahasiswa') }}" class="active"><i class="fa-solid fa-user-graduate"></i>Mahasiswa</a>
      <a href="{{ url('/dosenpenguji/kelompok') }}"><i class="fa-solid fa-users"></i>Kelompok</a>
      <a href="{{ url('/dosenpenguji/penilaian') }}"><i class="fa-solid fa-clipboard-check"></i>Penilaian</a>
      <a href="{{ url('/dosenpenguji/rubrik') }}"><i class="fa-solid fa-table-list"></i>Rubrik</a>
      <a href="{{ url('/dosenpenguji/cpmk') }}"><i class="fa-solid fa-bullseye"></i>CPMK</a>

      <div class="nav-title">Akun</div>
      <a href="{{ url('/dosenpenguji/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>

    <div class="logout">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
      </form>
    </div>
  </aside>

  <!-- MAIN -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="welcome"><h1>Detail Mahasiswa — Kelas {{ $kelas }}</h1></div>

      <div id="topActions">
        <div class="bell" id="bellBtn" aria-label="Notifikasi">
          <i class="fa-solid fa-bell"></i>
          @if($notifCount>0)
            <span class="dot" id="notifDot">{{ $notifCount }}</span>
          @endif
        </div>

        @php $u = auth()->user(); $initial = strtoupper(substr($u->name ?? 'AL',0,2)); @endphp
        <div class="userbox">
          <button id="userMenuBtn" class="userbtn" type="button" aria-expanded="false" aria-controls="userMenuDd">
            <span class="ava">{{ $initial }}</span>
            <span>{{ $u->name ?? 'Aldevianuri Handayani' }}</span>
            <i class="fa-solid fa-chevron-down"></i>
          </button>

          <div id="userMenuDd" class="user-dd" role="menu" aria-labelledby="userMenuBtn">
            <div class="hd">
              <div class="bigava">{{ $initial }}</div>
              <div style="min-width:0">
                <div style="font-weight:800;color:#0e257a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                  {{ $u->name ?? 'Aldevianuri Handayani' }}
                </div>
                <div style="font-size:12px;color:#6c7a8a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                  {{ $u->email ?? 'email@example.com' }}
                </div>
              </div>
            </div>
            <a class="item" href="{{ route('dosenpenguji.profile') }}"><i class="fa-solid fa-id-badge"></i> Lihat Profil</a>
            <a class="item" href="{{ route('dosenpenguji.profile.edit') }}"><i class="fa-solid fa-user-gear"></i> Edit Profil</a>
            <a class="item" href="#"><i class="fa-solid fa-circle-question"></i> Bantuan</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="item logout" style="width:100%;background:none;border:0;cursor:pointer">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
              </button>
            </form>
          </div>
        </div>
      </div>

      <div class="notif-dd" id="notifDd" role="menu" aria-hidden="true">
        <div class="notif-hd">
          <span>Notifikasi</span>
          <small style="color:#6c7a8a">{{ $notifCount }} baru</small>
        </div>
        @if($notifCount>0)
          <div class="notif-list" id="notifList">
            @foreach($notifications as $n)
              <div class="notif-item">
                <div class="notif-icon"><i class="fa-solid {{ $n['icon'] }}"></i></div>
                <div>
                  <div style="font-weight:700;color:#0e257a">{{ $n['title'] }}</div>
                  <div class="notif-meta">{{ $n['meta'] }}</div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="notif-empty">Belum ada notifikasi.</div>
        @endif
        <div class="notif-ft"><a href="#">Lihat semua pemberitahuan</a></div>
      </div>
    </header>

    <div class="page">
      <div class="header-row">
        <div>
          <div class="kelas-title-main">Kelas {{ $kelas }}</div>
          <div class="kelas-sub">Total: {{ $mahasiswa->total() }} mahasiswa</div>
        </div>
        <a href="{{ url('/dosenpenguji/mahasiswa') }}" class="btn-back">
          <i class="fa-solid fa-arrow-left"></i> Kembali ke daftar kelas
        </a>
      </div>

      {{-- Search dalam 1 kelas --}}
      <div class="toolbar">
        <form action="{{ url()->current() }}" method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
          <input type="hidden" name="kelas" value="{{ $kelas }}">
          <div class="search-group">
            <label for="q">Cari di kelas ini</label>
            <input type="text" id="q" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama / NIM">
          </div>
          <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>&nbsp; Cari
          </button>
        </form>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th style="width:40px;">No</th>
                  <th style="width:120px;">NIM</th>
                  <th>Nama</th>
                  <th style="width:220px;">Email</th>
                  <th style="width:190px;">Dosen Pembimbing</th>
                  <th>Proyek PBL</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($mahasiswa as $index => $mhs)
                  <tr>
                    <td>{{ ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + $loop->iteration }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->nama }}</td>
                    {{-- email biasa: ambil dari relasi user, ganti ke $mhs->email kalau kolomnya ada --}}
                    <td>{{ $mhs->user->email ?? '-' }}</td>
                    <td>{{ optional($mhs->dosenPembimbing)->nama ?? '-' }}</td>
                    <td>{{ optional($mhs->proyekPbl)->judul ?? optional($mhs->proyekPbl)->nama_proyek ?? '-' }}</td>
                  </tr>
                @empty
                  <tr class="empty-row">
                    <td colspan="6">Belum ada data mahasiswa di kelas ini.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        @if($mahasiswa->hasPages())
          <div class="card-footer">
            {{ $mahasiswa->links() }}
          </div>
        @endif
      </div>
    </div>
  </main>

  <script>
    // Sidebar mobile
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });

    // Notifikasi
    document.addEventListener('DOMContentLoaded', function(){
      const bell = document.getElementById('bellBtn');
      const dd   = document.getElementById('notifDd');
      const dot  = document.getElementById('notifDot');
      if(bell && dd){
        bell.addEventListener('click', function(e){
          e.stopPropagation();
          dd.classList.toggle('active');
          if (dd.classList.contains('active') && dot) dot.style.display = 'none';
        });
        dd.addEventListener('click', e => e.stopPropagation());
        document.addEventListener('click', () => dd.classList.remove('active'));
      }
    });

    // User dropdown
    document.addEventListener('DOMContentLoaded', function(){
      const btn = document.getElementById('userMenuBtn');
      const dd  = document.getElementById('userMenuDd');
      if(btn && dd){
        const open  = () => { dd.classList.add('active');  btn.setAttribute('aria-expanded','true');  };
        const close = () => { dd.classList.remove('active'); btn.setAttribute('aria-expanded','false'); };
        btn.addEventListener('click', function(e){
          e.stopPropagation();
          dd.classList.contains('active') ? close() : open();
        });
        dd.addEventListener('click', e => e.stopPropagation());
        document.addEventListener('click', close);
        document.addEventListener('keydown', e => { if(e.key === 'Escape') close(); });
      }
    });
  </script>
</body>
</html>
