@extends('layouts.admin')

{{-- judul di bar biru atas --}}
@section('page_title', 'Manajemen Mahasiswa')

@section('content')
<div class="container-fluid">

    <style>
        .mk-form-card{border-radius:18px;background:#fff;box-shadow:0 18px 40px rgba(15,23,42,.1);border:1px solid #e5edff;padding:24px 26px 22px;}
        .mk-section-title{font-size:12px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin-bottom:4px;}
        .mk-section-sub{font-size:13px;color:#6b7280;margin-bottom:14px;}
        .mk-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px 24px;}
        @media(max-width:768px){.mk-form-grid{grid-template-columns:1fr;}}
        .mk-field label{display:block;font-size:14px;font-weight:600;color:#111827;margin-bottom:4px;}
        .mk-field input,.mk-field select{width:100%;font-size:14px;border-radius:10px;border:1px solid #d1d5db;padding:9px 11px;}
        .mk-field input:focus,.mk-field select:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.2);}
        .mk-invalid{font-size:12px;color:#b91c1c;margin-top:2px;}
        .mk-actions{margin-top:24px;display:flex;justify-content:flex-end;gap:10px;}
        .mk-btn{padding-inline:22px;padding-block:8px;border-radius:999px;font-size:14px;font-weight:600;}

        .mk-back-link{
            display:inline-flex;
            align-items:center;
            gap:6px;
            font-size:14px;
            text-decoration:none;
            color:#1d4ed8;
            padding:4px 10px;
            border-radius:999px;
        }
        .mk-back-link:hover{
            background:#e5edff;
            color:#0f3fc0;
        }

        .mk-btn-cancel{
            border:1px solid #fca5a5;
            background:#ffecec;
            color:#b91c1c;
            text-decoration:none;
        }
        .mk-btn-cancel:hover{background:#ffdada;}

        .mk-btn-save{
            border:none;
            background:#1554d1;
            color:#ffffff;
        }
        .mk-btn-save:hover{background:#0f3fc0;}
    </style>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admins.mahasiswa.index', ['kelas' => request('kelas')]) }}" class="mk-back-link">
                <span>←</span><span>Kembali</span>
            </a>
            <h4 class="mb-0">Tambah Mahasiswa</h4>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <strong>Terjadi kesalahan.</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mk-form-card">
        <form action="{{ route('admins.mahasiswa.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <div class="mk-section-title">Informasi Mahasiswa</div>
                <div class="mk-section-sub">
                    Lengkapi NIM, nama, email, angkatan, nomor HP, dan kelas.
                </div>

                @php
                    $kelasSelected = old('kelas', $kelas ?? request('kelas'));
                @endphp

                <div class="mk-form-grid">
                    <div class="mk-field">
                        <label for="nim">NIM</label>
                        <input type="text" id="nim" name="nim" value="{{ old('nim') }}">
                        @error('nim') <div class="mk-invalid">{{ $message }}</div> @enderror
                    </div>

                    <div class="mk-field">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}">
                        @error('nama') <div class="mk-invalid">{{ $message }}</div> @enderror
                    </div>

                    <div class="mk-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}">
                        @error('email') <div class="mk-invalid">{{ $message }}</div> @enderror
                    </div>

                    <div class="mk-field">
                        <label for="angkatan">Angkatan</label>
                        <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan') }}">
                        @error('angkatan') <div class="mk-invalid">{{ $message }}</div> @enderror
                    </div>

                    <div class="mk-field">
                        <label for="no_hp">No. HP</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}">
                        @error('no_hp') <div class="mk-invalid">{{ $message }}</div> @enderror
                    </div>

                    <div class="mk-field">
                        <label for="kelas">Kelas</label>
                        <select id="kelas" name="kelas">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach(['A','B','C','D','E'] as $kls)
                                <option value="{{ $kls }}" {{ $kelasSelected == $kls ? 'selected' : '' }}>
                                    {{ $kls }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas') <div class="mk-invalid">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="mk-actions">
                <a href="{{ route('admins.mahasiswa.index', ['kelas' => request('kelas')]) }}"
                   class="mk-btn mk-btn-cancel">
                    Batal
                </a>

                <button type="submit" class="mk-btn mk-btn-save">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
