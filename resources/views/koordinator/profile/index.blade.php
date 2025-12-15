@extends('layouts.koordinator')

@section('page_title', 'Profil Koordinator')

@section('content')

<style>
/* ====== CSS KHUSUS HALAMAN PROFIL (SAMA DENGAN ADMIN) ====== */
.ap-wrap{ padding:18px 8px 30px; }
.ap-grid{ display:grid; grid-template-columns:360px 1fr; gap:18px; }
@media (max-width:992px){ .ap-grid{ grid-template-columns:1fr; } }

.ap-card{
    background:#fff;
    border-radius:14px;
    box-shadow:0 8px 24px rgba(0,0,0,.08);
    overflow:hidden;
}
.ap-card-h{
    padding:14px 18px;
    border-bottom:1px solid rgba(0,0,0,.06);
}
.ap-title{
    margin:0;
    font-weight:800;
    color:#4e73df;
    font-size:14px;
}
.ap-card-b{ padding:18px; }
.ap-card-f{
    padding:14px 18px;
    border-top:1px solid rgba(0,0,0,.06);
    display:flex;
    justify-content:flex-end;
    gap:10px;
}

/* Avatar */
.ap-avatar-wrap{
    --posX:50%;
    --posY:35%;
    width:120px;
    height:120px;
    margin:0 auto 12px;
    border-radius:999px;
}
.ap-avatar{
    width:120px;
    height:120px;
    border-radius:999px;
    object-fit:cover;
    object-position:var(--posX) var(--posY);
    border:4px solid #fff;
    box-shadow:0 10px 22px rgba(0,0,0,.12);
    cursor:grab;
}
.ap-avatar-tip{
    text-align:center;
    font-size:11px;
    color:#858796;
    margin-top:6px;
}

.ap-name{ text-align:center; font-weight:800; }
.ap-email{ text-align:center; font-size:12px; color:#858796; }

.ap-field{ margin-bottom:14px; }
.ap-label{ font-weight:700; font-size:13px; color:#5a5c69; }

.ap-input{
    width:100%;
    height:44px;
    padding:10px 12px;
    border-radius:10px;
    border:1px solid #d1d3e2;
}
.ap-btn-primary{
    background:#4e73df;
    color:#fff;
    border-radius:10px;
    padding:10px 14px;
    border:none;
    font-weight:700;
}
.ap-btn-secondary{
    background:#fff;
    border:1px solid #d1d3e2;
    border-radius:10px;
    padding:10px 14px;
}
.ap-alert{
    background:#d1fae5;
    color:#065f46;
    padding:10px 12px;
    border-radius:10px;
    margin-bottom:12px;
}
</style>

@php
    use Illuminate\Support\Facades\Storage;

    $photo = $user->profile_photo_path
        ? Storage::url($user->profile_photo_path)
        : asset('images/default-profile.png');

    $posX = old('photo_pos_x', 50);
    $posY = old('photo_pos_y', 35);
@endphp

<div class="ap-wrap">
    <div class="ap-grid">

        {{-- FOTO PROFIL --}}
        <div class="ap-card">
            <div class="ap-card-h">
                <h6 class="ap-title">Foto Profil</h6>
            </div>
            <div class="ap-card-b">
                <div class="ap-avatar-wrap" style="--posX:{{ $posX }}%; --posY:{{ $posY }}%;">
                    <img src="{{ $photo }}" class="ap-avatar">
                </div>
                <div class="ap-avatar-tip">Drag foto</div>

                <p class="ap-name">{{ $user->name }}</p>
                <p class="ap-email">{{ $user->email }}</p>

                <div class="ap-field">
                    <label class="ap-label">Upload Foto Baru</label>
                    <input type="file" name="profile_photo" form="profileForm">
                </div>
            </div>
        </div>

        {{-- FORM PROFIL --}}
        <div class="ap-card">
            <div class="ap-card-h">
                <h6 class="ap-title">Informasi Profil</h6>
            </div>

            <form id="profileForm"
                  action="{{ route('koordinator.profile.update') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="photo_pos_x" value="{{ $posX }}">
                <input type="hidden" name="photo_pos_y" value="{{ $posY }}">

                <div class="ap-card-b">
                    @if(session('success'))
                        <div class="ap-alert">{{ session('success') }}</div>
                    @endif

                    <div class="ap-field">
                        <label class="ap-label">Nama Lengkap</label>
                        <input type="text" name="name" class="ap-input"
                               value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="ap-field">
                        <label class="ap-label">Email</label>
                        <input type="email" name="email" class="ap-input"
                               value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="ap-field">
                        <label class="ap-label">Password Baru</label>
                        <input type="password" name="password" class="ap-input">
                    </div>

                    <div class="ap-field">
                        <label class="ap-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="ap-input">
                    </div>
                </div>

                <div class="ap-card-f">
                    <button type="reset" class="ap-btn-secondary">Batal</button>
                    <button type="submit" class="ap-btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection
