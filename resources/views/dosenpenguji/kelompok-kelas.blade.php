{{-- resources/views/dosenpenguji/kelompok-kelas.blade.php --}}
@extends('dosenpenguji.layout')

@section('title', 'Kelompok ' . $kelas . ' — Dosen Penguji')
@section('header', 'Kelompok — Dosen Penguji')

@section('content')
<div class="page">

  <style>
    /* ====== STYLE TABEL DETAIL KELAS (mirip dosen pembimbing) ====== */
    .table{
      width:100%;
      border-collapse:collapse;
      font-size:14px;
    }
    .table th,
    .table td{
      padding:12px 18px;
      text-align:left;
      border-bottom:1px solid #eef1f6;
      vertical-align:top;
    }
    .table th{
      background:#0b1d54;
      color:#ffffff;
      font-size:14px;
      font-weight:600;
    }
    .table tbody tr:nth-child(even){
      background:#f8fafc;
    }
    .table tbody tr:last-child td{
      border-bottom:none;
    }
  </style>

  <section class="card">
    <div class="card-hd">
      <div>
        <i class="fa-solid fa-users"></i>
        Detail Kelas yang Anda Uji:
        <strong>{{ $kelas }}</strong>
      </div>

      {{-- Dosen penguji TIDAK boleh tambah / edit kelompok di sini --}}
    </div>

    <div class="card-bd">
      <table class="table">
        <thead>
          <tr>
            <th>Nama Kelompok</th>
            <th>Kelas</th>
            <th>Ketua</th>
            <th>Dosen Pembimbing</th>
            {{-- Tidak ada kolom Aksi karena penguji hanya melihat --}}
          </tr>
        </thead>

        <tbody>
          @forelse ($kelompoks as $kelompok)
            <tr>
              <td>
                <strong>{{ $kelompok->nama }}</strong><br>
                <small>{{ $kelompok->judul_proyek }}</small><br>
                <small><b>Anggota:</b> {{ $kelompok->anggota }}</small>
              </td>

              <td>{{ $kelompok->kelas }}</td>
              <td>{{ $kelompok->ketua_kelompok }}</td>
              <td>{{ $kelompok->dosen_pembimbing }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" style="text-align:center; padding:3rem;">
                Belum ada kelompok di kelas ini yang Anda uji.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      {{-- tombol kembali opsional --}}
      <div style="margin-top:20px;">
        <a href="{{ route('dosenpenguji.kelompok') }}" class="btn btn-secondary">
          ← Kembali ke daftar kelas / kelompok
        </a>
      </div>
    </div>
  </section>

</div>
@endsection
