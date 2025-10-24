@extends('layouts.mahasiswa')

@section('title', 'Tambah Milestone')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
  .page-wrap{max-width:720px;margin:24px auto;}
</style>
@endpush

@section('content')
<div class="page-wrap">
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h2 class="fw-bold mb-4">Tambah Milestone</h2>

      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>Periksa kembali input:</strong>
          <ul class="mb-0">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('mahasiswa.milestone.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf

        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <input type="text" name="deskripsi" class="form-control" value="{{ old('deskripsi') }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal</label>
          <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
        </div>

        <div class="form-check form-switch mb-3">
          <input type="hidden" name="status" value="0">
          <input class="form-check-input" type="checkbox" id="statusSwitch" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
          <label class="form-check-label" for="statusSwitch">Selesai?</label>
        </div>

        <div class="d-flex gap-2 justify-content-between">
          <a href="{{ route('mahasiswa.milestone.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save me-1"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
