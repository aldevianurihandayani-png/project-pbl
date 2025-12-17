{{-- resources/views/jaminanmutu/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard — Jaminan Mutu</title>
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
      overflow: visible; /* ✅ biar dropdown bisa keluar */
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .userbox{ display:flex; align-items:center; gap:14px; overflow: visible; }

    .notif{ position:relative; }
    .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }

    .page{ padding:26px; display:grid; gap:18px }

    /* KPI cards */
    .kpi{ display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:16px }
    .kpi .card{
      background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); padding:16px 18px;
      display:flex; align-items:center; gap:12px; border:1px solid var(--ring);
    }
    .kpi .icon{ width:36px; height:36px; border-radius:10px; background:#eef3ff; display:grid; place-items:center; color:var(--navy-2) }
    .kpi .meta small{ color:var(--muted) }
    .kpi .meta b{ font-size:22px; color:var(--navy-2) }

    /* Section cards */
    .card{
      background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring);
    }
    .card .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; gap:10px; color:var(--navy-2); font-weight:700 }
    .card .card-bd{ padding:16px 18px; color:#233042 }
    .muted{ color:var(--muted) }
    ul.clean{ margin:8px 0 0 18px }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }

    /* =========================
       ✅ DROPDOWN NOTIF (HANYA UNTUK LONCENG)
       ========================= */
    .notif-btn{
      background:transparent;
      border:0;
      padding:0;
      color:inherit;
      cursor:pointer;
      display:inline-block;
      line-height:1;
    }
    .notif-dropdown{
      position:absolute;
      top:34px;
      right:0;
      width:320px;
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:14px;
      box-shadow:0 12px 30px rgba(0,0,0,.18);
      overflow:hidden;
      display:none;
      z-index:9999;
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
      <a href="{{ url('/jaminanmutu/dashboard') }}" class="active"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/jaminanmutu/penilaian') }}"><i class="fa-solid fa-users"></i>Penilaian</a>
      <a href="{{ url('/jaminanmutu/rubrik') }}"><i class="fa-solid fa-flag-checkered"></i>Rubrik</a>

      <div class="nav-title">Akun</div>
      <a href="{{ route('jaminanmutu.profile') }}"><i class="fa-solid fa-id-badge"></i>Profil
      </a>

    <div class="logout" style="margin-top:auto;">
    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
      @csrf
      <button type="submit"
        style="display:flex;align-items:center;gap:12px;width:100%;
               border:0;background:transparent;
               padding:10px 12px;border-radius:12px;margin:4px 6px;
               color:#ffb2b2;cursor:pointer;">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
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
        <h1>Dashboard Jaminan Mutu (PBL)</h1>
      </div>

      <div class="userbox">
        {{-- ✅ LONCENG: tampilan tetap sama, sekarang bisa klik + dropdown --}}
        <div class="notif" id="notifWrapJM">
          <button type="button" class="notif-btn" id="notifBtnJM" aria-label="Notifikasi">
            <i class="fa-solid fa-bell"></i>
            @if($notifBaru > 0)
              <span class="badge">{{ $notifBaru }}</span>
            @else
              {{-- kalau mau tetap tampil angka dummy, hapus bagian else ini.
                   tapi kamu minta data asli, jadi kalau 0 tidak tampil. --}}
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
      <!-- KPI -->
      <section class="kpi">
        <div class="card">
          <div class="icon"><i class="fa-solid fa-clipboard-check"></i></div>
          <div class="meta">
            <small>Penilaian PBL Terekam</small><br>
            <b>{{ $jumlahPenilaian ?? 32 }}</b>
          </div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-solid fa-book-open"></i></div>
          <div class="meta">
            <small>Rubrik Penilaian Aktif</small><br>
            <b>{{ $jumlahRubrik ?? 6 }}</b>
          </div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-solid fa-people-group"></i></div>
          <div class="meta">
            <small>Kelompok PBL Terlibat</small><br>
            <b>{{ $jumlahKelompokPbl ?? 18 }}</b>
          </div>
        </div>
      </section>

      <!-- Rekap Penilaian -->
      <section class="card">
        <div class="card-hd"><i class="fa-solid fa-chart-line"></i> Rekap Penilaian PBL</div>
        <div class="card-bd">
          Sebanyak <strong>{{ $kelompokSudahDinilai ?? 15 }}</strong> dari
          <strong>{{ $jumlahKelompokPbl ?? 18 }}</strong> kelompok PBL sudah dinilai oleh dosen/praktisi.
          <br>
          <span class="muted">
            Rata-rata skor mutu proses PBL: <strong>{{ $rataRataNilaiProses ?? '82' }}</strong> / 100.
          </span><br>
          <span class="muted">
            Terakhir diperbarui: {{ $lastUpdatedPenilaian ?? '9 Desember 2025' }}.
          </span>
        </div>
      </section>

      <!-- Capaian & Kepatuhan Mutu -->
      <section class="card">
        <div class="card-hd"><i class="fa-solid fa-bullseye"></i> Capaian Indikator Mutu PBL</div>
        <div class="card-bd">
          Persentase pemenuhan indikator mutu PBL semester ini:
          <strong>{{ $persenIndikatorTercapai ?? '78%' }}</strong>.
          <br><br>
          <span class="muted">
            - Ketercapaian CPL melalui PBL: {{ $cplTercapai ?? '80%' }}<br>
            - Ketersediaan bukti logbook & laporan: {{ $buktiLengkap ?? '92%' }}<br>
            - Keterlibatan mitra/industri: {{ $keterlibatanMitra ?? '65%' }}
          </span>
        </div>
      </section>

      <!-- Temuan & Rekomendasi -->
      <section class="card">
        <div class="card-hd"><i class="fa-solid fa-magnifying-glass-chart"></i> Temuan Jaminan Mutu & Rekomendasi</div>
        <div class="card-bd">
          <ul class="clean">
            <li>
              <strong>Dokumentasi aktivitas PBL</strong> sudah cukup lengkap, namun beberapa kelompok
              belum mengunggah <em>evidence</em> validasi mitra.
            </li>
            <li>
              <strong>Penilaian soft skill</strong> belum sepenuhnya konsisten antar dosen pembimbing.
              Disarankan untuk menyepakati rubrik dan contoh penilaian bersama.
            </li>
            <li>
              <strong>Umpan balik mitra</strong> menunjukkan kepuasan tinggi terhadap solusi yang diberikan,
              tetapi mengusulkan durasi PBL yang lebih panjang.
            </li>
          </ul>
        </div>
      </section>

      <!-- Notifikasi -->
      <section class="card" style="margin-bottom:28px">
        <div class="card-hd"><i class="fa-regular fa-bell"></i> Notifikasi</div>
        <div class="card-bd">
          <ul class="clean">
            <li>{{ $notif1 ?? '3 kelompok PBL baru saja mengunggah laporan akhir untuk diverifikasi.' }}</li>
            <li>{{ $notif2 ?? 'Koordinator PBL Teknik Informatika memperbarui data rubrik penilaian.' }}</li>
            <li>{{ $notif3 ?? 'Batas waktu review mutu PBL semester ini: 5 hari lagi.' }}</li>
          </ul>
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

    // ✅ Toggle dropdown notifikasi (jaminan mutu)
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
</body>
</html>
