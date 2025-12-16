<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard — Mahasiswa')</title>

  {{-- 1) Bootstrap CSS dulu --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- 2) Icons/fonts --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  {{-- 3) CSS kamu (override Bootstrap) --}}
  <link rel="stylesheet" href="{{ asset('css/mahasiswa.css') }}">

  {{-- CSS NOTIF DROPDOWN (langsung di layout) --}}
  @push('styles')
  <style>
    /* ===== NOTIFIKASI ICON ===== */
    .notif-wrap { position: relative; }

    .notif {
      width: 42px;
      height: 42px;
      border-radius: 999px;
      background: #1e3a8a;
      border: none;
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      position: relative;
      transition: background 0.2s ease, transform 0.15s ease;
    }

    .notif:hover {
      background: #2563eb;
      transform: scale(1.05);
    }

    .notif:focus { outline: none; box-shadow: none; }

    .notif i { font-size: 18px; }

    /* BADGE ANGKA */
    .notif .badge {
      position: absolute;
      top: -4px;
      right: -4px;
      min-width: 18px;
      height: 18px;
      padding: 0 5px;
      border-radius: 999px;
      background: #ef4444;
      color: #fff;
      font-size: 11px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid #0b1d54;
    }

    /* ===== DROPDOWN NOTIFIKASI ===== */
    .notif-menu {
      width: 340px;
      border-radius: 14px;
      overflow: hidden;
      padding: 0;
      box-shadow: 0 12px 30px rgba(0,0,0,.18);
      border: 1px solid #e5e7eb;
      margin-top: 10px;
    }

    /* Header dropdown */
    .notif-head {
      background: #0b1d54;
      color: #fff;
      padding: 14px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .notif-head__title { font-weight: 800; }
    .notif-head__meta  { font-size: 12px; opacity: .85; }

    /* List notif */
    .notif-list {
      max-height: 320px;
      overflow-y: auto;
      background: #fff;
    }

    .notif-item {
      display: flex;
      gap: 12px;
      padding: 14px 16px;
      text-decoration: none;
      color: #1f2937;
      border-bottom: 1px solid #eef2f7;
      transition: background .15s;
    }

    .notif-item:hover { background: #f3f6ff; }

    /* icon di list */
    .notif-item__icon {
      width: 36px;
      height: 36px;
      border-radius: 999px;
      background: #e0e7ff;
      color: #1e3a8a;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      flex: 0 0 auto;
    }

    /* body */
    .notif-item__title { font-weight: 800; font-size: 14px; line-height: 1.1; }
    .notif-item__desc  { font-size: 13px; color: #6b7280; }
    .notif-item__time  { font-size: 11px; color: #9ca3af; margin-top: 2px; }

    /* footer */
    .notif-foot {
      padding: 12px;
      text-align: center;
      background: #f9fafb;
      border-top: 1px solid #eef2f7;
    }

    .notif-foot__link {
      font-size: 14px;
      font-weight: 700;
      color: #1e3a8a;
      text-decoration: none;
    }

    .notif-foot__link:hover { text-decoration: underline; }

    /* mobile */
    @media (max-width: 520px){
      .notif-menu { width: 92vw; }
    }
  </style>
  @endpush

  {{-- Tambahan style dari partial/halaman --}}
  @stack('sidebar-styles')
  @stack('styles')
</head>

<body>
  {{-- SIDEBAR --}}
  @include('mahasiswa.partials.sidebar')

  {{-- MAIN --}}
  <main class="app-content">
    <header class="topbar">
      <button class="topbar-btn" id="toggleSidebar" type="button">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="welcome">
        <h1 class="mb-0">@yield('page_title', 'Dashboard Mahasiswa')</h1>
      </div>

      <div class="userbox">
        {{-- NOTIFIKASI (BISA DIKLIK) --}}
        @php
          $notifBaru = 7;
          $notifs = [
            ['title'=>'PERCOBAAN KESEKIAN','desc'=>'HFYUIIGIUG','time'=>'7 hours ago'],
            ['title'=>'gft','desc'=>'hgfq','time'=>'8 hours ago'],
            ['title'=>'testing lagiii','desc'=>'hgdtdyftuutudytyu','time'=>'8 hours ago'],
            ['title'=>'testing lagiii','desc'=>'mencoba notif biar muncul ke role','time'=>'8 hours ago'],
            ['title'=>'testing','desc'=>'coba coba notifikkasjiii','time'=>'9 hours ago'],
          ];
        @endphp

        <div class="dropdown notif-wrap">
          <button class="notif" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
            <i class="fa-regular fa-bell"></i>
            <span class="badge">{{ $notifBaru }}</span>
          </button>

          <div class="dropdown-menu dropdown-menu-end notif-menu">
            <div class="notif-head">
              <div class="notif-head__title">Notifikasi</div>
              <div class="notif-head__meta">{{ $notifBaru }} baru</div>
            </div>

            <div class="notif-list">
              @foreach($notifs as $n)
                <a class="notif-item" href="#">
                  <div class="notif-item__icon">
                    <i class="fa-solid fa-bell"></i>
                  </div>
                  <div class="notif-item__body">
                    <div class="notif-item__title">{{ $n['title'] }}</div>
                    <div class="notif-item__desc">{{ $n['desc'] }}</div>
                    <div class="notif-item__time">{{ $n['time'] }}</div>
                  </div>
                </a>
              @endforeach
            </div>

            <div class="notif-foot">
              <a class="notif-foot__link" href="#">Lihat semua</a>
            </div>
          </div>
        </div>

        {{-- USER --}}
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
            {{ strtoupper(substr(($nama ?? (auth()->user()?->name ?? 'MS')),0,2)) }}
          </div>
          <strong>{{ $nama ?? (auth()->user()?->name ?? 'Mahasiswa') }}</strong>
        </div>
      </div>
    </header>

    <div class="page">
      @yield('content')
    </div>
  </main>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  {{-- JS kamu --}}
  <script src="{{ asset('js/mahasiswa.js') }}"></script>

  @stack('scripts')
</body>
</html>
