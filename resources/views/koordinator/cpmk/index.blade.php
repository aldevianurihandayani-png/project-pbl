@extends('layouts.koordinator')

@section('title', 'CPMK — Koordinator')
@section('page_title', 'CPMK')

@section('content')
<style>
  .card{ background:#fff;border-radius:16px;border:1px solid rgba(13,23,84,.10);box-shadow:0 6px 20px rgba(13,23,84,.08); }
  .card-bd{ padding:16px 18px; }
  .table-wrap{ overflow:auto; }
  table{ width:100%; border-collapse:collapse; min-width:900px; }
  th,td{ padding:10px 12px; font-size:14px; border-bottom:1px solid #eef1f6; }
  thead th{ background:#eef3fa; color:#0b1d54; text-align:left; font-size:12px; text-transform:uppercase; }
  tbody tr:hover td{ background:#f9fbff; }
  .btn{ border:0;padding:8px 14px;border-radius:8px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;}
  .btn-secondary{ background:#eef3fa; color:#0e257a; }
  .btn-secondary:hover{ background:#e3eaf5; }
  .muted{ color:#6c7a8a; font-size:12px; }
  .badge-ro{ display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#eef3fa; color:#0e257a; font-weight:700; font-size:12px; }
  .toolbar{ display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:12px; }

  /* tambahan kecil buat filter */
  .filters{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
  .filters label{font-size:14px;color:#0b1d54;font-weight:700}
  .filters select,.filters input{
    padding:8px 12px;border:1px solid #d8dfeb;border-radius:8px;background:#fff;font-size:14px;
  }
</style>

@php
  $matakuliah = $matakuliah ?? collect();
  $cpmk = $cpmk ?? collect(); // biar aman kalau belum kelempar dari controller
@endphp

<div class="toolbar">
  <div>
    <div style="font-weight:900;color:#0e257a">Daftar CPMK</div>
    <div class="muted">Gunakan filter untuk menyaring data CPMK.</div>
  </div>

  {{-- ✅ FILTER (MK + Search) --}}
  <form method="GET" action="{{ route('koordinator.cpmk.index') }}" class="filters">
    <label for="filter-mk">Mata Kuliah:</label>
    <select id="filter-mk" name="matakuliah" onchange="this.form.submit()">
      <option value="">Semua MK</option>
      @foreach($matakuliah as $mk)
        <option value="{{ $mk->kode_mk }}" @selected(request('matakuliah') == $mk->kode_mk)>
          {{ $mk->nama_mk }}
        </option>
      @endforeach
    </select>

    <label for="filter-q">Cari:</label>
    <input id="filter-q"
           type="text"
           name="q"
           value="{{ request('q') }}"
           placeholder="Kode / deskripsi...">

    <button type="submit" class="btn btn-secondary">
      <i class="fa-solid fa-magnifying-glass"></i> Filter
    </button>

    @if(request()->filled('q') || request()->filled('matakuliah'))
      <a href="{{ route('koordinator.cpmk.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-rotate-left"></i> Reset
      </a>
    @endif
  </form>
</div>

<div class="card">
  <div class="card-bd">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:70px">No</th>
            <th>Kode</th>
            <th>Deskripsi</th>
            <th style="width:160px">Dibuat</th>
            <th style="width:140px;text-align:center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($cpmk as $i => $row)
            <tr>
              {{-- ✅ TANPA PAGINATION: nomor pakai index --}}
              <td>{{ $i + 1 }}</td>

              <td>
                <strong>{{ $row->kode_cpmk ?? ($row->kode ?? '-') }}</strong>
              </td>

              <td>
                {{ $row->deskripsi ?? $row->uraian ?? '-' }}
              </td>

              <td>{{ optional($row->created_at)->format('d/m/Y H:i') }}</td>

              <td style="text-align:center;">
                <a class="btn btn-secondary" href="{{ route('koordinator.cpmk.show', $row->id) }}">
                  <i class="fa-solid fa-eye"></i> Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align:center;padding:18px;color:#6c7a8a;">
                Belum ada data CPMK.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- ✅ TANPA PAGINATION: blok links dihapus --}}
  </div>
</div>
@endsection
