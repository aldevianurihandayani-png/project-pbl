@extends('layouts.jaminanmutu')

@section('title','Detail Rubrik — Jaminan Mutu')
@section('page_title','Detail Rubrik')

@section('content')
<style>
  .card{ background:#fff;border-radius:16px;border:1px solid rgba(13,23,84,.10);box-shadow:0 6px 20px rgba(13,23,84,.08); }
  .card-hd{ padding:14px 18px;border-bottom:1px solid #eef1f6;color:#0e257a;font-weight:800;display:flex;justify-content:space-between;align-items:center;gap:10px; }
  .card-bd{ padding:16px 18px; }
  .btn{ border:0;padding:8px 16px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px; }
  .btn-secondary{ background:#eef3fa; color:#0e257a; }
  .muted{ color:#6c7a8a; font-size:12px; }
</style>

<div class="card">
  <div class="card-hd">
    <div>Detail Rubrik</div>
    <a class="btn btn-secondary" href="{{ route('jaminanmutu.rubrik.index') }}">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="card-bd" style="display:grid;gap:10px;">
    <div>
      <div class="muted" style="font-weight:800">Nama Rubrik</div>
      <div style="font-weight:900;color:#0e257a">
        {{ $rubrik->nama_rubrik ?? ($rubrik->nama ?? ('Rubrik #'.$rubrik->id)) }}
      </div>
    </div>

    <div>
      <div class="muted" style="font-weight:800">Bobot</div>
      <div>{{ $rubrik->bobot ?? '-' }}%</div>
    </div>

    @if(!empty($rubrik->deskripsi))
      <div>
        <div class="muted" style="font-weight:800">Deskripsi</div>
        <div>{{ $rubrik->deskripsi }}</div>
      </div>
    @endif

    <div>
      <div class="muted" style="font-weight:800">Dibuat</div>
      <div>{{ optional($rubrik->created_at)->format('d/m/Y H:i') }}</div>
    </div>

    <div class="muted" style="margin-top:6px;">
      Halaman ini <b>read-only</b>. Rubrik dikelola oleh <b>Dosen Penguji</b>.
    </div>
  </div>
</div>
@endsection
