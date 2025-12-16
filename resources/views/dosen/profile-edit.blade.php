{{-- resources/views/dosen/profile-edit.blade.php --}}
@extends('layouts.dosen')
@section('title', 'Edit Profil — Dosen Pembimbing')
@section('header', 'Edit Profil Dosen Pembimbing')

@section('content')
<style>
  .page-header {display:none;}
  .table th, .table td {padding:10px 12px; border-bottom:1px solid #eef1f6;}
  .table th {color:#0e257a; font-weight:800; background:#f6f8fd;}
  .form-group label {font-weight:600; color:#0e257a;}
  .form-control {border:1px solid #d0d7e7; border-radius:8px; padding:8px 10px;}
  .form-control:focus {border-color:#0e257a; box-shadow:0 0 0 2px rgba(14,37,122,0.15);}
</style>

@if ($errors->any())
  <div class="alert alert-danger" style="background:#fff5f5;border:1px solid #ffd6d6;color:#8a1f1f;border-radius:10px;padding:10px 12px;margin-bottom:12px">
    <strong>Periksa kembali:</strong>
    <ul style="margin:6px 0 0 18px">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card">
  <div class="card-hd">
    <div>Informasi Akun</div>
  </div>

  <div class="card-bd">
    @php $u = auth()->user(); @endphp

    {{-- FORM UPDATE PROFIL --}}
    <form action="{{ url('dosen/profile') }}" method="POST" enctype="multipart/form-data"
          style="display:grid;grid-template-columns:260px 1fr;gap:24px">
      @csrf
      @method('PUT')

      {{-- FOTO --}}
      <div>
        <div style="text-align:center">
          <div style="width:160px;height:160px;border-radius:50%;overflow:hidden;border:3px solid #e3e9ff;margin:0 auto 12px;display:grid;place-items:center;background:#eef2ff">
            @if ($u->foto)
              <img id="preview"
                   src="{{ asset('storage/'.$u->foto) }}"
                   style="width:100%;height:100%;object-fit:cover">
            @else
              <span id="preview-initial"
                    style="font-size:56px;color:#31408a;font-weight:700">
                {{ strtoupper(substr($u->nama ?? $u->name ?? 'AL', 0, 2)) }}
              </span>
              <img id="preview" style="display:none;width:100%;height:100%;object-fit:cover">
            @endif
          </div>

          <label class="btn btn-secondary" style="cursor:pointer">
            <i class="fa-solid fa-image"></i> Ubah Foto
            <input type="file" name="foto" id="foto" accept="image/*" hidden>
          </label>
        </div>
      </div>

      {{-- FORM --}}
      <div>
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="nama" class="form-control"
                 value="{{ old('nama', $u->nama ?? $u->name) }}">
        </div>

        <div class="form-group">
          <label>Email *</label>
          <input type="email" name="email" class="form-control"
                 value="{{ old('email', $u->email) }}" required>
        </div>

        <div class="form-group">
          <label>Role</label>
          <input type="text" class="form-control"
                 value="{{ $u->role ?? 'dosen' }}" disabled>
        </div>

        <div class="form-group">
          <label>NIDN</label>
          <input type="text" name="nidn" class="form-control"
                 value="{{ old('nidn', $u->nidn) }}">
        </div>

        <div class="form-group">
          <label>Program Studi</label>
          <input type="text" name="prodi" class="form-control"
                 value="{{ old('prodi', $u->prodi) }}">
        </div>

        <div class="form-group">
          <label>Password (opsional)</label>
          <input type="password" name="password" class="form-control"
                 placeholder="Kosongkan jika tidak ganti">
        </div>

        <div class="form-group">
          <label>Tanggal Bergabung</label>
          <input type="text" class="form-control"
                 value="{{ optional($u->created_at)->format('d M Y') }}" disabled>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk"></i> Simpan
          </button>
          <a href="{{ url('dosen/profile') }}" class="btn btn-secondary">
            Batal
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('foto')?.addEventListener('change', function(e){
  const file = e.target.files?.[0];
  if(!file) return;
  const img = document.getElementById('preview');
  const ini = document.getElementById('preview-initial');
  img.src = URL.createObjectURL(file);
  img.style.display = 'block';
  if (ini) ini.style.display = 'none';
});
</script>
@endsection
