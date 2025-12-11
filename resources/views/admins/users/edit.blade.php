@extends('layouts.admin')

@section('title', 'Edit Akun')
@section('page_title', 'Edit Akun')

@section('content')

<style>
    .form-wrapper {
        background: #ffffff;
        border-radius: 14px;
        padding: 24px 24px 20px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .form-title {
        font-size: 18px;
        font-weight: 600;
        color: #0b1d54;
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #27314f;
        margin-bottom: 6px;
    }

    .form-control-simap {
        width: 100%;
        padding: 10px 12px;
        border-radius: 9px;
        border: 1px solid #d5d9ee;
        font-size: 14px;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        background: #f8f9ff;
    }

    .form-control-simap:focus {
        outline: none;
        border-color: #0b1d54;
        box-shadow: 0 0 0 2px rgba(11, 29, 84, 0.15);
        background: #ffffff;
    }

    .form-group + .form-group {
        margin-top: 14px;
    }

    .text-danger {
        font-size: 12px;
        margin-top: 4px;
    }

    .form-footer {
        margin-top: 22px;
        display: flex;
        gap: 10px;
        justify-content: flex-start;
        align-items: center;
    }

    .btn-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 18px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;   /* <-- hilangkan garis bawah */
        transition: background .15s ease, color .15s ease, border-color .15s ease,
                    box-shadow .15s ease, transform .05s ease;
        white-space: nowrap;
    }

    .btn-pill-primary {
        background: #0b1d54;
        border-color: #0b1d54;
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(11, 29, 84, 0.25);
    }

    .btn-pill-primary:hover {
        background: #13246b;
        border-color: #13246b;
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(11, 29, 84, 0.3);
        color: #ffffff;
        text-decoration: none;
    }

    .btn-pill-secondary {
        background: #ffffff;
        border-color: #cfd5f0;
        color: #0b1d54;
    }

    .btn-pill-secondary:hover {
        background: #f3f5ff;
        border-color: #9aa4d4;
        color: #0b1d54;
        text-decoration: none;
    }

    /* ===== TAMBAHAN: styling show/hide password ===== */
    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-wrapper .form-control-simap {
        padding-right: 42px; /* ruang buat icon mata */
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        border: none;
        background: transparent;
        padding: 0;
        cursor: pointer;
        outline: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
    }

    .toggle-password:hover {
        color: #0b1d54;
    }

    .toggle-password svg {
        width: 20px;
        height: 20px;
        stroke-width: 1.7;
    }

    .toggle-password .icon-hide {
        display: none;
    }

    .toggle-password.is-showing .icon-show {
        display: none;
    }

    .toggle-password.is-showing .icon-hide {
        display: inline;
    }
</style>

<div class="form-wrapper">
    <h5 class="form-title">
        Edit Akun: {{ $user->nama ?? $user->name ?? $user->email }}
    </h5>

    {{-- pesan sukses / error (opsional) --}}
    @if(session('success'))
        <div class="alert alert-success mb-3">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-3">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admins.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama">Nama</label>
            <input id="nama"
                   type="text"
                   name="nama"
                   class="form-control-simap"
                   value="{{ old('nama', $user->nama ?? $user->name) }}"
                   placeholder="Nama lengkap pengguna">
            @error('nama')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email <span style="color:#e11d48">*</span></label>
            <input id="email"
                   type="email"
                   name="email"
                   class="form-control-simap"
                   value="{{ old('email', $user->email) }}"
                   placeholder="nama@domain.ac.id"
                   required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">Role <span style="color:#e11d48">*</span></label>
            <select id="role"
                    name="role"
                    class="form-control-simap"
                    required>
                <option value="">-- Pilih Role --</option>
                @foreach($roles as $value => $label)
                    <option value="{{ $value }}"
                        {{ old('role', $user->role) == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- ===== PASSWORD dengan icon mata ===== --}}
        <div class="form-group">
            <label for="password">Password <span style="font-weight:400;">(kosongkan jika tidak diganti)</span></label>

            <div class="password-wrapper">
                <input id="password"
                       type="password"
                       name="password"
                       class="form-control-simap"
                       placeholder="Isi jika ingin mengganti password">

                <button type="button"
                        class="toggle-password"
                        data-target="password"
                        aria-label="Tampilkan password">

                    {{-- Icon mata (show) --}}
                    <svg class="icon-show" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M2.5 12.5C3.5 9 7 6 12 6c5 0 8.5 3 9.5 6.5-.9 3.5-4.5 6.5-9.5 6.5-5 0-8.6-3-9.5-6.5Z" />
                        <circle cx="12" cy="12.5" r="3.25" />
                    </svg>

                    {{-- Icon mata tertutup (hide) --}}
                    <svg class="icon-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 4.5 20.5 21" />
                        <path d="M5 8.5C6.5 6.5 9.2 5 12 5c5 0 8.5 3 9.5 6.5-.5 2-1.9 4-3.9 5.3" />
                        <path d="M9.5 9A3.5 3.5 0 0 1 15 14.5" />
                        <path d="M6.5 11.5C5.4 12.3 4.5 13.4 4 14.5c1 3.5 4.5 6.5 9.5 6.5 1 0 2.1-.1 3-.4" />
                    </svg>
                </button>
            </div>

            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-footer">
            {{-- tombol UPDATE --}}
            <button type="submit" class="btn-pill btn-pill-primary">
                Update
            </button>

            {{-- tombol BATAL --}}
            <a href="{{ route('admins.users.index') }}"
               class="btn-pill btn-pill-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

{{-- ===== JS toggle show/hide password ===== --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                if (!input) return;

                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';

                this.classList.toggle('is-showing', isPassword);
                this.setAttribute(
                    'aria-label',
                    isPassword ? 'Sembunyikan password' : 'Tampilkan password'
                );
            });
        });
    });
</script>

@endsection
