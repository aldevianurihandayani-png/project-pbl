@extends('layouts.dosen')

@section('content')
<style>
  .page-wrap{padding:24px;background:#f4f7ff;min-height:calc(100vh - 80px)}
  .panel{background:#fff;border:1px solid #e6ecff;border-radius:18px;padding:20px;box-shadow:0 10px 30px rgba(2,10,36,.06)}
  .panel-head{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
  .h1{font-size:18px;font-weight:800;color:#0f172a;margin:0}
  .filters{display:grid;grid-template-columns:160px 160px 1fr 140px;gap:14px;margin-top:16px;align-items:end}
  @media (max-width:900px){.filters{grid-template-columns:1fr}}
  .fg label{display:block;font-size:11px;font-weight:800;letter-spacing:.08em;color:#64748b;margin-bottom:6px}
  .input,.select{width:100%;height:40px;border-radius:999px;border:1px solid #cfd9ff;padding:0 14px;outline:none;background:#fff}
  .input:focus,.select:focus{border-color:#2563eb;box-shadow:0 0 0 4px rgba(37,99,235,.12)}
  .btn-primary{height:40px;border-radius:999px;border:none;padding:0 18px;font-weight:800;color:#fff;background:#1d4ed8;box-shadow:0 10px 20px rgba(29,78,216,.25);cursor:pointer;width:100%}
  .btn-primary:hover{background:#1e40af}
  .section-title{margin:18px 0 10px;font-weight:800;color:#0f172a}
  .cards{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:18px}
  @media (max-width:1100px){.cards{grid-template-columns:repeat(3,minmax(0,1fr))}}
  @media (max-width:700px){.cards{grid-template-columns:1fr}}
  .card{background:#fff;border:1px solid #e6ecff;border-radius:18px;padding:18px;box-shadow:0 12px 26px rgba(2,10,36,.06)}
  .card h3{margin:0 0 4px;font-size:18px;font-weight:900;color:#0f172a}
  .card .meta{font-size:13px;color:#64748b;margin-bottom:16px}
  .badge{font-weight:900;color:#2563eb}
  .btn-outline{display:inline-flex;align-items:center;justify-content:center;width:100%;height:40px;border-radius:999px;border:1px solid #cfd9ff;color:#1d4ed8;font-weight:800;text-decoration:none;background:#f3f7ff}
  .btn-outline:hover{background:#eaf1ff}
</style>

<div class="page-wrap">

  <div class="panel">
    <div class="panel-head">
      <h1 class="h1">Data Mahasiswa per Kelas</h1>
      {{-- tanpa CRUD --}}
    </div>

    <form method="GET" class="filters">
      <div class="fg">
        <label>KELAS</label>
        <select name="kelas" class="select">
          <option {{ request('kelas','Semua')=='Semua'?'selected':'' }}>Semua</option>
          @foreach($kelasList as $k)
            <option value="{{ $k }}" {{ request('kelas')==$k?'selected':'' }}>Kelas {{ $k }}</option>
          @endforeach
        </select>
      </div>

      <div class="fg">
        <label>ANGKATAN</label>
        <select name="angkatan" class="select">
          <option {{ request('angkatan','Semua')=='Semua'?'selected':'' }}>Semua</option>
          @foreach($angkatanOptions as $a)
            <option value="{{ $a }}" {{ request('angkatan')==$a?'selected':'' }}>{{ $a }}</option>
          @endforeach
        </select>
      </div>

      <div class="fg">
        <label>CARI (NAMA / NIM)</label>
        <input type="text" name="q" value="{{ request('q') }}" class="input" placeholder="Ketik nama atau NIM...">
      </div>

      <div class="fg">
        <button class="btn-primary" type="submit">Cari</button>
      </div>
    </form>
  </div>

  <div class="section-title">Data Mahasiswa per Kelas</div>

  <div class="cards">
    @foreach($kelasToShow as $k)
      @php $total = (int) ($counts[$k] ?? 0); @endphp
      <div class="card">
        <h3>Kelas {{ $k }}</h3>
        <div class="meta">
          Jumlah mahasiswa: <span class="badge">{{ $total }}</span> orang
        </div>

        <a class="btn-outline"
           href="{{ route('dosen.mahasiswa.kelas', $k) . '?' . http_build_query(request()->only(['angkatan','q'])) }}">
          Lihat detail →
        </a>
      </div>
    @endforeach
  </div>

</div>
@endsection
