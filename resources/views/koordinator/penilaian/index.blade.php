@extends('layouts.koordinator')

@section('title', 'Penilaian — Koordinator')
@section('page_title', 'Penilaian')

@section('content')
<style>
  /* mirip dosenpenguji/penilaian */
  .card{
    background:#ffffff;
    border-radius:16px;
    border:1px solid rgba(13,23,84,.10);
    box-shadow:0 6px 20px rgba(13,23,84,.08);
  }
  .card-bd{ padding:16px 18px; }
  .card-ft{
    padding:12px 18px;
    border-top:1px solid #eef1f6;
    background:#fcfdff;
  }

  .toolbar{
    display:flex; align-items:center; justify-content:space-between;
    gap:12px; margin-bottom:8px; flex-wrap:wrap;
  }
  .filters{
    display:flex; align-items:center; gap:10px; flex-wrap:wrap;
  }
  .filters label{
    font-size:14px; color:#0b1d54; font-weight:700;
  }
  .filters select{
    padding:8px 12px; border:1px solid #d8dfeb; border-radius:8px;
    background:#fff; font-size:14px;
  }

  .badge-ro{
    display:inline-flex; align-items:center; gap:8px;
    padding:8px 12px; border-radius:999px;
    background:#eef3fa; color:#0e257a; font-weight:700; font-size:12px;
  }

  .table-wrap{ overflow:auto; }
  table{ width:100%; border-collapse:collapse; min-width:1000px; }
  th,td{
    padding:10px 12px; font-size:14px;
    border-bottom:1px solid #eef1f6; vertical-align:middle;
  }
  thead th{
    background:#eef3fa; color:#0b1d54; text-align:left;
    font-size:12px; text-transform:uppercase;
  }
  tbody tr:hover td{ background:#f9fbff; }

  .btn{
    border:0; padding:8px 16px; border-radius:8px;
    font-size:14px; font-weight:700; cursor:pointer;
    text-decoration:none; display:inline-flex; align-items:center; gap:6px;
  }
  .btn-secondary{ background:#eef3fa; color:#0e257a; }
  .btn-secondary:hover{ background:#e3eaf5; }

  .muted{ color:#6c7a8a; font-size:12px; }
</style>

@php
  $matakuliah = $matakuliah ?? collect();
  $kelasList  = $kelasList ?? collect();
  $penilaian  = $penilaian ?? collect();
@endphp

<div class="toolbar">
  {{-- FILTER --}}
  <form id="filter-form" method="GET" action="{{ route('koordinator.penilaian.index') }}" class="filters">
    <label for="filter-mk">Mata Kuliah:</label>
    <select id="filter-mk" name="matakuliah" onchange="this.form.submit()">
      <option value="">Pilih MK</option>
      @foreach($matakuliah as $mk)
        <option value="{{ $mk->kode_mk }}" @selected(request('matakuliah') == $mk->kode_mk)>
          {{ $mk->nama_mk }}
        </option>
      @endforeach
    </select>

    <label for="filter-kelas">Kelas:</label>
    <select id="filter-kelas" name="kelas" onchange="this.form.submit()">
      <option value="">Semua Kelas</option>
      @foreach($kelasList as $kls)
        <option value="{{ $kls->id }}" @selected((string)request('kelas') === (string)$kls->id)>
          {{ $kls->nama_kelas ?? ('Kelas #' . $kls->id) }}
        </option>
      @endforeach
    </select>
  </form>
</div>

<div class="card">
  <div class="card-bd">
    <div>
      <div style="font-weight:800;color:#0e257a">Data Penilaian</div>
    </div>

    <div class="table-wrap" style="margin-top:12px;">
      <table>
        <thead>
          <tr>
            <th style="width:60px">No</th>
            <th style="width:240px">Mahasiswa</th>
            <th style="width:260px">Mata Kuliah</th>
            <th style="width:200px">Kelas</th>
            <th style="width:130px">Nilai Akhir</th>
            <th style="width:200px">Dosen Penguji</th>
            <th style="width:140px;text-align:center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($penilaian as $i => $p)
            <tr>
              <td>{{ method_exists($penilaian,'firstItem') ? $penilaian->firstItem() + $i : $i+1 }}</td>

              <td>
                <strong>{{ optional($p->mahasiswa)->nama ?? '-' }}</strong><br>
                <span class="muted">{{ optional($p->mahasiswa)->nim ?? '-' }}</span>
              </td>

              <td>
                <strong>{{ optional($p->matakuliah)->nama_mk ?? '-' }}</strong><br>
                <span class="muted">Kode: {{ $p->matakuliah_kode ?? '-' }}</span>
              </td>

              <td>
                {{ optional($p->kelas)->nama_kelas ?? (optional($p->kelas)->kode_kelas ?? '-') }}
              </td>

              <td>
                <strong>{{ number_format((float)($p->nilai_akhir ?? 0), 2) }}</strong>
              </td>

              <td>
                {{ optional($p->dosen)->nama ?? (optional($p->dosen)->name ?? '-') }}
              </td>

              <td style="text-align:center;">
                <a class="btn btn-secondary" href="{{ route('koordinator.penilaian.show', $p->id) }}">
                  <i class="fa-solid fa-eye"></i> Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align:center;padding:18px;color:#6c7a8a;">
                Belum ada data penilaian.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if(method_exists($penilaian,'hasPages') && $penilaian->hasPages())
    <div class="card-ft">
      {{ $penilaian->links() }}
    </div>
  @endif
</div>
@endsection
