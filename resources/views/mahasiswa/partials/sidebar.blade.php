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

    {{-- Dashboard --}}
    <a href="{{ route('mahasiswa.dashboard') }}"
       class="{{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
      <i class="fa-solid fa-house"></i>
      <span>Dashboard</span>
    </a>

    {{-- Kelompok --}}
    <a href="{{ route('mahasiswa.kelompok') }}"
       class="{{ request()->routeIs('mahasiswa.kelompok') ? 'active' : '' }}">
      <i class="fa-solid fa-users"></i>
      <span>Kelompok</span>
    </a>

    {{-- Milestone --}}
    <a href="{{ route('mahasiswa.milestone.index') }}"
       class="{{ request()->routeIs('mahasiswa.milestone.*') ? 'active' : '' }}">
      <i class="fa-solid fa-flag-checkered"></i>
      <span>Milestone</span>
    </a>

    {{-- Logbook --}}
    <a href="{{ route('mahasiswa.logbook') }}"
       class="{{ request()->routeIs('mahasiswa.logbook*') ? 'active' : '' }}">
      <i class="fa-regular fa-clipboard"></i>
      <span>Logbook</span>
    </a>

    {{-- Peringkat --}}
    <a href="{{ route('tpk.mahasiswa.index') }}"
       class="{{ request()->routeIs('tpk.mahasiswa.*') ? 'active' : '' }}">
      <i class="fa-solid fa-ranking-star"></i>
      <span>Peringkat</span>
    </a>

    {{-- Laporan Penilaian --}}
    <a href="{{ route('mahasiswa.laporan-penilaian') }}"
       class="{{ request()->routeIs('mahasiswa.laporan-penilaian') ? 'active' : '' }}">
      <i class="fa-solid fa-file-lines"></i>
      <span>Laporan Penilaian</span>
    </a>

    <div class="nav-title">Akun</div>

    {{-- 🔥 PROFIL MAHASISWA (KHUSUS, BUKAN GLOBAL) --}}
    <a href="{{ route('mahasiswa.profile') }}"
       class="{{ request()->routeIs('mahasiswa.profile') ? 'active' : '' }}">
      <i class="fa-solid fa-id-badge"></i>
      <span>Profil</span>
    </a>
  </div>

  <div class="logout">
    <form action="{{ route('logout') }}" method="POST" style="margin:0">
      @csrf
      <button type="submit"
              class="menu"
              style="display:flex;align-items:center;gap:12px;border:0;background:transparent;color:#ffb2b2">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Logout</span>
      </button>
    </form>
  </div>
</aside>
