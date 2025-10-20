{{-- resources/views/logbooks/create.blade.php --}}
@extends('layouts.mahasiswa')

@section('title', 'Tambah Logbook')

@push('styles')
  {{-- pakai skin yang sama dengan halaman logbook --}}
  <link rel="stylesheet" href="{{ asset('css/logbook-skin.css') }}">
  {{-- sedikit helper style supaya header/form mengikuti gaya index --}}
  <style>
    .form-card{background:#fff;border-radius:12px;box-shadow:0 6px 20px rgba(13,23,84,.08);border:1px solid rgba(13,23,84,.10);overflow:hidden}
    .form-card__head{background:#0b1d54;color:#fff;font-weight:700;padding:14px 18px}
    .form-card__body{padding:18px}
    .btn-save{background:#0b1d54;border-color:#0b1d54}
    .btn-save:hover{background:#102a78;border-color:#102a78}
    .btn-cancel{border-radius:8px}
  </style>
@endpush

@section('content')
  <div class="container my-5">
    <h1 class="mb-4">Tambah Logbook</h1>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="form-card">
      <div class="form-card__head">Input Logbook</div>
      <div class="form-card__body">
        <form action="{{ route('logbooks.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>
          </div>

          <div class="mb-3">
            <label for="minggu" class="form-label">Minggu ke</label>
            <select id="minggu" name="minggu" class="form-select" required>
              <option value="">-- Pilih Minggu --</option>
              @foreach($mingguOptions as $opt)
                <option value="{{ $opt }}" {{ old('minggu') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="aktivitas" class="form-label">Aktivitas</label>
            <input type="text" class="form-control" id="aktivitas" name="aktivitas" value="{{ old('aktivitas') }}" required>
          </div>

          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
          </div>

          <div class="mb-3">
            <label for="foto" class="form-label">Upload Dokumentasi</label>
            <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
          </div>

          <button type="submit" class="btn btn-save text-white">Simpan Logbook</button>
          <a href="{{ route('logbooks.index') }}" class="btn btn-outline-secondary btn-cancel">Kembali</a>
        </form>
      </div>
    </div>
  </div>
@endsection
