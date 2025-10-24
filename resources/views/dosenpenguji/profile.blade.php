{{-- resources/views/dosenpenguji/profile.blade.php --}}
@extends('dosenpenguji.layout')
@section('title', 'Profil — Dosen Penguji')
@section('header', 'Profil Dosen Penguji') 

@section('content')
<style>
  /* Sembunyikan header lama supaya tidak dobel judul, tidak menghapus elemen */
  .page-header{display:none;}

  /* Seragamkan gaya tabel & chip seperti halaman lain */
  .table th, .table td{padding:10px 12px; border-bottom:1px solid #eef1f6}
  .table th{color:#0e257a; font-weight:800; background:#f6f8fd}
  .chip{display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; background:#eef2ff; color:#22336b; font-weight:700; font-size:13px}
</style>

<div class="page-header">
  <h1 class="page-title">Profil Dosen Penguji</h1>
</div>

@if (session('success'))
  <div class="alert alert-success" style="background:#eafbf1;border:1px solid #b7f0cf;color:#11633b;border-radius:10px;padding:10px 12px;margin-bottom:12px">
    {{ session('success') }}
  </div>
@endif

<div class="card">
  <div class="card-hd">
    <div>Informasi Akun</div>
    <div class="actions">
      <a href="{{ route('dosenpenguji.profile.edit') }}" class="btn btn-primary">
        <i class="fa-solid fa-pen-to-square"></i> Edit Profil
      </a>
    </div>
  </div>

  <div class="card-bd">
    @php
      $u = auth()->user();
      $displayName = $u->nama ?? $u->name ?? 'Nama Dosen';
      $initials = strtoupper(substr($displayName,0,1) . (preg_replace('/.*\s/','',$displayName)[0] ?? ''));
    @endphp

    <div style="display:grid;grid-template-columns:200px 1fr;gap:24px;align-items:flex-start">

      {{-- Foto Profil --}}
      <div style="text-align:center">
        @if (!empty($u->avatar_url))
          <img src="{{ $u->avatar_url }}" alt="Foto Profil" style="width:140px;height:140px;border-radius:50%;object-fit:cover;border:3px solid #e3e9ff">
        @else
          <div style="width:140px;height:140px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;margin:0 auto 12px;font-size:48px;color:#31408a;font-weight:700">
            {{ $initials }}
          </div>
        @endif
        <div style="font-weight:700;color:#0e257a">{{ $displayName }}</div>
        <div style="font-size:13px;color:#6c7a8a">{{ $u->email ?? 'email@example.com' }}</div>
      </div>

      {{-- Detail --}}
      <div>
        <table class="table" style="width:100%;border-collapse:collapse">
          <tbody>
            <tr>
              <th style="width:180px;text-align:left">Nama Lengkap</th>
              <td>{{ $displayName }}</td>
            </tr>
            <tr>
              <th style="text-align:left">Email</th>
              <td>{{ $u->email ?? '-' }}</td>
            </tr>
            <tr>
              <th style="text-align:left">Role</th>
              <td><span class="chip">{{ $u->role ?? 'dosen_penguji' }}</span></td>
            </tr>
            <tr>
              <th style="text-align:left">Nomor Induk Dosen</th>
              <td>{{ $u->nidn ?? 'Belum diisi' }}</td>
            </tr>
            <tr>
              <th style="text-align:left">Program Studi</th>
              <td>{{ $u->prodi ?? 'Teknologi Informasi' }}</td>
            </tr>
            <tr>
              <th style="text-align:left">Tanggal Bergabung</th>
              <td>{{ optional($u->created_at)->translatedFormat('d M Y') ?? '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
@endsection
