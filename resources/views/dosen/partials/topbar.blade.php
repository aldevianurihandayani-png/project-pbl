@php
  $user = auth()->user();
  $nama = $user->nama ?? $user->name ?? 'User';
  $inisial = strtoupper(substr($nama, 0, 2));
@endphp

<style>
  /* ========== TOPBAR ========== */
  header.topbar{
    background:#0a1a54;
    color:#fff;
    padding:12px 22px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    position:sticky;
    top:0;
    z-index:3;
    box-shadow:var(--shadow);
  }

  .welcome h1{
    margin:0;
    font-size:18px;
    letter-spacing:.2px;
  }

  .userbox{
    display:flex;
    align-items:center;
    gap:14px;
  }

  .notif{
    position:relative;
    color:inherit;
    text-decoration:none;
  }

  .notif i{ font-size:18px; }

  .notif .badge{
    position:absolute;
    top:-6px;
    right:-6px;
    background:#e53935;
    color:#fff;
    border-radius:10px;
    font-size:10px;
    padding:2px 5px;
  }

  .userlink{
    display:flex;
    align-items:center;
    gap:10px;
    text-decoration:none;
    color:inherit;
  }

  .avatar{
    width:32px;
    height:32px;
    border-radius:50%;
    background:#e3e9ff;
    display:grid;
    place-items:center;
    color:#31408a;
    font-weight:700;
  }

  /* tombol sidebar mobile */
  .topbar-btn{
    display:none;
    border:0;
    background:transparent;
    color:#fff;
    font-size:20px;
    cursor:pointer;
  }

  @media (max-width: 980px){
    .topbar-btn{ display:inline-flex; }
  }
</style>

<header class="topbar">
  <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
    <i class="fa-solid fa-bars"></i>
  </button>

  <div class="welcome">
    <h1>Dashboard Dosen</h1>
  </div>

  <div class="userbox">
    <a href="#" class="notif" aria-label="Notifikasi">
      <i class="fa-regular fa-bell"></i>
      <span class="badge">3</span>
    </a>

    <a href="{{ url('/profile') }}" class="userlink">
      <div class="avatar">{{ $inisial }}</div>
      <strong>{{ $nama }}</strong>
    </a>
  </div>
</header>
