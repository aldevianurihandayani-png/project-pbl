{{-- resources/views/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - SIMAP Politala</title>
  <style>
    :root { --navy:#001744; --navy-2:#001133; --bg:#f4f7fb; }
    *{box-sizing:border-box} 
    body{background:var(--bg);font-family:Arial, sans-serif;margin:0;min-height:100vh;display:flex;flex-direction:column}
    .navbar{background:var(--navy);color:#fff;padding:14px 28px;display:flex;justify-content:space-between;align-items:center}
    .brand{font-weight:700}
    .navbar nav{display:flex;gap:16px}
    .navbar a{color:#fff;text-decoration:none;font-weight:600}
    .navbar a:hover{text-decoration:underline}
    .container{flex:1;display:flex;align-items:center;justify-content:center;padding:32px 16px}
    .card{width:100%;max-width:460px;background:#fff;padding:28px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.08)}
    .logo{display:block;margin:0 auto 12px;width:56px;height:auto}
    h2{color:var(--navy);margin:4px 0 18px;font-size:18px;text-align:center}
    .row{margin-bottom:12px}
    label{display:block;margin-bottom:6px;color:#05225d;font-size:14px;font-weight:600}
    input,select{width:100%;padding:12px;border:1px solid #cfd6e4;border-radius:8px;font-size:14px}
    button{width:100%;padding:12px;border:none;border-radius:8px;background:var(--navy);color:#fff;font-weight:700;cursor:pointer}
    button:hover{background:var(--navy-2)}
    .muted{font-size:14px;text-align:center;margin-top:12px}
    .muted a{color:var(--navy);text-decoration:none;font-weight:700}
    .alert{padding:10px 12px;border-radius:8px;margin-bottom:12px}
    .alert-success{background:#e7f7ee;color:#087443}
    .alert-error{background:#fdecea;color:#b81d24}
    footer{background:var(--navy);color:#fff;text-align:center;padding:14px}
  </style>
</head>
<body>

  <div class="navbar">
    <div class="brand">SIMAP Politala</div>
    <nav>
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/about') }}">Tentang</a>
      <a href="{{ route('register') }}">Register</a>
      <a href="{{ url('/contact') }}">Contact</a>
      <a href="{{ route('login') }}">Login</a>
    </nav>
  </div>

  <div class="container">
    <div class="card">
      <img src="{{ asset('assets/PBL.png') }}" alt="Logo PBL" class="logo">
      <h2>Sistem Informasi Manajemen PBL</h2>

      {{-- Flash message (verifikasi / sukses) --}}
      @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
      @endif
      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      {{-- Validation errors --}}
      @if ($errors->any())
        <div class="alert alert-error">
          <ul style="margin:0;padding-left:18px">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Form registrasi --}}
      <form action="{{ url('/register') }}" method="POST" novalidate>
        @csrf

        <div class="row">
          <label for="name">Nama Lengkap</label>
          <input id="name" type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
          @error('name') <div class="alert alert-error" style="margin-top:6px">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label for="nim">NIM / NIP (opsional)</label>
          <input id="nim" type="text" name="nim" placeholder="NIM / NIP" value="{{ old('nim') }}">
          @error('nim') <div class="alert alert-error" style="margin-top:6px">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label for="prodi">Program Studi (opsional)</label>
          <input id="prodi" type="text" name="prodi" placeholder="Program Studi" value="{{ old('prodi') }}">
          @error('prodi') <div class="alert alert-error" style="margin-top:6px">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
          @error('email') <div class="alert alert-error" style="margin-top:6px">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label for="role">Role</label>
          <select id="role" name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="mahasiswa"        {{ old('role')=='mahasiswa'?'selected':'' }}>Mahasiswa</option>
            <option value="dosen_pembimbing" {{ old('role')=='dosen_pembimbing'?'selected':'' }}>Dosen Pembimbing</option>
            <option value="dosen_penguji"    {{ old('role')=='dosen_penguji'?'selected':'' }}>Dosen Penguji</option>
            <option value="koordinator"      {{ old('role')=='koordinator'?'selected':'' }}>Koordinator PBL</option>
            <option value="jaminan_mutu"     {{ old('role')=='jaminan_mutu'?'selected':'' }}>Jaminan Mutu</option>
            <option value="admins"            {{ old('role')=='admins'?'selected':'' }}>Admin</option>

          </select>
          @error('role') <div class="alert alert-error" style="margin-top:6px">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label for="password">Password</label>
          <input id="password" type="password" name="password" placeholder="Password" required>
          @error('password') <div class="alert alert-error" style="margin-top:6px">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label for="password_confirmation">Konfirmasi Password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
        </div>

        <button type="submit">Register</button>
      </form>

      <p class="muted">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
    </div>
  </div>

  <footer>
    © 2025 SIMAP Politala — Jurusan Teknologi Informasi.
  </footer>
</body>
</html>
