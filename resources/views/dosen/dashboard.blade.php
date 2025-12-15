{{-- resources/views/dosen/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard — Dosen Pembimbing</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10);
      --shadow:0 6px 20px rgba(13,23,84,.08); --radius:16px;
    }
    *{ box-sizing:border-box }
    body{
      margin:0;
      font-family:Arial,Helvetica,sans-serif;
      background:var(--bg);
      display:grid;
      grid-template-columns:260px 1fr;
      min-height:100vh;
    }

    main{ display:flex; flex-direction:column; min-width:0; }

    .page{ padding:26px; display:grid; gap:18px; }

    /* KPI */
    .kpi{ display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:16px; }
    .kpi .card{
      background:var(--card);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      padding:16px 18px;
      display:flex;
      align-items:center;
      gap:12px;
      border:1px solid var(--ring);
    }
    .kpi .icon{
      width:36px; height:36px;
      border-radius:10px;
      background:#eef3ff;
      display:grid; place-items:center;
      color:var(--navy-2);
    }
    .kpi .meta small{ color:var(--muted); }
    .kpi .meta b{ font-size:22px; color:var(--navy-2); }

    /* Card umum */
    .card{
      background:var(--card);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      border:1px solid var(--ring);
    }
    .card-hd{
      padding:14px 18px;
      border-bottom:1px solid #eef1f6;
      display:flex; align-items:center; gap:10px;
      color:var(--navy-2);
      font-weight:700;
    }
    .card-bd{ padding:16px 18px; color:#233042; }
    .muted{ color:var(--muted); }

    /* Responsive: body jadi 1 kolom, sidebar slide */
    @media (max-width: 980px){
      body{ grid-template-columns:1fr; }
    }
  </style>
</head>

<body>
  {{-- SIDEBAR --}}
  @include('dosen.partials.sidebar')

  <main>
    {{-- TOPBAR --}}
    @include('dosen.partials.topbar')

    <div class="page">

      {{-- KPI --}}
      <section class="kpi">
        <div class="card">
          <div class="icon"><i class="fa-solid fa-users"></i></div>
          <div class="meta">
            <small>Jumlah Kelompok</small><br>
            <b>{{ $jumlahKelompok ?? 4 }}</b>
          </div>
        </div>

        <div class="card">
          <div class="icon"><i class="fa-solid fa-book"></i></div>
          <div class="meta">
            <small>Logbook</small><br>
            <b>{{ $jumlahLogbook ?? 5 }}</b>
          </div>
        </div>

        <div class="card">
          <div class="icon"><i class="fa-solid fa-user-graduate"></i></div>
          <div class="meta">
            <small>Mahasiswa</small><br>
            <b>{{ $jumlahMahasiswa ?? 100 }}</b>
          </div>
        </div>
      </section>

      {{-- Status Logbook --}}
      <section class="card">
        <div class="card-hd">
          <i class="fa-solid fa-clipboard-check"></i> Status Logbook
        </div>
        <div class="card-bd">
          Logbook terakhir mahasiswa Anda telah <strong>Disetujui</strong>.<br>
          <span class="muted">Terakhir diperbarui: 2 Oktober 2025</span>
        </div>
      </section>

      {{-- Milestone --}}
      <section class="card">
        <div class="card-hd">
          <i class="fa-solid fa-flag"></i> Milestone
        </div>
        <div class="card-bd">
          Deadline milestone berikutnya: <strong>10 Oktober 2025</strong>
        </div>
      </section>

      {{-- Nilai --}}
      <section class="card">
        <div class="card-hd">
          <i class="fa-solid fa-star"></i> Nilai & Peringkat
        </div>
        <div class="card-bd">
          Nilai TPK: 85, Pemweb Lanjut: 90, Integrasi Sistem: 88, Sistem Operasi: 80.<br>
          Peringkat: <strong>Top 5</strong>
        </div>
      </section>

    </div>
  </main>

  <script>
    // Tutup sidebar saat klik di luar (mobile)
    document.addEventListener('click', (e) => {
      const sb = document.getElementById('sidebar');
      if(!sb || !sb.classList.contains('show')) return;

      const clickSidebar = e.target.closest('#sidebar');
      const clickBtn = e.target.closest('.topbar-btn');

      if(!clickSidebar && !clickBtn) sb.classList.remove('show');
    });
  </script>
</body>
</html>
