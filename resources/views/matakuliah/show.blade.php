@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h1 class="mb-3">Detail Matakuliah</h1>

  <div class="card p-3 mb-3">
    <div><strong>Kode:</strong> {{ $matakuliah->kode }}</div>
    <div><strong>Nama:</strong> {{ $matakuliah->nama }}</div>
    <div><strong>SKS:</strong> {{ $matakuliah->sks }}</div>
    <div><strong>Semester:</strong> {{ $matakuliah->semester ?? '-' }}</div>
    <div><strong>Deskripsi:</strong><br>{{ $matakuliah->deskripsi ?? '-' }}</div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('matakuliah.edit', $matakuliah) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('matakuliah.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>
@endsection
