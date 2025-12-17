@extends('layouts.dosen')

@section('content')
<style>
  .wrap{padding:24px;background:#f4f7ff;min-height:calc(100vh - 80px)}
  .back{display:inline-flex;align-items:center;gap:8px;text-decoration:none;color:#0f172a;
        background:#fff;border:1px solid #e6ecff;border-radius:999px;padding:10px 14px;
        box-shadow:0 10px 25px rgba(2,10,36,.06);font-weight:700}
  .title{margin:14px 0 2px;font-size:22px;font-weight:900;color:#0f172a}
  .sub{margin:0 0 14px;color:#64748b;font-size:13px;font-weight:600}

  .table-card{background:#fff;border:1px solid #e6ecff;border-radius:18px;overflow:hidden;
              box-shadow:0 12px 26px rgba(2,10,36,.06)}
  table{width:100%;border-collapse:collapse;font-size:14px}
  thead th{background:#eaf1ff;color:#0f172a;text-align:left;padding:14px 16px;font-weight:900}
  tbody td{padding:14px 16px;border-top:1px solid #eef2ff}
  tbody tr:nth-child(even){background:#fbfdff}
  .muted{color:#64748b;font-weight:700}
  .pill{display:inline-flex;align-items:center;justify-content:center;padding:6px 12px;
        border-radius:999px;background:#f3f7ff;border:1px solid #cfd9ff;color:#1d4ed8;font-weight:800}
  .empty{padding:18px;color:#64748b}

  .pager{padding:14px 16px;border-top:1px solid #eef2ff;background:#fff}
</style>

<div class="wrap">

  <a class="back" href="{{ route('dosen.mahasiswa.index') }}">
    ← Kembali ke semua kelas
  </a>

  <div class="title">Data Mahasiswa — Kelas {{ $kelas }}</div>
  <p class="sub">Daftar mahasiswa terdaftar di Kelas {{ $kelas }}.</p>

  <div class="table-card">
    <table>
      <thead>
        <tr>
          <th style="width:70px;">NO</th>
          <th style="width:140px;">NIM</th>
          <th>NAMA</th>
          <th style="width:140px;">KELAS</th>
          <th style="width:160px;">NO HP</th>
          <th style="width:120px;">ANGKATAN</th>
          <th style="width:240px;">EMAIL</th>
        </tr>
      </thead>
      <tbody>
        @forelse($data as $i => $mhs)
          <tr>
            <td>{{ $data->firstItem() + $i }}</td>
            <td class="muted">{{ $mhs->nim }}</td>
            <td style="font-weight:800; letter-spacing:.02em;">{{ $mhs->nama }}</td>
            <td><span class="pill">Kelas {{ $mhs->kelas }}</span></td>
            <td>{{ $mhs->no_hp ?? '-' }}</td>
            <td>{{ $mhs->angkatan }}</td>
            <td class="muted">{{ $mhs->email ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="empty">Tidak ada data mahasiswa.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div class="pager">
      {{ $data->links() }}
    </div>
  </div>

</div>
@endsection
