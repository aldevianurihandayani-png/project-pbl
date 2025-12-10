{{-- resources/views/koordinator/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard — Koordinator</title>
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
    .welcome p{ margin:2px 0 0; font-size:12px; opacity:.8 }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative; cursor:pointer; }
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
    .kpi .meta span{ font-size:11px; color:var(--muted) }

    /* Section cards */
    .card{
      background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring);
    }
    .card .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; justify-content:space-between; gap:10px; color:var(--navy-2); font-weight:700 }
    .card .card-hd span.small{ font-size:11px; font-weight:400; color:var(--muted); }
    .card .card-bd{ padding:16px 18px; color:#233042 }
    .muted{ color:var(--muted) }
    ul.clean{ margin:8px 0 0 18px }

    /* layout di dalam page */
    .grid-2{
      display:grid; grid-template-columns:2fr 1.1fr; gap:18px;
    }
    .grid-bottom{
      display:grid; grid-template-columns:1.5fr 1.2fr; gap:18px;
    }

    /* table mini */
    .table-mini{ width:100%; border-collapse:collapse; font-size:12px; }
    .table-mini th, .table-mini td{ padding:6px 4px; text-align:left; }
    .table-mini th{
      font-size:11px; text-transform:uppercase; letter-spacing:.06em;
      color:#9ca3af; border-bottom:1px solid #e3e7f2;
    }
    .table-mini tr + tr td{ border-top:1px solid #f0f2f8; }
    .table-mini tbody tr:hover{ background:#f7f8fe; }

    .tag{
      font-size:11px; padding:3px 7px; border-radius:999px; background:#e4ebff; color:#273b90;
      white-space:nowrap;
    }
    .tag-ok{ background:#dcfce7; color:#166534; }
    .tag-warn{ background:#fef9c3; color:#854d0e; }
    .tag-bad{ background:#fee2e2; color:#b91c1c; }

    /* list sederhana */
    .list{ list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:6px; }
    .list-item{
      font-size:13px; padding:6px 0; display:flex; justify-content:space-between; gap:10px; align-items:flex-start;
      border-bottom:1px dashed #eef1f6;
    }
    .list-text{ max-width:80%; }
    .list-sub{ font-size:11px; color:var(--muted); }
    .badge-pill{
      font-size:11px; padding:3px 7px; border-radius:999px; background:#eef3ff; color:var(--navy-2);
      white-space:nowrap;
    }

    /* progress bar */
    .progress-wrap{ margin-top:6px; }
    .progress{
      height:6px; border-radius:999px; background:#e5e7f3; overflow:hidden;
    }
    .progress > div{
      height:100%; background:linear-gradient(90deg,#2563eb,#4f46e5);
    }

    @media (max-width: 1100px){
      .grid-2, .grid-bottom{ grid-template-columns:1fr; }
    }
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
      <a href="{{ url('/koordinator/dashboard') }}" class="active">
        <i class="fa-solid fa-house"></i>Dashboard
      </a>
      <a href="{{ url('/koordinator/kelompok') }}">
        <i class="fa-solid fa-user-graduate"></i>Kelompok
      </a>
      <a href="{{ url('/koordinator/mahasiswa') }}">
        <i class="fa-solid fa-users"></i>Mahasiswa
      </a>
      {{-- ikon CPMK dibedakan --}}
      <a href="{{ url('/koordinator/cpmk') }}">
        <i class="fa-solid fa-list-check"></i>Cpmk
      </a>
      {{-- ikon Penilaian juga beda --}}
      <a href="{{ url('/koordinator/penilaian') }}">
        <i class="fa-solid fa-clipboard-list"></i>Penilaian
      </a>
      {{-- menu baru: Kelola Peringkat --}}
      <a href="{{ url('/koordinator/peringkat') }}">
        <i class="fa-solid fa-ranking-star"></i>Kelola Peringkat
      </a>

      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}">
        <i class="fa-solid fa-id-badge"></i>Profil
      </a>
    </div>

    <div class="logout">
      <a href="{{ url('/logout') }}" class="menu" style="display:block">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </aside>

  <!-- ========== MAIN ========== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome">
        <h1>Dashboard Koordinator</h1>
      </div>
      <div class="userbox">
        <div class="notif">
          <i class="fa-regular fa-bell"></i>
          <span class="badge">{{ $jumlahNotifBaru ?? 3 }}</span>
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
          <div class="icon"><i class="fa-solid fa-users"></i></div>
          <div class="meta">
            <small>Jumlah Kelompok Aktif</small><br>
            <b>{{ $jumlahKelompok ?? 8 }}</b><br>
            <span>{{ $jumlahMahasiswa ?? 100 }} Mahasiswa</span>
          </div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-solid fa-book"></i></div>
          <div class="meta">
            <small>Logbook Minggu Ini</small><br>
            <b>{{ $logbookMasuk ?? 76 }}</b><br>
            <span>{{ $logbookDisetujui ?? 58 }} disetujui, {{ $logbookTerlambat ?? 4 }} terlambat</span>
          </div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-solid fa-chart-line"></i></div>
          <div class="meta">
            <small>Rata-rata Nilai Kelompok</small><br>
            <b>{{ $rataNilai ?? 87 }}</b><br>
            <span>Kelompok terbaik: {{ $kelompokTerbaik ?? 'Kelompok B - Diskominfo' }}</span>
          </div>
        </div>
      </section>

      <!-- ROW 2 : Logbook & Milestone -->
      <section class="grid-2">
        <!-- Aktivitas Logbook -->
        <section class="card">
          <div class="card-hd">
            <div>
              <i class="fa-solid fa-clipboard-check"></i>&nbsp; Aktivitas Logbook Terbaru
            </div>
            <span class="small">5 update terakhir mahasiswa</span>
          </div>
          <div class="card-bd" style="overflow-x:auto;">
            <table class="table-mini">
              <thead>
                <tr>
                  <th>Mahasiswa</th>
                  <th>Kelompok</th>
                  <th>Minggu</th>
                  <th>Status</th>
                  <th>Update</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentLogbooks ?? [] as $item)
                  <tr>
                    <td>{{ $item->mahasiswa }}</td>
                    <td>{{ $item->kelompok }}</td>
                    <td>M{{ $item->minggu }}</td>
                    <td>
                      @if($item->status === 'Disetujui')
                        <span class="tag tag-ok">Disetujui</span>
                      @elseif($item->status === 'Revisi')
                        <span class="tag tag-warn">Perlu Revisi</span>
                      @else
                        <span class="tag">Menunggu</span>
                      @endif
                    </td>
                    <td>{{ $item->updated_at->diffForHumans() }}</td>
                  </tr>
                @empty
                  {{-- Contoh data jika controller belum dikaitkan --}}
                  <tr>
                    <td>Fikri Alamsyah</td>
                    <td>Kelompok A - PT Telkom</td>
                    <td>M5</td>
                    <td><span class="tag tag-ok">Disetujui</span></td>
                    <td>10 menit lalu</td>
                  </tr>
                  <tr>
                    <td>Nadia Salsabila</td>
                    <td>Kelompok B - Diskominfo</td>
                    <td>M5</td>
                    <td><span class="tag">Menunggu</span></td>
                    <td>30 menit lalu</td>
                  </tr>
                  <tr>
                    <td>Dika Pratama</td>
                    <td>Kelompok C - RSUD Kota</td>
                    <td>M4</td>
                    <td><span class="tag tag-warn">Perlu Revisi</span></td>
                    <td>1 jam lalu</td>
                  </tr>
                  <tr>
                    <td>Intan Puspitasari</td>
                    <td>Kelompok D - Dinas Pendidikan</td>
                    <td>M5</td>
                    <td><span class="tag tag-ok">Disetujui</span></td>
                    <td>2 jam lalu</td>
                  </tr>
                  <tr>
                    <td>Rama Abdillah</td>
                    <td>Kelompok E - Bank Syariah</td>
                    <td>M3</td>
                    <td><span class="tag">Menunggu</span></td>
                    <td>3 jam lalu</td>
                  </tr>
                @endforelse
              </tbody>
            </table>

            <p class="muted" style="margin-top:10px;font-size:12px;">
              Ringkasan: <strong>{{ $logbookDisetujui ?? 58 }}</strong> logbook disetujui,
              <strong>{{ $logbookMenunggu ?? 14 }}</strong> menunggu verifikasi,
              <strong>{{ $logbookTerlambat ?? 4 }}</strong> terlambat.
            </p>
          </div>
        </section>

        <!-- Milestone -->
        <section class="card">
          <div class="card-hd">
            <div>
              <i class="fa-solid fa-flag"></i>&nbsp; Milestone Periode Ini
            </div>
            <span class="small">Pantau deadline penting setiap kelompok</span>
          </div>
          <div class="card-bd">
            @php
              $milestones = $milestones ?? [
                ['judul'=>'Kickoff & Kontrak Belajar','tgl'=>'10 September 2025','status'=>'Selesai'],
                ['judul'=>'Review Tengah Program','tgl'=>'10 Oktober 2025','status'=>'Sedang Berjalan','progress'=>0.78],
                ['judul'=>'Submit Laporan Akhir','tgl'=>'20 November 2025','status'=>'Belum Dimulai','progress'=>0.1],
                ['judul'=>'Presentasi Akhir PPL','tgl'=>'25 November 2025','status'=>'Belum Dimulai','progress'=>0],
              ];
            @endphp

            <ul class="list">
              @foreach($milestones as $m)
                <li class="list-item">
                  <div class="list-text">
                    <strong>{{ $m['judul'] }}</strong>
                    <div class="list-sub">
                      Deadline: {{ $m['tgl'] }}
                      @if(isset($m['status']))
                        &middot;
                        @if($m['status']==='Selesai')
                          <span class="tag tag-ok">{{ $m['status'] }}</span>
                        @elseif($m['status']==='Sedang Berjalan')
                          <span class="tag tag-warn">{{ $m['status'] }}</span>
                        @else
                          <span class="tag">{{ $m['status'] }}</span>
                        @endif
                      @endif
                    </div>
                    @if(isset($m['progress']))
                      <div class="progress-wrap">
                        <div class="progress"><div style="width:{{ $m['progress']*100 }}%"></div></div>
                      </div>
                    @endif
                  </div>
                  <span class="badge-pill">
                    {{ isset($m['progress']) ? (int)($m['progress']*100).'%' : '' }}
                  </span>
                </li>
              @endforeach
            </ul>

            <p class="muted" style="margin-top:10px;font-size:12px;">
              Terdapat <strong>{{ $milestoneMepet ?? 3 }}</strong> kelompok dengan deadline &lt; 7 hari lagi —
              disarankan melakukan follow-up.
            </p>
          </div>
        </section>
      </section>

      <!-- ROW 3 : Peringkat & Notifikasi -->
      <section class="grid-bottom">
        <!-- Peringkat Kelompok -->
        <section class="card">
          <div class="card-hd">
            <div>
              <i class="fa-solid fa-star"></i>&nbsp; Peringkat Kelompok
            </div>
            <span class="small">Top 5 berdasarkan nilai & progres logbook</span>
          </div>
          <div class="card-bd">
            @php
              $peringkat = $peringkat ?? [
                ['nama'=>'Kelompok B - Diskominfo','nilai'=>93,'progress'=>0.94],
                ['nama'=>'Kelompok A - PT Telkom','nilai'=>90,'progress'=>0.91],
                ['nama'=>'Kelompok E - Bank Syariah','nilai'=>88,'progress'=>0.89],
                ['nama'=>'Kelompok D - Dinas Pendidikan','nilai'=>84,'progress'=>0.83],
                ['nama'=>'Kelompok C - RSUD Kota','nilai'=>81,'progress'=>0.78],
              ];
            @endphp

            <ul class="list">
              @foreach($peringkat as $i => $row)
                <li class="list-item">
                  <div class="list-text">
                    <strong>#{{ $i+1 }} {{ $row['nama'] }}</strong>
                    <div class="list-sub">
                      Nilai akhir: <b>{{ $row['nilai'] }}</b> &middot;
                      Progres logbook: <b>{{ (int)($row['progress']*100) }}%</b>
                    </div>
                    <div class="progress-wrap">
                      <div class="progress"><div style="width:{{ $row['progress']*100 }}%"></div></div>
                    </div>
                  </div>
                  <span class="badge-pill">
                    <i class="fa-solid fa-trophy"></i> {{ $row['nilai'] }}
                  </span>
                </li>
              @endforeach
            </ul>

            <p class="muted" style="margin-top:10px;font-size:12px;">
              Posisi rata-rata kelas: <strong>{{ $infoPeringkat ?? 'Top 25% dari seluruh kelas' }}</strong>.
            </p>
          </div>
        </section>

        <!-- Notifikasi -->
        <section class="card" style="margin-bottom:28px">
          <div class="card-hd">
            <div>
              <i class="fa-regular fa-bell"></i>&nbsp; Notifikasi & Tindakan Koordinator
            </div>
            <span class="small">Hal yang perlu segera dicek</span>
          </div>
          <div class="card-bd">
            <ul class="list">
              <li class="list-item">
                <div class="list-text">
                  <strong>2 Mahasiswa belum mengisi logbook minggu ini</strong>
                  <div class="list-sub">Sistem sudah mengirimkan pengingat otomatis ke mahasiswa terkait.</div>
                </div>
                <span class="tag tag-bad">Perlu follow-up</span>
              </li>
              <li class="list-item">
                <div class="list-text">
                  <strong>Dosen pembimbing menambahkan catatan baru</strong>
                  <div class="list-sub">Kelompok C - RSUD Kota &middot; Revisi lingkup pekerjaan.</div>
                </div>
                <span class="badge-pill">Catatan logbook</span>
              </li>
              <li class="list-item">
                <div class="list-text">
                  <strong>Pengajuan perubahan jadwal presentasi</strong>
                  <div class="list-sub">Kelompok E - Bank Syariah mengajukan pindah ke 28 November 2025.</div>
                </div>
                <span class="tag tag-warn">Menunggu respon</span>
              </li>
              <li class="list-item">
                <div class="list-text">
                  <strong>Rekap nilai otomatis berhasil disinkronkan</strong>
                  <div class="list-sub">Total {{ $jumlahMahasiswa ?? 100 }} nilai mahasiswa diperbarui dari sistem.</div>
                </div>
                <span class="tag tag-ok">Sistem</span>
              </li>
            </ul>
          </div>
        </section>
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
