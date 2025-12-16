<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard — Mahasiswa')</title>

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Icons --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  {{-- CSS utama --}}
  <link rel="stylesheet" href="{{ asset('css/mahasiswa.css') }}">

  {{-- ===== NOTIFIKASI (SAMA DENGAN DOSEN PENGUJI) ===== --}}
  <style>
    header.topbar{
      position:sticky;
      top:0;
      z-index:5000;
    }

    /* wrapper */
    .notif-wrap{ position:relative; }

    /* tombol lonceng */
    .notif{
      background:transparent;
      border:0;
      color:#fff;
      cursor:pointer;
      position:relative;
    }
    .notif:hover{ background:transparent; }
    .notif i{ font-size:16px; color:#fff; }

    /* badge */
    .notif .badge{
      position:absolute;
      top:-4px;
      right:-4px;
      min-width:18px;
      height:18px;
      padding:0 4px;
      border-radius:999px;
      background:#ff3b3b;
      color:#fff;
      font-size:12px;
      font-weight:700;
      border:2px solid #0a1a54;
      display:flex;
      align-items:center;
      justify-content:center;
    }

    /* dropdown */
    .notif-menu{
      width:360px;
      max-height:420px;
      overflow:auto;
      border-radius:14px;
      padding:0;
      border:1px solid rgba(13,23,84,.12);
      box-shadow:0 10px 28px rgba(13,23,84,.15);
      margin-top:10px;
      z-index:6000;
    }

    .notif-head{
      padding:10px 12px;
      border-bottom:1px solid #eef1f6;
      font-weight:700;
      color:#0e257a;
      background:#fff;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }

    .notif-item{
      display:flex;
      gap:10px;
      padding:12px;
      text-decoration:none;
      color:inherit;
      border-bottom:1px solid #f3f5fb;
      background:#fff;
    }
    .notif-item:hover{ background:#f7f9ff; }

    .notif-item__icon{
      width:28px;
      height:28px;
      border-radius:8px;
      display:grid;
      place-items:center;
      background:#e9efff;
      color:#1d4ed8;
      flex:0 0 auto;
    }

    .notif-item__title{ font-weight:700; color:#0e257a; }
    .notif-item__desc{ font-size:12px; color:#6c7a8a; }
    .notif-item__time{ font-size:12px; color:#6c7a8a; }

    .notif-foot{
      padding:10px;
      border-top:1px solid #eef1f6;
      text-align:center;
      background:#fff;
    }
    .notif-foot a{
      color:#0e257a;
      font-weight:700;
      text-decoration:none;
    }
  </style>
</head>

<body>
  {{-- SIDEBAR --}}
  @include('mahasiswa.partials.sidebar')

  {{-- MAIN --}}
  <main class="app-content">
    <header class="topbar d-flex align-items-center justify-content-between px-3">
      <div class="d-flex align-items-center gap-3">
        <h1 class="m-0 fs-5">@yield('page_title', 'Dashboard Mahasiswa')</h1>
      </div>

      <div class="d-flex align-items-center gap-3">

        {{-- ===== NOTIFIKASI ===== --}}
        @php
          $notifBaru = \App\Models\Notification::getUnreadCount();
          $notifs    = \App\Models\Notification::getListForTopbar(10);
        @endphp

        <div class="dropdown notif-wrap">
          <button class="notif" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-bell"></i>
            @if($notifBaru > 0)
              <span class="badge">{{ $notifBaru }}</span>
            @endif
          </button>

          <div class="dropdown-menu dropdown-menu-end notif-menu">
            <div class="notif-head">
              <span>Notifikasi</span>
              <small>{{ $notifBaru }} baru</small>
            </div>

            @forelse($notifs as $n)
              <a class="notif-item" href="{{ route('mahasiswa.notifikasi.read', $n->id) }}">
                <div class="notif-item__icon">
                  <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                  <div class="notif-item__title">{{ $n->judul }}</div>
                  @if($n->pesan)
                    <div class="notif-item__desc">{{ $n->pesan }}</div>
                  @endif
                  <div class="notif-item__time">
                    {{ optional($n->created_at)->diffForHumans() }}
                  </div>
                </div>
              </a>
            @empty
              <div class="p-3 text-muted small text-center">
                Tidak ada notifikasi
              </div>
            @endforelse

            <div class="notif-foot">
              <a href="{{ route('mahasiswa.notifikasi.index') }}">Lihat semua</a>
            </div>
          </div>
        </div>

        {{-- USER --}}
        <div class="d-flex align-items-center gap-2">
          <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
            {{ strtoupper(substr(auth()->user()->name ?? 'MS',0,2)) }}
          </div>
          <strong class="text-white">{{ auth()->user()->name ?? 'Mahasiswa' }}</strong>
        </div>
      </div>
    </header>

    <div class="page p-3">
      @yield('content')
    </div>
  </main>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>