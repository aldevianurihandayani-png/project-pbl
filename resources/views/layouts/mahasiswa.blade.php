<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard — Mahasiswa')</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/mahasiswa.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  @stack('sidebar-styles')
  @stack('styles')
</head>
<body>
  {{-- SIDEBAR (partial) --}}
  @include('mahasiswa.partials.sidebar')

  {{-- MAIN --}}
  <main class="app-content">
    <header class="topbar">
      <button class="topbar-btn" id="toggleSidebar"><i class="fa-solid fa-bars"></i></button>
      <div class="welcome"><h1>@yield('page_title', 'Dashboard Mahasiswa')</h1></div>
      <div class="userbox">
        <div class="notif">
          <i class="fa-regular fa-bell"></i><span class="badge">3</span>
        </div>
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

  <script src="{{ asset('js/mahasiswa.js') }}"></script>
  @stack('scripts')
</body>
</html>
