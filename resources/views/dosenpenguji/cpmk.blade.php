{{-- resources/views/dosenpenguji/cpmk.blade.php --}}
@extends('dosenpenguji.layout')
@section('title', 'CPMK — Dosen Penguji')

@section('content')
<div class="page-header">
  <h1 class="page-title">CPMK (Capaian Pembelajaran Mata Kuliah)</h1>
</div>

<div class="card">
  <div class="card-hd">
    <div>Filter</div>
    <div class="actions" style="display:flex;gap:8px">
      {{-- Tombol opsional untuk nanti CRUD CPMK --}}
      <a href="#" class="btn btn-secondary">Import</a>
      <a href="#" class="btn btn-primary">Tambah CPMK</a>
    </div>
  </div>

  <div class="card-bd">
    {{-- Dropdown Mata Kuliah --}}
    <form method="GET" action="{{ url('/dosenpenguji/cpmk') }}" class="row g-2 mb-3">
      <div class="col-auto d-flex align-items-center fw-semibold">Mata Kuliah:</div>
      <div class="col-md-4">
        <select name="matakuliah" class="form-select" onchange="this.form.submit()">
          <option value="">Pilih MK</option>
          @foreach(($matakuliah ?? collect()) as $mkrow)
            <option value="{{ $mkrow->kode_mk }}" @selected(($mk ?? '') === $mkrow->kode_mk)>
              {{ $mkrow->nama_mk }}
            </option>
          @endforeach
        </select>
      </div>
    </form>

    {{-- Tabel CPMK --}}
    @php $ada = isset($cpmk) && count($cpmk) > 0; @endphp

    @if ($mk)
      <div class="alert alert-light border d-inline-flex align-items-center gap-2 py-2 mb-3">
        <span class="badge bg-primary">MK:</span>
        <span class="fw-semibold">{{ ($matakuliah->firstWhere('kode_mk', $mk)->nama_mk ?? $mk) }}</span>
      </div>
    @endif

    @if ($ada)
      <div class="table-responsive mt-2" style="border:1px solid #eef1f6;border-radius:12px;overflow:hidden">
        <table class="table align-middle">
          <thead style="background:#f8fafc">
            <tr>
              <th style="min-width:160px">Kode</th>
              <th>Deskripsi CPMK</th>
              <th class="text-center" style="width:120px">Bobot (%)</th>
              <th class="text-center" style="width:120px">Urutan</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cpmk as $row)
              <tr style="border-top:1px solid #f0f2f7">
                <td><span class="badge bg-secondary-subtle text-secondary">{{ $row->kode }}</span></td>
                <td>{{ $row->deskripsi }}</td>
                <td class="text-center">{{ $row->bobot }}</td>
                <td class="text-center">{{ $row->urutan }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div style="display:flex;align-items:center;gap:10px;color:#6c7a8a;background:#f7f9ff;border:1px dashed #d9e3ff;border-radius:12px;padding:14px">
        <i class="fa-regular fa-circle-question"></i>
        <div>
          <div style="font-weight:700;color:#0e257a">Belum ada data CPMK</div>
          <div>Pilih Mata Kuliah untuk menampilkan daftar CPMK.</div>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
