
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar Kelompok — Dosen Pembimbing</title>
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

    .form-inline { display: flex; align-items: flex-end; gap: 1rem; flex-wrap:wrap; }
    .form-inline .form-group { flex: 1 1 200px; }
    .form-control { display: block; width: 100%; padding: .75rem 1rem; font-size: 1rem; border: 1px solid #ced4da; border-radius: .5rem; }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }

    /* ====== KARTU KELAS (ganti tabel CRUD) ====== */
    .kelas-grid{
      padding:18px;
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
      gap:18px;
    }
    .kelas-card{
      background:#ffffff;
      border-radius:18px;
      border:1px solid #e0e5f4;
      box-shadow:0 6px 16px rgba(9,23,84,.06);
      padding:16px 18px 18px;
      display:flex;
      flex-direction:column;
      gap:8px;
      cursor:pointer;                       /* bisa diklik */
      transition:transform .15s, box-shadow .15s;
    }
    .kelas-card:hover{
      transform:translateY(-2px);
      box-shadow:0 10px 20px rgba(9,23,84,.10);
    }
    .kelas-tag{
      font-size:12px;
      text-transform:uppercase;
      letter-spacing:.5px;
      color:var(--muted);
    }
    .kelas-title{
      font-weight:700;
      font-size:15px;
      color:#0b1d54;
    }
    .kelas-sub{
      font-size:13px;
      color:var(--muted);
      margin-bottom:4px;
    }
    .kelas-meta{
      font-size:13px;
      color:#22314a;
      display:flex;
      align-items:flex-start;
      gap:6px;
    }
    .kelas-meta i{
      margin-top:2px;
    }
    .kelas-footer{
      margin-top:10px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      font-size:12px;
      color:var(--muted);
    }
    .btn-outline{
      background:#eef2ff;
      color:#1b2f80;
      border-radius:999px;
      padding:6px 12px;
      font-size:12px;
      font-weight:600;
      text-decoration:none;
    }
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
      <a href="{{ url('/dosen/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/dosen/mahasiswa') }}"><i class="fa-solid fa-user-graduate"></i>Mahasiswa</a>
      <a href="{{ url('/dosen/kelompok') }}" class="active"><i class="fa-solid fa-users"></i>Kelompok</a>
      <a href="{{ url('/dosen/milestone') }}"><i class="fa-solid fa-flag-checkered"></i>Milestone</a>
      <a href="{{ url('/dosen/logbook') }}"><i class="fa-solid fa-book"></i>Logbook</a>

      
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
        <h1>Daftar Kelompok</h1>
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

      {{-- ================= FILTER ================= --}}
      <section class="card">
        <div class="card-hd">Filter Kelompok</div>
        <div class="card-bd" style="padding: 1.25rem;">

          @php
              // pilihan kelas berdasar semester
              $kelasOptions = ['A','B','C','D','E'];           // default sampai E (misal 3E)
              if (in_array($request->semester, [1,2])) {
                  $kelasOptions = ['A','B','C','D'];          // sem 1 & 2 -> A–D
              } elseif (in_array($request->semester, [3,4,6,7])) {
                  $kelasOptions = ['A','B','C','D','E'];      // sem 3,4,6,7 -> A–E (3E termasuk)
              }
          @endphp

          <form method="GET" action="{{ route('dosen.kelompok.index') }}" class="form-inline">
            <div class="form-group">
              <label for="search">Pencarian</label>
              <input
                type="text"
                name="search"
                id="search"
                class="form-control"
                placeholder="Cari nama kelompok / judul proyek..."
                value="{{ $request->search ?? '' }}">
            </div>

            <div class="form-group">
              <label for="semester">Semester</label>
              <select name="semester" id="semester" class="form-control">
                <option value="">Semua</option>
                @for ($i = 1; $i <= 7; $i++)
                  <option value="{{ $i }}" {{ ($request->semester == $i) ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
              </select>
            </div>

            <div class="form-group">
              <label for="kelas">Kelas</label>
              <select name="kelas" id="kelas" class="form-control">
                <option value="">Semua</option>
                @foreach ($kelasOptions as $kelas)
                  <option value="{{ $kelas }}" {{ ($request->kelas == $kelas) ? 'selected' : '' }}>
                    {{ $kelas }}
                  </option>
                @endforeach
              </select>
            </div>

            <button type="submit" class="btn">Filter</button>
          </form>
        </div>
      </section>

      {{-- =============== KELAS (GANTI TABEL CRUD) =============== --}}
      <section class="card">
        <div class="card-hd">
          <div><i class="fa-solid fa-users"></i> Kelas Bimbingan Anda</div>
          <a href="{{ route('dosen.kelompok.create') }}" class="btn">Tambah Kelompok</a>
        </div>

        <div class="card-bd">
          @php
            // grupkan berdasarkan kelas (misal: TI-3E, TI-3D, dsb)
            $kelasGrouped = $kelompoks->groupBy('kelas');
          @endphp

          <div class="kelas-grid">
            @forelse ($kelasGrouped as $namaKelas => $items)
              @php
                  // label kelas rapi, contoh: "TI 3E"
                  $labelKelas = str_replace(['TI-','TI '],'TI ', $namaKelas);
                  $jumlahKelompok = $items->count();
                  $contoh = $items->first();

                  // URL detail: ke halaman CRUD kelas tertentu (TI-3E, dst)
                  $detailUrl = route('dosen.kelompok.kelas', $namaKelas);
              @endphp

              <div class="kelas-card" data-href="{{ $detailUrl }}">
                <div class="kelas-tag">Teknologi Informasi</div>
                <div class="kelas-title">{{ $labelKelas }}</div>
                <div class="kelas-sub">
                  {{ $jumlahKelompok }} kelompok bimbingan
                </div>

                @if ($contoh)
                  <div class="kelas-meta">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Dosen pembimbing: {{ $contoh->dosen_pembimbing }}</span>
                  </div>
                  <div class="kelas-meta">
                    <i class="fa-solid fa-user-group"></i>
                    <span>Contoh ketua: {{ $contoh->ketua_kelompok }}</span>
                  </div>
                @endif

                <div class="kelas-footer">
                  <span>{{ $jumlahKelompok }} kelompok terdata</span>
                  <a href="{{ $detailUrl }}" class="btn-outline">Lihat detail</a>
                </div>
              </div>
            @empty
              <div style="padding:2.5rem; text-align:center; color:var(--muted); width:100%;">
                Belum ada data kelompok untuk ditampilkan.
              </div>
            @endforelse
          </div>
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

    // Bikin seluruh kartu kelas bisa diklik ke halaman detail
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.kelas-card[data-href]').forEach(function(card) {
        card.addEventListener('click', function(e) {
          // kalau yang diklik adalah link "Lihat detail", biarkan pakai href-nya sendiri
          if (e.target.closest('a')) return;
          const url = this.dataset.href;
          if (url && url !== '#') {
            window.location = url;
          }
        });
      });
    });
  </script>
</body>
</html>
