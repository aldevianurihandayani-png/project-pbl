@php
  use Illuminate\Support\Facades\Storage;

  $user = auth()->user();
  $userName = $user->nama ?? $user->name ?? 'Dosen';
  $parts = preg_split('/\s+/', trim($userName));
  $initial = strtoupper(
      mb_substr($parts[0] ?? 'D', 0, 1) .
      mb_substr($parts[1] ?? '', 0, 1)
  );

  // role label (samakan gaya admin)
  $userRole = $user->role ?? 'dosen_pembimbing';
  $userRoleLabel = ucwords(str_replace('_',' ',$userRole));

  // foto (kalau ada kolom yang sama seperti admin)
  $userPhoto = ($user && $user->profile_photo_path)
      ? Storage::url($user->profile_photo_path)
      : null;

  // URL profil dosen (silakan sesuaikan route kamu)
  $profileUrl = url('/dosen/profile');

  // 🔥 DATA NOTIFIKASI (pakai yang kamu punya)
  $notifCount = \App\Models\Notification::getUnreadCount();
  $notifList  = \App\Models\Notification::getListForTopbar(5);
@endphp

<style>
  header.topbar{
    background:#0a1a54;
    color:#fff;
    padding:12px 22px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    position:sticky;
    top:0;
    z-index:5000;
    box-shadow:var(--shadow);
  }
  .topbar-left{display:flex;align-items:center;gap:10px;}
  .topbar-btn{display:none;border:0;background:transparent;color:#fff;font-size:20px;cursor:pointer;}
  .welcome h1{margin:0;font-size:18px;letter-spacing:.2px;}

  .userbox{display:flex;align-items:center;gap:14px;position:relative;}

  /* === notif ala admin === */
  .top-actions{display:flex;align-items:center;gap:14px;position:relative}
  .bell-btn{
    position:relative;cursor:pointer;
    display:inline-flex;align-items:center;justify-content:center;
    background:transparent;border:0;z-index:7000;
  }
  .bell-btn i{color:#fff;font-size:16px}
  .bell-btn .badge{
    position:absolute; top:-6px; right:-6px;
    background:#e53935; color:#fff;
    border-radius:999px;
    font-size:11px;
    padding:2px 6px;
    min-width:18px;height:18px;
    line-height:14px;text-align:center;
    border:2px solid #0a1a54;
    font-weight:800;
  }
  .notif-dd{
    position:absolute; right:0; top:46px;
    width:360px; max-height:420px; overflow:auto;
    background:#fff;
    border:1px solid rgba(13,23,84,.12);
    border-radius:14px;
    box-shadow:0 12px 30px rgba(13,23,84,.18);
    display:none;
    z-index:9999;
    pointer-events:auto;
  }
  .notif-dd.active{display:block}
  .notif-hd{
    padding:10px 12px;border-bottom:1px solid #eef1f6;
    font-weight:900;color:#0e257a;
    display:flex;justify-content:space-between;align-items:center
  }
  .notif-item-link{display:block;text-decoration:none;color:inherit}
  .notif-item{
    display:flex;gap:10px;padding:12px;
    border-bottom:1px solid #f3f5fb;background:#fff;
  }
  .notif-item:hover{background:#f7f9ff}
  .notif-item.unread{background:#f7f9ff}
  .notif-icon{
    width:28px;height:28px;border-radius:8px;
    display:grid;place-items:center;
    background:#e9efff;color:#1d4ed8;flex:0 0 auto;
  }
  .notif-title{font-weight:900;color:#0e257a;font-size:13px;line-height:1.2}
  .notif-meta{font-size:12px;color:#6c7a8a;margin-top:2px}
  .notif-empty{padding:18px;text-align:center;color:#6c7a8a}
  .notif-ft{padding:10px;border-top:1px solid #eef1f6;text-align:center;background:#fff;}
  .notif-ft a{color:#0e257a;text-decoration:none;font-weight:900;}

  /* === profile ala admin === */
  .profile-box{
    display:flex;align-items:center;gap:8px;
    text-decoration:none;color:inherit;cursor:pointer;
    padding:6px 8px;border-radius:12px;
    transition:background .18s;
  }
  .profile-box:hover{background: rgba(255,255,255,.08);}
  .profile-avatar{
    width:32px;height:32px;border-radius:999px;
    background:#1a2a6b;
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-weight:700;font-size:13px;
    overflow:hidden;border:1px solid rgba(255,255,255,.35);
  }
  .profile-avatar img{width:100%;height:100%;object-fit:cover;display:block;}
  .profile-meta{display:flex;flex-direction:column;line-height:1.1;}
  .profile-name{font-size:13px;font-weight:600;}
  .profile-role{font-size:11px;opacity:.8;}

  @media (max-width: 980px){
    .topbar-btn{ display:inline-flex; }
  }
</style>

<header class="topbar">
  <div class="topbar-left">
    <button class="topbar-btn" onclick="document.getElementById('sidebar')?.classList.toggle('show')">
      <i class="fa-solid fa-bars"></i>
    </button>
    <div class="welcome">
      {{-- bisa dibuat dinamis kalau mau: @yield('page_title','Dashboard Dosen') --}}
      <h1>Dashboard Dosen</h1>
    </div>
  </div>

  <div class="userbox">
    {{-- 🔔 Notif dropdown ala admin --}}
    <div class="top-actions" id="topActions">
      <div class="bell-btn" id="bellBtn" aria-label="Notifikasi">
        <i class="fa-solid fa-bell"></i>
        @if($notifCount > 0)
          <span class="badge" id="notifDot">{{ $notifCount }}</span>
        @endif
      </div>

      <div class="notif-dd" id="notifDd" role="menu" aria-hidden="true">
        <div class="notif-hd">
          <span>Notifikasi</span>
          <small style="color:#6c7a8a;font-weight:800">{{ $notifCount }} baru</small>
        </div>

        @forelse($notifList as $n)
          {{-- ✅ sesuaikan route detail notif untuk dosen --}}
          <a class="notif-item-link" href="{{ route('admins.notifikasi.read', $n->id) }}">
            <div class="notif-item unread">
              <div class="notif-icon"><i class="fa-solid fa-bell"></i></div>
              <div style="min-width:0">
                <div class="notif-title">{{ $n->judul ?? '-' }}</div>
                @if(!empty($n->pesan))
                  <div class="notif-meta" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ \Illuminate\Support\Str::limit($n->pesan, 70) }}
                  </div>
                @endif
                <div class="notif-meta">{{ $n->created_at?->diffForHumans() }}</div>
              </div>
            </div>
          </a>
        @empty
          <div class="notif-empty">Belum ada notifikasi.</div>
        @endforelse

        <div class="notif-ft">
          <a href="{{ route('admins.notifikasi.index') }}">Lihat Semua Notifikasi</a>
        </div>
      </div>
    </div>

    {{-- Profile box ala admin --}}
    <a href="{{ $profileUrl }}" class="profile-box">
      <div class="profile-avatar">
        @if($userPhoto)
          <img
            src="{{ $userPhoto }}"
            alt="Foto Profil"
            data-fallback="{{ asset('images/default-profile.png') }}"
            onerror="this.onerror=null;this.src=this.dataset.fallback;"
          >
        @else
          {{ $initial }}
        @endif
      </div>

      <div class="profile-meta">
        <span class="profile-name">{{ $userName }}</span>
        <span class="profile-role">{{ $userRoleLabel }}</span>
      </div>
    </a>
  </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const bell = document.getElementById('bellBtn');
  const dd   = document.getElementById('notifDd');
  const dot  = document.getElementById('notifDot');

  if(!bell || !dd) return;

  bell.addEventListener('click', function(e){
    e.stopPropagation();
    dd.classList.toggle('active');
    if (dd.classList.contains('active') && dot) dot.style.display = 'none';
  });

  dd.addEventListener('click', function(e){
    e.stopPropagation();
  });

  document.addEventListener('click', function(){
    dd.classList.remove('active');
  });

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape') dd.classList.remove('active');
  });
});
</script>
