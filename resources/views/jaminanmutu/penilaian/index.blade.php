{{-- resources/views/jaminanmutu/penilaian/index.blade.php --}}
@extends('layouts.jaminanmutu')

@section('title','Penilaian — Jaminan Mutu')
@section('page_title','Penilaian')

@push('styles')
<style>
  .toolbar{display:flex;gap:12px;align-items:center;justify-content:space-between;flex-wrap:wrap;margin-bottom:14px}
  .filters{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
  select{padding:8px 12px;border:1px solid #d8dfeb;border-radius:8px;background:#fff}
  .badge-ro{background:#eef3fa;color:#0e257a;padding:8px 12px;border-radius:999px;font-weight:700;font-size:12px;display:inline-flex;gap:8px;align-items:center}
  table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden}
  th,td{padding:10px 12px;border-bottom:1px solid #eef1f6;text-align:left}
  th{background:#eef3fa;font-size:12px;text-transform:uppercase}
  .muted{color:#6c7a8a;font-size:12px}
  .btn{display:inline-flex;gap:6px;align-items:center;padding:8px 12px;border-radius:8px;background:#eef3fa;color:#0e257a;text-decoration:none;font-weight:700}
</style>
@endpush

@section('content')

  <div class="toolbar">
    <form method="GET" action="{{ route('jaminanmutu.penilaian.index') }}" class="filters">
      <label>Mata Kuliah:</label>
      <select name="matakuliah" onchange="this.form.submit()">
        <option value="">Pilih MK</option>
        @foreach($matakuliah as $mk)
          <option value="{{ $mk->kode_mk }}" @selected(request('matakuliah') == $mk->kode_mk)>
            {{ $mk->nama_mk }}
          </option>
        @endforeach
      </select>

      <label>Kelas:</label>
      <select name="kelas" onchange="this.form.submit()">
        <option value="">Semua Kelas</option>
        @foreach($kelasList as $k)
          <option value="{{ $k->id }}" @selected((string)request('kelas') === (string)$k->id)>
            {{ $k->nama_kelas }}
          </option>
        @endforeach
      </select>
    </form>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:60px">No</th>
        <th>Mahasiswa</th>
        <th>Mata Kuliah</th>
        <th style="width:140px">Kelas</th>
        <th style="width:120px">Nilai Akhir</th>
        <th style="width:180px">Dosen</th>
        <th style="width:120px">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($penilaian as $i => $p)
        <tr>
          <td>{{ method_exists($penilaian,'firstItem') ? $penilaian->firstItem() + $i : $i + 1 }}</td>

          <td>
            <b>{{ optional($p->mahasiswa)->nama ?? '-' }}</b><br>
            <span class="muted">{{ optional($p->mahasiswa)->nim ?? '-' }}</span>
          </td>

          <td>
            <b>{{ optional($p->matakuliah)->nama_mk ?? '-' }}</b><br>
            <span class="muted">Kode: {{ $p->matakuliah_kode ?? '-' }}</span>
          </td>

          <td>{{ optional($p->kelas)->nama_kelas ?? '-' }}</td>

          <td><b>{{ number_format((float)($p->nilai_akhir ?? 0), 2) }}</b></td>

          <td>{{ optional($p->dosen)->name ?? '-' }}</td>

          <td>
            <a class="btn" href="{{ route('jaminanmutu.penilaian.show', $p->id) }}">
              <i class="fa-solid fa-eye"></i> Detail
            </a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="muted" style="text-align:center;padding:18px;">
            Belum ada data penilaian.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:12px;">
    @if(method_exists($penilaian,'links'))
      {{ $penilaian->links() }}
    @endif
  </div>

@endsection
