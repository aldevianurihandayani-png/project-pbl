{{-- resources/views/dosen/milestone/index.blade.php --}}
@extends('layouts.dosen')

@section('title','Daftar Milestone — Dosen Pembimbing')
@section('page_title','Daftar Milestone')

@section('content')
  <h1 class="section-title">Daftar Milestone</h1>

  <section class="card">
    <div class="card-bd">

      {{-- FILTER --}}
      <div class="milestone-filter">
        <div class="filter-title">Filter Kelompok</div>
        <div class="filter-grid">
          <div>
            <label for="search">Pencarian</label>
            <input type="text" id="search" name="q"
                   placeholder="Cari nama kelompok / judul proyek"
                   value="{{ request('q') }}">
          </div>
          {{-- kalau ada filter lain, taruh di sini --}}
        </div>
      </div>

      {{-- TABEL --}}
      <div class="table-wrapper">
        <table class="tbl-milestone">
          <thead>
          <tr>
            <th style="width:40px;">No</th>
            <th>Judul / Kelompok</th>
            <th>Deskripsi</th>
            <th style="width:130px;">Tanggal</th>
            <th style="width:110px;">Status</th>
            <th style="width:110px;">Proyek</th>
          </tr>
          </thead>
          <tbody>
          @forelse($milestones as $milestone)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>
                <div class="milestone-title">{{ $milestone->judul ?? '—' }}</div>
                @if(!empty($milestone->kelompok_name))
                  <div class="muted small">Kelompok: {{ $milestone->kelompok_name }}</div>
                @endif
              </td>
              <td>{{ $milestone->deskripsi }}</td>
              <td>
                {{ \Carbon\Carbon::parse($milestone->tanggal)->format('d-m-Y') }}
              </td>
              <td>
                @php
                  $status = strtolower($milestone->status ?? 'belum');
                @endphp
                <span class="badge-status {{ $status === 'selesai' ? 'selesai' : 'belum' }}">
                  {{ ucfirst($status) }}
                </span>
              </td>
              <td>{{ $milestone->proyek ?? '—' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center muted">Belum ada data milestone.</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </section>
@endsection

@push('styles')
<style>
  .section-title{
    margin:0 0 16px;
    color:#0b1d54;
  }

  /* FILTER */
  .milestone-filter{
    margin-bottom:18px;
  }
  .milestone-filter .filter-title{
    font-size:13px;
    font-weight:600;
    margin-bottom:6px;
  }
  .milestone-filter .filter-grid{
    display:flex;
    flex-wrap:wrap;
    gap:14px;
    align-items:flex-end;
  }
  .milestone-filter label{
    display:block;
    font-size:13px;
    font-weight:600;
    margin-bottom:4px;
  }
  .milestone-filter input,
  .milestone-filter select{
    width:100%;
    max-width:260px;
    padding:8px 10px;
    border-radius:8px;
    border:1px solid #ccd3e0;
    font-size:13px;
    outline:none;
  }
  .milestone-filter input:focus,
  .milestone-filter select:focus{
    border-color:#0e257a;
    box-shadow:0 0 0 2px rgba(14,37,122,.18);
  }

  /* TABEL */
  .table-wrapper{
    overflow:auto;
    border-radius:12px;
    border:1px solid #eef1f6;
    background:#fff;
  }
  .tbl-milestone{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
  }
  .tbl-milestone thead{
    background:#f3f5fc;
  }
  .tbl-milestone th,
  .tbl-milestone td{
    padding:10px 12px;
    text-align:left;
    vertical-align:top;
  }
  .tbl-milestone th{
    font-size:12px;
    letter-spacing:.4px;
    text-transform:uppercase;
    color:#4a5670;
    border-bottom:1px solid #e1e5f0;
    white-space:nowrap;
  }
  .tbl-milestone tbody tr:nth-child(even){
    background:#fafbff;
  }
  .tbl-milestone tbody tr:hover{
    background:#eef3ff;
  }
  .milestone-title{
    font-weight:600;
    margin-bottom:4px;
  }
  .small{
    font-size:11px;
  }
  .text-center{
    text-align:center;
  }

  .badge-status{
    display:inline-block;
    padding:3px 8px;
    border-radius:999px;
    font-size:11px;
    font-weight:600;
  }
  .badge-status.belum{
    background:#fff4e5;
    color:#c6781c;
  }
  .badge-status.selesai{
    background:#e6f7e9;
    color:#2e7d32;
  }

  @media (max-width: 768px){
    .milestone-filter .filter-grid{
      flex-direction:column;
      align-items:stretch;
    }
    .milestone-filter input,
    .milestone-filter select{
      max-width:100%;
    }
    .tbl-milestone th:nth-child(3),
    .tbl-milestone td:nth-child(3){
      min-width:260px; /* deskripsi biar ga mepet */
    }
  }
</style>
@endpush
