@push('sidebar-styles')
  <link rel="stylesheet" href="{{ asset('css/sidebar-mahasiswa.css') }}">
@endpush

<aside class="sidebar" id="sidebar">
  <div class="brand">
    <div class="brand-badge">SI</div>
    <div class="brand-title">
      <strong>SIMAP</strong>
      <small>Mahasiswa</small>
    </div>
  </div>

  <div class="menu">
    <div class="nav-title">Menu</div>
    <a href="{{ url('/mahasiswa/dashboard') }}" class="{{ request()->is('mahasiswa/dashboard') ? 'active':'' }}">
      <i class="fa-solid fa-house"></i>Dashboard
    </a>
    <a href="{{ url('/mahasiswa/kelompok') }}"><i class="fa-solid fa-users"></i>Kelompok</a>
    <a href="{{ url('/mahasiswa/milestone') }}"><i class="fa-solid fa-flag-checkered"></i>Milestone</a>
    <a href="{{ route('logbooks.index') }}"><i class="fa-regular fa-clipboard"></i>Logbook</a>
    <a href="{{ url('/mahasiswa/laporan-penilaian') }}"><i class="fa-solid fa-file-lines"></i>Laporan Penilaian</a>

    <div class="nav-title">Akun</div>
    <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
  </div>

  <div class="logout">
    <form action="{{ route('logout') }}" method="POST" style="margin:0">
      @csrf
      <button type="submit" class="menu" style="display:block;border:0;background:transparent">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </button>
    </form>
  </div>
</aside>
