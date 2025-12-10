@extends('layouts.admin')

@section('title', 'Tambah Akun')
@section('page_title', 'Tambah Akun')

@section('content')
<style>
    .form-grid {
        max-width: 420px;
        display: grid;
        gap: 10px;
    }
    .form-grid label {
        font-size: 13px;
        font-weight: 600;
        color: #0b1d54;
        margin-bottom: 3px;
    }
    .form-grid input,
    .form-grid select {
        width: 100%;
        padding: 6px 8px;
        border-radius: 8px;
        border: 1px solid #cfd5f0;
        font-size: 14px;
    }
    .form-actions {
        margin-top: 14px;
        display: flex;
        gap: 8px;
    }
</style>

<div class="card">
    <div class="card-hd">
        <span>Tambah Akun Pengguna</span>
    </div>
    <div class="card-bd">
        <form action="{{ route('admins.users.store') }}" method="POST" class="form-grid">
            @csrf

            <div>
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}">
                @error('nama') <div style="color:#c00;font-size:12px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="email">Email <span style="color:#c00">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email') <div style="color:#c00;font-size:12px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="role">Role <span style="color:#c00">*</span></label>
                <select id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                    <option value="mahasiswa" {{ old('role')=='mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen_pembimbing" {{ old('role')=='dosen_pembimbing' ? 'selected' : '' }}>Dosen Pembimbing</option>
                    <option value="dosen_penguji" {{ old('role')=='dosen_penguji' ? 'selected' : '' }}>Dosen Penguji</option>
                    <option value="koordinator" {{ old('role')=='koordinator' ? 'selected' : '' }}>Koordinator</option>
                    <option value="jaminan_mutu" {{ old('role')=='jaminan_mutu' ? 'selected' : '' }}>Jaminan Mutu</option>
                </select>
                @error('role') <div style="color:#c00;font-size:12px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="password">Password <span style="color:#c00">*</span></label>
                <input type="password" id="password" name="password" required>
                @error('password') <div style="color:#c00;font-size:12px;">{{ $message }}</div> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-pill btn-pill-primary">
                    Simpan
                </button>
                <a href="{{ route('admins.users.index') }}" class="btn-pill btn-pill-ghost">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
