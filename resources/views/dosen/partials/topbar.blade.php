@php
  $user = auth()->user();
  $nama = $user->nama ?? $user->name ?? 'User';
  $inisial = strtoupper(substr($nama, 0, 2));

  // 🔥 DATA ASLI NOTIFIKASI
  $notifBaru = \App\Models\Notification::getUnreadCount();
  $notifs    = \App\Models\Notification::getListForTopbar(5);
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

  .welcome h1{ margin:0; font-size:18px; }

  .userbox{
    display:flex;
    align-items:center;
    gap:14px;
  }

  .notif{
    position:relative;
    color:inherit;
    text-decoration:none;
    cursor:pointer;
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

  /* ==========================
     🔥 DROPDOWN NOTIF (BOX SEPERTI MAHASISWA)
     (khusus notif saja, tidak ganggu yang lain)
     ========================== */
  .notif-dd-wrap{ position:relative; }

  .notif-dd{
    position:absolute;
    right:0;
    top:38px;
    width:320px;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:14px;
    box-shadow:0 12px 30px rgba(0,0,0,.18);
    overflow:hidden;
    display:none;
    z-index:9999;
  }
  .notif-dd.show{ display:block; }

  .notif-dd-head{
    background:#0b1d54;
    color:#fff;
    padding:12px 14px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-weight:700;
  }
  .notif-dd-head small{ font-weight:600; opacity:.9; }

  .notif-dd-list{ background:#fff; }

  .notif-dd-item{
    display:block;
    padding:12px 14px;
    text-decoration:none;
    color:#111827;
    border-bottom:1px solid #eef2f7;
  }
  .notif-dd-item:hover{ background:#f3f6ff; }

  .notif-dd-item strong{ display:block; font-size:14px; }
  .notif-dd-item span{ display:block; color:#6b7280; font-size:12px; margin-top:2px; }

  .notif-dd-foot{
    background:#f9fafb;
    padding:10px;
    text-align:center;
  }
  .notif-dd-foot a{
    color:#1e3a8a;
    text-decoration:none;
    font-weight:700;
  }
  .notif-dd-foot a:hover{ text-decoration:underline; }
</style>

<header class="topbar">
  <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
    <i class="fa-solid fa-bars"></i>
  </button>

  <div class="welcome">
    <h1>Dashboard Dosen</h1>
  </div>

  <div class="userbox">
    {{-- 🔔 NOTIFIKASI (DROPDOWN BOX, DATA ASLI) --}}
    <div class="notif-dd-wrap">
      <a href="#" class="notif" id="notifBtn" aria-label="Notifikasi">
        <i class="fa-solid fa-bell"></i>
        @if($notifBaru > 0)
          <span class="badge">{{ $notifBaru }}</span>
        @endif
      </a>

      <div class="notif-dd" id="notifBox">
        <div class="notif-dd-head">
          <div>Notifikasi</div>
          <small>{{ $notifBaru }} baru</small>
        </div>

        <div class="notif-dd-list">
          @forelse($notifs as $n)
            <a class="notif-dd-item" href="{{ route('admins.notifikasi.read', $n->id) }}">
              <strong>{{ $n->judul }}</strong>
              <span>{{ \Illuminate\Support\Str::limit($n->pesan, 50) }}</span>
            </a>
          @empty
            <div class="notif-dd-item" style="border-bottom:0;color:#6b7280;">
              Tidak ada notifikasi
            </div>
          @endforelse
        </div>

        <div class="notif-dd-foot">
          <a href="{{ route('admins.notifikasi.index') }}">Lihat semua</a>
        </div>
      </div>
    </div>

    {{-- USER --}}
    <a href="{{ url('/profile') }}" class="userlink">
      <div class="avatar">{{ $inisial }}</div>
      <strong>{{ $nama }}</strong>
    </a>
  </div>
</header>

<script>
  document.addEventListener('click', function(e){
    const btn = document.getElementById('notifBtn');
    const box = document.getElementById('notifBox');

    if(!btn || !box) return;

    if(btn.contains(e.target)){
      e.preventDefault();
      box.classList.toggle('show');
      return;
    }

    if(!box.contains(e.target)){
      box.classList.remove('show');
    }
  });
</script>
