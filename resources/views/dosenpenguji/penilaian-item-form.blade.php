@extends('dosenpenguji.layout')

@php
  // Buat judul utama dinamis
  $pageTitle = ($mode === 'create' ? 'Tambah Nilai' : 'Edit Nilai');

  // Tambah nama MK jika tersedia di query
  $mkName = null;
  if (!empty($mk ?? null)) {
      $mkName = optional(collect($matakuliah ?? [])->firstWhere('kode_mk', $mk))->nama_mk;
  }

  // ==========================================================
  // ✅ FIX: action form aman & TIDAK hitung route update saat create
  // ==========================================================
  $qs = http_build_query(array_filter([
    'matakuliah' => request('matakuliah', $mk),
    'kelas'      => request('kelas', $kelas ?? null),
  ]));

  // STORE action
  if (\Illuminate\Support\Facades\Route::has('dosenpenguji.penilaian.item.store')) {
      $storeAction = route('dosenpenguji.penilaian.item.store') . ($qs ? ('?'.$qs) : '');
  } else {
      $storeAction = url('/dosenpenguji/penilaian-item') . ($qs ? ('?'.$qs) : '');
  }

  // Default destroy action (hanya dipakai di edit)
  $destroyAction = null;

  // Tentukan action sesuai mode
  if (($mode ?? 'create') === 'edit') {
      // UPDATE action (hanya untuk edit)
      if (\Illuminate\Support\Facades\Route::has('dosenpenguji.penilaian.item.update')) {
          // ⚠️ beberapa route pakai parameter {item} bukan {id}
          // route() tetap menerima value saja
          $updateAction = route('dosenpenguji.penilaian.item.update', $item->id) . ($qs ? ('?'.$qs) : '');
      } else {
          $updateAction = url('/dosenpenguji/penilaian-item/' . $item->id) . ($qs ? ('?'.$qs) : '');
      }

      // DESTROY action (hanya untuk edit)
      if (\Illuminate\Support\Facades\Route::has('dosenpenguji.penilaian.item.destroy')) {
          $destroyAction = route('dosenpenguji.penilaian.item.destroy', $item->id) . ($qs ? ('?'.$qs) : '');
      } else {
          $destroyAction = url('/dosenpenguji/penilaian-item/' . $item->id) . ($qs ? ('?'.$qs) : '');
      }

      $action = $updateAction;
  } else {
      $action = $storeAction;
  }

  // tombol batal/kembali
  $backUrl = \Illuminate\Support\Facades\Route::has('dosenpenguji.penilaian')
      ? route('dosenpenguji.penilaian', request()->only('matakuliah','kelas'))
      : url('/dosenpenguji/penilaian') . ($qs ? ('?'.$qs) : '');
@endphp

@section('title', $pageTitle . ' — Dosen Penguji')

@section('content')
<div class="page-header">
  <h1 class="page-title" style="font-size:20px; font-weight:700; margin-bottom:2px;">
    {{ $pageTitle }} @if($mkName) — {{ $mkName }} @endif
  </h1>
  <p class="text-muted" style="margin-top:0; font-size:14px;">
    Kelola nilai mahasiswa berdasarkan rubrik penilaian dosen penguji.
  </p>
</div>

@if ($errors->any())
  <div class="alert alert-danger" style="background:#fff5f5;border:1px solid #ffd6d6;color:#8a1f1f;border-radius:10px;padding:10px 12px;margin-bottom:12px">
    <strong>Periksa kembali:</strong>
    <ul style="margin:6px 0 0 18px">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card">
  <div class="card-hd">{{ $pageTitle }}</div>
  <div class="card-bd">

    <form action="{{ $action }}" method="POST" style="max-width:720px">
      @csrf
      @if($mode==='edit') @method('PUT') @endif

      <div class="form-group">
        <label>Mata Kuliah</label>
        <select name="dummy_mk" class="form-control" onchange="syncRubrikByMk(this.value)">
          <option value="">Pilih MK</option>
          @foreach($matakuliah as $mkrow)
            <option value="{{ $mkrow->kode_mk }}" @selected(($mk ?? '')===$mkrow->kode_mk)>
              {{ $mkrow->nama_mk }}
            </option>
          @endforeach
        </select>
        <small class="text-muted">Hanya untuk membantu filter rubrik di bawah.</small>
      </div>

      <div class="form-group">
        <label>Mahasiswa</label>
        <select name="mahasiswa_nim" class="form-control" required>
          <option value="">Pilih Mahasiswa</option>
          @foreach($mhs as $m)
            @php $kelasSafe = property_exists($m, 'kelas') ? $m->kelas : null; @endphp
            <option value="{{ $m->nim }}" @selected(old('mahasiswa_nim',$item->mahasiswa_nim ?? '')==$m->nim)>
              {{ $m->nim }} — {{ $m->nama }}
              @if(!empty($kelasSafe)) ({{ $kelasSafe }}) @endif
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Komponen Rubrik</label>
        <select name="rubrik_id" id="rubrikSelect" class="form-control" required>
          <option value="">Pilih Komponen</option>
          @foreach($rubriks as $r)
            <option value="{{ $r->id }}" @selected(old('rubrik_id',$item->rubrik_id ?? '')==$r->id)>
              {{ $r->nama_rubrik }} ({{ $r->bobot }}%)
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Nilai (0–100)</label>
        <input type="number" name="nilai" class="form-control" min="0" max="100"
               value="{{ old('nilai', $item->nilai) }}" placeholder="opsional">
      </div>

      <div style="display:flex;gap:10px;margin-top:12px">
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> {{ $mode==='create'?'Simpan':'Update' }}
        </button>
        <a href="{{ $backUrl }}" class="btn btn-secondary">Batal</a>
      </div>
    </form>

    @if($mode==='edit')
      <form action="{{ $destroyAction }}" method="POST" onsubmit="return confirm('Hapus nilai ini?')" style="margin-top:10px">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger">
          <i class="fa-solid fa-trash"></i> Hapus
        </button>
      </form>
    @endif
  </div>
</div>

<script>
  function syncRubrikByMk(kode) {
    const url = new URL(window.location.href);
    if (kode) url.searchParams.set('matakuliah', kode);
    else url.searchParams.delete('matakuliah');
    window.location.href = url.toString();
  }
</script>
@endsection
