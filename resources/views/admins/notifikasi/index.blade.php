
@include('admins.partials.header', ['title' => 'Manajemen Notifikasi'])

<style>
    .notification-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 15px;
        padding: 15px;
        border: 1px solid #eef1f6;
        display: flex;
        align-items: center;
    }
    .notification-card.unread {
        border-left: 5px solid var(--navy-2);
    }
    .notification-icon {
        font-size: 1.8em;
        color: var(--navy-2);
        margin-right: 15px;
    }
    .notification-content {
        flex-grow: 1;
    }
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    .notification-title {
        font-weight: bold;
        color: #333;
        font-size: 1.1em;
    }
    .notification-timestamp {
        font-size: 0.8em;
        color: var(--muted);
    }
    .notification-message {
        color: #555;
        line-height: 1.4;
    }
    .notification-user {
        font-size: 0.9em;
        color: var(--muted);
        margin-top: 5px;
    }
</style>

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Notifikasi</h6>
    </div>
    <div class="card-body">
        @forelse ($notifikasis as $notifikasi)
            <div class="notification-card {{ !$notifikasi->is_read ? 'unread' : '' }}">
                <div class="notification-icon">
                    <i class="fa-solid {{ !$notifikasi->is_read ? 'fa-bell' : 'fa-bell-slash' }}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <div class="notification-title">{{ $notifikasi->title }}</div>
                        <div class="notification-timestamp">{{ $notifikasi->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="notification-message">{{ $notifikasi->message }}</div>
                    <div class="notification-user">Untuk: {{ $notifikasi->user->name ?? 'Pengguna Tidak Dikenal' }}</div>
                </div>
                {{-- Anda bisa menambahkan tombol aksi di sini jika diperlukan --}}
                {{-- <div class="ml-auto">
                    <a href="#" class="btn btn-sm btn-outline-primary">Tandai Dibaca</a>
                </div> --}}
            </div>
        @empty
            <div class="text-center text-muted">
                Tidak ada notifikasi.
            </div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $notifikasis->links() }}
        </div>
    </div>
</div>

@include('admins.partials.footer')

{{-- resources/views/admins/notifications/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kelola Notifikasi — Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10); --shadow:0 6px 20px rgba(13,23,84,.08);
      --radius:16px;
      --primary-color: #2c3e50; --secondary-color: #3498db; --background-color: #f4f6f9;
      --white-color: #ffffff; --text-color: #333; --border-color: #e0e0e0;
      --success-color: #28a745; --warning-color: #ffc107; --info-color: #17a2b8;
    }
    *{box-sizing:border-box}
    body{
      margin:0; font-family:Arial,Helvetica,sans-serif; background:var(--bg);
      display:grid; grid-template-columns:260px 1fr; min-height:100vh;
    }

    /* ========== SIDEBAR ========== */
    .sidebar{
      background:var(--navy); color:#e9edf7; padding:18px 16px; display:flex; flex-direction:column;
    }
    .brand{
      display:flex; align-items:center; gap:10px; margin-bottom:22px;
    }
    .brand-badge{
      width:36px;height:36px; border-radius:10px; background:#1a2a6b; display:grid; place-items:center;
      font-weight:700; letter-spacing:.5px;
    }
    .brand-title{line-height:1.1}
    .brand-title strong{font-size:18px}
    .brand-title small{display:block; font-size:12px; opacity:.85}

    .nav-title{font-size:12px; letter-spacing:.6px; text-transform:uppercase; opacity:.7; margin:16px 10px 6px}
    .menu a{
      display:flex; align-items:center; gap:12px; text-decoration:none;
      color:#e9edf7; padding:10px 12px; border-radius:12px; margin:4px 6px;
      transition:background .18s, transform .18s;
    }
    .menu a:hover{ background:#11245f; transform:translateX(2px) }
    .menu a.active{ background:#1c3d86 }
    .menu i{ width:18px; text-align:center }

    .logout{ margin-top:auto }
    .logout a{ color:#ffb2b2 }
    .logout a:hover{ background:#5c1020 }

    /* ========== MAIN ========== */
    main{ display:flex; flex-direction:column; min-width:0 }
    header.topbar{
      background:#0a1a54; color:#fff; padding:12px 22px; display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:3; box-shadow:var(--shadow);
    }
    .welcome h1{ margin:0; font-size:18px; letter-spacing:.2px }
    .userbox{ display:flex; align-items:center; gap:14px }
    .notif{ position:relative; }
    .notif i{ font-size:18px }
    .notif .badge{ position:absolute; top:-6px; right:-6px; background:#e53935; color:#fff; border-radius:10px; font-size:10px; padding:2px 5px }

    .page{ padding:26px; }

    /* Notification Dropdown */
    .notif-dropdown {
      position: relative;
      display: inline-block;
      cursor: pointer;
    }
    .notif-icon {
      position: relative;
      padding: 5px;
    }
    .notif-icon .badge {
      position: absolute;
      top: -2px;
      right: -2px;
      background: #e53935;
      color: #fff;
      border-radius: 50%;
      font-size: 10px;
      padding: 2px 5px;
      min-width: 18px;
      text-align: center;
      line-height: 14px;
    }
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 300px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
      right: 0;
      border-radius: 8px;
      overflow: hidden;
      border: 1px solid #ddd;
    }
    .notif-dropdown:hover .dropdown-content {
      display: block;
    }
    .dropdown-content.show-dropdown {
      display: block;
    }
    .dropdown-header {
      padding: 12px 16px;
      border-bottom: 1px solid #eee;
      font-weight: bold;
      color: var(--navy-2);
      background-color: #f0f2f5;
    }
    .dropdown-item {
      display: flex;
      padding: 10px 16px;
      text-decoration: none;
      color: #333;
      border-bottom: 1px solid #eee;
      transition: background-color 0.2s;
      align-items: center;
    }
    .dropdown-item:hover {
      background-color: #f1f1f1;
    }
    .dropdown-item:last-of-type {
      border-bottom: none;
    }
    .dropdown-item .item-icon {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      margin-right: 10px;
      color: #fff;
      font-size: 14px;
    }
    .item-icon.info { background-color: #3498db; }
    .item-icon.materi { background-color: #2ecc71; }
    .item-icon.tugas { background-color: #e67e22; }

    .dropdown-item .item-content {
      flex-grow: 1;
    }
    .dropdown-item .item-title {
      font-size: 14px;
      font-weight: 600;
      line-height: 1.3;
    }
    .dropdown-item .item-time {
      font-size: 11px;
      color: #777;
    }
    .dropdown-item.no-notif {
      text-align: center;
      font-style: italic;
      color: #777;
      padding: 20px 16px;
    }
    .dropdown-footer {
      display: flex;
      justify-content: space-between;
      padding: 10px 16px;
      border-top: 1px solid #eee;
      background-color: #f0f2f5;
    }
    .mark-all-read-btn, .view-all-btn {
      background: none;
      border: none;
      color: var(--secondary-color);
      cursor: pointer;
      font-size: 13px;
      text-decoration: none;
      padding: 5px 8px;
      border-radius: 4px;
      transition: background-color 0.2s;
    }
    .mark-all-read-btn:hover, .view-all-btn:hover {
      background-color: #e0e0e0;
    }

    @media (max-width: 980px){
      body{ grid-template-columns:1fr }
      .sidebar{ position:fixed; inset:0 auto 0 0; width:240px; transform:translateX(-102%); transition:transform .2s; z-index:10 }
      .sidebar.show{ transform:none }
      .topbar-btn{ display:inline-flex }
    }
    .topbar-btn{ display:none; border:0; background:transparent; color:#fff; font-size:20px; cursor:pointer }
    a.card-link{ text-decoration:none }

    /* Custom styles for notifications index page */
    .notifications-container {
        padding: 24px;
    }
    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .notifications-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }
    .notifications-header .actions {
        display: flex;
        gap: 10px;
    }
    .notifications-header .actions .btn {
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .notifications-header .actions .btn-mark-all {
        background-color: var(--secondary-color);
        color: var(--white-color);
        border: none;
    }
    .notifications-header .actions .btn-mark-all:hover {
        background-color: #2980b9;
    }
    .notifications-filter-card {
        margin-bottom: 24px;
        background-color: var(--white-color);
        border-radius: 12px;
        box-shadow: 0 4px 12px var(--shadow);
        padding: 20px;
    }
    .notifications-filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
        align-items: flex-end;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group:last-of-type {
        margin-bottom: 0;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 14px;
    }
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        color: var(--text-color);
        background-color: var(--white-color);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        outline: none;
    }
    .btn-primary {
        background-color: var(--secondary-color);
        color: var(--white-color);
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
    }
    .btn-primary:hover {
        background-color: #2980b9;
    }
    .notifications-list .notification-item {
        background-color: var(--white-color);
        border-radius: 12px;
        box-shadow: 0 4px 12px var(--shadow);
        border: 1px solid var(--border-color);
        padding: 16px 24px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        color: var(--text-color);
        transition: background-color 0.2s;
        cursor: pointer;
    }
    .notifications-list .notification-item:hover {
        background-color: #f8f8f8;
    }
    .notifications-list .notification-item.unread {
        border-left: 5px solid var(--secondary-color);
        padding-left: 19px;
    }
    .notifications-list .notification-item .item-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 18px;
        color: #fff;
        flex-shrink: 0;
    }
    .notifications-list .notification-item .item-icon.info { background-color: #3498db; }
    .notifications-list .notification-item .item-icon.materi { background-color: #2ecc71; }
    .notifications-list .notification-item .item-icon.tugas { background-color: #e67e22; }

    .notifications-list .notification-item .item-content {
        flex-grow: 1;
    }
    .notifications-list .notification-item .item-title {
        font-weight: 600;
        font-size: 16px;
        line-height: 1.4;
    }
    .notifications-list .notification-item.unread .item-title {
        color: var(--primary-color);
    }
    .notifications-list .notification-item .item-meta {
        font-size: 12px;
        color: #6c757d;
    }
    .notifications-list .notification-item .item-time {
        font-size: 12px;
        color: #6c757d;
        flex-shrink: 0;
        margin-left: 15px;
    }
    .notifications-list .notification-item .notification-content-link {
        flex-grow: 1;
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        color: var(--text-color);
    }
    .notifications-list .notification-item .notification-actions {
        display: flex;
        gap: 8px;
        margin-left: auto; /* Pushes actions to the right */
        opacity: 0; /* Hide by default */
        transition: opacity 0.2s ease-in-out;
    }
    .notifications-list .notification-item:hover .notification-actions {
        opacity: 1; /* Show on hover */
    }
    .notifications-list .notification-item .notification-actions .btn-action {
        background-color: #f0f2f5;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 14px;
        cursor: pointer;
        color: var(--text-color);
        transition: background-color 0.2s, color 0.2s;
    }
    .notifications-list .notification-item .notification-actions .btn-action:hover {
        background-color: var(--secondary-color);
        color: var(--white-color);
        border-color: var(--secondary-color);
    }
    .pagination-links {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }
    .pagination-links nav {
        display: flex;
        gap: 5px;
    }
    .pagination-links .page-item .page-link {
        display: block;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        text-decoration: none;
        color: var(--primary-color);
        transition: background-color 0.2s;
    }
    .pagination-links .page-item .page-link:hover {
        background-color: #eef3ff;
    }
    .pagination-links .page-item.active .page-link {
        background-color: var(--secondary-color);
        color: var(--white-color);
        border-color: var(--secondary-color);
    }
    .pagination-links .page-item.disabled .page-link {
        color: #ccc;
        cursor: not-allowed;
    }

    /* Alerts */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 14px;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    @media (max-width: 768px) {
        .notifications-filter-form {
            grid-template-columns: 1fr;
        }
        .notifications-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        .notifications-header .actions {
            width: 100%;
            justify-content: stretch;
        }
        .notifications-header .actions .btn {
            flex-grow: 1;
            text-align: center;
        }
    }

    /* Modal Styles */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        border: 1px solid #888;
        width: 90%;
        max-width: 600px; /* Adjust as needed */
        border-radius: 12px;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
        animation-name: animatetop;
        animation-duration: 0.4s;
        position: relative;
    }

    /* Add Animation */
    @keyframes animatetop {
        from {top: -300px; opacity: 0}
        to {top: 0; opacity: 1}
    }

    .modal-header {
        padding: 15px 20px;
        background-color: var(--navy-2);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 20px;
    }

    .close-btn-edit {
        color: #fff;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s;
    }

    .close-btn-edit:hover,
    .close-btn-edit:focus {
        color: #ccc;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-body .form-group {
        margin-bottom: 15px;
    }

    .modal-body .form-group:last-of-type {
        margin-bottom: 20px; /* Space before the button */
    }

    .modal-body .btn-primary {
        width: auto;
        padding: 10px 25px;
        float: right; /* Align button to the right */
    }

    @media (max-width: 600px) {
        .modal-content {
            width: 95%;
        }
    }
  </style>
</head>
<body>

  <!-- ========== SIDEBAR ========== -->
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
      <a href="{{ url('/admins/dashboard') }}"><i class="fa-solid fa-house"></i>Dashboard</a>
      <a href="{{ url('/admins/matakuliah') }}"><i class="fa-solid fa-user-graduate"></i>Mata Kuliah</a>
      <a href="{{ url('/admins/feedback') }}"><i class="fa-solid fa-users"></i>Feedback</a>
      <a href="{{ route('admins.notifikasi.index') }}" class="active"><i class="fa-solid fa-bell"></i>Notifikasi</a>
      
      <div class="nav-title">Akun</div>
      <a href="{{ url('/profile') }}"><i class="fa-solid fa-id-badge"></i>Profil</a>
    </div>

    <div class="logout">
      <a href="{{ url('/logout') }}" class="menu" style="display:block"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  <!-- ========== MAIN ========== -->
  <main>
    <header class="topbar">
      <button class="topbar-btn" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="welcome">
        <h1>Kelola Notifikasi</h1>
      </div>
      <div class="userbox">
        <div class="notif-dropdown">
          <div class="notif-icon">
            <i class="fa-regular fa-bell"></i>
            @if(isset($unreadCount) && $unreadCount > 0)
              <span class="badge">{{ $unreadCount }}</span>
            @endif
          </div>
          <div class="dropdown-content">
            <div class="dropdown-header">Notifikasi (@if(isset($unreadCount)){{ $unreadCount }}@else 0 @endif Baru)</div>
            @if(isset($notifications))
              @forelse($notifications as $notification)
                <a href="{{ route('admins.notifikasi.read', $notification) }}" class="dropdown-item">
                  <div class="item-icon {{ $notification->type }}">
                    @if($notification->type == 'materi') <i class="fa-solid fa-book"></i>
                    @elseif($notification->type == 'tugas') <i class="fa-solid fa-clipboard-list"></i>
                    @else <i class="fa-solid fa-info-circle"></i>
                    @endif
                  </div>
                  <div class="item-content">
                    <div class="item-title">{{ $notification->title }}</div>
                    <div class="item-time">{{ $notification->created_at->diffForHumans() }}</div>
                  </div>
                </a>
              @empty
                <div class="dropdown-item no-notif">Tidak ada notifikasi baru.</div>
              @endforelse
            @else
              <div class="dropdown-item no-notif">Tidak ada notifikasi baru.</div>
            @endif
            <div class="dropdown-footer">
              <form action="{{ route('admins.notifikasi.markAll') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="mark-all-read-btn">Tandai Semua Dibaca</button>
              </form>
              <a href="{{ route('admins.notifikasi.index') }}" class="view-all-btn">Lihat Semua</a>
            </div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:32px;height:32px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;color:#31408a;font-weight:700">
            {{ strtoupper(substr(auth()->user()->name ?? 'NU',0,2)) }}
          </div>
          <strong>{{ auth()->user()->name ?? 'Nama User' }}</strong>
        </div>
      </div>
    </header>

    <div class="page notifications-container">
        <div class="notifications-header">
            <h1>Daftar Notifikasi</h1>
            <div class="actions">
                <form action="{{ route('admins.notifikasi.markAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-mark-all">Tandai Semua Dibaca</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card notifications-filter-card">
            <form class="notifications-filter-form" method="GET" action="{{ route('admins.notifikasi.index') }}">
                <div class="form-group">
                    <label for="search">Pencarian</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Cari judul atau kursus..." value="{{ request('search') }}">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="type">Tipe</label>
                    <select id="type" name="type" class="form-control">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="materi" {{ request('type') == 'materi' ? 'selected' : '' }}>Materi</option>
                        <option value="tugas" {{ request('type') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                        <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="notifications-list">
            @forelse($notifications as $notification)
                <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}">
                    <a href="{{ route('admins.notifikasi.read', $notification) }}" class="notification-content-link">
                        <div class="item-icon {{ $notification->type }}">
                            @if($notification->type == 'materi') <i class="fa-solid fa-book"></i>
                            @elseif($notification->type == 'tugas') <i class="fa-solid fa-clipboard-list"></i>
                            @else <i class="fa-solid fa-info-circle"></i>
                            @endif
                        </div>
                        <div class="item-content">
                            <div class="item-title">{{ $notification->title }}</div>
                            <div class="item-meta">
                                @if($notification->course)
                                    <span class="course">{{ $notification->course }}</span> - 
                                @endif
                                <span class="time">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="notification-actions">
                        <button class="btn-action edit-notification-btn" title="Edit Notifikasi" data-notification='{{ json_encode($notification) }}'><i class="fa-solid fa-edit"></i></button>
                        <form action="{{ route('admins.notifikasi.destroy', $notification) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus notifikasi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action" title="Hapus Notifikasi"><i class="fa-solid fa-trash-alt"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card" style="text-align: center; padding: 20px;">Tidak ada notifikasi.</div>
            @endforelse
        </div>

        <div class="pagination-links">
            {{ $notifications->links() }}
        </div>
    </div>
  </main>

  <!-- Modal Edit Notification -->
  <div id="editNotificationModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Edit Notifikasi</h2>
        <span class="close-btn-edit">&times;</span>
      </div>
      <div class="modal-body">
        <form id="editNotificationForm" method="POST">
          @csrf
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" id="edit_notification_id" name="id">
          <div class="form-group">
            <label for="edit_user_id">User ID (Kosongkan untuk umum)</label>
            <input type="number" id="edit_user_id" name="user_id" class="form-control">
          </div>
          <div class="form-group">
            <label for="edit_type">Tipe</label>
            <select id="edit_type" name="type" class="form-control" required>
              <option value="materi">Materi</option>
              <option value="tugas">Tugas</option>
              <option value="info">Info</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_title">Judul</label>
            <input type="text" id="edit_title" name="title" class="form-control" required maxlength="160">
          </div>
          <div class="form-group">
            <label for="edit_course">Mata Kuliah (Opsional)</label>
            <input type="text" id="edit_course" name="course" class="form-control" maxlength="120">
          </div>
          <div class="form-group">
            <label for="edit_link_url">URL Link (Opsional)</label>
            <input type="url" id="edit_link_url" name="link_url" class="form-control">
          </div>
          <div class="form-group">
            <label for="edit_is_read">Status Baca</label>
            <select id="edit_is_read" name="is_read" class="form-control" required>
              <option value="0">Belum Dibaca</option>
              <option value="1">Sudah Dibaca</option>
            </select>
          </div>
          <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Tutup sidebar ketika klik di luar (mobile)
    document.addEventListener('click', (e)=>{
      const sb = document.getElementById('sidebar');
      if(!sb.classList.contains('show')) return;
      const btn = e.target.closest('.topbar-btn');
      if(!btn && !e.target.closest('#sidebar')) sb.classList.remove('show');
    });

    // Notification Dropdown Toggle
    const notifDropdown = document.querySelector('.notif-dropdown');
    if (notifDropdown) {
        notifDropdown.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent window click from closing immediately
            this.querySelector('.dropdown-content').classList.toggle('show-dropdown');
        });

        // Close the dropdown if the user clicks outside of it
        window.addEventListener('click', function(event) {
            if (!event.target.closest('.notif-dropdown')) {
                document.querySelectorAll('.dropdown-content').forEach(function(dropdown) {
                    dropdown.classList.remove('show-dropdown');
                });
            }
        });
    }

    // Edit Notification Modal Logic
    const editModal = document.getElementById("editNotificationModal");
    const closeEditBtn = editModal.querySelector(".close-btn-edit");
    const editNotificationForm = document.getElementById("editNotificationForm");

    document.querySelectorAll('.edit-notification-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const notification = JSON.parse(this.dataset.notification);
            console.log('Notification ID:', notification.id);
            
            document.getElementById('edit_notification_id').value = notification.id;
            document.getElementById('edit_user_id').value = notification.user_id || '';
            document.getElementById('edit_type').value = notification.type;
            document.getElementById('edit_title').value = notification.title;
            document.getElementById('edit_course').value = notification.course || '';
            document.getElementById('edit_link_url').value = notification.link_url || '';
            document.getElementById('edit_is_read').value = notification.is_read ? '1' : '0';

            // Set form action dynamically
            editNotificationForm.action = `/admins/notifikasi/${notification.id}`;
            console.log('Form Action:', editNotificationForm.action);
            console.log('Form Method:', editNotificationForm.method);
            editModal.style.display = "block";
        });
    });

    closeEditBtn.onclick = function() {
      editModal.style.display = "none";
    }

    window.addEventListener('click', function(event) {
      if (event.target == editModal) {
        editModal.style.display = "none";
      }
    });
  </script>
</body>
</html>

