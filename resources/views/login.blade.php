<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem PBL</title>
    <style>
        body { background-color:#f4f7fb; font-family:Arial,sans-serif; margin:0; padding:0; display:flex; flex-direction:column; min-height:100vh;}
        .container { flex:1; display:flex; justify-content:center; align-items:center; padding:40px 20px;}
        .login-box { background:#fff; padding:40px 30px; border-radius:12px; box-shadow:0 8px 18px rgba(0,0,0,.2); width:380px; text-align:center;}
        .login-box img { max-width:80px; margin-bottom:15px;}
        .login-box h2 { color:#002366; margin-bottom:25px;}
        .error-box { background:#ffe6e6; border:1px solid #ff4d4d; color:#b30000; padding:10px; border-radius:8px; margin-bottom:15px; font-size:14px; text-align:left;}
        .login-box select, .login-box input { width:100%; padding:12px; margin:10px 0; border:1px solid #ccc; border-radius:8px; font-size:15px;}
        .login-box select:focus, .login-box input:focus { border-color:#002366; outline:none;}
        .login-box button { width:100%; padding:12px; background:#002366; border:none; border-radius:8px; color:#fff; font-size:16px; font-weight:bold; cursor:pointer; margin-top:10px;}
        .login-box button:hover { background:#001744;}
        .extra-links { text-align:center; margin-top:15px; font-size:14px;}
        .extra-links a { color:#002366; text-decoration:none; font-weight:bold;}
        .extra-links a:hover { text-decoration:underline;}
        footer { background:#001744; color:#fff; text-align:center; padding:15px;}
    </style>
</head>
<body>
 @include('header')

<div class="container">
  <div class="login-box">
    <img src="{{ asset('assets/PBL.png') }}" alt="Logo PBL">
    <h2>Sistem Informasi Manajemen PBL</h2>

    @if ($errors->any())
      <div class="error-box">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('login.authenticate') }}" method="POST">
      @csrf
      <select name="role" required>
        <option value="">-- Pilih Jenis User --</option>
        <option value="mahasiswa">Mahasiswa</option>
        <option value="dosen_pembimbing">Dosen Pembimbing</option>
        <option value="dosen_penguji">Dosen Penguji</option>
        <option value="koor_pbl">Koordinator PBL</option>
        <option value="jaminan_mutu">Jaminan Mutu</option>
        <option value="admin">Admin</option>
      </select>

      <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
      <input type="password" name="password" placeholder="Password" required>

      <button type="submit">Login</button>
    </form>

    <div class="extra-links">
      {{-- Link daftar HARUS tampil. Jika route('register') tidak ada, fallback ke url('/register') --}}
      @if (Route::has('register'))
        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
      @else
        <p>Belum punya akun? <a href="{{ url('/register') }}">Daftar</a></p>
      @endif
    </div>
  </div>
</div>

<footer>© 2025 Sistem PBL - Jurusan Teknologi Informasi. All rights reserved.</footer>
</body>
</html>