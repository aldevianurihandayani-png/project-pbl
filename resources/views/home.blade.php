<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMAP Politala</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
</head>
<body>

@include('header')
<div class="container my-5 mt-5">
  <div class="row align-items-center">
    <div class="col-lg-5">
      <h5 class="fw-bold text-primary mb-2">Selamat Datang di Sistem Project Based Learning</h5>
      <p>
        Sistem ini dirancang untuk mendukung pengelolaan kegiatan Project Based Learning (PBL) pada
        Jurusan Teknologi Informasi, mulai dari pengelolaan kelompok, dosen pembimbing,
        hingga penilaian akhir mahasiswa.
      </p>
      <div class="d-flex gap-3 mt-3">
        <!-- ke form login -->
        <a href="{{ route('login') }}" class="btn btn-primary px-4">Mulai Sekarang</a>
        <a href="{{ route('about') }}" class="btn btn-outline-primary px-4">Pelajari Lebih Lanjut</a>
      </div>
    </div>
    <div class="col-lg-6 text-center">
      <img src="gambar-ilustrasi.png" class="img-fluid rounded-3 shadow-sm" alt="Ilustrasi PBL">
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
