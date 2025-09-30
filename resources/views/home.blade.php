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
    footer {
      margin-top: auto; /* selalu di bawah */
      background: #002868;
      color: #fff;
      text-align: center;
      padding: 10px 0;
      font-size: .9rem;
    }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container-fluid px-4">
    <!-- kiri -->
    <a class="navbar-brand fw-bold text-uppercase" href="#">
      SISTEM INFORMASI MANAJEMEN PBL
    </a>

    <!-- toggler mobile -->
    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- kanan -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav fw-semibold">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Register</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Logbook</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Konten -->
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
        <a href="login.php" class="btn btn-primary px-4">Mulai Sekarang</a>
        <a href="#" class="btn btn-outline-primary px-4">Pelajari Lebih Lanjut</a>
      </div>
    </div>
    <div class="col-lg-6 text-center">
      <img src="gambar-ilustrasi.png" class="img-fluid rounded-3 shadow-sm" alt="Ilustrasi PBL">
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
