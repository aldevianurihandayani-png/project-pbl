{{-- resources/views/dosen/kelompok/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kelompok — Dosen Penguji</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0a1a54; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --head:#eef3fa;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Arial, Helvetica, sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh; color:#233042;
    }

    /* ===== SIDEBAR ===== */
    .sidebar{ background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px }
    .brand-badge{ width:36px; height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center; font-weight:700; color:#fff }
    .brand-title strong{ font-size:18px; color:#fff }
    .brand-title small{ display:block; font-size:12px; opacity:.85 }
    .nav-title{ font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px }
    .menu a{
      display:flex; align-items:center; gap:12px; text-decoration:none; color:#e9edf7;
      padding:10px 12px; border-radius:12px; margin:4px 6px; transition:.18s;
    }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }
    .logout{ margin-top:auto }
    .logout a{ color:#ffb2b2; display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; text-decoration:none }
    .logout a:hover{ background:#5c1020 }

    /* ===== HEADER / LAYOUT ===== */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:var(--navy-2); color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:3; box-shadow:var(--shadow);
    }
    .welcome h1{ margin:0; font-size:18px }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative } .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }

    @media (max-width:980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }

    /* ===== CONTENT ===== */
    .page{ padding:26px; display:grid; gap:18px }
    .toolbar{ display:flex; gap:12px; align-items:center; justify-content:space-between; flex-wrap:wrap }
    .filters{ display:flex; gap:10px; align-items:center; flex-wrap:wrap }
    .filters select,
    .filters input[type="search"]{ padding:8px 10px; border:1px solid #cfd6e3; border-radius:8px; background:#fff }
    .filters input[type="search"]{ min-width:260px }
    .btn{ border:none; border-radius:8px; padding:9px 14px; cursor:pointer; font-weight:700; font-size:14px; background:var(--navy); color:#fff }
    .btn:hover{ background:#142f85 }

    .sheet{ background:var(--card); border:1px solid var(--ring); border-radius:12px; box-shadow:var(--shadow); overflow:hidden }
    .sheet-head{ background:var(--head); font-weight:700; color:#283a5a }
    .row{ display:grid; grid-template-columns:100px 140px 1fr 120px 90px 140px; padding:10px 12px }
    .rows{ position:relative }
    .rows::before{
      content:""; position:absolute; inset:0;
      background:repeating-linear-gradient(to bottom, transparent 0 42px, #e8edf6 42px 43px);
      pointer-events:none;
    }
    .row > div{ padding:4px 6px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis }
    .title{ font-weight:700; color:#0b1d54; margin:0 0 8px 0 }

    @media(max-width:860px){ .row{ grid-template-columns:80px 120px 1fr 100px 80px 120px } }
    @media(max-width:680px){
      .sheet-head{ display:none }
      .row{ grid-template-columns:1fr 1fr; padding:12px }
      .row > div{ display:flex; gap:6px }
      .row > div::before{ content:attr(data-label) ":"; min-width:100px; color:#6b7a93 }
      .rows::before{ background:none }
    }
  </style>
</head>
<body>

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="brand-badge">SI</div>
      <div class="brand-title"><strong>SIMAP</strong><small>Politala</small></div>
    </div>

    <div class="menu">
      <div class="nav-title">Menu</div>
      <a href="{{ url('/dosenpenguji/dashboard') }}"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="{{ url('/dosenpenguji/mahasiswa') }}"><i class="fa-solid fa-user-graduate"></i> Mahasiswa</a>
      <a href="{{ url('/dosenpenguji/kelompok') }}" class="active"><i class="fa-solid fa-users"></i> Kelompok</a>
      <a href="{{ url('/dosenpenguji/penilaian') }}"><i class="fa-solid fa-clipboard-check"></i> Penilaian</a>
      <a href="{{ url('/dosenpenguji/rubrik') }}"><i class="fa-solid fa-table-list"></i> Rubrik</a>
      <a href="{{ url('/dosenpenguji/cpmk') }}"><i class="fa-solid fa-bullseye"></i> CPMK</a>

      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i> Profil</a>
    </div>

    <div class="logout">
      <a href="{{ url('/logout') }}"><i class="fa-solid fa-power-off"></i> Logout</a>
    </div>
  </aside>

  <!-- ===== MAIN ===== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome"><h1>Kelompok — Dosen Penguji</h1></div>
      <div class="userbox">
        <div class="notif"><i class="fa-regular fa-bell"></i><span class="badge">3</span></div>
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
            {{ strtoupper(substr(auth()->user()->name ?? 'NU',0,2)) }}
          </div>
          <strong>{{ auth()->user()->name ?? 'Nama User' }}</strong>
        </div>
      </div>
    </header>

    <div class="page">
      <div class="toolbar">
        <h2 class="title">Data Kelompok</h2>

        {{-- FILTER + CARI --}}
        <form class="filters" method="GET" action="{{ url('/dosenpenguji/kelompok') }}" id="filterForm">
          <label>Kelas:
            <select id="fKelas" name="kelas">
              @php $kelasNow = request('kelas','all'); @endphp
              <option value="all" {{ $kelasNow==='all'?'selected':'' }}>Semua</option>
              @foreach (['A','B','C','D','E'] as $k)
                <option value="{{ $k }}" {{ $kelasNow===$k?'selected':'' }}>{{ $k }}</option>
              @endforeach
            </select>
          </label>

          <label>Semester:
            <select id="fSemester" name="semester">
              @php $sNow = request('semester','all'); @endphp
              <option value="all" {{ $sNow==='all'?'selected':'' }}>Semua</option>
              @for ($i=1;$i<=6;$i++)
                <option value="{{ $i }}" {{ (string)$sNow===(string)$i?'selected':'' }}>{{ $i }}</option>
              @endfor
            </select>
          </label>

          <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama/kelompok/dosen/klien…">
          <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
        </form>
      </div>

      <!-- ===== DATA ASLI DARI CONTROLLER ===== -->
      @isset($kelompok)
      <div class="sheet" id="sheet">
        <div class="row sheet-head">
          <div>Kelompok</div>
          <div>NIM</div>
          <div>Nama</div>
          <div>Angkatan</div>
          <div>Kelas</div>
          <div>Klien</div>
        </div>

        <div class="rows" id="rows">
          @forelse ($kelompok as $k)
            <div class="row" data-kelas="{{ $k->kelas }}" data-semester="{{ $k->semester ?? '-' }}">
              <div data-label="Kelompok">{{ $k->nama_kelompok }}</div>
              <div data-label="Nim">{{ $k->nims }}</div>
              <div data-label="Nama">{{ $k->anggota }}</div>
              <div data-label="Angkatan">{{ $k->angkatan }}</div>
              <div data-label="Kelas">{{ $k->kelas }}</div>
              <div data-label="Klien">{{ $k->klien }}</div>
            </div>
          @empty
            <div style="padding:14px; text-align:center; color:#6b7a93;">
              Tidak ada data kelompok.
            </div>
          @endforelse
        </div>
      </div>

      <div style="margin-top:16px;">
        {{ $kelompok->links() }}
      </div>
      @endisset
    </div>
  </main>

  <script>
    const fKelas = document.getElementById('fKelas');
    const fSemester = document.getElementById('fSemester');
    fKelas.addEventListener('change', ()=> document.getElementById('filterForm').submit());
    fSemester.addEventListener('change', ()=> document.getElementById('filterForm').submit());

    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });
  </script>
</body>
</html>
