@extends('admin.layouts.app')

@section('title','Dashboard Admin')

@section('content')
  <h1 class="h1">Dashboard Admin</h1>
  <p class="muted">Selamat datang di sistem PBL</p>

  <div class="cards">
    <div class="card">
      <div class="card-title">Jumlah Kelompok</div>
      <div class="card-value">{{ $jumlahKelompok }}</div>
    </div>
    <div class="card">
      <div class="card-title">Logbook</div>
      <div class="card-value">{{ $jumlahLogbook }}</div>
    </div>
    <div class="card">
      <div class="card-title">Mahasiswa</div>
      <div class="card-value">{{ $jumlahMhs }}</div>
    </div>
  </div>
@endsection
