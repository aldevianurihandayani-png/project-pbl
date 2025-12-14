@extends('layouts.admin')

@section('page_title', 'Tambah Kelas')

@section('content')
<div class="container-fluid">

    <style>
        body, .container-fluid{
            background:#f4f6ff !important;
        }

        .kls-shell{
            max-width:820px;
            margin:0 auto;
            padding:20px 0 40px;
        }

        /* back link */
        .kls-back-link{
            display:inline-flex;
            align-items:center;
            gap:6px;
            font-size:13px;
            text-decoration:none !important;
            color:#4b5563;
            padding:6px 14px;
            border-radius:999px;
            background:#ffffff;
            border:1px solid #e5e7eb;
            margin-bottom:18px;
        }
        .kls-back-link:hover{
            background:#edf2ff;
            color:#0b1f4d;
        }

        /* card */
        .kls-card{
            width:100%;
            border-radius:18px;
            padding:24px 30px;
            background:#ffffff;
            border:1px solid #e4ebff;
            box-shadow:0 18px 40px rgba(15,23,42,.08);
        }

        .kls-title{
            font-size:13px;
            font-weight:700;
            color:#6b7280;
            text-transform:uppercase;
            letter-spacing:.14em;
            margin-bottom:2px;
        }
        .kls-sub{
            font-size:14px;
            color:#4b5563;
            margin-bottom:20px;
        }

        .kls-field{
            margin-bottom:16px;
        }
        .kls-field label{
            display:block;
            font-size:14px;
            font-weight:600;
            color:#0b1f4d;
            margin-bottom:6px;
        }
        .kls-input{
            width:100%;
            border-radius:10px;
            padding:10px 12px;
            border:1px solid #d1d5db;
            font-size:14px;
        }
        .kls-input:focus{
            outline:none;
            border-color:#2563eb;
            box-shadow:0 0 0 3px rgba(37,99,235,.18);
        }

        .kls-invalid{
            font-size:12px;
            color:#b91c1c;
            margin-top:3px;
        }

        /* tombol */
        .kls-actions{
            display:flex;
            justify-content:flex-end;
            gap:12px;
            margin-top:20px;
        }
        .kls-btn{
            padding:10px 26px;
            border-radius:999px;
            font-size:14px;
            font-weight:600;
            cursor:pointer;
            transition:.18s ease-in-out;
            text-decoration:none !important;  /* HILANGKAN GARIS BAWAH SEMUA TOMBOL */
        }

        .kls-btn-cancel{
            background:#ffe4e6;
            color:#b91c1c !important;
            border:1px solid #fbcfd2;
        }
        .kls-btn-cancel:hover{
            background:#fecdd3;
            color:#b91c1c !important;
            text-decoration:none !important;
        }

        .kls-btn-save{
            background:#1d4ed8;
            color:#ffffff !important;
            border:none;
        }
        .kls-btn-save:hover{
            background:#1e40af;
            color:#ffffff !important;
        }
    </style>

    <div class="kls-shell">

        {{-- back --}}
        <a href="{{ route('admins.kelas.index') }}" class="kls-back-link">
            ← Kembali ke daftar kelas
        </a>

        {{-- error --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM CARD --}}
        <div class="kls-card">

            <div class="kls-title">Tambah Kelas</div>
            <div class="kls-sub">Isi nama kelas, semester, dan periode akademik sesuai data sistem.</div>

            <form action="{{ route('admins.kelas.store') }}" method="POST">
                @csrf

                {{-- Nama Kelas --}}
                <div class="kls-field">
                    <label>Nama Kelas *</label>
                    <input type="text" name="nama_kelas" class="kls-input"
                           value="{{ old('nama_kelas') }}">
                    @error('nama_kelas')
                        <div class="kls-invalid">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Semester --}}
                <div class="kls-field">
                    <label>Semester *</label>
                    <input type="number" min="1" name="semester" class="kls-input"
                           value="{{ old('semester') }}">
                    @error('semester')
                        <div class="kls-invalid">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Periode --}}
                <div class="kls-field">
                    <label>Periode *</label>
                    <input type="text" name="periode" class="kls-input"
                           value="{{ old('periode') }}">
                    @error('periode')
                        <div class="kls-invalid">{{ $message }}</div>
                    @enderror
                </div>

                <div class="kls-actions">
                    <a href="{{ route('admins.kelas.index') }}" class="kls-btn kls-btn-cancel">
                        Batal
                    </a>
                    <button type="submit" class="kls-btn kls-btn-save">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
