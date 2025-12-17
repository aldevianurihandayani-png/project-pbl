{{-- resources/views/jaminanmutu/profile.blade.php --}}
@extends('layouts.jaminanmutu')

@section('title', 'Profil — Jaminan Mutu')
@section('page_title', 'Profil Jaminan Mutu')

@section('content')

<style>
/* samakan style dg koordinator */
.dp-wrap{ padding:18px 8px 30px; }
.dp-grid{ display:grid; grid-template-columns:360px 1fr; gap:18px; }
@media (max-width:992px){ .dp-grid{ grid-template-columns:1fr; } }

.dp-card{
  background:#fff; border-radius:14px; box-shadow:0 8px 24px rgba(0,0,0,.08);
  overflow:hidden;
}
.dp-card-h{
  padding:14px 18px; border-bottom:1px solid rgba(0,0,0,.06);
  display:flex; align-items:center; justify-content:space-between;
}
.dp-title{ margin:0; font-weight:800; color:#4e73df; font-size:14px; }
.dp-card-b{ padding:18px; }

.dp-avatar{
  width:140px;height:140px; border-radius:999px; object-fit:cover; display:block;
  margin:0 auto 12px; border:4px solid #fff; box-shadow:0 10px 22px rgba(0,0,0,.12);
}
.dp-avatar-initial{
  width:140px;height:140px; border-radius:999px; display:grid; place-items:center;
  margin:0 auto 12px; border:4px solid #fff; box-shadow:0 10px 22px rgba(0,0,0,.12);
  background:#eef2ff; color:#31408a; font-weight:900; font-size:52px;
}

.dp-name{ font-weight:800; font-size:18px; text-align:center; margin:0; color:#0e257a; }
.dp-email{ text-align:center; color:#858796; font-size:13px; margin:6px 0 14px; }

.dp-chip{
  display:inline-flex; align-items:center; gap:6px; padding:6px 12px;
  border-radius:999px; background:#eef2ff; color:#22336b; font-weight:800;
  font-size:12px; border:1px solid #d7deff;
}
.dp-chip-wrap{ display:flex; justify-content:center; }

.dp-info{
  width:100%; border-collapse:separate; border-spacing:0; border:1px solid #eef1f6;
  border-radius:12px; overflow:hidden;
}
.dp-info th{
  width:240px; background:#f6f8fd; color:#0e257a; font-weight:900;
  padding:12px 14px; border-bottom:1px solid #eef1f6; font-size:13px;
}
.dp-info td{
  padding:12px 14px; border-bottom:1px solid #eef1f6; font-size:13px;
}
.dp-info tr:last-child th, .dp-info tr:last-child td{ border-bottom:none; }

.dp-btn{
  background:#0e257a; color:#fff !important; border-radius:10px; padding:8px 14px;
  font-weight:800; font-size:13px; text-decoration:none !important;
  display:inline-flex; align-items:center; gap:8px; border:0; cursor:pointer;
}

.dp-alert{
  padding:10px 12px; border-radius:10px; background:#d1fae5; color:#065f46;
  border:1px solid rgba(6,95,70,.15); margin-bottom:12px;
}
</style>

@php
  $u = auth()->user();

  $displayName = $u->nama ?? $u->name ?? 'Nama Jaminan Mutu';
  $email       = $u->email ?? '-';

  $parts = preg_split('/\s+/', trim($displayName));
  $initials = strtoupper(
    mb_substr($parts[0] ?? 'J', 0, 1) .
    mb_substr($parts[1] ?? '', 0, 1)
  );

  // samakan pola foto (pakai kolom foto)
  $photoPath = $u->foto ?? null;
  $photoUrl  = $photoPath ? asset('storage/'.$photoPath) : null;

  // label role biar enak dibaca
  $roleLabel = 'Jaminan Mutu';
@endphp

<div class="dp-wrap">

  @if (session('success'))
    <div class="dp-alert">{{ session('success') }}</div>
  @endif

  <div class="dp-grid">

    {{-- KIRI : FOTO --}}
    <div class="dp-card">
      <div class="dp-card-h">
        <h6 class="dp-title">Foto Profil</h6>
      </div>

      <div class="dp-card-b">
        @if ($photoUrl)
          <img src="{{ $photoUrl }}" class="dp-avatar" alt="Foto Profil"
               onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
          <div class="dp-avatar-initial" style="display:none">{{ $initials }}</div>
        @else
          <div class="dp-avatar-initial">{{ $initials }}</div>
        @endif

        <p class="dp-name">{{ $displayName }}</p>
        <p class="dp-email">{{ $email }}</p>

        <div class="dp-chip-wrap">
          <span class="dp-chip">{{ $roleLabel }}</span>
        </div>
      </div>
    </div>

    {{-- KANAN : INFO --}}
    <div class="dp-card">
      <div class="dp-card-h">
        <h6 class="dp-title">Informasi Akun</h6>

        <a href="{{ route('jaminanmutu.profile.edit') }}" class="dp-btn">
          <i class="fa-solid fa-pen-to-square"></i> Edit Profil
        </a>
      </div>

      <div class="dp-card-b">
        <table class="dp-info">
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
            <td><span class="dp-chip">{{ $roleLabel = 'Jaminan Mutu';}}</span></td>
          </tr>
          <tr>
            <th>Program Studi</th>
            <td>{{ $u->prodi ?? '-' }}</td>
          </tr>
          <tr>
            <th>Tanggal Bergabung</th>
            <td>{{ optional($u->created_at)->translatedFormat('d M Y') }}</td>
          </tr>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
