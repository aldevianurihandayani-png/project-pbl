{{-- resources/views/dosenpenguji/mahasiswa/index.blade.php --}}
@extends('dosenpenguji.layout')

@section('title', 'Mahasiswa — Dosen Penguji')
@section('header', 'Mahasiswa — Dosen Penguji')

@section('content')
  <style>
    /* ===== STYLE KHUSUS HALAMAN MAHASISWA ===== */
    h2.page-title{
      color:#0b1d54;
      font-size:14px;
      margin:0 0 8px 0;
      letter-spacing:.3px;
    }

    .toolbar{
      display:flex;
      flex-wrap:wrap;
      align-items:flex-end;
      gap:12px;
      margin-bottom:8px;
    }
    .toolbar form{
      display:flex;
      flex-wrap:wrap;
      align-items:flex-end;
      gap:12px;
    }

    .filter-group{
      display:flex;
      flex-direction:column;
      gap:4px;
    }
    .filter-group label{
      font-size:12px;
      color:#0b1d54;
      font-weight:700;
    }
    .filter-group select,
    .filter-group input{
      padding:6px 10px;
      border:1px solid #d8dfeb;
      border-radius:8px;
      background:#fff;
      font-size:13px;
      min-width:130px;
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
    .toolbar button:hover{ filter:brightness(.95) }

    /* ===== CARD GRID PER KELAS ===== */
    .kelas-grid{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
      gap:18px;
    }
    .kelas-card{
      background:#ffffff;
      border-radius:16px;
      border:1px solid rgba(13,23,84,.10);
      box-shadow:0 6px 20px rgba(13,23,84,.08);
      padding:16px 16px 14px;
      display:flex;
      flex-direction:column;
      gap:8px;
    }
    .kelas-card-header{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:8px;
    }
    .kelas-label{
      font-size:11px;
      text-transform:uppercase;
      color:#6c7a8a;
      letter-spacing:.5px;
      margin-bottom:3px;
    }
    .kelas-title{
      font-size:18px;
      font-weight:800;
      color:#0b1d54;
    }
    .kelas-badge{
      padding:4px 10px;
      border-radius:999px;
      background:#e3ebff;
      color:#2341a8;
      font-size:11px;
      font-weight:700;
    }
    .kelas-meta{
      font-size:12px;
      color:#6c7a8a;
    }
    .kelas-count{
      font-size:20px;
      font-weight:800;
      color:#0e257a;
    }
    .kelas-footer{ margin-top:auto; }

    .btn-detail{
      display:inline-flex;
      justify-content:center;
      align-items:center;
      gap:6px;
      width:100%;
      padding:8px 10px;
      border-radius:999px;
      border:1px solid #c5d3f4;
      background:#f5f7ff;
      color:#2341a8;
      font-size:13px;
      font-weight:700;
      text-decoration:none;
      transition:background .16s, box-shadow .16s, transform .12s;
    }
    .btn-detail i{ font-size:12px; }
    .btn-detail:hover{
      background:#e1e8ff;
      box-shadow:0 6px 16px rgba(15,37,104,.18);
      transform:translateY(-1px);
    }

    .empty-state{
      padding:32px 18px;
      border-radius:16px;
      border:1px dashed #cbd4e6;
      background:#ffffff;
      text-align:center;
      color:#6c7a8a;
    }
    .empty-state-title{
      font-weight:800;
      color:#0b1d54;
      margin-bottom:4px;
    }

    /* Mobile tweak utk filter */
    @media (max-width:980px){
      .toolbar{
        flex-direction:column;
        align-items:stretch;
      }
      .toolbar form{ width:100%; }
      .filter-group{ flex:1; }
    }
  </style>

  <h2 class="page-title">DATA MAHASISWA PER KELAS</h2>

  {{-- FILTER --}}
  <div class="toolbar">
    <form action="{{ route('dosenpenguji.mahasiswa') }}" method="GET">
      <div class="filter-group">
        <label for="kelas">Kelas</label>
        <select name="kelas" id="kelas">
          <option value="Semua" {{ ($filterKelas ?? '') === 'Semua' ? 'selected' : '' }}>Semua</option>
          <option value="A" {{ ($filterKelas ?? '') === 'A' ? 'selected' : '' }}>A</option>
          <option value="B" {{ ($filterKelas ?? '') === 'B' ? 'selected' : '' }}>B</option>
          <option value="C" {{ ($filterKelas ?? '') === 'C' ? 'selected' : '' }}>C</option>
          <option value="D" {{ ($filterKelas ?? '') === 'D' ? 'selected' : '' }}>D</option>
          <option value="E" {{ ($filterKelas ?? '') === 'E' ? 'selected' : '' }}>E</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="semester">Semester</label>
        <select name="semester" id="semester">
          <option value="">Semua</option>
          @for($i = 1; $i <= 8; $i++)
            <option value="{{ $i }}" {{ ($filterSmtr ?? '') == $i ? 'selected' : '' }}>
              {{ $i }}
            </option>
          @endfor
        </select>
      </div>

      <div class="filter-group" style="min-width:200px;">
        <label for="q">Cari</label>
        <input type="text"
               name="q"
               id="q"
               placeholder="Cari nama / NIM"
               value="{{ $keyword ?? '' }}">
      </div>

      <button type="submit">
        <i class="fa-solid fa-magnifying-glass"></i>&nbsp; Cari
      </button>
    </form>
  </div>

  {{-- GRID KARTU PER KELAS --}}
  @if($kelasSummary->count())
    <div class="kelas-grid">
      @foreach($kelasSummary as $row)
        @php
          $kelasRaw = (string) $row->kelas;

          // hilangkan semua kata "kelas" (case-insensitive) lalu rapikan spasi
          $kelasNoWord = preg_replace('/\bkelas\b/i', '', $kelasRaw);
          $kelasNoWord = trim(preg_replace('/\s+/', ' ', $kelasNoWord));

          // ambil token pertama sebagai kode kelas
          $kelasCode = strtoupper(strtok($kelasNoWord, ' '));
        @endphp

        <div class="kelas-card">
          <div class="kelas-card-header">
            <div>
              <div class="kelas-label">KELAS</div>
              <div class="kelas-title">Kelas {{ $kelasCode }}</div>
            </div>
            <span class="kelas-badge">Kelas {{ $kelasCode }}</span>
          </div>

          <div>
            <div class="kelas-meta">Jumlah mahasiswa</div>
            <div class="kelas-count">{{ $row->total_mahasiswa }} orang</div>
          </div>

          <div class="kelas-footer">
            <a href="{{ route('dosenpenguji.mahasiswa.kelas', $kelasCode) }}"
               class="btn-detail">
              <span>Lihat detail</span>
              <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="empty-state">
      <div class="empty-state-title">Belum ada data mahasiswa.</div>
      <div>Tambahkan data terlebih dahulu atau ubah filter kelas/semester.</div>
    </div>
  @endif
@endsection
