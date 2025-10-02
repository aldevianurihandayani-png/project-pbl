<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMAP Politala</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      background:#f9f9fb;
    }
    .navbar-custom {
      background-color: #002868; /* biru tua */
    }
    .navbar-custom .nav-link,
    .navbar-custom .navbar-brand {
      color: #fff;
    }
    .navbar-custom .nav-link:hover {
      color: #d1d5db; /* abu hover */
    }
    .brand-subtitle {
      font-size: .8rem;
      display: block;
      line-height: 1;
    }
    h5 {
      color:#0b1d54; /* warna navy untuk judul */
      font-size:1.6rem;
    }
    p {
      color:#374151; /* abu gelap untuk teks */
    }
    .btn-primary {
      background:#0b1d54 !important; 
      border:none;
      font-weight:bold;
    }
    .btn-outline-primary {
      background:#e5e7eb !important;
      color:#0b1d54 !important;
      border:none;
      font-weight:bold;
    }
    footer {
      margin-top: auto;
      background: #001744;
      color: #fff;
      text-align: center;
      padding: 10px 0;
      font-size: .9rem;
    }
  </style>
</head>
<body>

@include('header')
<div class="container my-5 mt-5">
  <div class="row align-items-center">
    <div class="col-lg-5">
      <h5 class="fw-bold mb-3">Selamat Datang di Sistem Project Based Learning</h5>
      <p>
        Sistem ini dirancang untuk mendukung pengelolaan kegiatan 
        <b style="color:#0b1d54;">Project Based Learning (PBL)</b> pada
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
      <img src="{{ asset('assets/pbl2.png') }}" class="img-fluid rounded-3 shadow-sm" alt="Ilustrasi PBL">
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  © 2025 Sistem PBL - Jurusan Teknologi Informasi. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
