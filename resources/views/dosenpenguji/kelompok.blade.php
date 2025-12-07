{{-- resources/views/dosenpenguji/kelompok.blade.php --}}
@extends('dosenpenguji.layout')

@section('title', 'Kelompok — Dosen Penguji')
@section('header', 'Kelompok — Dosen Penguji')

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

    .sheet{
      background:#ffffff;
      border:1px solid rgba(13,23,84,.10);
      border-radius:12px;
      box-shadow:0 6px 20px rgba(13,23,84,.08);
      overflow:hidden;
    }

    /* ==== GRID KARTU KELOMPOK ==== */
    .group-grid{
      display:grid;
      grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
      gap:16px;
    }

    .group-card{
      background:#ffffff;
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
      margin-bottom:8px;
      gap:10px;
    }

    .gc-label{
      font-size:11px;
      text-transform:uppercase;
      letter-spacing:.06em;
      color:#6c7a8a;
      margin-bottom:2px;
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
      white-space:nowrap;
    }

    .group-card-body{
      display:grid;
      gap:6px;
      margin-top:4px;
      font-size:13px;
      color:#233042;
    }

    .gc-row{
      display:flex;
      gap:8px;
      align-items:flex-start;
    }

    .gc-row i{
      width:16px;
      margin-top:2px;
      color:#4e65c6;
    }

    .gc-row-label{
      font-size:11px;
      text-transform:uppercase;
      letter-spacing:.04em;
      color:#6c7a8a;
      margin-bottom:1px;
    }

    .gc-row-value{
      word-break:break-word;
    }

    .card-footer-btn{
      margin-top:10px;
    }

    /* tombol detail SEKARANG aktif & bisa diklik */
    .btn-detail{
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
      opacity:1;
      pointer-events:auto;
    }

    .btn-detail:hover{
      background:#e6ebff;
    }

    @media (max-width:980px){
      .toolbar{
        flex-direction:column;
        align-items:flex-start;
      }
    }
  </style>

  <div class="toolbar">
    <h2 class="title">Data Kelompok</h2>

    {{-- FILTER + CARI --}}
    <form class="filters" method="GET" action="{{ url('/dosenpenguji/kelompok') }}" id="filterForm">
      <label>
        Kelas:
        <select id="fKelas" name="kelas">
          @php $kelasNow = request('kelas','all'); @endphp
          <option value="all" {{ $kelasNow==='all'?'selected':'' }}>Semua</option>
          @foreach (['A','B','C','D','E'] as $k)
            <option value="{{ $k }}" {{ $kelasNow===$k?'selected':'' }}>{{ $k }}</option>
          @endforeach
        </select>
      </label>

      <label>
        Semester:
        <select id="fSemester" name="semester">
          @php $sNow = request('semester','all'); @endphp
          <option value="all" {{ $sNow==='all'?'selected':'' }}>Semua</option>
          @for ($i=1;$i<=6;$i++)
            <option value="{{ $i }}" {{ (string)$sNow===(string)$i?'selected':'' }}>{{ $i }}</option>
          @endfor
        </select>
      </label>

      <input type="search"
             name="q"
             value="{{ request('q') }}"
             placeholder="Cari nama/kelompok/dosen/klien…">

      <button class="btn" type="submit">
        <i class="fa-solid fa-magnifying-glass"></i> Cari
      </button>
    </form>
  </div>

  {{-- ===== DATA DARI CONTROLLER (KARTU GRID) ===== --}}
  @isset($kelompok)
    @if($kelompok->count())
      <div class="group-grid">
        @foreach($kelompok as $k)
          <div class="group-card">
            <div class="group-card-header">
              <div>
                <div class="gc-label">Kelompok</div>
                <div class="gc-title">{{ $k->nama ?? '-' }}</div>
              </div>
              <div class="gc-badge">{{ $k->kelas ?? '-' }}</div>
            </div>

            <div class="group-card-body">
              <div class="gc-row">
                <i class="fa-solid fa-id-card"></i>
                <div>
                  <div class="gc-row-label">NIM Ketua</div>
                  <div class="gc-row-value">
                    {{ $k->ketua_kelompok ?? optional($k->ketua)->nim ?? '-' }}
                  </div>
                </div>
              </div>

              <div class="gc-row">
                <i class="fa-solid fa-users"></i>
                <div>
                  <div class="gc-row-label">Anggota</div>
                  <div class="gc-row-value">{{ $k->anggota ?? '-' }}</div>
                </div>
              </div>

              <div class="gc-row">
                <i class="fa-solid fa-calendar"></i>
                <div>
                  <div class="gc-row-label">Angkatan</div>
                  <div class="gc-row-value">{{ optional($k->ketua)->angkatan ?? '-' }}</div>
                </div>
              </div>

              <div class="gc-row">
                <i class="fa-solid fa-building"></i>
                <div>
                  <div class="gc-row-label">Klien</div>
                  <div class="gc-row-value">{{ $k->nama_klien ?? '-' }}</div>
                </div>
              </div>
            </div>

            <div class="card-footer-btn">
              {{-- LINK DETAIL: penguji cuma lihat, tidak edit --}}
              <a href="{{ route('dosenpenguji.kelompok.show', $k->id) }}"
                 class="btn-detail">
                Lihat detail
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="sheet" id="sheet">
        <div style="padding:14px; text-align:center; color:#6b7a93;">
          Tidak ada data kelompok.
        </div>
      </div>
    @endif

    <div style="margin-top:16px;">
      {{ $kelompok->links() }}
    </div>
  @endisset

  {{-- JS kecil untuk auto-submit filter --}}
  <script>
    const fKelas    = document.getElementById('fKelas');
    const fSemester = document.getElementById('fSemester');
    const form      = document.getElementById('filterForm');

    if (fKelas) {
      fKelas.addEventListener('change', () => form.submit());
    }
    if (fSemester) {
      fSemester.addEventListener('change', () => form.submit());
    }
  </script>
@endsection
