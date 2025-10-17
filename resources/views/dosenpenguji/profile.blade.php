@extends('dosenpenguji.layout')
@section('title', 'Profil — Dosen Penguji')

@section('content')
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
    @php $u = auth()->user(); @endphp
    <div style="display:grid;grid-template-columns:200px 1fr;gap:24px;align-items:flex-start">

      {{-- Foto Profil --}}
      <div style="text-align:center">
        @if ($u->avatar_url)
          <img src="{{ $u->avatar_url }}" alt="Foto Profil" style="width:140px;height:140px;border-radius:50%;object-fit:cover;border:3px solid #e3e9ff">
        @else
          <div style="width:140px;height:140px;border-radius:50%;background:#e3e9ff;display:grid;place-items:center;margin:0 auto 12px;font-size:48px;color:#31408a;font-weight:700">
            {{ strtoupper(substr($u->name ?? 'DP', 0, 2)) }}
          </div>
        @endif
        <div style="font-weight:700;color:#0e257a">{{ $u->name ?? 'Nama Dosen' }}</div>
        <div style="font-size:13px;color:#6c7a8a">{{ $u->email ?? 'email@example.com' }}</div>
      </div>

      {{-- Detail --}}
      <div>
        <table class="table" style="width:100%;border-collapse:collapse">
          <tbody>
            <tr><th style="width:180px;padding:10px;background:#f8fafc;text-align:left">Nama Lengkap</th><td style="padding:10px">{{ $u->name ?? '-' }}</td></tr>
            <tr><th style="padding:10px;background:#f8fafc;text-align:left">Email</th><td style="padding:10px">{{ $u->email ?? '-' }}</td></tr>
            <tr><th style="padding:10px;background:#f8fafc;text-align:left">Role</th><td style="padding:10px"><span class="badge bg-primary">{{ $u->role ?? 'dosen_penguji' }}</span></td></tr>
            <tr><th style="padding:10px;background:#f8fafc;text-align:left">Nomor Induk Dosen</th><td style="padding:10px">{{ $u->nidn ?? 'Belum diisi' }}</td></tr>
            <tr><th style="padding:10px;background:#f8fafc;text-align:left">Program Studi</th><td style="padding:10px">{{ $u->prodi ?? 'Teknologi Informasi' }}</td></tr>
            <tr><th style="padding:10px;background:#f8fafc;text-align:left">Tanggal Bergabung</th><td style="padding:10px">{{ optional($u->created_at)->format('d M Y') ?? '-' }}</td></tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
@endsection
