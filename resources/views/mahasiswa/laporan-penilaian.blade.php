@extends('layouts.mahasiswa')

@section('title', 'Laporan Penilaian — Mahasiswa')
@section('page_title', 'Laporan Penilaian')

@section('content')
<section class="card">
  <div class="card-hd">
    <i class="fa-solid fa-file-lines"></i> Laporan Penilaian
  </div>

  <div class="card-bd">

    {{-- AMAN: cek variabel ADA atau TIDAK --}}
    @if(!isset($nilai) || $nilai->count() === 0)
      <div class="muted">
        Belum ada penilaian dari dosen penguji.
      </div>
    @else
      <table class="table">
        <thead>
          <tr>
            <th>Komponen</th>
            <th>Bobot</th>
            <th>Skor</th>
            <th>Nilai</th>
          </tr>
        </thead>

        <tbody>
          @foreach($nilai as $n)
            <tr>
              <td>{{ $n->komponen }}</td>
              <td>{{ $n->bobot }}%</td>
              <td>{{ $n->skor }}</td>
              <td>{{ round(($n->bobot / 100) * $n->skor, 2) }}</td>
            </tr>
          @endforeach
        </tbody>

        <tfoot>
          <tr>
            <th colspan="3" class="text-end">Total</th>
            <th>{{ $nilaiAkhir ?? '-' }}</th>
          </tr>
        </tfoot>
      </table>

      <p class="muted mt-2">
        * Data bersumber dari penilaian dosen penguji (read-only).
      </p>
    @endif

  </div>
</section>
@endsection
