@extends('layouts.admin')

@section('page_title', 'Profil Administrator')

@section('content')

<style>
/* ====== WRAPPER KHUSUS: SEMUA CSS HANYA BERLAKU DI HALAMAN INI ====== */
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
.ap-card-f{
    padding: 14px 18px;
    border-top: 1px solid rgba(0,0,0,.06);
    display:flex; justify-content:flex-end; gap:10px;
    background:#fff;
}

/* ====== AVATAR DRAGGABLE ====== */
.ap-avatar-wrap{
    --posX: 50%;
    --posY: 35%;
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto 12px auto;
    border-radius: 999px;
}
.ap-avatar{
    width:120px; height:120px;
    border-radius:999px;
    object-fit: cover;
    object-position: var(--posX) var(--posY);
    display:block;
    border: 4px solid #fff;
    background:#f8f9fc;
    box-shadow: 0 10px 22px rgba(0,0,0,.12);
    cursor: grab;
    user-select: none;
    -webkit-user-drag: none;
}
.ap-avatar:active{ cursor: grabbing; }

/* Hint kecil (tanpa tulisan panjang) */
.ap-avatar-tip{
    position:absolute;
    bottom:-8px;
    left:50%;
    transform: translateX(-50%);
    font-size:11px;
    color:#858796;
    background:#fff;
    padding:4px 8px;
    border-radius:999px;
    border:1px solid rgba(0,0,0,.06);
    box-shadow: 0 6px 18px rgba(0,0,0,.06);
    white-space: nowrap;
}

/* Text */
.ap-name{ font-weight:800; font-size:16px; text-align:center; margin:12px 0 0; }
.ap-email{ text-align:center; color:#858796; font-size:12px; margin:3px 0 14px; }
.ap-hint{ color:#858796; font-size:12px; margin-top:6px; }

/* Form layout */
.ap-field{ margin-bottom: 14px; }
.ap-label{
    display:block;
    font-weight:700;
    color:#5a5c69;
    margin: 0 0 6px 0;
    font-size:13px;
}

/* Paksa input rapi walau CSS global jelek */
.ap-input{
    width:100% !important;
    height:44px !important;
    padding: 10px 12px !important;
    border-radius: 10px !important;
    border: 1px solid #d1d3e2 !important;
    background:#fff !important;
    color:#5a5c69 !important;
    font-size:14px !important;
    outline:none !important;
    box-shadow:none !important;
}
.ap-input:focus{
    border-color:#4e73df !important;
    box-shadow: 0 0 0 3px rgba(78,115,223,.15) !important;
}

/* File input */
.ap-file{
    width:100% !important;
    padding: 10px 12px !important;
    border-radius: 10px !important;
    border: 1px solid #d1d3e2 !important;
    background:#fff !important;
}

/* Section divider */
.ap-divider{ height:1px; background:rgba(0,0,0,.06); margin: 16px 0; }
.ap-row{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
@media (max-width: 768px){
    .ap-row{ grid-template-columns: 1fr; }
}

/* Buttons */
.ap-btn{
    border-radius: 10px;
    padding: 10px 14px;
    font-weight:700;
    border: 1px solid transparent;
    cursor:pointer;
}
.ap-btn-secondary{
    background:#fff;
    border-color:#d1d3e2;
    color:#5a5c69;
}
.ap-btn-primary{
    background:#4e73df;
    border-color:#4e73df;
    color:#fff;
}
.ap-alert{
    padding: 10px 12px;
    border-radius: 10px;
    background: #d1fae5;
    color:#065f46;
    border: 1px solid rgba(6,95,70,.15);
    margin-bottom: 12px;
}

/* error */
.ap-error{ color:#e74a3b; font-size:12px; margin-top:6px; }
</style>

<div class="ap-wrap">
    @php
        use Illuminate\Support\Facades\Storage;

        $photo = $user->profile_photo_path
            ? Storage::url($user->profile_photo_path)
            : asset('images/default-profile.png');

        // default posisi (kalau belum ada di DB)
        $posX = old('photo_pos_x', 50);
        $posY = old('photo_pos_y', 35);
    @endphp

    <div class="ap-grid">

        {{-- FOTO PROFIL --}}
        <div class="ap-card">
            <div class="ap-card-h">
                <h6 class="ap-title">Foto Profil</h6>
            </div>

            <div class="ap-card-b">

                <div class="ap-avatar-wrap" id="avatarWrap" style="--posX: {{ $posX }}%; --posY: {{ $posY }}%;">
                    <img src="{{ $photo }}" alt="Foto Profil" class="ap-avatar" id="avatarImg">
                    <div class="ap-avatar-tip">Drag foto</div>
                </div>

                <p class="ap-name">{{ $user->name }}</p>
                <p class="ap-email">{{ $user->email }}</p>

                <div class="ap-field">
                    <label class="ap-label" for="profile_photo">Upload Foto Baru</label>
                    <input
                        type="file"
                        id="profile_photo"
                        name="profile_photo"
                        class="ap-file @error('profile_photo') is-invalid @enderror"
                        form="profileForm"
                        accept="image/*"
                    >
                    @error('profile_photo')
                        <div class="ap-error">{{ $message }}</div>
                    @enderror
                    <div class="ap-hint">Format JPG/PNG, maksimal 2MB.</div>
                </div>
            </div>
        </div>

        {{-- FORM PROFIL --}}
        <div class="ap-card">
            <div class="ap-card-h">
                <h6 class="ap-title">Informasi Profil</h6>
            </div>

            <form id="profileForm" action="{{ route('admins.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Hidden input posisi foto (ikut tersubmit) --}}
                <input type="hidden" name="photo_pos_x" id="photo_pos_x" value="{{ $posX }}">
                <input type="hidden" name="photo_pos_y" id="photo_pos_y" value="{{ $posY }}">

                <div class="ap-card-b">

                    @if (session('success'))
                        <div class="ap-alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="ap-field">
                        <label class="ap-label" for="name">Nama Lengkap</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="ap-input @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                        @error('name')
                            <div class="ap-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="ap-field">
                        <label class="ap-label" for="email">Alamat Surel</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="ap-input @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')
                            <div class="ap-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="ap-divider"></div>

                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                        <div style="font-weight:800; color:#5a5c69;">Ubah Password</div>
                        <div class="ap-hint" style="margin:0;">Kosongkan jika tidak ingin mengubah</div>
                    </div>

                    <div class="ap-row" style="margin-top:12px;">
                        <div class="ap-field">
                            <label class="ap-label" for="password">Password Baru</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="ap-input @error('password') is-invalid @enderror"
                                autocomplete="new-password"
                            >
                            @error('password')
                                <div class="ap-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="ap-field">
                            <label class="ap-label" for="password_confirmation">Konfirmasi Password Baru</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="ap-input"
                                autocomplete="new-password"
                            >
                        </div>
                    </div>

                </div>

                <div class="ap-card-f">
                    <button type="reset" class="ap-btn ap-btn-secondary">Batal</button>
                    <button type="submit" class="ap-btn ap-btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
(function(){
    var wrap = document.getElementById('avatarWrap');
    var img = document.getElementById('avatarImg');
    var hiddenX = document.getElementById('photo_pos_x');
    var hiddenY = document.getElementById('photo_pos_y');

    if (!wrap || !img || !hiddenX || !hiddenY) return;

    // ambil posisi awal dari hidden
    var posX = parseInt(hiddenX.value || '50', 10);
    var posY = parseInt(hiddenY.value || '35', 10);

    // clamp 0..100
    function clamp(n){ return Math.max(0, Math.min(100, n)); }
    function apply(){
        wrap.style.setProperty('--posX', posX + '%');
        wrap.style.setProperty('--posY', posY + '%');
        hiddenX.value = posX;
        hiddenY.value = posY;
    }
    apply();

    var dragging = false;
    var startX = 0, startY = 0;
    var startPosX = 0, startPosY = 0;

    // sensitivitas drag (semakin kecil semakin halus)
    var sensitivity = 0.25; // 0.2 - 0.4 recommended

    function onDown(e){
        dragging = true;
        var p = (e.touches && e.touches[0]) ? e.touches[0] : e;
        startX = p.clientX;
        startY = p.clientY;
        startPosX = posX;
        startPosY = posY;
        img.style.cursor = 'grabbing';
        e.preventDefault();
    }

    function onMove(e){
        if (!dragging) return;
        var p = (e.touches && e.touches[0]) ? e.touches[0] : e;
        var dx = p.clientX - startX;
        var dy = p.clientY - startY;

        posX = clamp(Math.round(startPosX + dx * sensitivity));
        posY = clamp(Math.round(startPosY + dy * sensitivity));

        apply();
        e.preventDefault();
    }

    function onUp(){
        dragging = false;
        img.style.cursor = 'grab';
    }

    // mouse
    img.addEventListener('mousedown', onDown);
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseup', onUp);

    // touch
    img.addEventListener('touchstart', onDown, {passive:false});
    window.addEventListener('touchmove', onMove, {passive:false});
    window.addEventListener('touchend', onUp);

})();
</script>

@endsection
