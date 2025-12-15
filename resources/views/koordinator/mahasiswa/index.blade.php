@extends('layouts.koordinator')

@section('title', 'Data Mahasiswa — Koordinator')
@section('header', 'Data Mahasiswa')

@section('content')
<style>
/* ===============================
   HALAMAN MAHASISWA KOORDINATOR
   (Disamakan dengan Admin)
================================ */

.page-title{
  font-size:15px;
  font-weight:800;
  color:#0b1d54;
  margin-bottom:14px;
}

/* ===== FILTER BAR ===== */
.toolbar{
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  align-items:flex-end;
  margin-bottom:22px;
}
.filter-group{
  display:flex;
  flex-direction:column;
  gap:5px;
  min-width:160px;
}
.filter-group label{
  font-size:11px;
  font-weight:700;
  color:#6c7a8a;
  letter-spacing:.4px;
  text-transform:uppercase;
}
.filter-group select,
.filter-group input{
  padding:8px 12px;
  border-radius:999px;
  border:1px solid #d8dfeb;
  font-size:13px;
  background:#fff;
}
.filter-group input{
  min-width:260px;
}
.toolbar button{
  padding:9px 22px;
  border-radius:999px;
  border:0;
  background:#1f5bd9;
  color:#fff;
  font-size:13px;
  font-weight:700;
  box-shadow:0 4px 12px rgba(31,91,217,.35);
}
.toolbar button:hover{
  filter:brightness(.95);
}

/* ===== GRID KELAS ===== */
.kelas-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:20px;
}
.kelas-card{
  border:1px solid #e1e7ff;
  border-radius:18px;
  padding:18px 18px 16px;
  background:#ffffff;
  box-shadow:0 14px 32px rgba(15,23,42,.08);
  display:flex;
  flex-direction:column;
  text-decoration:none;
  transition:.18s;
}
.kelas-card:hover{
  transform:translateY(-3px);
  box-shadow:0 20px 42px rgba(15,23,42,.14);
}
.kelas-label{
  font-size:11px;
  font-weight:700;
  color:#6c7a8a;
  text-transform:uppercase;
  letter-spacing:.5px;
}
.kelas-title{
  font-size:18px;
  font-weight:800;
  color:#0b1d54;
  margin-top:2px;
}
.kelas-count{
  font-size:13px;
  margin-top:6px;
  color:#334155;
}
.kelas-count strong{
  color:#1f5bd9;
}
.kelas-link{
  margin-top:auto;
  padding:8px 18px;
  border-radius:999px;
  background:#f1f5ff;
  border:1px solid #cfd9ff;
  color:#1f5bd9;
  font-size:13px;
  font-weight:700;
  text-align:center;
}

/* ===== DETAIL TABLE ===== */
.detail-header{
  margin-bottom:14px;
}
.detail-header h4{
  font-size:18px;
  font-weight:800;
  color:#0b1d54;
}
.detail-header p{
  font-size:13px;
  color:#6c7a8a;
}

.table-wrap{
  border-radius:18px;
  overflow:hidden;
  border:1px solid #e1e7ff;
  box-shadow:0 14px 32px rgba(15,23,42,.08);
  background:#fff;
}
table{
  width:100%;
  border-collapse:collapse;
}
thead th{
  background:#e9efff;
  font-size:12px;
  font-weight:800;
  text-transform:uppercase;
  padding:12px 14px;
  color:#0b1d54;
}
tbody td{
  padding:12px 14px;
  border-bottom:1px solid #f1f4ff;
  font-size:13px;
}
tbody tr:nth-child(even) td{
  background:#fafbff;
}
</style>

<div class="page">
<section class="card">

  <div class="card-hd">
    <div>
      <i class="fa-solid fa-user-graduate"></i>
      Data Mahasiswa per Kelas
    </div>
  </div>

  <div class="card-bd">

    @php $kelasFilter = $kelasFilter ?? request('kelas'); @endphp

    {{-- ================= MODE OVERVIEW ================= --}}
    @if(!$kelasFilter)

      <form method="GET"
            action="{{ route('koordinator.mahasiswa.index') }}"
            class="toolbar">

        <div class="filter-group">
          <label>Kelas</label>
          <select name="filter_kelas">
            <option value="">Semua</option>
            @foreach($daftarKelas as $row)
              <option value="{{ $row->nama_kelas }}"
                {{ request('filter_kelas') == $row->nama_kelas ? 'selected' : '' }}>
                {{ $row->nama_kelas }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="filter-group">
          <label>Angkatan</label>
          <select name="filter_angkatan">
            <option value="">Semua</option>
            @for($y = date('Y') + 1; $y >= 2018; $y--)
              <option value="{{ $y }}"
                {{ request('filter_angkatan') == $y ? 'selected' : '' }}>
                {{ $y }}
              </option>
            @endfor
          </select>
        </div>

        <div class="filter-group" style="flex:1">
          <label>Cari (Nama / NIM)</label>
          <input type="text"
                 name="q"
                 value="{{ request('q') }}"
                 placeholder="Cari nama atau NIM">
        </div>

        <button type="submit">
          <i class="fa fa-search"></i> Cari
        </button>
      </form>

      <div class="kelas-grid">
        @foreach($daftarKelas as $row)
          @php
            $total = $kelasStats[$row->nama_kelas]->total ?? 0;
          @endphp
          <a href="{{ route('koordinator.mahasiswa.index',['kelas'=>$row->nama_kelas]) }}"
             class="kelas-card">
            <div class="kelas-label">KELAS</div>
            <div class="kelas-title">{{ $row->nama_kelas }}</div>
            <div class="kelas-count">
              Jumlah mahasiswa:
              <strong>{{ $total }}</strong> orang
            </div>
            <div class="kelas-link">Lihat detail →</div>
          </a>
        @endforeach
      </div>

    {{-- ================= MODE DETAIL ================= --}}
    @else

      <a href="{{ route('koordinator.mahasiswa.index') }}"
         class="btn btn-secondary mb-3">
        ← Kembali ke semua kelas
      </a>

      <div class="detail-header">
        <h4>Data Mahasiswa — {{ $kelasFilter }}</h4>
        <p>Daftar mahasiswa terdaftar di kelas {{ $kelasFilter }}</p>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th style="width:60px">No</th>
              <th style="width:160px">NIM</th>
              <th>Nama</th>
              <th style="width:120px">Kelas</th>
              <th style="width:160px">No HP</th>
            </tr>
          </thead>
          <tbody>
            @foreach($mahasiswas as $mhs)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mhs->nim }}</td>
                <td>{{ $mhs->nama }}</td>
                <td>{{ $mhs->kelas }}</td>
                <td>{{ $mhs->no_hp ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    @endif

  </div>
</section>
</div>
@endsection
