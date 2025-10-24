<aside class="sidebar" id="sidebar">
  <div class="brand">
    <div class="brand-badge">SI</div>
    <div class="brand-title">
      <strong>SIMAP</strong>
      <small>Politala</small>
    </div>
  </div>

  <div class="menu">
    <div class="nav-title">Menu</div>

    {{-- Dashboard --}}
    <a href="{{ route('admins.dashboard') }}"
       class="{{ request()->routeIs('admins.dashboard') ? 'active' : '' }}">
      <i class="fa-solid fa-house"></i>
      <span>Dashboard</span>
    </a>

    {{-- Mata Kuliah --}}
    <a href="{{ route('admins.matakuliah.index') }}"
       class="{{ request()->routeIs('admins.matakuliah.*') ? 'active' : '' }}">
      <i class="fa-solid fa-user-graduate"></i>
      <span>Mata Kuliah</span>
    </a>

    {{-- Mahasiswa --}}
    <a href="{{ route('admins.mahasiswa.index') }}"
       class="{{ request()->routeIs('admins.mahasiswa.*') ? 'active' : '' }}">
      <i class="fa-solid fa-users"></i>
      <span>Mahasiswa</span>
    </a>

    {{-- Kelompok --}}
    <a href="{{ route('admins.kelompok.index') }}"
       class="{{ request()->routeIs('admins.kelompok.*') ? 'active' : '' }}">
      <i class="fa-solid fa-people-group"></i>
      <span>Kelompok</span>
    </a>

    {{-- Logbook --}}
    <a href="{{ route('admins.logbook.index') }}"
       class="{{ request()->routeIs('admins.logbook.*') ? 'active' : '' }}">
      <i class="fa-solid fa-book"></i>
      <span>Logbook</span>
    </a>

    {{-- Feedback --}}
    <a href="{{ route('admins.feedback.index') }}"
       class="{{ request()->routeIs('admins.feedback.*') ? 'active' : '' }}">
      <i class="fa-solid fa-comments"></i>
      <span>Feedback</span>
    </a>

    {{-- Notifikasi --}}
    <a href="{{ route('admins.notifikasi.index') }}"
       class="{{ request()->routeIs('admins.notifikasi.*') ? 'active' : '' }}">
      <i class="fa-regular fa-bell"></i>
      <span>Notifikasi</span>
    </a>

    <div class="nav-title">Akun</div>

    {{-- Profil --}}
    <a href="{{ route('admins.profile.index') }}"
       class="{{ request()->routeIs('admins.profile.*') ? 'active' : '' }}">
      <i class="fa-solid fa-id-badge"></i>
      <span>Profil</span>
    </a>

  </div>

  <div class="logout">
    <a href="{{ url('/logout') }}" class="menu" style="display:block">
      <i class="fa-solid fa-right-from-bracket"></i> 
      <span>Logout</span>
    </a>
  </div>
</aside>
