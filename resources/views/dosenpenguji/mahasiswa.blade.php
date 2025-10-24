{{-- resources/views/dosenpenguji/mahasiswa/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mahasiswa — Dosen Penguji</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px; --blue:#2f73ff; --yellow:#ffcc00; --red:#e80000;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh; color:#233042;
    }

    /* ===== SIDEBAR ===== */
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

    /* ===== MAIN / TOPBAR ===== */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:5000; box-shadow:var(--shadow);
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }

    .page{ padding:26px; display:grid; gap:18px }

    /* ===== PAGE CONTENT ===== */
    h2.page-title{ color:var(--navy); font-size:14px; margin:0 0 8px 0; letter-spacing:.3px }
    .toolbar{ display:flex; align-items:center; gap:12px; margin-bottom:8px }
    .filters{ display:flex; align-items:center; gap:10px }
    .filters label{ font-size:14px; color:var(--navy); font-weight:700 }
    .filters select{ padding:6px 10px; border:1px solid #d8dfeb; border-radius:8px; background:#fff }

    .card{ background:var(--card); border-radius:var(--radius); border:1px solid var(--ring); box-shadow:var(--shadow) }
    .table-wrap{ overflow:auto; border-radius:12px; border:1px solid var(--ring); background:#fff }
    table{ width:100%; border-collapse:collapse; min-width:860px }
    th,td{ padding:12px 12px; font-size:14px; border-bottom:1px solid #eef1f6 }
    thead th{ background:#eef3fa; text-align:left }
    tbody tr:hover td{ background:#f9fbff }

    /* ===== Notifikasi + User Menu ===== */
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

    /* ===== Mobile ===== */
    @media (max-width:980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
      .toolbar{ flex-direction:column; align-items:stretch }
    }
  </style>
</head>
<body>

  {{-- Notifikasi dummy (aman jika nanti diganti dari controller) --}}
  @php
    $notifications = $notifications ?? [
      ['icon'=>'fa-bell', 'title'=>'Milestone baru dibuka', 'meta'=>'2 jam lalu'],
      ['icon'=>'fa-clipboard-check', 'title'=>'Logbook Minggu 3 disetujui', 'meta'=>'Kemarin'],
      ['icon'=>'fa-star', 'title'=>'Nilai Pemweb Lanjut dirilis', 'meta'=>'3 hari lalu'],
    ];
    $notifCount = count($notifications ?? []);
  @endphp

  <!-- ===== SIDEBAR ===== -->
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

  <!-- ===== MAIN ===== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="welcome"><h1>Mahasiswa — Dosen Penguji</h1></div>

      <div id="topActions">
        {{-- Bell --}}
        <div class="bell" id="bellBtn" aria-label="Notifikasi">
          <i class="fa-solid fa-bell"></i>
          @if($notifCount>0)
            <span class="dot" id="notifDot">{{ $notifCount }}</span>
          @endif
        </div>

        {{-- User dropdown --}}
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

      {{-- Dropdown notifikasi --}}
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
      <h2 class="page-title">DAFTAR MAHASISWA</h2>

      {{-- Filter (client-side) --}}
      <div class="toolbar">
        <div class="filters">
          <label for="filter-kelas">Filter Kelas:</label>
          <select id="filter-kelas" onchange="filterKelas()">
            <option value="all">Semua</option>
            <option value="A">Kelas A</option>
            <option value="B">Kelas B</option>
            <option value="C">Kelas C</option>
            <option value="D">Kelas D</option>
            <option value="E">Kelas E</option>
          </select>
        </div>
      </div>

      {{-- Tabel --}}
      <div class="card">
        <div class="table-wrap">
          <table id="tabelMahasiswa">
            <thead>
              <tr>
                <th style="width:60px">NO</th>
                <th style="width:120px">NIM</th>
                <th>NAMA</th>
                <th style="width:80px">KELAS</th>
                <th>EMAIL POLITALA</th>
                <th>DOSEN PEMBIMBING</th>
                <th>PROYEK PBL</th>
              </tr>
            </thead>
            <tbody>
              @php
                // Kompatibel: controller mungkin kirim $mahasiswa atau $mahasiswas
                $list = $mahasiswa ?? $mahasiswas ?? null;
              @endphp

              @if($list && $list->count())
                @foreach($list as $m)
                  <tr data-kelas="{{ $m->kelas }}">
                    <td>{{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}</td>
                    <td>{{ $m->nim }}</td>
                    <td>{{ $m->nama }}</td>
                    <td>{{ $m->kelas }}</td>
                    <td>{{ $m->email_politala ?? '-' }}</td>
                    <td>{{ $m->kelompok?->dosen?->nama ?? '-' }}</td>
                    <td>{{ $m->kelompok?->proyek?->nama_proyek ?? '-' }}</td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="7" style="padding:28px 16px;">
                    <div style="display:grid;place-items:center;text-align:center;gap:8px;">
                      <svg width="90" height="90" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="8" y="8" width="48" height="48" rx="6" stroke="#c9d3e6" stroke-width="2"/>
                        <path d="M20 24h24M20 32h24M20 40h16" stroke="#c9d3e6" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                      <div style="font-weight:800;color:#0b1d54">Belum ada data mahasiswa</div>
                      <div style="color:#6c7a8a">Tambahkan data terlebih dahulu atau ubah filter kelas.</div>
                    </div>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

        {{-- Pagination --}}
        @if($list && method_exists($list,'hasPages') && $list->hasPages())
          <div class="card-footer" style="padding:12px 18px;">
            {{ $list->links() }}
          </div>
        @endif
      </div>
    </div>
  </main>

  <script>
    // Filter kelas — client-side
    function filterKelas() {
      const selected = document.getElementById('filter-kelas').value;
      const rows = document.querySelectorAll('#tabelMahasiswa tbody tr');
      rows.forEach(row => {
        if(!row.dataset.kelas) return; // lewati baris empty-state
        row.style.display = (selected === 'all' || row.dataset.kelas === selected) ? '' : 'none';
      });
    }

    // Toggling sidebar (mobile) — tutup ketika klik di luar
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });

    // Dropdown notifikasi
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

    // Dropdown user
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
