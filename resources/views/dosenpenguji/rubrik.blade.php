{{-- resources/views/dosenpenguji/rubrik.blade.php --}}
@extends('dosenpenguji.layout')
@section('title', 'Rubrik — Dosen Penguji')
@section('header', 'Rubrik Penilaian')

@section('content')
<style>
  .filters{display:flex; gap:10px; align-items:center; flex-wrap:wrap}
  .chip{display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; background:#eef2ff; color:#22336b; font-weight:700; font-size:13px}
  .empty{display:flex; align-items:center; gap:12px; padding:14px 16px; color:#667085; background:#f8fafc; border:1px dashed #e5e7eb; border-radius:12px}
  .table th, .table td{padding:10px 12px; border-bottom:1px solid #eef1f6}
  .table th{color:#0e257a; font-weight:800; background:#f6f8fd}
  .right{text-align:right} .center{text-align:center}
</style>

<div class="card">
  <div class="card-hd">
    <div class="filters">
      <div class="fw-semibold" style="min-width:92px">Mata Kuliah:</div>
      <form method="GET" action="{{ route('dosenpenguji.rubrik.index') }}" class="filters">
        <select name="matakuliah" class="form-control" style="min-width:220px" onchange="this.form.submit()">
          <option value="">Pilih MK</option>
          @foreach(($matakuliah ?? collect()) as $mkrow)
            <option value="{{ $mkrow->kode_mk }}" @selected(($mk ?? '') === $mkrow->kode_mk)>{{ $mkrow->nama_mk }}</option>
          @endforeach
        </select>
        @if(!empty($mk))
          <span class="chip"><i class="fa-solid fa-book"></i> {{ $matakuliah->firstWhere('kode_mk',$mk)->nama_mk ?? $mk }}</span>
        @endif
      </form>
    </div>

    @php
      $totalBobot = ($rubriks ?? collect())->sum('bobot');
    @endphp
    <div class="chip" title="Total bobot semua komponen">
      <i class="fa-solid fa-percent"></i> Total Bobot: {{ number_format($totalBobot,0) }}%
    </div>
  </div>

  <div class="card-bd">
    @if(isset($rubriks) && $rubriks->count())
      <div class="table-responsive">
        <table class="table align-middle" style="width:100%">
          <thead>
            <tr>
              <th style="width:50%">Nama Komponen</th>
              <th class="center" style="width:15%">Bobot (%)</th>
              <th class="center" style="width:15%">Urutan</th>
              <th class="right"  style="width:20%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($rubriks as $r)
              <tr>
                <td>
                  <strong>{{ $r->nama_rubrik }}</strong>
                  @if(!empty($r->deskripsi))
                    <div style="color:#6b7280; font-size:13px">{{ $r->deskripsi }}</div>
                  @endif
                </td>
                <td class="center">{{ $r->bobot }}</td>
                <td class="center">{{ $r->urutan }}</td>
                <td class="right">
                  {{-- placeholder tombol, aktifkan kalau sudah ada route edit/destroy --}}
                  <a class="btn btn-secondary" href="javascript:void(0)"><i class="fa-solid fa-pen"></i> Edit</a>
                  <a class="btn btn-primary"   href="javascript:void(0)"><i class="fa-solid fa-eye"></i> Detail</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if (method_exists($rubriks, 'hasPages') && $rubriks->hasPages())
        <div class="mt-2">{{ $rubriks->links() }}</div>
      @endif
    @else
      <div class="empty">
        <i class="fa-solid fa-circle-info"></i>
        <div>
          <div class="fw-bold">Belum ada data rubrik</div>
          <div style="font-size:13px">Pilih Mata Kuliah terlebih dahulu untuk menampilkan komponen penilaian.</div>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
