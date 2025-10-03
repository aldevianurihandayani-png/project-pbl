<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Sistem PBL</title>
  <style>
    :root{ --navy:#001744; --bg:#f4f7fb; --ring:#e5e7eb; }
    *{ box-sizing:border-box }
    body{
      margin:0; font-family: Arial, Helvetica, sans-serif;
      background:var(--bg); min-height:100vh; display:flex; flex-direction:column;
    }

    /* NAVBAR */
    .navbar{
      background:var(--navy); color:#fff;
      display:flex; justify-content:space-between; align-items:center;
      padding:14px 40px;
    }
    .navbar .brand{ font-weight:800; color:#fff; }
    .navbar nav{ display:flex; gap:20px }
    .navbar nav a{ color:#fff; text-decoration:none; font-weight:700 }
    .navbar nav a:hover{ text-decoration:underline }

    /* WRAP */
    .wrap{
      flex:1; max-width:920px; width:100%;
      margin:34px auto; padding:0 18px;
    }

    h1{ color:#0b1d54; margin:0 0 18px; font-size:34px }
    .meta{ margin-bottom:18px; color:#0f172a; line-height:1.7 }
    .meta b{ color:#0b1d54 }

    /* FORM */
    .card{
      background:#fff; border:1px solid var(--ring); border-radius:12px;
      padding:18px; box-shadow:0 8px 20px rgba(2,6,23,.06);
    }
    .field{ margin-bottom:12px }
    .input, .textarea{
      width:100%; border:1px solid #cbd5e1; background:#fff;
      border-radius:8px; padding:12px; font-size:14px; outline:none;
    }
    .input:focus, .textarea:focus{ border-color:#1e3a8a }
    .textarea{ min-height:120px; resize:vertical }
    .btn{
      background:#0b1d54; color:#fff; border:none; border-radius:8px;
      padding:12px 18px; font-weight:700; cursor:pointer;
    }
    .btn:hover{ background:#07204a }

    /* ALERTS */
    .alert-success{ background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; padding:10px 12px; border-radius:10px; margin-bottom:12px }
    .alert-error{ background:#fff1f2; color:#9f1239; border:1px solid #fecdd3; padding:10px 12px; border-radius:10px; margin-bottom:12px }

    /* FOOTER */
    footer{
      background:var(--navy); color:#fff; text-align:center; padding:14px; margin-top:auto;
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <div class="navbar">
    <div class="brand">SIMAP Politala</div>
    <nav>
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/about') }}">Tentang</a>
      <a href="{{ route('register') }}">Register Eksternal</a>
      <a href="{{ url('/contact') }}">Contact</a>
      <a href="{{ route('login') }}">Login</a>
    </nav>
  </div>

  <!-- CONTENT -->
  <main class="wrap">

    <h1>Hubungi Kami</h1>

    <div class="meta">
      <div><b>Alamat:</b> Jl. A. Yani KM 6, Politeknik Negeri Tanah Laut, Kalimantan Selatan</div>
      <div><b>Email:</b> pbl@politala.ac.id</div>
      <div><b>Telepon:</b> (0512) 123456</div>
    </div>

    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
      <div class="alert-error">
        <ul style="margin:0 0 0 18px; padding:0">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form class="card" method="POST" action="{{ route('contact.send') }}">
      @csrf
      <div class="field">
        <input class="input" type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
      </div>
      <div class="field">
        <input class="input" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
      </div>
      <div class="field">
        <textarea class="textarea" name="message" placeholder="Tulis pesan Anda..." required>{{ old('message') }}</textarea>
      </div>
      <button type="submit" class="btn">Kirim Pesan</button>
    </form>
  </main>

  <footer>
    © 2025 Sistem PBL - Jurusan Teknologi Informasi. All rights reserved.
  </footer>
</body>
</html>
