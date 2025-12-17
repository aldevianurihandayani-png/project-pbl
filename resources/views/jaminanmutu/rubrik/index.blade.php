@extends('layouts.jaminanmutu')

@section('title','Rubrik — Jaminan Mutu')
@section('page_title','Rubrik')

@section('content')
<style>
  .card{ background:#fff;border-radius:16px;border:1px solid rgba(13,23,84,.10);box-shadow:0 6px 20px rgba(13,23,84,.08); }
  .card-hd{ padding:14px 18px;border-bottom:1px solid #eef1f6;color:#0e257a;font-weight:800;display:flex;justify-content:space-between;align-items:center;gap:10px; }
  .card-bd{ padding:16px 18px; }
  .table-wrap{ overflow:auto; }
  table{ width:100%; border-collapse:collapse; min-width:900px; }
  th,td{ padding:10px 12px; font-size:14px; border-bottom:1px solid #eef1f6; vertical-align:middle; }
  thead th{ background:#eef3fa; color:#0b1d54; text-align:left; font-size:12px; text-transform:uppercase; }
  tbody tr:hover td{ background:#f9fbff; }
  .muted{ color:#6c7a8a; font-size:12px; }
  .btn{ border:0;padding:8px 16px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px; }
  .btn-secondary{ background:#eef3fa; color:#0e257a; }
</style>

@php $rubrik = $rubrik ?? collect(); @endphp

<div class="card">
  <div class="card-hd">
    <div>
      Daftar Rubrik
    </div>
  </div>

  <div class="card-bd">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:70px">No</th>
            <th>Nama Rubrik</th>
            <th style="width:120px">Bobot</th>
            <th style="width:180px">Dibuat</th>
            <th style="width:140px;text-align:center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rubrik as $i => $r)
            <tr>
              <td>{{ method_exists($rubrik,'firstItem') ? $rubrik->firstItem() + $i : $i+1 }}</td>
              <td>
                <strong>{{ $r->nama_rubrik ?? ($r->nama ?? ('Rubrik #'.$r->id)) }}</strong>
                @if(!empty($r->deskripsi))
                  <div class="muted">{{ \Illuminate\Support\Str::limit($r->deskripsi, 80) }}</div>
                @endif
              </td>
              <td>{{ $r->bobot ?? '-' }}%</td>
              <td>{{ optional($r->created_at)->format('d/m/Y H:i') }}</td>
              <td style="text-align:center;">
                <a class="btn btn-secondary" href="{{ route('jaminanmutu.rubrik.show', $r->id) }}">
                  <i class="fa-solid fa-eye"></i> Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align:center;padding:18px;color:#6c7a8a;">
                Belum ada rubrik.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(method_exists($rubrik,'hasPages') && $rubrik->hasPages())
      <div style="margin-top:12px;">
        {{ $rubrik->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
