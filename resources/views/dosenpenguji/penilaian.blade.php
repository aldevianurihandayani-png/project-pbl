{{-- resources/views/dosenpenguji/penilaian.blade.php --}}
@extends('dosenpenguji.layout')

@section('title', 'Penilaian — Dosen Penguji')
@section('header', 'Penilaian')

@section('content')
<style>
  .card{background:#fff;border-radius:16px;border:1px solid rgba(13,23,84,.10);box-shadow:0 6px 20px rgba(13,23,84,.08);}
  .card-bd{padding:16px 18px;}
  .card-ft{padding:12px 18px;border-top:1px solid #eef1f6;background:#fcfdff;}

  .toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:8px;flex-wrap:wrap;}
  .filters{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
  .filters label{font-size:14px;color:#0b1d54;font-weight:700;}
  .filters select{padding:8px 12px;border:1px solid #d8dfeb;border-radius:8px;background:#fff;font-size:14px;}

  .btn{border:0;padding:8px 16px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;}
  .btn-secondary{background:#eef3fa;color:#0e257a;}
  .btn-success{background:#00b167;color:#fff;}
  .btn-warning{background:#ff8c00;color:#fff;}

  table{width:100%;border-collapse:collapse;min-width:1000px;}
  th,td{padding:10px 12px;font-size:14px;border-bottom:1px solid #eef1f6;}
  thead th{background:#eef3fa;color:#0b1d54;font-size:12px;text-transform:uppercase;}

  .grade-input{width:60px;padding:6px 8px;border:1px solid #d8dfeb;border-radius:6px;text-align:center;}
  .final-grade{font-weight:700;color:#0e257a;}
</style>

@php
  $mkSelected    = request('matakuliah');
  $kelasSelected = request('kelas');

  $rubCount = isset($rubrics) ? $rubrics->count() : 0;

  $mhsCount = 0;
  if (isset($mahasiswa)) {
    $mhsCount = ($mahasiswa instanceof \Illuminate\Contracts\Pagination\Paginator)
      ? $mahasiswa->total()
      : $mahasiswa->count();
  }

  $totalBobot = 0;
  foreach(($rubrics ?? collect()) as $rb) $totalBobot += (int)$rb->bobot;

  $kelasOptions = ($kelasList ?? collect(['A','B','C','D','E']));
  if (!($kelasOptions instanceof \Illuminate\Support\Collection)) {
    $kelasOptions = collect($kelasOptions);
  }

  $colspan = 1 + ($rubCount > 0 ? $rubCount : 1) + 1;
@endphp

<div class="toolbar">
  <form method="GET" action="{{ route('dosenpenguji.penilaian') }}" class="filters">
    <label>Mata Kuliah</label>
    <select name="matakuliah" onchange="this.form.submit()">
      <option value="">Pilih MK</option>
      @foreach($matakuliah as $mk)
        <option value="{{ $mk->kode_mk }}" @selected($mkSelected==$mk->kode_mk)>
          {{ $mk->nama_mk }}
        </option>
      @endforeach
    </select>

    <label>Kelas</label>
    <select name="kelas" onchange="this.form.submit()">
      <option value="">Semua Kelas</option>
      @foreach($kelasOptions as $kls)
        <option value="{{ $kls }}" @selected($kelasSelected==$kls)>
          Kelas {{ $kls }}
        </option>
      @endforeach
    </select>
  </form>

  <div style="display:flex;gap:10px;">
    <a class="btn btn-secondary" href="{{ route('dosenpenguji.penilaian.export.excel', request()->only('matakuliah','kelas')) }}">Export Excel</a>
    <a class="btn btn-secondary" href="{{ route('dosenpenguji.penilaian.export.pdf', request()->only('matakuliah','kelas')) }}">Export PDF</a>
    <a class="btn btn-warning" href="{{ route('dosenpenguji.penilaian.item.create', request()->query()) }}">Tambah Nilai</a>
    <button class="btn btn-success" type="submit" form="grade-form">Simpan Semua</button>
  </div>
</div>

<form id="grade-form" method="POST" action="{{ route('dosenpenguji.penilaian.bulkSave') }}">
@csrf
<input type="hidden" name="matakuliah" value="{{ $mkSelected }}">
<input type="hidden" name="kelas" value="{{ $kelasSelected }}">

<div class="card">
<table>
<thead>
<tr>
  <th>Mahasiswa</th>
  @forelse($rubrics as $r)
    <th>{{ $r->nama_rubrik }} ({{ $r->bobot }}%)</th>
  @empty
    <th>Komponen Penilaian Belum Ada</th>
  @endforelse
  <th>Nilai Akhir</th>
</tr>
</thead>

<tbody>
@if(empty($mkSelected))
<tr><td colspan="{{ $colspan }}" style="text-align:center;padding:20px;">Pilih Mata Kuliah</td></tr>
@elseif($mhsCount===0)
<tr><td colspan="{{ $colspan }}" style="text-align:center;padding:20px;">Mahasiswa tidak ditemukan</td></tr>
@else
@foreach($mahasiswa as $m)
<tr>
  <td><b>{{ $m->nama }}</b><br><small>{{ $m->nim }} {{ $m->kelas ?? '' }}</small></td>
  @forelse($rubrics as $r)
    <td>
      <input class="grade-input" type="number"
        name="nilai[{{ $m->nim }}][{{ $r->id }}]"
        value="{{ optional($m->penilaian->firstWhere('rubrik_id',$r->id))->nilai }}">
    </td>
  @empty
    <td>-</td>
  @endforelse
  <td class="final-grade">0.00</td>
</tr>
@endforeach
@endif
</tbody>
</table>

<div class="card-ft">
  Total Bobot: <b>{{ $totalBobot }}</b>%
</div>
</div>
</form>
@endsection
