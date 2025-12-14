@extends('layouts.admin')

@section('page_title', 'Detail Notifikasi')

@section('content')
@php
  $title   = $notifikasi->judul ?? '-';
  $message = $notifikasi->pesan ?? '-';
  $time    = optional($notifikasi->created_at)->format('d M Y H:i');
  $isBroadcast = empty($notifikasi->user_id);

  if ($isBroadcast) {
      $recipientText = ($totalUsers ?? 0) > 0
          ? "Semua User ({$totalUsers})"
          : "Semua User";
  } else {
      $recipientName = optional($notifikasi->user)->nama
          ?? optional($notifikasi->user)->name
          ?? 'User';
      $recipientText = $recipientName;
  }

  $recipientCount = $recipientCount ?? 0;
@endphp

<style>
  .cardx{background:#fff;border:1px solid #eef1f6;border-radius:14px;box-shadow:0 1px 2px rgba(0,0,0,.06);padding:16px;}
  .rowx{display:flex;gap:14px;flex-wrap:wrap;align-items:center;justify-content:space-between;margin-bottom:12px}
  .btnx{display:inline-flex;align-items:center;gap:8px;border:1px solid #e9ecef;background:#fff;border-radius:10px;padding:8px 12px;text-decoration:none;color:#111;cursor:pointer}
  .btnx:hover{background:#f8fafc}
  .btnx.danger{border-color:#ffd6d6;color:#b42318}
  .meta{color:#64748b;font-size:13px}
  .title{font-size:18px;font-weight:800;color:#0e257a;margin:0}
  .msg{margin:10px 0 0;color:#111;line-height:1.6;white-space:pre-wrap}
  .grid2{display:grid;grid-template-columns:1fr;gap:10px;margin-top:12px}
  @media(min-width:900px){ .grid2{grid-template-columns:1fr 1fr} }
  .kpi{background:#f8fafc;border:1px solid #eef1f6;border-radius:12px;padding:12px}
  .kpi b{display:block;color:#0e257a}
</style>

<div class="cardx">
  <div class="rowx">
    <div>
      <h2 class="title">{{ $title }}</h2>
      <div class="meta">
        Dibuat: {{ $time }} · Tipe: {{ $isBroadcast ? 'Broadcast' : 'Personal' }}
      </div>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a class="btnx" href="{{ route('admins.notifikasi.index') }}">
        <i class="fa-solid fa-arrow-left"></i> Kembali
      </a>

      {{-- ✅ FIX: WAJIB pakai key "notifikasi" sesuai parameter route --}}
      <a class="btnx" href="{{ route('admins.notifikasi.edit', ['notifikasi' => $notifikasi->id]) }}">
        <i class="fa-solid fa-pen"></i> Edit
      </a>

      {{-- ✅ FIX: WAJIB pakai key "notifikasi" sesuai parameter route --}}
      <form method="POST" action="{{ route('admins.notifikasi.destroy', ['notifikasi' => $notifikasi->id]) }}" style="margin:0"
            onsubmit="return confirm('Hapus notifikasi ini?')">
        @csrf
        @method('DELETE')
        <button class="btnx danger" type="submit">
          <i class="fa-solid fa-trash"></i> Hapus
        </button>
      </form>
    </div>
  </div>

  <div class="grid2">
    <div class="kpi">
      <b>Penerima</b>
      <div class="meta">{{ $recipientText }}</div>
    </div>

    <div class="kpi">
      <b>Jumlah penerima (pivot)</b>
      <div class="meta">{{ $recipientCount }}</div>
    </div>
  </div>

  <div class="msg">{{ $message }}</div>
</div>
@endsection
