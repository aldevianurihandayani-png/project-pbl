@extends('layouts.admin')

@section('page_title', 'Profil Administrator')
@section('content')

<style>
/* ====== WRAPPER KHUSUS: CSS HANYA UNTUK HALAMAN INI ====== */
.ap-wrap{ padding: 18px 8px 30px; }
.ap-grid{
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 18px;
}
@media (max-width: 992px){
    .ap-grid{ grid-template-columns: 1fr; }
}

/* Card */
.ap-card{
    background:#fff;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(0,0,0,.08);
    overflow:hidden;
}
.ap-card-h{
    padding: 14px 18px;
    border-bottom: 1px solid rgba(0,0,0,.06);
    display:flex; align-items:center; justify-content:space-between;
}
.ap-title{
    margin:0;
    font-weight:800;
    color:#4e73df;
    letter-spacing:.2px;
    font-size:14px;
}
.ap-card-b{ padding: 18px; }

/* Avatar */
.ap-avatar{
    width:140px; height:140px;
    border-radius:999px;
    object-fit:cover;
    display:block;
    margin: 0 auto 12px auto;
    border: 4px solid #fff;
    box-shadow: 0 10px 22px rgba(0,0,0,.12);
    background:#f8f9fc;
}
.ap-avatar-initial{
    width:140px; height:140px;
    border-radius:999px;
    display:grid;
    place-items:center;
    margin: 0 auto 12px auto;
    border: 4px solid #fff;
    box-shadow: 0 10px 22px rgba(0,0,0,.12);
    background:#eef2ff;
    color:#31408a;
    font-weight:900;
    font-size:52px;
}

/* Text */
.ap-name{ font-weight:800; font-size:18px; text-align:center; margin:0; color:#0e257a; }
.ap-email{ text-align:center; color:#858796; font-size:13px; margin:6px 0 14px; }

/* Chip */
.ap-chip{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:6px;
    padding:6px 12px;
    border-radius:999px;
    background:#eef2ff;
    color:#22336b;
    font-weight:800;
    font-size:12px;
    border:1px solid #d7deff;
}
.ap-chip-wrap{ display:flex; justify-content:center; }

/* Table info */
.ap-info{
    width:100%;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid #eef1f6;
    border-radius: 12px;
    overflow: hidden;
}
.ap-info th{
    width:240px;
    background:#f6f8fd;
    color:#0e257a;
    font-weight:900;
    text-align:left;
    padding:12px 14px;
    border-bottom:1px solid #eef1f6;
    font-size:13px;
}
.ap-info td{
    padding:12px 14px;
    border-bottom:1px solid #eef1f6;
    font-size:13px;
}
.ap-info tr:last-child th,
.ap-info tr:last-child td{ border-bottom:none; }

.ap-btn{
    background:#0e257a;
    color:#fff !important;
    border-radius:10px;
    padding:8px 14px;
    font-weight:800;
    font-size:13px;
    text-decoration:none !important;
    display:inline-flex;
    align-items:center;
    gap:8px;
}
.ap-alert{
    padding: 10px 12px;
    border-radius: 10px;
    background: #d1fae5;
    color:#065f46;
    border: 1px solid rgba(6,95,70,.15);
    margin-bottom: 12px;
}
</style>

@php
  // Use the $user variable passed from the controller
  $displayName = $user->nama ?? $user->name ?? 'Nama Admin';
  $email = $user->email ?? 'email@example.com';

  // inisial 2 huruf (depan + belakang)
  $parts = preg_split('/\s+/', trim($displayName));
  $initials = strtoupper(
      mb_substr($parts[0] ?? 'A', 0, 1) .
      mb_substr($parts[count($parts) - 1] ?? '', 0, 1)
  );
@endphp

<div class="ap-wrap">

  @if (session('success'))
    <div class="ap-alert">
      {{ session('success') }}
    </div>
  @endif

  <div class="ap-grid">

    {{-- KIRI : FOTO PROFIL --}}
    <div class="ap-card">
      <div class="ap-card-h">
        <h6 class="ap-title">Foto Profil</h6>
      </div>

      <div class="ap-card-b">

        @if ($user->foto)
          <img
            src="{{ asset('storage/'.$user->foto) }}"
            alt="Foto Profil"
            class="ap-avatar"
          >
        @elseif ($user->profile_photo_path)
        <img
            src="{{ asset('storage/'.$user->profile_photo_path) }}"
            alt="Foto Profil"
            class="ap-avatar"
          >
        @else
          <div class="ap-avatar-initial">
            {{ $initials }}
          </div>
        @endif

        <p class="ap-name">{{ $displayName }}</p>
        <p class="ap-email">{{ $email }}</p>

        <div class="ap-chip-wrap">
          <span class="ap-chip">{{ $user->role ?? 'admin' }}</span>
        </div>
      </div>
    </div>

    {{-- KANAN : INFORMASI AKUN --}}
    <div class="ap-card">
      <div class="ap-card-h">
        <h6 class="ap-title">Informasi Akun</h6>

        {{-- The edit button should go to the user management page for admins --}}
        <a href="{{ route('admins.profile.edit', $user->id) }}" class="ap-btn">
          <i class="fa-solid fa-pen-to-square"></i> Edit Profil
        </a>
      </div>

      <div class="ap-card-b">
        <table class="ap-info">
          <tr>
            <th>Nama Lengkap</th>
            <td>{{ $displayName }}</td>
          </tr>
          <tr>
            <th>Email</th>
            <td>{{ $email }}</td>
          </tr>
          <tr>
            <th>Role</th>
            <td><span class="ap-chip">{{ $user->role ?? 'admin' }}</span></td>
          </tr>
          <tr>
            <th>NIDN/NIM</th>
            <td>{{ $user->nidn ?? ($user->nim ?? 'Belum diisi') }}</td>
          </tr>
          <tr>
            <th>Program Studi</th>
            <td>{{ $user->prodi ?? 'Teknologi Informasi' }}</td>
          </tr>
          <tr>
            <th>Tanggal Bergabung</th>
            <td>{{ optional($user->created_at)->translatedFormat('d M Y') ?? '-' }}</td>
          </tr>
        </table>
      </div>
    </div>

  </div>
</div>

@endsection