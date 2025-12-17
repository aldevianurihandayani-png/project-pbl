<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Penilaian — Jaminan Mutu</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f5f7fb;margin:0;padding:20px}
    .card{background:#fff;border:1px solid rgba(13,23,84,.10);border-radius:16px;box-shadow:0 6px 20px rgba(13,23,84,.08);padding:16px}
    .muted{color:#6c7a8a;font-size:12px}
    .btn{display:inline-block;margin-bottom:12px;text-decoration:none;background:#eef3fa;color:#0e257a;font-weight:700;padding:8px 12px;border-radius:8px}
    pre{background:#0b1d5410;padding:12px;border-radius:12px;overflow:auto}
  </style>
</head>
<body>

  <a class="btn" href="{{ route('jaminanmutu.penilaian.index') }}">← Kembali</a>

  <div class="card">
    <h2 style="margin:0 0 10px 0;">Detail Penilaian (Read-only)</h2>

    <p><b>Mahasiswa:</b> {{ optional($penilaian->mahasiswa)->nama ?? '-' }} ({{ optional($penilaian->mahasiswa)->nim ?? '-' }})</p>
    <p><b>Mata Kuliah:</b> {{ optional($penilaian->matakuliah)->nama_mk ?? '-' }} ({{ $penilaian->matakuliah_kode ?? '-' }})</p>
    <p><b>Kelas:</b> {{ optional($penilaian->kelas)->nama_kelas ?? (optional($penilaian->kelas)->kode_kelas ?? '-') }}</p>
    <p><b>Dosen:</b> {{ optional($penilaian->dosen)->name ?? '-' }}</p>
    <p><b>Nilai Akhir:</b> {{ number_format((float)($penilaian->nilai_akhir ?? 0), 2) }}</p>

    <p class="muted" style="margin-top:16px;"><b>Komponen (JSON):</b></p>
    <pre>{{ json_encode($penilaian->komponen, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
  </div>

</body>
</html>
