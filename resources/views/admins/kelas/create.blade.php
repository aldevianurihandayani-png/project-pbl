@extends('layouts.admin')

@section('page_title', 'Tambah Kelas')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admins.kelas.index') }}" class="btn btn-light btn-sm">
                ← Kembali
            </a>
            <h4 class="mb-0">Tambah Kelas</h4>
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

    <div class="card" style="border-radius:18px;">
        <div class="card-body">
            <form action="{{ route('admins.kelas.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Kelas</label>
                    <input type="text" name="nama_kelas" class="form-control"
                           value="{{ old('nama_kelas') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <input type="number" name="semester" min="1" class="form-control"
                           value="{{ old('semester') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Periode</label>
                    <input type="text" name="periode" class="form-control"
                           placeholder="Contoh: 2024/2025 Ganjil"
                           value="{{ old('periode') }}">
                </div>

                <a href="{{ route('admins.kelas.index') }}" class="btn btn-outline-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
