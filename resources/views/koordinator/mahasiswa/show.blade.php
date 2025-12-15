@extends('layouts.koordinator')

@section('title', 'Detail Mahasiswa — Koordinator')
@section('page_title', 'Detail Mahasiswa')

@section('content')
<style>
  h2.page-title{
    color:#0b1d54;
    font-size:14px;
    margin:0 0 6px 0;
    letter-spacing:.3px;
    font-weight:800;
  }

  .header-row{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    flex-wrap:wrap;
    gap:12px;
    margin-bottom:14px;
  }
  .kelas-title-main{
    font-size:20px;
    font-weight:800;
    color:#0b1d54;
  }
  .kelas-sub{
    font-size:13px;
    color:#6c7a8a;
  }

  .btn-back{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:7px 12px;
    border-radius:999px;
    border:1px solid #d0d7ea;
    background:#fff;
    color:#233042;
    font-size:13px;
    text-decoration:none;
  }
  .btn-back i{ font-size:12px; }

  .toolbar{
    display:flex;
    justify-content:flex-end;
    margin-bottom:12px;
  }
  .search-group{
    display:flex;
    flex-direction:column;
    gap:4px;
    min-width:220px;
  }
  .search-group label{
    font-size:12px;
    font-weight:700;
    color:#0b1d54;
  }
  .search-group input{
    padding:6px 10px;
    border-radius:8px;
    border:1px solid #d8dfeb;
    font-size:13px;
  }
  .toolbar button{
    padding:7px 14px;
    border-radius:10px;
    border:0;
    background:#2f73ff;
    color:#fff;
    font-size:13px;
    font-weight:700;
    cursor:pointer;
  }

  table{
    width:100%;
    border-collapse:collapse;
    min-width:880px;
  }
  th, td{
    padding:10px 12px;
    font-size:13px;
    border-bottom:1px solid #eef1f6;
  }
  thead th{
    background:#eef3fa;
    text-align:left;
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.4px;
  }
  tbody tr:hover td{
    background:#f9fbff;
  }
  .empty-row td{
    padding:24px 16px;
    text-align:center;
    color:#6c7a8a;
  }
</style>

<h2 class="page-title">DETAIL MAHASISWA</h2>

<div class="header-row">
  <div>
    <div class="kelas-title-main">Kelas {{ $kelas }}</div>
    <div class="kelas-sub">Total: {{ $mahasiswa->total() }} mahasiswa</div>
  </div>

  <a href="{{ route('koordinator.mahasiswa.index') }}" class="btn-back">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke daftar kelas
  </a>
</div>

{{-- SEARCH DALAM KELAS --}}
<div class="toolbar">
  <form method="GET" action="{{ url()->current() }}" style="display:flex;gap:10px;align-items:flex-end;">
    <input type="hidden" name="kelas" value="{{ $kelas }}">
    <div class="search-group">
      <label for="q">Cari di kelas ini</label>
      <input type="text" name="q" id="q"
             placeholder="Cari nama / NIM"
             value="{{ request('q') }}">
    </div>
    <button type="submit">
      <i class="fa-solid fa-magnifying-glass"></i>&nbsp; Cari
    </button>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th style="width:40px;">No</th>
            <th style="width:120px;">NIM</th>
            <th>Nama</th>
            <th style="width:220px;">Email</th>
            <th style="width:120px;">Angkatan</th>
            <th style="width:140px;">No HP</th>
            <th style="width:80px;">Kelas</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($mahasiswa as $mhs)
            <tr>
              <td>{{ ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + $loop->iteration }}</td>
              <td>{{ $mhs->nim }}</td>
              <td>{{ $mhs->nama }}</td>
              <td>{{ $mhs->email ?? '-' }}</td>
              <td>{{ $mhs->angkatan ?? '-' }}</td>
              <td>{{ $mhs->no_hp ?? '-' }}</td>
              <td>{{ $mhs->kelas }}</td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="7">Belum ada data mahasiswa di kelas ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($mahasiswa->hasPages())
    <div class="card-footer">
      {{ $mahasiswa->links() }}
    </div>
  @endif
</div>
@endsection
