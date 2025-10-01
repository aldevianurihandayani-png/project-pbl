<aside class="sidebar">
  <div class="sidebar-title">SIMAP<br>Politala</div>
  <nav class="menu">
    <a href="{{ route('admin.dashboard') }}" class="menu-item">Beranda</a>
    <a href="#" class="menu-item">Kelompok</a>
    <a href="#" class="menu-item">Mahasiswa</a>
    <a href="#" class="menu-item sub">Dosen Pembimbing</a>
    <a href="#" class="menu-item">Logbook</a>
    <a href="#" class="menu-item">Penilaian</a>
    <a href="#" class="menu-item">Users</a>
    <a href="#" class="menu-item">Pengaturan</a>
    <a href="{{ route('logout') }}" class="menu-item danger"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
  </nav>
</aside>
