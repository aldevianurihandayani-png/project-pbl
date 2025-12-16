@extends('layouts.admin')
@section('page_title', 'Edit Profil Admin')

@section('content')
<style>
  /* ====== FULL WIDTH & RAPI ====== */
  .profile-edit-container{
    width:100% !important;
    max-width:none !important;
    margin:0 !important;
    padding:18px 18px 28px !important;
  }

  .card{
    width:100%;
    border:1px solid #e3e6f0;
    border-radius:16px;
    box-shadow:0 10px 24px rgba(16,24,40,.08);
    overflow:hidden;
  }
  .card-hd{
    padding:14px 18px;
    border-bottom:1px solid #eef1f6;
    font-weight:800;
    color:#0e257a;
    background:#fff;
  }
  .card-bd{padding:18px;}

  /* GRID */
  .profile-form-grid{
    display:grid;
    grid-template-columns:260px 1fr;
    gap:24px;
    align-items:flex-start;
    width:100%;
  }
  .profile-form-grid > div:last-child{
    width:100%;
    min-width:0;
  }
  @media(max-width:768px){
    .profile-form-grid{grid-template-columns:1fr;}
  }

  /* AVATAR */
  .avatar-section{text-align:center;}
  .avatar-preview-wrapper{
    width:150px;height:150px;
    border-radius:50%;
    overflow:hidden;
    margin:0 auto 12px;
    background:#eef2ff;
    border:6px solid #e6ecff;
    display:grid;place-items:center;
  }
  .avatar-preview-wrapper img{width:100%;height:100%;object-fit:cover;}
  .avatar-preview-initials{
    font-size:56px;
    font-weight:800;
    color:#31408a;
  }

  /* TOMBOL UBAH FOTO */
  .btn-upload-photo{
    background:#6b7280;
    border:1px solid #6b7280;
    color:#fff;
    padding:8px 14px;
    border-radius:10px;
    font-weight:700;
    display:inline-flex;
    align-items:center;
    gap:8px;
    cursor:pointer;
  }
  .btn-upload-photo i{color:#fff;}
  .btn-upload-photo:hover{
    background:#4b5563;
    border-color:#4b5563;
  }

  /* FORM */
  .form-group{margin-bottom:14px;}
  .form-group label{
    display:block;
    margin-bottom:6px;
    font-weight:800;
    color:#0e257a;
  }
  .form-control{
    width:100%;
    border:1px solid #d0d7e7;
    border-radius:10px;
    padding:10px 12px;
  }
  .form-control:focus{
    border-color:#0e257a;
    box-shadow:0 0 0 3px rgba(14,37,122,.12);
  }
  .form-control[disabled],
  .form-control[readonly]{
    background:#f1f4f9;
  }

  /* HILANGKAN GARIS */
  hr{display:none !important;}

  /* ACTIONS */
  .form-actions{
    display:flex;
    gap:12px;
    margin-top:10px;
    border-top:none !important;
    padding-top:0;
  }

  /* SIMPAN */
  .btn-primary{
    background:#0e257a !important;
    border-color:#0e257a !important;
    color:#fff !important;
    font-weight:800;
    border-radius:10px;
    padding:10px 16px;
  }
  .btn-primary i{color:#fff !important;}
  .btn-primary:hover{
    background:#0b1f66 !important;
    border-color:#0b1f66 !important;
  }

  /* ====== BATAL (HILANGKAN GARIS BAWAH) ====== */
  .btn-secondary{
    background:#eef2ff;
    border:1px solid #cfd8ee;
    color:#0e257a;
    font-weight:800;
    border-radius:10px;
    padding:10px 16px;
    text-decoration:none !important;   /* INI YANG HILANGKAN GARIS */
  }
  .btn-secondary:hover,
  .btn-secondary:focus,
  .btn-secondary:active{
    background:#e6ecff;
    text-decoration:none !important;   /* PAKSA tetap tanpa underline */
  }
</style>

<div class="profile-edit-container">
  <div class="card">
    <div class="card-hd">Informasi Akun</div>

    <div class="card-bd">
      <form class="profile-form-grid"
            action="{{ route('admins.profile.update', $user->id) }}"
            method="POST"
            enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- FOTO -->
        <div class="avatar-section">
          <div class="avatar-preview-wrapper">
            @if ($user->foto)
              <img id="preview" src="{{ asset('storage/'.$user->foto) }}">
            @else
              <span id="preview-initial" class="avatar-preview-initials">
                {{ strtoupper(substr($user->nama ?? $user->name ?? 'A',0,2)) }}
              </span>
              <img id="preview" style="display:none;">
            @endif
          </div>

          <label class="btn-upload-photo">
            <i class="fa-solid fa-image"></i> Ubah Foto
            <input type="file" name="foto" id="foto" hidden>
          </label>
        </div>

        <!-- FORM -->
        <div>
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control"
                   value="{{ old('nama',$user->nama ?? $user->name) }}">
          </div>

          <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email',$user->email) }}">
          </div>

          <div class="form-group">
            <label>Role</label>
            <input type="text" class="form-control"
                   value="{{ $user->role ?? 'admin' }}" readonly>
          </div>

          <div class="form-group">
            <label>NIDN</label>
            <input type="text" name="nidn" class="form-control"
                   value="{{ old('nidn',$user->nidn) }}">
          </div>

          <div class="form-group">
            <label>Program Studi</label>
            <input type="text" name="prodi" class="form-control"
                   value="{{ old('prodi',$user->prodi) }}">
          </div>

          <div class="form-group">
            <label>Password Baru (opsional)</label>
            <input type="password" name="password" class="form-control">
          </div>

          <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control">
          </div>

          <div class="form-actions">
            <button class="btn btn-primary">
              <i class="fa-solid fa-floppy-disk"></i> Simpan
            </button>
            <a href="{{ route('admins.profile.index') }}" class="btn btn-secondary">
              Batal
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('foto')?.addEventListener('change', e=>{
  const f=e.target.files?.[0];
  if(!f) return;
  const img=document.getElementById('preview');
  const ini=document.getElementById('preview-initial');
  img.src=URL.createObjectURL(f);
  img.style.display='block';
  if(ini) ini.style.display='none';
});
</script>
@endsection
