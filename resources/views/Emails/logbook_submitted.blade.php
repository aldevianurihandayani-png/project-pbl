<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Logbook Baru Dikirim</title>
</head>
<body>
  <h2>Halo, {{ $userName }}!</h2>

  <p>Logbook kamu telah berhasil dikirim.</p>

  <ul>
    <li><strong>Tanggal:</strong> {{ $tanggal }}</li>
    <li><strong>Minggu:</strong> {{ $minggu }}</li>
    <li><strong>Aktivitas:</strong> {{ $aktivitas }}</li>
  </ul>

  <p>Terima kasih sudah memperbarui logbook kamu di SIMAP Politala.</p>

  <hr>
  <small>SIMAP Politala - Sistem Informasi Manajemen PBL</small>
</body>
</html>
