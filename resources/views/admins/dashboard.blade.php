@extends('layouts.admin')

@section('title','Dashboard — Admin')
@section('page_title','Dashboard Admin')

@section('content')
  {{-- KPI --}}
  <section class="kpi">
    <a href="{{ route('admins.users.index') }}" class="card-link">
      <div class="card">
        <div class="icon"><i class="fa-solid fa-user-gear"></i></div>
        <div class="meta"><small>Jumlah Akun</small><br><b>{{ $jumlahUsers ?? 0 }}</b></div>
      </div>
    </a>
    <a href="{{ route('admins.logbook.index') }}" class="card-link">
      <div class="card">
        <div class="icon"><i class="fa-solid fa-book"></i></div>
        <div class="meta"><small>Logbook</small><br><b>{{ $jumlahLogbook ?? 5 }}</b></div>
      </div>
    </a>
    <a href="{{ route('admins.mahasiswa.index') }}" class="card-link">
      <div class="card">
        <div class="icon"><i class="fa-solid fa-user-graduate"></i></div>
        <div class="meta"><small>Mahasiswa</small><br><b>{{ $jumlahMahasiswa ?? 100 }}</b></div>
      </div>
    </a>
  </section>

  {{-- Status Logbook --}}
  <section class="card">
    <div class="card-hd"><i class="fa-solid fa-clipboard-check"></i> Status Logbook</div>
    <div class="card-bd">
      Logbook terakhir mahasiswa Anda telah <strong>Disetujui</strong>.<br>
      <span class="muted">Terakhir diperbarui: 2 Oktober 2025</span>
    </div>
  </section>

  {{-- Milestone (fix kurung kurawal nyasar) --}}
  <section class="card">
    <div class="card-hd"><i class="fa-solid fa-flag"></i> Milestone</div>
    <div class="card-bd">
      Deadline milestone berikutnya: <strong>10 Oktober 2025</strong>.
    </div>
  </section>

  {{-- Nilai & Peringkat --}}
  <section class="card">
    <div class="card-hd"><i class="fa-solid fa-star"></i> Nilai & Peringkat</div>
    <div class="card-bd">
      Nilai TPK: 85, Pemweb Lanjut: 90, Integrasi Sistem: 88, Sistem Operasi: 80. <br/>
      Peringkat: <strong>Top 5</strong> dalam kelas.
    </div>
  </section>

  {{-- Notifikasi --}}
  <section class="card" style="margin-bottom:28px">
    <div class="card-hd"><i class="fa-regular fa-bell"></i> Notifikasi</div>
    <div class="card-bd">
      <ul class="clean">
        <li>Logbook Minggu 3 disetujui</li>
        <li>Milestone Presentasi Final 7 hari lagi</li>
        <li>Dosen pembimbing menambahkan nilai baru</li>
      </ul>
    </div>
  </section>
@endsection
