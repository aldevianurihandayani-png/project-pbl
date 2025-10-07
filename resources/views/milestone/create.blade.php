<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Tambah Milestone</title>
  <style>
    body{margin:0;font-family:Arial;background:#eef3fa;display:grid;grid-template-columns:240px 1fr;min-height:100vh}
    .sidebar{background:#0b1d54;color:#fff;padding:18px}
    .menu a{display:block;color:#e9edf7;text-decoration:none;padding:10px;border-radius:10px;margin-bottom:8px}
    .menu a:hover{background:#12306d}
    .content{padding:24px}
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 26px rgba(13,23,84,.18);padding:18px;max-width:760px}
    label{display:block;margin:10px 0 6px}
    input,select{width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:10px}
    .actions{margin-top:16px;display:flex;gap:10px}
    .btn{border:0;border-radius:10px;padding:10px 14px;color:#fff;background:#0b1d54;text-decoration:none}
    .btn.secondary{background:#64748b}
  </style>
</head>
<body>
  <aside class="sidebar">
    <div style="font-weight:700;">SIMAP POLITALA</div>
    <div style="font-size:12px;opacity:.8">Dosen Pembimbing</div>
    <nav class="menu" style="margin-top:12px">
      <a href="/dashboard/dosen">Dashboard</a>
      <a href="{{ route('dosen.milestone') }}">Kembali ke Milestone</a>
    </nav>
  </aside>

  <main class="content">
    <h1 style="color:#0b1d54;margin-top:0">Tambah Milestone</h1>
    <div class="card">
      <form method="POST" action="{{ route('dosen.milestone.store') }}">
        @csrf
        <label>Minggu</label>
        <input type="number" name="minggu" required>

        <label>Kegiatan</label>
        <input type="text" name="kegiatan" required>

        <label>Deadline</label>
        <input type="date" name="deadline" required>

        <label>Status</label>
        <select name="status" required>
          <option>Belum</option>
          <option>Pending</option>
          <option>Selesai</option>
        </select>

        <div class="actions">
          <button class="btn" type="submit">Simpan</button>
          <a class="btn secondary" href="{{ route('dosen.milestone') }}">Batal</a>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
