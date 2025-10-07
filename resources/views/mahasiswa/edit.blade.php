@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Mahasiswa</h2>

    <form action="{{ route('mahasiswa.update', $mhs->nim) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>NIM</label>
            <input type="text" class="form-control" value="{{ $mhs->nim }}" disabled>
        </div>
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $mhs->nama }}" required>
        </div>
        <div class="mb-3">
            <label>Angkatan</label>
            <input type="number" name="angkatan" class="form-control" value="{{ $mhs->angkatan }}" required>
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ $mhs->no_hp }}">
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
