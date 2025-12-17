{{-- resources/views/koordinator/profile/profile-edit.blade.php --}}
@extends('layouts.koordinator')

@section('title', 'Edit Profil — Koordinator')
@section('header', 'Edit Profil Koordinator')

@section('content')
<style>
  .page-header {display:none;}
  .form-group{ margin-bottom:12px; }
  .form-group label{ font-weight:800; color:#0e257a; font-size:12px; display:block; margin-bottom:6px; }
  .form-control{
    width:100%;
    border:1px solid #d0d7e7;
    border-radius:10px;
    padding:10px 12px;
    outline:none;
  }
  .form-control:focus{ border-color:#0e257a; box-shadow:0 0 0 2px rgba(14,37,122,0.15); }
  .alert-danger{
    background:#fff5f5;border:1px solid #ffd6d6;color:#8a1f1f;
    border-radius:10px;padding:10px 12px;margin-bottom:12px
  }
  .btn{
    border:0; border-radius:10px; padding:10px 14px; font-weight:800; cursor:pointer;
    display:inline-flex; align-items:center; gap:8px; text-decoration:none;
  }
  .btn-primary{ background:#0e257a; color:#fff; }
  .btn-secondary{ background:#eef2ff; color:#22336b; }
</style>

@php $u = auth()->user(); @endphp

@if ($errors->any())
  <div class="alert-danger">
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
    <form action="{{ route('koordinator.profile.update') }}"
          method="POST" enctype="multipart/form-data"
          style="display:grid;grid-template-columns:260px 1fr;gap:24px">
      @csrf
      @method('PUT')

      {{-- Kolom Foto + Preview --}}
      <div>
        <div style="text-align:center">
          <div style="width:160px;height:160px;border-radius:50%;overflow:hidden;border:3px solid #e3e9ff;margin:0 auto 12px;display:grid;place-items:center;background:#eef2ff">
            @if ($u->foto)
              <img id="preview"
                   src="{{ asset('storage/'.$u->foto) }}"
                   alt="Foto Profil"
                   style="width:100%;height:100%;object-fit:cover">
            @else
              <span id="preview-initial"
                    style="font-size:56px;color:#31408a;font-weight:900">
                {{ strtoupper(substr($u->nama ?? $u->name ?? 'KO', 0, 2)) }}
              </span>
              <img id="preview"
                   src=""
                   alt=""
                   style="display:none;width:100%;height:100%;object-fit:cover">
            @endif
          </div>

          <label class="btn btn-secondary" style="cursor:pointer">
            <i class="fa-solid fa-image"></i> Ubah Foto (opsional)
            <input type="file" name="foto" id="foto" accept="image/*" hidden>
          </label>

          @if($u->foto)
            <div style="margin-top:8px;font-size:12px;color:#6c7a8a">
              Saat ini: {{ basename($u->foto) }}
            </div>
          @endif
        </div>
      </div>

      {{-- Form Fields --}}
      <div>
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="nama" class="form-control"
                 value="{{ old('nama', $u->nama ?? $u->name) }}"
                 placeholder="Nama lengkap">
        </div>

        <div class="form-group">
          <label>Email <span style="color:#e00">*</span></label>
          <input type="email" name="email" class="form-control"
                 value="{{ old('email', $u->email) }}" required>
        </div>

        <div class="form-group">
          <label>Role</label>
          <input type="text" class="form-control" value="koordinator PBL" disabled>
        </div>

        <div class="form-group">
          <label>Program Studi</label>
          <input type="text" name="prodi" class="form-control"
                 value="{{ old('prodi', $u->prodi) }}"
                 placeholder="Opsional">
        </div>

        <div class="form-group">
          <label>NIM (opsional)</label>
          <input type="text" name="nim" class="form-control"
                 value="{{ old('nim', $u->nim) }}"
                 placeholder="Opsional">
        </div>

        <div class="form-group">
          <label>Password (opsional)</label>
          <input type="password" name="password" class="form-control"
                 placeholder="Kosongkan jika tidak ganti">
        </div>

        <div class="form-group">
          <label>Tanggal Bergabung</label>
          <input type="text" class="form-control"
                 value="{{ optional($u->created_at)->format('d M Y') ?? '-' }}"
                 disabled>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk"></i> Simpan
          </button>
          <a href="{{ route('koordinator.profile') }}" class="btn btn-secondary">
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
  const img  = document.getElementById('preview');
  const ini  = document.getElementById('preview-initial');
  if(!file) return;
  const url = URL.createObjectURL(file);
  img.src = url;
  img.style.display = 'block';
  if (ini) ini.style.display = 'none';
});
</script>
@endsection
