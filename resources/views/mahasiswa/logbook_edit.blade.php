{{-- resources/views/mahasiswa/logbook_edit.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Logbook — Mahasiswa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{ --navy:#0b1d54; --bg:#f5f7fb; --card:#fff; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08); --radius:16px }
    *{box-sizing:border-box} body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);display:grid;grid-template-columns:260px 1fr;min-height:100vh}
    .sidebar{background:var(--navy);color:#e9edf7;padding:18px 16px}
    .menu a{display:block;color:#e9edf7;text-decoration:none;padding:10px 12px;border-radius:12px;margin:4px 6px}
    .menu a:hover{background:#11245f}.menu a.active{background:#1c3d86}
    main{display:flex;flex-direction:column} header{background:#0a1a54;color:#fff;padding:12px 22px}
    .page{padding:26px}
    .card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--ring)}
    .card-hd{padding:14px 18px;border-bottom:1px solid #eef1f6;font-weight:700}
    .card-bd{padding:16px 18px}
    .input{display:flex;flex-direction:column;gap:6px;margin-bottom:10px}
    .input input,.input textarea{width:100%;padding:10px 12px;border-radius:10px;border:1px solid #cfd7e6;background:#fff}
    .btn{border:0;background:#155eef;color:#fff;padding:10px 14px;border-radius:10px;cursor:pointer;font-weight:600}
  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="menu">
      <a href="{{ url('/mahasiswa/logbook') }}" class="active"><i class="fa-regular fa-clipboard"></i> Kembali ke Logbook</a>
    </div>
  </aside>

  <main>
    <header><strong>Edit Logbook</strong></header>
    <div class="page">
      <section class="card">
        <div class="card-hd">Form Edit</div>
        <div class="card-bd">
          <form action="{{ route('mhs.logbook.update',$logbook) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="input">
              <label>Tanggal</label>
              <input type="date" name="tanggal" value="{{ old('tanggal', $logbook->tanggal->toDateString()) }}">
            </div>
            <div class="input">
              <label>Minggu</label>
              <input type="number" name="minggu" min="1" value="{{ old('minggu', $logbook->minggu) }}">
            </div>
            <div class="input">
              <label>Aktivitas</label>
              <input type="text" name="aktivitas" value="{{ old('aktivitas', $logbook->aktivitas) }}">
            </div>
            <div class="input">
              <label>Rincian</label>
              <textarea name="rincian">{{ old('rincian', $logbook->rincian) }}</textarea>
            </div>
            <div class="input">
              <label>Ganti Lampiran (opsional)</label>
              <input type="file" name="lampiran">
              @if($logbook->lampiran_path)
                <small>File saat ini: <a href="{{ route('mhs.logbook.download',$logbook) }}">download</a></small>
              @endif
            </div>
            <button class="btn" type="submit">Simpan Perubahan</button>
          </form>
        </div>
      </section>
    </div>
  </main>
</body>
</html>
