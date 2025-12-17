{{-- resources/views/koordinator/profile.blade.php --}}
@extends('layouts.koordinator')

@section('title', 'Profil — Koordinator')
@section('header', 'Profil Koordinator')

@section('content')

<style>
.page-header{display:none;}

.dp-wrap{ padding:18px 8px 30px; }
.dp-grid{
    display:grid;
    grid-template-columns:360px 1fr;
    gap:18px;
}
@media (max-width:992px){
    .dp-grid{ grid-template-columns:1fr; }
}

.dp-card{
    background:#fff;
    border-radius:14px;
    box-shadow:0 8px 24px rgba(0,0,0,.08);
    overflow:hidden;
}
.dp-card-h{
    padding:14px 18px;
    border-bottom:1px solid rgba(0,0,0,.06);
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.dp-title{
    margin:0;
    font-weight:800;
    color:#4e73df;
    font-size:14px;
}

.dp-card-b{ padding:18px; }

.dp-avatar{
    width:140px;height:140px;
    border-radius:999px;
    object-fit:cover;
    display:block;
    margin:0 auto 12px;
    border:4px solid #fff;
    box-shadow:0 10px 22px rgba(0,0,0,.12);
}
.dp-avatar-initial{
    width:140px;height:140px;
    border-radius:999px;
    display:grid;
    place-items:center;
    margin:0 auto 12px;
    border:4px solid #fff;
    box-shadow:0 10px 22px rgba(0,0,0,.12);
    background:#eef2ff;
    color:#31408a;
    font-weight:900;
    font-size:52px;
}

.dp-name{
    font-weight:800;
    font-size:18px;
    text-align:center;
    margin:0;
    color:#0e257a;
}
.dp-email{
    text-align:center;
    color:#858796;
    font-size:13px;
    margin:6px 0 14px;
}

.dp-chip{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 12px;
    border-radius:999px;
    background:#eef2ff;
    color:#22336b;
    font-weight:800;
    font-size:12px;
    border:1px solid #d7deff;
}
.dp-chip-wrap{ display:flex; justify-content:center; }

.dp-info{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
    border:1px solid #eef1f6;
    border-radius:12px;
    overflow:hidden;
}
.dp-info th{
    width:240px;
    background:#f6f8fd;
    color:#0e257a;
    font-weight:900;
    padding:12px 14px;
    border-bottom:1px solid #eef1f6;
    font-size:13px;
}
.dp-info td{
    padding:12px 14px;
    border-bottom:1px solid #eef1f6;
    font-size:13px;
}
.dp-info tr:last-child th,
.dp-info tr:last-child td{ border-bottom:none; }

.dp-btn{
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
    border:0;
    cursor:pointer;
}

.dp-alert{
    padding:10px 12px;
    border-radius:10px;
    background:#d1fae5;
    color:#065f46;
    border:1px solid rgba(6,95,70,.15);
    margin-bottom:12px;
}

/* ===== Modal ===== */
.dp-modal-backdrop{
  position:fixed; inset:0;
  background:rgba(0,0,0,.45);
  display:none;
  align-items:center;
  justify-content:center;
  padding:18px;
  z-index:9999;
}
.dp-modal{
  width:min(720px, 100%);
  background:#fff;
  border-radius:14px;
  box-shadow:0 18px 60px rgba(0,0,0,.25);
  overflow:hidden;
}
.dp-modal-h{
  padding:14px 18px;
  border-bottom:1px solid rgba(0,0,0,.06);
  display:flex;
  justify-content:space-between;
  align-items:center;
}
.dp-modal-h h6{
  margin:0; font-weight:900; color:#0e257a;
}
.dp-x{
  border:0; background:transparent; font-size:20px; cursor:pointer; color:#334155;
}
.dp-modal-b{ padding:18px; }
.dp-form-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:12px;
}
@media (max-width:720px){
  .dp-form-grid{ grid-template-columns:1fr; }
}
.dp-label{ font-weight:800; font-size:12px; color:#0e257a; margin-bottom:6px; display:block; }
.dp-input{
  width:100%;
  padding:10px 12px;
  border-radius:10px;
  border:1px solid #dbe3f3;
  outline:none;
}
.dp-input:focus{ border-color:#94a3ff; box-shadow:0 0 0 3px rgba(79,70,229,.12); }
.dp-actions{
  display:flex;
  gap:10px;
  justify-content:flex-end;
  margin-top:14px;
}
.dp-btn-ghost{
  background:#eef2ff;
  color:#22336b !important;
}
.dp-errors{
  background:#fee2e2;
  border:1px solid rgba(185,28,28,.18);
  color:#7f1d1d;
  padding:10px 12px;
  border-radius:10px;
  margin-bottom:12px;
}
.dp-preview{
  display:flex; align-items:center; gap:12px; margin-bottom:12px;
}
.dp-preview img{
  width:52px;height:52px;border-radius:999px;object-fit:cover;border:2px solid #fff;
  box-shadow:0 8px 18px rgba(0,0,0,.12);
}
</style>

@php
  $u = auth()->user();
  $displayName = $u->nama ?? $u->name ?? 'Nama Koordinator';
  $email = $u->email ?? '-';

  $parts = preg_split('/\s+/', trim($displayName));
  $initials = strtoupper(
      mb_substr($parts[0] ?? 'K', 0, 1) .
      mb_substr($parts[1] ?? '', 0, 1)
  );

  // ✅ pakai kolom yang benar untuk path foto
  $photoPath = $u->profile_photo_path ?? null;
  $photoUrl  = $photoPath ? asset('storage/'.$photoPath) : null;
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
          <span class="dp-chip">koordinator</span>
        </div>
      </div>
    </div>

    {{-- KANAN : INFO --}}
    <div class="dp-card">
      <div class="dp-card-h">
        <h6 class="dp-title">Informasi Akun</h6>

        {{-- ✅ tombol buka modal (bukan pindah halaman) --}}
        <button type="button" class="dp-btn" onclick="openEditModal()">
          <i class="fa-solid fa-pen-to-square"></i> Edit Profil
        </button>
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
            <td><span class="dp-chip">koordinator</span></td>
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

{{-- ===== MODAL EDIT PROFIL (gabung di sini) ===== --}}
<div class="dp-modal-backdrop" id="editModal">
  <div class="dp-modal" role="dialog" aria-modal="true" aria-labelledby="editTitle">
    <div class="dp-modal-h">
      <h6 id="editTitle">Edit Profil Koordinator</h6>
      <button class="dp-x" type="button" onclick="closeEditModal()">&times;</button>
    </div>

    <div class="dp-modal-b">

      {{-- tampil error validasi --}}
      @if ($errors->any())
        <div class="dp-errors">
          <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="dp-preview">
        @if ($photoUrl)
          <img src="{{ $photoUrl }}" alt="Preview foto">
        @else
          <div class="dp-avatar-initial" style="width:52px;height:52px;font-size:18px;">{{ $initials }}</div>
        @endif
        <div>
          <div style="font-weight:900;color:#0e257a">{{ $displayName }}</div>
          <div style="font-size:12px;color:#6c7a8a">{{ $email }}</div>
        </div>
      </div>

      <form action="{{ route('koordinator.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="dp-form-grid">
          <div>
            <label class="dp-label">Nama Lengkap</label>
            <input class="dp-input" type="text" name="nama" value="{{ old('nama', $u->nama) }}">
          </div>

          <div>
            <label class="dp-label">Email</label>
            <input class="dp-input" type="email" name="email" value="{{ old('email', $u->email) }}" required>
          </div>

          <div>
            <label class="dp-label">Program Studi</label>
            <input class="dp-input" type="text" name="prodi" value="{{ old('prodi', $u->prodi) }}">
          </div>

          <div>
            <label class="dp-label">Password Baru (opsional)</label>
            <input class="dp-input" type="password" name="password" placeholder="Kosongkan jika tidak diubah">
          </div>

          {{-- kalau koordinator tidak pakai nim, boleh hapus input ini --}}
          <div>
            <label class="dp-label">NIM (opsional)</label>
            <input class="dp-input" type="text" name="nim" value="{{ old('nim', $u->nim) }}">
          </div>

          <div>
            <label class="dp-label">Upload Foto (opsional)</label>
            <input class="dp-input" type="file" name="foto" accept="image/*">
            <div style="font-size:11px;color:#6c7a8a;margin-top:6px;">jpg/jpeg/png/webp max 2MB</div>
          </div>
        </div>

        <div class="dp-actions">
          <button type="button" class="dp-btn dp-btn-ghost" onclick="closeEditModal()">Batal</button>
          <button type="submit" class="dp-btn">Simpan</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  function openEditModal(){
    document.getElementById('editModal').style.display = 'flex';
  }
  function closeEditModal(){
    document.getElementById('editModal').style.display = 'none';
  }

  // tutup modal kalau klik backdrop
  document.getElementById('editModal').addEventListener('click', function(e){
    if(e.target.id === 'editModal') closeEditModal();
  });

  // jika ada error validasi, otomatis buka modal supaya keliatan
  @if ($errors->any())
    openEditModal();
  @endif
</script>

@endsection
