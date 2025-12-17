<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tambah Kelompok — Dosen Pembimbing</title>
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
    .card .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; gap:10px; color:var(--navy-2); font-weight:700 }
    .card .card-bd{ padding:16px 18px; color:#233042 }

    /* Form Styles */
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; margin-bottom: .5rem; color: var(--navy); font-weight: 600; }
    .form-control {
        display: block;
        width: 100%;
        padding: .75rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .5rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .form-control:focus {
        color: #495057;
        background-color: #fff;
        border-color: var(--navy-2);
        outline: 0;
        box-shadow: 0 0 0 .2rem var(--ring);
    }
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
        padding: .75rem 1.25rem;
        font-size: 1rem;
        border-radius: .5rem;
        transition: background-color .15s ease-in-out;
    }
    .btn:hover { background-color: var(--navy); }

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
        <h1>Tambah Kelompok Baru</h1>
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
        <div class="card-hd"><i class="fa-solid fa-plus"></i> Form Tambah Kelompok</div>
        <div class="card-bd">
          <form action="{{ route('dosen.kelompok.store') }}" method="POST">
              @csrf

              <div class="form-group">
                  <label for="nama">Nama Kelompok</label>
                  <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama') }}" required>
              </div>

              {{-- KELAS: dropdown dari tabel kelas --}}
              <div class="form-group">
                  <label for="kelas">Kelas</label>
                  <select name="kelas" id="kelas" class="form-control" required>
                      <option value="">-- Pilih Kelas --</option>
                      @foreach($daftarKelas as $k)
                          @php
                              $value    = $k->nama_kelas;
                              $selected = old('kelas', $kelasTerpilih ?? '') == $value ? 'selected' : '';
                          @endphp
                          <option value="{{ $value }}" {{ $selected }}>
                              {{ $k->nama_kelas }}
                          </option>
                      @endforeach
                  </select>
              </div>

              {{-- JUDUL PROYEK --}}
              <div class="form-group">
                  <label for="judul">Judul Proyek</label>
                  <select name="judul" id="judul" class="form-control" required>
                      <option value="">-- Pilih Judul Proyek --</option>
                      @foreach($daftarJudulProyek as $p)
                          <option value="{{ $p->judul }}" @selected(old('judul') == $p->judul)>
                              {{ $p->judul }}
                          </option>
                      @endforeach
                  </select>
              </div>

              {{-- NAMA KLIEN: dropdown dari tabel dosen --}}
              <div class="form-group">
                  <label for="nama_klien">Nama Klien</label>
                  <select name="nama_klien" id="nama_klien" class="form-control" required>
                      <option value="">-- Pilih Nama Klien --</option>
                      @foreach($daftarKlien as $klien)
                          <option value="{{ $klien->nama_dosen }}" @selected(old('nama_klien') == $klien->nama_dosen)>
                              {{ $klien->nama_dosen }}
                          </option>
                      @endforeach
                  </select>
              </div>

              {{-- KETUA KELOMPOK --}}
              <div class="form-group">
                  <label for="ketua_kelompok">Ketua Kelompok</label>
                  <select name="ketua_kelompok" id="ketua_kelompok" class="form-control" required>
                      <option value="">-- Pilih Ketua Kelompok --</option>
                      @foreach($mahasiswas as $mhs)
                          <option value="{{ $mhs->nim }}" @selected(old('ketua_kelompok') == $mhs->nim)>
                              {{ $mhs->nim }} - {{ $mhs->nama }}
                          </option>
                      @endforeach
                  </select>
              </div>

{{-- ANGGOTA KELOMPOK (CHECKBOX SEPERTI DOSEN PEMBIMBING) --}}
<div class="form-group">
    <label>Anggota Kelompok</label>

    <div style="
        border:1px solid #ced4da;
        border-radius:8px;
        padding:12px;
        background:#fff;
    ">
        @forelse($mahasiswas as $mhs)
            <div style="margin-bottom:8px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input
                        type="checkbox"
                        name="anggota[]"
                        value="{{ $mhs->nim }}"
                        {{ in_array($mhs->nim, old('anggota', [])) ? 'checked' : '' }}
                    >
                    {{ $mhs->nim }} - {{ $mhs->nama }}
                </label>
            </div>
        @empty
            <p style="margin:0;">Tidak ada mahasiswa tersedia.</p>
        @endforelse
    </div>

    @error('anggota')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>


           {{-- DOSEN PEMBIMBING: multi pilih (checkbox) --}}
<div class="form-group">
  <label>Dosen Pembimbing (boleh lebih dari satu)</label>

  <div style="border:1px solid #ced4da;border-radius:.5rem;padding:.75rem;max-height:180px;overflow:auto;">
    @foreach($dosenPembimbings as $dosen)
      <label style="display:flex;align-items:center;gap:10px;padding:6px 4px;cursor:pointer;">
        <input type="checkbox"
               name="id_dosen[]"
               value="{{ $dosen->id_dosen }}"
               {{ in_array($dosen->id_dosen, old('id_dosen', [])) ? 'checked' : '' }}>
        <span>{{ $dosen->nama_dosen }}</span>
      </label>
    @endforeach
  </div>

  @error('id_dosen')
    <div class="text-danger small">{{ $message }}</div>
  @enderror
</div>

              <button type="submit" class="btn">Simpan Kelompok</button>
          </form>
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

    // ✅ Filter mahasiswa berdasarkan kelas (reload dengan ?kelas=...)
    document.addEventListener('DOMContentLoaded', function () {
      const kelasSelect = document.getElementById('kelas');
      if (!kelasSelect) return;

      kelasSelect.addEventListener('change', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('kelas', this.value);
        window.location.href = url.toString();
      });
    });
  </script>
</body>
</html>
