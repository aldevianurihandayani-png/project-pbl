@extends('layouts.admin')

@section('page_title', 'Edit Kelas')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">Edit Kelas: {{ $kelas->nama_kelas }}</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan.</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admins.kelas.update', $kelas->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control"
                   value="{{ old('nama_kelas', $kelas->nama_kelas) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Semester</label>
            <input type="number" name="semester" min="1" class="form-control"
                   value="{{ old('semester', $kelas->semester) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Periode</label>
            <input type="text" name="periode" class="form-control"
                   value="{{ old('periode', $kelas->periode) }}">
        </div>

        <a href="{{ route('admins.kelas.index') }}" class="btn btn-outline-secondary">
            Batal
        </a>
        <button type="submit" class="btn btn-primary">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
