@extends('layouts.koordinator')

@section('title', 'Detail CPMK — Koordinator')
@section('page_title', 'Detail CPMK')

@section('content')
<style>
  .card{ background:#fff;border-radius:16px;border:1px solid rgba(13,23,84,.10);box-shadow:0 6px 20px rgba(13,23,84,.08); }
  .card-bd{ padding:16px 18px; }
  .info-grid{ display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:14px; }
  .info-box{ background:#f8faff;border:1px solid #e7ecf6;border-radius:12px;padding:12px 14px; }
  .info-label{ font-size:11px;color:#6c7a8a;font-weight:700;text-transform:uppercase; }
  .info-value{ margin-top:4px;font-weight:900;color:#0e257a; }
  .muted{ color:#6c7a8a; font-size:12px; }
  .btn{ border:0;padding:8px 14px;border-radius:8px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;}
  .btn-secondary{ background:#eef3fa; color:#0e257a; }
  .btn-secondary:hover{ background:#e3eaf5; }
</style>

<div class="card">
  <div class="card-hd" style="justify-content:space-between">
    <div>Detail CPMK</div>
    <a class="btn btn-secondary" href="{{ route('koordinator.cpmk.index') }}">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="card-bd">
    <div class="info-grid">
      <div class="info-box">
        <div class="info-label">Kode CPMK</div>
        <div class="info-value">{{ $cpmk->kode_cpmk ?? ($cpmk->kode ?? '-') }}</div>
      </div>

      <div class="info-box">
        <div class="info-label">Dibuat</div>
        <div class="info-value">{{ optional($cpmk->created_at)->format('d/m/Y H:i') }}</div>
      </div>
    </div>

    <div style="margin-top:16px">
      <div class="info-label">Deskripsi</div>
      <div style="margin-top:6px">
        {{ $cpmk->deskripsi ?? $cpmk->uraian ?? '-' }}
      </div>
    </div>

    </div>
  </div>
</div>
@endsection
