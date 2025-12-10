@extends('layouts.admin')

@section('title', 'Edit Akun')
@section('page_title', 'Edit Akun')

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
        <span>Edit Akun: {{ $user->nama ?? $user->name ?? $user->email }}</span>
    </div>
    <div class="card-bd">
        <form action="{{ route('admins.users.update', $user->id) }}" method="POST" class="form-grid">
            @csrf
            @method('PUT')

            <div>
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama"
                       value="{{ old('nama', $user->nama ?? $user->name) }}">
                @error('nama') <div style="color:#c00;font-size:12px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="email">Email <span style="color:#c00">*</span></label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $user->email) }}" required>
                @error('email') <div style="color:#c00;font-size:12px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="role">Role <span style="color:#c00">*</span></label>
                <select id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    @php
                        $roles = [
                            'admin'            => 'Admin',
                            'mahasiswa'        => 'Mahasiswa',
                            'dosen_pembimbing' => 'Dosen Pembimbing',
                            'dosen_penguji'    => 'Dosen Penguji',
                            'koordinator'      => 'Koordinator',
                            'jaminan_mutu'     => 'Jaminan Mutu',
                        ];
                    @endphp
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}"
                            {{ old('role', $user->role) == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('role') <div style="color:#c00;font-size:12px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="password">Password (kosongkan jika tidak diganti)</label>
                <input type="password" id="password" name="password">
                @error('password') <div style="color:#c00;font-size:12px;">{{ $message }}</div> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-pill btn-pill-primary">
                    Update
                </button>
                <a href="{{ route('admins.users.index') }}" class="btn-pill btn-pill-ghost">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
