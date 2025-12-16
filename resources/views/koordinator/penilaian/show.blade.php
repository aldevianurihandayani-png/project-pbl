@extends('layouts.koordinator')

@section('title','Detail Penilaian')
@section('page_title','Detail Penilaian')

@section('content')
<style>
  .info-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap:14px;
  }
  .info-box{
    background:#f8faff;
    border:1px solid #e7ecf6;
    border-radius:12px;
    padding:12px 14px;
  }
  .info-label{
    font-size:11px;
    color:#6c7a8a;
    font-weight:700;
    text-transform:uppercase;
  }
  .info-value{
    margin-top:4px;
    font-weight:800;
    color:#0e257a;
  }
</style>

<div class="card">
  <div class="card-hd" style="justify-content:space-between">
    <div>Detail Penilaian</div>
    <a class="btn btn-secondary" href="{{ route('koordinator.penilaian.index') }}">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="card-bd">

    {{-- ===== IDENTITAS ===== --}}
    <div class="info-grid">
      <div class="info-box">
        <div class="info-label">Mahasiswa</div>
        <div class="info-value">
          {{ optional($penilaian->mahasiswa)->nama ?? '-' }}
        </div>
        <div class="muted">
          {{ optional($penilaian->mahasiswa)->nim ?? '-' }}
        </div>
      </div>

      <div class="info-box">
        <div class="info-label">Mata Kuliah</div>
        <div class="info-value">
          {{ optional($penilaian->matakuliah)->nama_mk ?? '-' }}
        </div>
        <div class="muted">
          Kode: {{ $penilaian->matakuliah_kode ?? '-' }}
        </div>
      </div>

      <div class="info-box">
        <div class="info-label">Kelas</div>
        <div class="info-value">
          {{ optional($penilaian->kelas)->nama_kelas ?? '-' }}
        </div>
      </div>

      <div class="info-box">
        <div class="info-label">Dosen Penguji</div>
        <div class="info-value">
          {{ optional($penilaian->dosen)->name ?? '-' }}
        </div>
      </div>

      <div class="info-box">
        <div class="info-label">Nilai Akhir</div>
        <div class="info-value">
          {{ number_format((float)$penilaian->nilai_akhir, 2) }}
        </div>
      </div>

      <div class="info-box">
        <div class="info-label">Dibuat</div>
        <div class="info-value">
          {{ optional($penilaian->created_at)->format('d/m/Y H:i') }}
        </div>
      </div>
    </div>

    {{-- ===== KOMPONEN PENILAIAN ===== --}}
    <div style="margin-top:22px">
      <div style="font-weight:800;color:#0e257a;margin-bottom:8px">
        Komponen Penilaian
      </div>

      @if(!empty($penilaian->komponen))
        <table style="width:100%;border-collapse:collapse">
          <thead>
            <tr style="background:#eef3fa">
              <th style="padding:8px">Komponen</th>
              <th style="padding:8px;width:120px">Bobot (%)</th>
              <th style="padding:8px;width:120px">Nilai</th>
            </tr>
          </thead>
          <tbody>
            @foreach($penilaian->komponen as $k)
              <tr>
                <td style="padding:8px">{{ $k['nama'] ?? '-' }}</td>
                <td style="padding:8px">{{ $k['bobot'] ?? 0 }}</td>
                <td style="padding:8px">{{ $k['skor'] ?? 0 }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <div class="muted">
          Tidak ada detail komponen penilaian.
        </div>
      @endif
    </div>

    <div style="margin-top:16px;font-size:12px;color:#6c7a8a">
    </div>

  </div>
</div>
@endsection
