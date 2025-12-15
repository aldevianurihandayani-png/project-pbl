{{-- resources/views/dosen/partials/sidebar.blade.php --}}

<style>
  /* ========== SIDEBAR ========== */
  .sidebar{
    background:#0b1d54;
    color:#e9edf7;
    padding:20px 16px;
    display:flex;
    flex-direction:column;
    min-height:100vh;
  }

  .brand{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:28px;
  }

  .brand-badge{
    width:38px;
    height:38px;
    border-radius:12px;
    background:#1c2f7a;
    display:grid;
    place-items:center;
    font-weight:700;
    letter-spacing:.5px;
  }

  .brand-title strong{
    font-size:18px;
    display:block;
    line-height:1.1;
  }
  .brand-title small{
    font-size:12px;
    opacity:.8;
    display:block;
    margin-top:2px;
  }

  .nav-title{
    font-size:11px;
    margin:16px 8px 6px;
    letter-spacing:1px;
    opacity:.6;
  }

  .menu a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:10px 14px;
    margin:4px 6px;
    border-radius:12px;
    color:#e9edf7;
    text-decoration:none;
    transition:background .18s, transform .18s;
  }

  .menu a i{
    width:20px;
    text-align:center;
  }

  .menu a:hover{
    background:#142b6f;
    transform:translateX(2px);
  }

  .menu a.active{
    background:#1f3f8f;
    font-weight:600;
  }

  .logout{
    margin-top:auto;
    padding:0 6px;
  }

  .logout a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:10px 14px;
    border-radius:12px;
    color:#ffb4b4;
    text-decoration:none;
    transition:background .18s, transform .18s;
  }

  .logout a:hover{
    background:#5c1020;
    transform:translateX(2px);
  }

  /* Mobile sidebar (kalau dipakai bareng topbar-btn) */
  @media (max-width:980px){
    .sidebar{
      position:fixed;
      inset:0 auto 0 0;
      width:240px;
      transform:translateX(-102%);
      transition:transform .2s;
      z-index:10;
      min-height:100vh;
    }
    .sidebar.show{ transform:none; }
  }
</style>

<aside class="sidebar" id="sidebar">
  <div class="brand">
    <div class="brand-badge">SI</div>
    <div class="brand-title">
      <strong>SIMAP</strong>
      <small>Politala</small>
    </div>
  </div>

  <nav class="menu">
    <div class="nav-title">MENU</div>

    <a href="{{ url('/dosen/dashboard') }}" class="{{ request()->is('dosen/dashboard') ? 'active' : '' }}">
      <i class="fa-solid fa-house"></i><span>Dashboard</span>
    </a>

    <a href="{{ url('/dosen/mahasiswa') }}" class="{{ request()->is('dosen/mahasiswa*') ? 'active' : '' }}">
      <i class="fa-solid fa-user-graduate"></i><span>Mahasiswa</span>
    </a>

    <a href="{{ url('/dosen/kelompok') }}" class="{{ request()->is('dosen/kelompok*') ? 'active' : '' }}">
      <i class="fa-solid fa-users"></i><span>Kelompok</span>
    </a>

    <a href="{{ url('/dosen/milestone') }}" class="{{ request()->is('dosen/milestone*') ? 'active' : '' }}">
      <i class="fa-solid fa-flag-checkered"></i><span>Milestone</span>
    </a>

    <a href="{{ url('/dosen/logbook') }}" class="{{ request()->is('dosen/logbook*') ? 'active' : '' }}">
      <i class="fa-solid fa-book"></i><span>Logbook</span>
    </a>

    <div class="nav-title">AKUN</div>

    <a href="{{ url('/dosen/profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}">
      <i class="fa-solid fa-id-badge"></i><span>Profil</span>
    </a>
  </nav>

  <div class="logout">
    <a href="{{ url('/logout') }}">
      <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
    </a>
  </div>
</aside>
