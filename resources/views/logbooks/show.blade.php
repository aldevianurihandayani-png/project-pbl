{{-- resources/views/logbooks/show.blade.php --}}
@extends('layouts.mahasiswa')

@section('title', 'Detail Logbook')

@section('content')
<div class="container my-5">
  <h1 class="mb-4">Detail Logbook</h1>

  <div class="detail-card">
    <div class="detail-card__header">
      <span>Informasi Logbook</span>
    </div>

   <div class="detail-card__body">
  <div class="detail-row">
    <div class="detail-label">Tanggal</div>
    <div class="detail-value">
      {{ \Carbon\Carbon::parse($logbook->tanggal)->format('d-m-Y') }}
    </div>
  </div>


      <div class="detail-row">
        <div class="detail-label">Aktivitas</div>
        <div class="detail-value">{{ $logbook->aktivitas }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Keterangan</div>
        <div class="detail-value">{{ $logbook->keterangan }}</div>
      </div>

     @if($logbook->foto)
    <p><strong>Dokumentasi:</strong></p>
    <img src="{{ asset('storage/' . $logbook->foto) }}" 
         alt="Foto Logbook" 
         class="logbook-foto">
@endif

    </div>
  </div>

  <a href="{{ route('logbooks.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
