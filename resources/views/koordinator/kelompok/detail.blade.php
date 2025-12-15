{{-- resources/views/koordinator/kelompok/detail.blade.php --}}
@extends('layouts.koordinator')

@section('title', 'Detail Kelompok — Koordinator')
@section('page_title', 'Detail Kelompok')

@section('content')
<div class="card">

  {{-- ===== HEADER ===== --}}
  <div class="card-hd">
    <div>
      <i class="fa-solid fa-users"></i>
      Detail Kelompok:
      <strong>{{ $kelompok->nama ?? '-' }}</strong>
    </div>
  </div>

  {{-- ===== BODY ===== --}}
  <div class="card-bd" style="font-size:15px;">

    {{-- ===== GRID INFO ===== --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:25px;">

      {{-- INFORMASI UTAMA --}}
      <div>
        <h3 style="margin:0 0 8px; font-size:17px; color:#0b1d54;">Informasi Utama</h3>
        <p><strong>Nama Kelompok:</strong> {{ $kelompok->nama ?? '-' }}</p>
        <p><strong>Kelas:</strong> {{ $kelompok->kelas ?? '-' }}</p>
        <p><strong>Semester:</strong> {{ $kelompok->semester ?? '-' }}</p>
        <p><strong>Angkatan:</strong> {{ optional($kelompok->ketua)->angkatan ?? '-' }}</p>
      </div>

      {{-- KETUA & PEMBIMBING --}}
      <div>
        <h3 style="margin:0 0 8px; font-size:17px; color:#0b1d54;">Ketua & Pembimbing</h3>
        <p><strong>Ketua Kelompok:</strong> {{ $kelompok->ketua_kelompok ?? '-' }}</p>
        <p><strong>NIM Ketua:</strong> {{ optional($kelompok->ketua)->nim ?? '-' }}</p>

        <p>
          <strong>Dosen Pembimbing:</strong>
          {{ optional($kelompok->dosenPembimbing)->nama_dosen
              ?? optional($kelompok->dosenPembimbing)->name
              ?? '-' }}
        </p>

        <p>
          <strong>Dosen Penguji:</strong>
          @if($kelompok->penguji && $kelompok->penguji->count())
            @foreach($kelompok->penguji as $p)
              {{ $p->nama ?? $p->name }}@if(!$loop->last), @endif
            @endforeach
          @else
            -
          @endif
        </p>
      </div>

    </div>

    {{-- ===== JUDUL PROYEK ===== --}}
    <div style="margin-bottom:25px;">
      <h3 style="margin:0 0 10px; font-size:17px; color:#0b1d54;">Judul Proyek</h3>
      <p style="background:#f4f6ff; padding:12px; border-radius:8px; border:1px solid #d8e0f0;">
        {{ $kelompok->judul_proyek
            ?? optional($kelompok->proyekPbl)->judul
            ?? '-' }}
      </p>
    </div>

    {{-- ===== DAFTAR ANGGOTA ===== --}}
    <div style="margin-bottom:25px;">
      <h3 style="margin:0 0 10px; font-size:17px; color:#0b1d54;">Daftar Anggota</h3>

      @if($kelompok->mahasiswas && $kelompok->mahasiswas->count())
        <table class="table" style="width:100%; border-collapse:collapse;">
          <thead>
            <tr style="background:#0b1d54; color:white;">
              <th style="padding:10px;">NIM</th>
              <th style="padding:10px;">Nama</th>
              <th style="padding:10px;">Angkatan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($kelompok->mahasiswas as $mhs)
              <tr style="border-bottom:1px solid #eef1f6;">
                <td style="padding:10px;">{{ $mhs->nim }}</td>
                <td style="padding:10px;">{{ $mhs->nama }}</td>
                <td style="padding:10px;">{{ $mhs->angkatan }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <p style="color:#6c7a8a;">Belum ada data anggota.</p>
      @endif
    </div>

    {{-- ===== KLIEN ===== --}}
    <div style="margin-bottom:25px;">
      <h3 style="margin:0 0 10px; font-size:17px; color:#0b1d54;">Klien</h3>
      <p><strong>Nama Klien:</strong> {{ $kelompok->nama_klien ?? '-' }}</p>
    </div>

    {{-- ===== KEMBALI ===== --}}
    <div style="margin-top:20px;">
      <a href="{{ route('koordinator.kelompok') }}" class="btn btn-secondary">
        ← Kembali ke daftar kelompok
      </a>
    </div>

  </div>
</div>
@endsection
