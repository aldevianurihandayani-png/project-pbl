@extends('layouts.koordinator')

@section('title', 'Tambah Proyek PBL — Koordinator')
@section('page_title', 'Tambah Proyek PBL')

@push('styles')
<style>
  .form-grid{ display:grid; gap:14px; }
  .form-label{ display:block; font-size:12px; font-weight:700; color:var(--navy-2); margin-bottom:6px; }
  .form-control{
    width:100%; padding:10px 12px; border-radius:12px;
    border:1px solid #dfe6f5; outline:0; background:#fff;
    transition:box-shadow .15s, border-color .15s;
  }
  .form-control:focus{
    border-color:#9db7ff;
    box-shadow:0 0 0 4px rgba(13,23,84,.10);
  }

  .btn{
    display:inline-flex; align-items:center; gap:8px;
    padding:10px 12px; border-radius:12px; border:1px solid transparent;
    font-weight:800; font-size:13px; cursor:pointer; text-decoration:none;
  }
  .btn-primary{ background:#1c3d86; color:#fff; }
  .btn-primary:hover{ filter:brightness(1.05); }
  .btn-ghost{ background:#fff; border-color:#dfe6f5; color:#22314a; }
  .btn-ghost:hover{ background:#f7f9ff; }

  .actions{ display:flex; gap:10px; flex-wrap:wrap; margin-top:4px; }

  .alert{
    padding:12px 14px; border-radius:14px; border:1px solid;
    font-size:13px; font-weight:600; margin-bottom:12px;
  }
  .alert-danger{ background:#fef2f2; border-color:#fecaca; color:#991b1b; }
  .alert-danger ul{ margin:8px 0 0 18px; }
</style>
@endpush

@section('content')

  @if ($errors->any())
    <div class="alert alert-danger">
      <i class="fa-solid fa-triangle-exclamation"></i>
      Ada input yang belum valid:
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <section class="card">
    <div class="card-hd">
      <div style="display:flex;align-items:center;gap:10px">
        <i class="fa-solid fa-plus"></i>
        <span>Form Tambah Proyek</span>
      </div>
      <span class="small">Isi sesuai data proyek</span>
    </div>

    <div class="card-bd">
      <form method="POST" action="{{ route('koordinator.proyek-pbl.store') }}" class="form-grid">
        {{-- form fields --}}
        @include('koordinator.proyek_pbl._form')

        {{-- kalau di _form kamu sudah ada tombol, hapus tombol di bawah ini --}}
        {{-- <div class="actions">
          <button class="btn btn-primary" type="submit">
            <i class="fa-solid fa-floppy-disk"></i> Simpan
          </button>
          <a class="btn btn-ghost" href="{{ route('koordinator.proyek-pbl.index') }}">
            <i class="fa-solid fa-arrow-left"></i> Kembali
          </a>
        </div> --}}
      </form>
    </div>
  </section>

@endsection
