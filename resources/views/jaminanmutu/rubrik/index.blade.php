@extends('layouts.jaminanmutu')

@section('title','Rubrik — Jaminan Mutu')
@section('page_title','Rubrik')

@push('styles')
<style>
  .toolbar{display:flex;gap:12px;align-items:center;justify-content:space-between;flex-wrap:wrap;margin-bottom:14px}
  .filters{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
  .filters label{font-size:14px;color:#0b1d54;font-weight:700}
  .filters select,.filters input{
    padding:8px 12px;border:1px solid #d8dfeb;border-radius:8px;background:#fff;font-size:14px
  }
  .badge{background:#eef3fa;color:#0e257a;padding:8px 12px;border-radius:999px;font-weight:700;font-size:12px;display:inline-flex;gap:8px;align-items:center}
  .table-wrap{overflow:auto}
  table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;min-width:900px}
  th,td{padding:10px 12px;border-bottom:1px solid #eef1f6;text-align:left;vertical-align:middle}
  th{background:#eef3fa;font-size:12px;text-transform:uppercase}
  .muted{color:#6c7a8a;font-size:12px}
  .btn{display:inline-flex;gap:6px;align-items:center;padding:8px 12px;border-radius:8px;background:#eef3fa;color:#0e257a;text-decoration:none;font-weight:700}
</style>
@endpush

@section('content')

@php
  $matakuliah = $matakuliah ?? collect();
  $rubrik = $rubrik ?? collect();
@endphp

<div class="toolbar">
  <form method="GET" action="{{ route('jaminanmutu.rubrik.index') }}" class="filters">

    {{-- Dropdown MK (opsional, tampil kalau datanya ada) --}}
    @if($matakuliah->count())
      <label for="mk">Mata Kuliah:</label>
      <select id="mk" name="matakuliah" onchange="this.form.submit()">
        <option value="">Semua MK</option>
        @foreach($matakuliah as $mk)
          <option value="{{ $mk->kode_mk }}" @selected(request('matakuliah') == $mk->kode_mk)>
            {{ $mk->nama_mk }}
          </option>
        @endforeach
      </select>
    @endif

    {{-- Search --}}
    <label for="q">Cari:</label>
    <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Nama rubrik / deskripsi...">

    <button type="submit" class="btn" style="border:0;cursor:pointer;">
      <i class="fa-solid fa-magnifying-glass"></i> Filter
    </button>

    {{-- reset --}}
    @if(request()->filled('q') || request()->filled('matakuliah'))
      <a class="btn" href="{{ route('jaminanmutu.rubrik.index') }}">
        <i class="fa-solid fa-rotate-left"></i> Reset
      </a>
    @endif
  </form>

  <div class="badge">
  </div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th style="width:60px">No</th>
        <th>Nama Rubrik</th>
        <th style="width:110px">Bobot</th>
        <th style="width:170px">Dibuat</th>
        <th style="width:120px;text-align:center;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rubrik as $i => $r)
        <tr>
          {{-- ✅ tanpa paginator: pakai index + 1 --}}
          <td>{{ $i + 1 }}</td>

          <td>
            <b>{{ $r->nama_rubrik ?? ($r->nama ?? '-') }}</b><br>
            <span class="muted">{{ \Illuminate\Support\Str::limit($r->deskripsi ?? ($r->keterangan ?? ''), 90) }}</span>
          </td>

          <td>{{ (int)($r->bobot ?? 0) }}%</td>
          <td>{{ optional($r->created_at)->format('d/m/Y H:i') }}</td>

          <td style="text-align:center;">
            <a class="btn" href="{{ route('jaminanmutu.rubrik.show', $r->id) }}">
              <i class="fa-solid fa-eye"></i> Detail
            </a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="muted" style="text-align:center;padding:18px;">
            Data rubrik tidak ditemukan.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
