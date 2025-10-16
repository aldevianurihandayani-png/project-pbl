@extends('dosenpenguji.layout')

@section('content')
<div class="container">
    <h1>Edit Kelompok (Dosen Penguji)</h1>
    <form action="{{ route('dosenpenguji.kelompok.update', $kelompok->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama">Kelompok</label>
            <input type="text" name="nama" class="form-control" id="nama" value="{{ $kelompok->nama }}" required>
        </div>
        <div class="form-group">
            <label for="judul_proyek">Judul Proyek</label>
            <input type="text" name="judul_proyek" class="form-control" id="judul_proyek" value="{{ $kelompok->judul_proyek }}" required>
        </div>
        <div class="form-group">
            <label for="nama_klien">Nama Klien</label>
            <input type="text" name="nama_klien" class="form-control" id="nama_klien" value="{{ $kelompok->nama_klien }}" required>
        </div>
        <div class="form-group">
            <label for="ketua_kelompok">Ketua Kelompok</label>
            <input type="text" name="ketua_kelompok" class="form-control" id="ketua_kelompok" value="{{ $kelompok->ketua_kelompok }}" required>
        </div>
        <div class="form-group">
            <label for="kelas">Kelas</label>
            <input type="text" name="kelas" class="form-control" id="kelas" value="{{ $kelompok->kelas }}" required>
        </div>
        <div class="form-group">
            <label for="anggota">Anggota</label>
            <textarea name="anggota" class="form-control" id="anggota" rows="3" required>{{ $kelompok->anggota }}</textarea>
        </div>
        <div class="form-group">
            <label for="dosen_pembimbing">Dosen Pembimbing</label>
            <input type="text" name="dosen_pembimbing" class="form-control" id="dosen_pembimbing" value="{{ $kelompok->dosen_pembimbing }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
