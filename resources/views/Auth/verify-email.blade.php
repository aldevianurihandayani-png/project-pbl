{{-- resources/views/auth/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Verifikasi Email — SIMAP Politala</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body{font-family:Arial,sans-serif;background:#f6f9fc;min-height:100vh;display:flex;align-items:center;justify-content:center;margin:0}
    .card{background:#fff;padding:28px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.08);max-width:420px;width:100%;text-align:center}
    h2{margin:0 0 10px;color:#001744}
    p{color:#333}
    button{background:#001744;color:#fff;border:none;padding:10px 16px;border-radius:8px;cursor:pointer}
    button:hover{opacity:.9}
    .muted{margin-top:12px;font-size:14px;color:#666}
  </style>
</head>
<body>
  <div class="card">
    <h2>Verifikasi Email Anda</h2>

    @if (session('status') === 'verification-link-sent')
      <p style="color:green">Link verifikasi baru sudah dikirim ke email Anda.</p>
    @endif

    <p>Silakan cek inbox/spam lalu klik tautan untuk memverifikasi akun.</p>
    <p class="muted">Belum menerima email?</p>

    <form method="POST" action="{{ route('verification.send') }}">
      @csrf
      <button type="submit">Kirim Ulang Email Verifikasi</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:10px">
      @csrf
      <button type="submit" style="background:#ddd;color:#000">Kembali</button>
    </form>
  </div>
</body>
</html>
