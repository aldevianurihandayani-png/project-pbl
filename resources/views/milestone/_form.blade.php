<div class="row g-3">
  {{-- Deskripsi --}}
  <div class="col-12 col-md-8">
    <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
    <input
      type="text"
      id="deskripsi"
      name="deskripsi"
      class="form-control"
      placeholder="Contoh: Implementasi fitur CRUD User"
      value="{{ old('deskripsi', $milestone->deskripsi ?? '') }}"
      required
    >
  </div>

  {{-- Tanggal --}}
  <div class="col-12 col-md-4">
    <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
    <input
      type="date"
      id="tanggal"
      name="tanggal"
      class="form-control"
      value="{{ old('tanggal', isset($milestone) ? optional($milestone->tanggal)->format('Y-m-d') : '') }}"
      required
    >
  </div>

  {{-- Status --}}
  <div class="col-12">
    <div class="form-check form-switch">
      <input
        class="form-check-input"
        type="checkbox"
        id="status"
        name="status"
        value="1"
        {{ old('status', $milestone->status ?? false) ? 'checked' : '' }}
      >
      <label class="form-check-label" for="status">Selesai?</label>
    </div>
  </div>
</div>

<div class="d-flex gap-2 mt-4">
  <button type="submit" class="btn btn-primary px-4">
    <i class="fa-solid fa-floppy-disk me-2"></i>{{ $submitLabel ?? 'Simpan' }}
  </button>
  <a href="{{ route('mahasiswa.milestone.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>
