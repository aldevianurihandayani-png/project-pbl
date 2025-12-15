{{-- resources/views/koordinator/kelompok/index.blade.php --}}
@extends('layouts.koordinator')

@section('title', 'Kelompok — Koordinator')
@section('header', 'Kelompok — Koordinator')

@section('content')
<style>
  /* ===== STYLE KHUSUS HALAMAN KELOMPOK ===== */
  .title{
    font-weight:700;
    color:#0b1d54;
    margin:0;
  }

  .toolbar{
    display:flex;
    gap:12px;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
  }

  .filters{
    display:flex;
    gap:10px;
    align-items:center;
    flex-wrap:wrap;
  }

  .filters select,
  .filters input[type="search"]{
    padding:8px 10px;
    border:1px solid #cfd6e3;
    border-radius:8px;
    background:#fff;
    font-size:13px;
  }

  .filters input[type="search"]{
    min-width:260px;
  }

  .btn{
    border:none;
    border-radius:8px;
    padding:9px 14px;
    cursor:pointer;
    font-weight:700;
    font-size:14px;
    background:#0b1d54;
    color:#fff;
  }
  .btn:hover{ background:#142f85 }

  /* ==== GRID KARTU ==== */
  .group-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
    gap:16px;
    margin-top:16px;
  }

  .group-card{
    background:#fff;
    border:1px solid rgba(13,23,84,.10);
    border-radius:16px;
    box-shadow:0 6px 20px rgba(13,23,84,.08);
    padding:14px 16px 12px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
  }

  .group-card-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:10px;
    margin-bottom:8px;
  }

  .gc-label{
    font-size:11px;
    text-transform:uppercase;
    letter-spacing:.06em;
    color:#6c7a8a;
  }

  .gc-title{
    font-weight:700;
    color:#0b1d54;
    font-size:15px;
    max-width:210px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
  }

  .gc-badge{
    padding:4px 8px;
    border-radius:999px;
    background:#eef3ff;
    color:#1f3b95;
    font-size:12px;
    font-weight:700;
  }

  .group-card-body{
    display:grid;
    gap:6px;
    font-size:13px;
    color:#233042;
  }

  .gc-row{
    display:flex;
    gap:8px;
  }

  .gc-row-label{
    font-size:11px;
    text-transform:uppercase;
    color:#6c7a8a;
  }

  .btn-detail{
    margin-top:10px;
    display:block;
    width:100%;
    text-align:center;
    border-radius:999px;
    padding:7px 10px;
    border:1px solid #c7d2f3;
    background:#f4f6ff;
    color:#21409a;
    font-size:13px;
    font-weight:700;
    text-decoration:none;
  }

  .btn-detail:hover{
    background:#e6ebff;
  }
</style>

<div class="toolbar">
  <h2 class="title">Data Kelompok</h2>

  {{-- FILTER & SEARCH --}}
  <form class="filters" method="GET" action="{{ route('koordinator.kelompok') }}" id="filterForm">
    <select name="kelas" id="fKelas">
      <option value="">Semua Kelas</option>
      @foreach (['A','B','C','D','E'] as $k)
        <option value="{{ $k }}" {{ request('kelas')==$k?'selected':'' }}>
          {{ $k }}
        </option>
      @endforeach
    </select>

    <select name="semester" id="fSemester">
      <option value="">Semua Semester</option>
      @for ($i=1;$i<=6;$i++)
        <option value="{{ $i }}" {{ request('semester')==$i?'selected':'' }}>
          {{ $i }}
        </option>
      @endfor
    </select>

    <input type="search"
           name="search"
           value="{{ request('search') }}"
           placeholder="Cari nama kelompok / judul proyek…">

    <button class="btn" type="submit">Cari</button>
  </form>
</div>

{{-- DATA KELOMPOK --}}
@if($kelompoks->count())
  <div class="group-grid">
    @foreach($kelompoks as $k)
      <div class="group-card">
        <div class="group-card-header">
          <div>
            <div class="gc-label">Kelompok</div>
            <div class="gc-title">{{ $k->nama }}</div>
          </div>
          <div class="gc-badge">{{ $k->kelas }}</div>
        </div>

        <div class="group-card-body">
          <div>
            <div class="gc-row-label">Judul Proyek</div>
            {{ $k->judul_proyek }}
          </div>

          <div>
            <div class="gc-row-label">Dosen Pembimbing</div>
            {{ $k->dosenPembimbing->nama_dosen ?? '-' }}
          </div>

          <div>
            <div class="gc-row-label">Klien</div>
            {{ $k->nama_klien ?? '-' }}
          </div>
        </div>

        <a href="{{ route('koordinator.kelompok.detail', $k->id) }}"
           class="btn-detail">
          Lihat Detail
        </a>
      </div>
    @endforeach
  </div>
@else
  <div style="margin-top:20px; text-align:center; color:#6c7a93;">
    Tidak ada data kelompok.
  </div>
@endif

<script>
  document.getElementById('fKelas')?.addEventListener('change', () =>
    document.getElementById('filterForm').submit()
  );
  document.getElementById('fSemester')?.addEventListener('change', () =>
    document.getElementById('filterForm').submit()
  );
</script>
@endsection
