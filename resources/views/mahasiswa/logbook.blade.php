{{-- resources/views/mahasiswa/logbook.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Logbook — Mahasiswa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{ --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
           --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
           --radius:16px; }
    *{box-sizing:border-box} body{ margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh; }
    .sidebar{ background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column; }
    .brand{ display:flex; align-items:center; gap:10px; margin-bottom:22px } .brand-badge{ width:36px;height:36px;border-radius:10px;background:#1a2a6b;display:grid;place-items:center;color:#fff;font-weight:700 }
    .menu a{ display:flex; align-items:center; gap:12px; text-decoration:none; color:#e9edf7; padding:10px 12px; border-radius:12px; margin:4px 6px }
    .menu a:hover{ background:#11245f } .menu a.active{ background:#1c3d86 } .nav-title{font-size:12px;opacity:.7;margin:16px 10px 6px}
    .logout{ margin-top:auto } .logout a{ color:#ffb2b2 }
    main{ display:flex; flex-direction:column } header.topbar{ background:#0a1a54;color:#fff;padding:12px 22px;display:flex;align-items:center;justify-content:space-between;box-shadow:var(--shadow) }
    .page{ padding:26px; display:grid; gap:18px }
    .card{ background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring) }
    .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; gap:10px; color:var(--navy-2); font-weight:700 }
    .card-bd{ padding:16px 18px }
    .two-col{ display:grid; grid-template-columns:1fr 340px; gap:16px }
    .input{ display:flex; flex-direction:column; gap:6px; margin-bottom:10px }
    .input input,.input textarea{ width:100%; padding:10px 12px; border-radius:10px; border:1px solid #cfd7e6; background:#fff }
    .input textarea{ min-height:84px; resize:vertical }
    .btn{ border:0; background:#155eef; color:#fff; padding:10px 14px; border-radius:10px; cursor:pointer; font-weight:600 }
    table{ width:100%; border-collapse:collapse } th,td{ padding:10px 12px; border-bottom:1px solid #eef2f7; text-align:left }
    th{ font-size:12px; color:#6c7a8a; text-transform:uppercase; letter-spacing:.06em }
    .pill{ padding:6px 10px; border-radius:999px; font-size:12px; border:1px solid }
    .pill.ok{ color:#166534;border-color:#86efac;background:#ecfdf5 }
    .pill.warn{ color:#92400e;border-color:#fcd34d;background:#fffbeb }
    .pill.danger{ color:#991b1b;border-color:#fecaca;background:#fef2f2 }
    .act{ display:flex; gap:8px; flex-wrap:wrap } .act a,.act button{ padding:6px 10px; border-radius:8px; border:1px solid #cbd5e1; background:#fff; cursor:pointer }
    .alert{ padding:10px 12px; border-radius:10px; background:#ecfdf5; border:1px solid #86efac; color:#166534 }
    .errors{ padding:10px 12px; border-radius:10px; background:#fef2f2; border:1px solid #fecaca; color:#991b1b }
    @media(max-width:980px){ body{grid-template-columns:1fr} .two-col{grid-template-columns:1fr} .sidebar{display:none} header.topbar{border-radius:0} }
  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand"><div class="brand-badge">SI</div><div><strong>SIMAP</strong><div style="opacity:.85;font-size:12px">Mahasiswa</div></div></div>
    <div class="menu">
      <div class="nav-title">Menu</div>
      <a href="{{ url('/mahasiswa/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/mahasiswa/kelompok') }}"><i class="fa-solid fa-users"></i>Kelompok</a>
      <a href="{{ url('/mahasiswa/milestone') }}"><i class="fa-solid fa-flag-checkered"></i>Milestone</a>
      <a href="{{ url('/mahasiswa/logbook') }}" class="active"><i class="fa-regular fa-clipboard"></i>Logbook</a>
      <a href="{{ url('/mahasiswa/laporan-penilaian') }}"><i class="fa-solid fa-file-lines"></i>Laporan Penilaian</a>

      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>
    <div class="logout"><a href="{{ url('/logout') }}" class="menu" style="display:block"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></div>
  </aside>

  <main>
    <header class="topbar">
      <div><strong>Logbook</strong><div style="font-size:12px;opacity:.85">Sistem Informasi Manajemen PBL</div></div>
      <div style="display:flex;align-items:center;gap:10px">
        <i class="fa-regular fa-bell"></i>
        <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
          {{ strtoupper(substr((auth()->user()?->name ?? 'MS'),0,2)) }}
        </div>
      </div>
    </header>

    <div class="page">
      @if (session('ok')) <div class="alert">{{ session('ok') }}</div> @endif
      @if ($errors->any())
        <div class="errors">
          <b>Gagal menyimpan:</b>
          <ul style="margin:6px 0 0 18px">
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
          </ul>
        </div>
      @endif

      <div class="two-col">
        <!-- FORM -->
        <section class="card">
          <div class="card-hd"><i class="fa-regular fa-clipboard"></i> Input Logbook</div>
          <div class="card-bd">
            <form action="{{ route('mhs.logbook.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="input">
                <label>Tanggal / Minggu ke</label>
                <div style="display:grid;grid-template-columns:1fr 140px;gap:10px">
                  <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}">
                  <input type="number" name="minggu" min="1" placeholder="Minggu ke-" value="{{ old('minggu') }}">
                </div>
              </div>
              <div class="input">
                <label>Aktivitas</label>
                <input type="text" name="aktivitas" placeholder="Judul Aktivitas..." value="{{ old('aktivitas') }}">
              </div>
              <div class="input">
                <label>Rincian kegiatan</label>
                <textarea name="rincian" placeholder="Deskripsi singkat milestone...">{{ old('rincian') }}</textarea>
              </div>
              <div class="input">
                <label>Upload Dokumentasi (opsional)</label>
                <input type="file" name="lampiran">
              </div>
              <button class="btn" type="submit">Simpan Logbook</button>
            </form>
          </div>
        </section>

        <!-- KOMENTAR DOSEN -->
        <aside class="card">
          <div class="card-hd"><i class="fa-regular fa-comments"></i> Komentar Dosen</div>
          <div class="card-bd">
            <p style="margin:0; color:#6c7a8a">
              {{ $komentarTerbaru ?? 'Belum ada komentar untuk logbook ini' }}
            </p>
          </div>
        </aside>
      </div>

      <!-- LIST -->
      <section class="card" style="margin-top:6px">
        <div class="card-hd"><i class="fa-solid fa-list-check"></i> Riwayat Logbook</div>
        <div class="card-bd">
          <table>
            <thead>
              <tr><th>Tanggal</th><th>Minggu</th><th>Aktivitas</th><th>Status</th><th>Lampiran</th><th>Aksi</th></tr>
            </thead>
            <tbody>
              @forelse ($items as $row)
                @php
                  $cls = ['menunggu'=>'warn','disetujui'=>'ok','ditolak'=>'danger'][$row->status] ?? 'warn';
                @endphp
                <tr>
                  <td>{{ $row->tanggal->format('Y-m-d') }}</td>
                  <td>{{ $row->minggu ?? '-' }}</td>
                  <td>{{ $row->aktivitas }}</td>
                  <td><span class="pill {{ $cls }}">{{ ucfirst($row->status) }}</span></td>
                  <td>
                    @if($row->lampiran_path)
                      <a href="{{ route('mhs.logbook.download',$row) }}">download</a>
                    @else
                      <span class="muted">-</span>
                    @endif
                  </td>
                  <td class="act">
                    <a href="{{ route('mhs.logbook.edit',$row) }}">Edit</a>
                    <form action="{{ route('mhs.logbook.destroy',$row) }}" method="POST" onsubmit="return confirm('Hapus logbook ini?')">
                      @csrf @method('DELETE')
                      <button type="submit">Hapus</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="muted">Belum ada logbook.</td></tr>
              @endforelse
            </tbody>
          </table>

          <div style="margin-top:10px">{{ $items->links() }}</div>
        </div>
      </section>
    </div>
  </main>
</body>
</html>
