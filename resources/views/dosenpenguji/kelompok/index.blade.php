
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar Kelompok — Dosen Penguji</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative; }
    .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }

    .page{ padding:26px; display:grid; gap:18px }

    /* Section cards */
    .card{
      background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring);
    }
    .card .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; justify-content:space-between; color:var(--navy-2); font-weight:700 }
    .card .card-bd{ padding:0; color:#233042; }
    
    /* Table Styles */
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 12px 18px; text-align: left; border-bottom: 1px solid #eef1f6; }
    .table th { color: var(--muted); font-size: 14px; font-weight: 600; }
    .table td { font-size: 15px; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover { background-color: #f5f7fb; }

    /* Button Styles */
    .btn {
        display: inline-block;
        font-weight: 600;
        color: #fff;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        background-color: var(--navy-2);
        border: 1px solid transparent;
        padding: .5rem 1rem;
        font-size: .875rem;
        border-radius: .5rem;
        text-decoration: none;
        transition: background-color .15s ease-in-out;
    }
    .btn:hover { background-color: var(--navy); }
    .btn-warning { background-color: #f59e0b; }
    .btn-warning:hover { background-color: #d97706; }
    .btn-danger { background-color: #ef4444; }
    .btn-danger:hover { background-color: #dc2626; }
    .btn-sm { padding: .25rem .5rem; font-size: .75rem; }

    .form-inline { display: flex; align-items: flex-end; gap: 1rem; }
    .form-inline .form-group { flex-grow: 1; }
    .form-control { display: block; width: 100%; padding: .75rem 1rem; font-size: 1rem; border: 1px solid #ced4da; border-radius: .5rem; }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }
  </style>
</head>
<body>

  <!-- ========== SIDEBAR ========== -->
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
      <a href="{{ url('/dosenpenguji/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/dosenpenguji/mahasiswa') }}"><i class="fa-solid fa-user-graduate"></i>Mahasiswa</a>
      <a href="{{ url('/dosenpenguji/kelompok') }}" class="active"><i class="fa-solid fa-users"></i>Kelompok</a>
      <a href="{{ url('/dosenpenguji/penilaian') }}"><i class="fa-solid fa-flag-checkered"></i>Penilaian</a>
      <a href="{{ url('/dosenpenguji/rubrik') }}"><i class="fa-solid fa-book"></i>Rubrik</a>
      <a href="{{ url('/dosenpenguji/matakuliah') }}"><i class="fa-solid fa-book"></i>Mata Kuliah</a>
      
      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>

    <div class="logout">
      <form action="{{ route('logout') }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit" class="menu" style="border:none; background:none; cursor:pointer; width:100%; text-align:left;">
              <a style="display:block;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
          </button>
      </form>
    </div>
  </aside>

  <!-- ========== MAIN ========== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome">
        <h1>Daftar Kelompok (Dosen Penguji)</h1>
      </div>
      <div class="userbox">
        <div class="notif">
          <i class="fa-regular fa-bell"></i>
          <span class="badge">3</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
            {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 2)) }}
          </div>
          <strong>{{ auth()->user()->name ?? 'Guest' }}</strong>
        </div>
      </div>
    </header>

    <div class="page">

      <section class="card">
        <div class="card-hd">Filter Kelompok</div>
        <div class="card-bd" style="padding: 1.25rem;">
          <form method="GET" action="{{ route('dosenpenguji.kelompok.index') }}" class="form-inline">
            <div class="form-group">
              <label for="semester">Semester</label>
              <select name="semester" id="semester" class="form-control">
                <option value="">Semua</option>
                @for ($i = 1; $i <= 6; $i++)
                  <option value="{{ $i }}" {{ ($request->semester == $i) ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
              </select>
            </div>
            <div class="form-group">
              <label for="kelas">Kelas</label>
              <select name="kelas" id="kelas" class="form-control">
                <option value="">Semua</option>
                @foreach (['A', 'B', 'C', 'D', 'E'] as $kelas)
                  <option value="{{ $kelas }}" {{ ($request->kelas == $kelas) ? 'selected' : '' }}>{{ $kelas }}</option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="btn">Filter</button>
          </form>
        </div>
      </section>

      <section class="card">
        <div class="card-hd">
            <div><i class="fa-solid fa-users"></i> Daftar Kelompok</div>
        </div>
        <div class="card-bd">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Kelompok</th>
                        <th>Kelas</th>
                        <th>Ketua</th>
                        <th>Dosen Pembimbing</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelompoks as $kelompok)
                    <tr>
                        <td>
                            <strong>{{ $kelompok->nama }}</strong><br>
                            <small style="color:var(--muted)">{{ $kelompok->judul_proyek }}</small><br>
                            <small style="color:var(--muted);"><b>Anggota:</b> {{ $kelompok->anggota }}</small>
                        </td>
                        <td>{{ str_replace('TI-', '', $kelompok->kelas) }}</td>
                        <td>{{ $kelompok->ketua_kelompok }}</td>
                        <td>{{ $kelompok->dosen_pembimbing }}</td>
                        <td>
                            <a href="{{ route('dosenpenguji.kelompok.edit', $kelompok->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 3rem;">Tidak ada data kelompok.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
      </section>
    </div>
  </main>

  <script>
    // Tutup sidebar ketika klik di luar (mobile)
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });
  </script>
</body>
</html>
