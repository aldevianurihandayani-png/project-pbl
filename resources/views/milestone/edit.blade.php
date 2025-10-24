@extends('layouts.mahasiswa')

@section('title', 'Edit Milestone')
@section('page_title', 'Edit Milestone')

@section('content')
<div class="container-fluid px-0">
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex align-items-center gap-2">
      <i class="fa-regular fa-clipboard text-primary"></i>
      <span class="fw-semibold">Edit Milestone</span>
    </div>

    <div class="card-body pt-2">
      {{-- Error global --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <div class="fw-semibold mb-1">Periksa kembali input:</div>
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('mahasiswa.milestone.update', $milestone->id_milestone) }}" method="POST" class="needs-validation" novalidate onsubmit="this.querySelector('button[type=submit]').disabled=true;">
        @csrf
        @method('PUT')

        <div class="row g-3">
          {{-- Deskripsi --}}
          <div class="col-12 col-md-8">
            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
            <input
              type="text"
              id="deskripsi"
              name="deskripsi"
              class="form-control @error('deskripsi') is-invalid @enderror"
              placeholder="Contoh: Implementasi fitur CRUD User"
              value="{{ old('deskripsi', $milestone->deskripsi) }}"
              required
              autofocus
              autocomplete="off"
            >
            @error('deskripsi')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Tanggal --}}
          <div class="col-12 col-md-4">
            <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
            <input
              type="date"
              id="tanggal"
              name="tanggal"
              class="form-control @error('tanggal') is-invalid @enderror"
              value="{{ old('tanggal', optional($milestone->tanggal)->format('Y-m-d')) }}"
              required
            >
            @error('tanggal')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Status --}}
          <div class="col-12">
            {{-- hidden agar saat unchecked tetap mengirim 0 --}}
            <input type="hidden" name="status" value="0">
            <div class="form-check form-switch">
              <input
                class="form-check-input"
                type="checkbox"
                id="status"
                name="status"
                value="1"
                {{ old('status', $milestone->status) ? 'checked' : '' }}
              >
              <label class="form-check-label" for="status">Selesai?</label>
            </div>
            @error('status')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fa-solid fa-floppy-disk me-2"></i>Perbarui
          </button>
          <a href="{{ route('mahasiswa.milestone.index') }}" class="btn btn-outline-secondary">
            Kembali
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Bootstrap native validation
(() => {
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
@endpush

@endsection
