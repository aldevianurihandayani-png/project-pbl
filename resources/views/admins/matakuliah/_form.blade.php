@php
  // helper untuk value lama atau dari model
  $val = fn($field, $default='') => old($field, isset($matakuliah) ? ($matakuliah->{$field} ?? $default) : $default);
@endphp

<div class="row">
  <div class="col-md-6">
    <div class="form-group mb-3">
      <label for="kode_mk">Kode MK</label>
      <input
        type="text" id="kode_mk" name="kode_mk"
        class="form-control @error('kode_mk') is-invalid @enderror"
        value="{{ $val('kode_mk') }}"
        {{ (isset($mode) && $mode === 'edit') ? 'readonly' : 'required' }}
      >
      @error('kode_mk') <div class="invalid-feedback">{{ $message }}</div> @enderror
      @if(isset($mode) && $mode === 'edit')
        <small class="text-muted">Kode MK sebagai primary key tidak dapat diubah.</small>
      @endif
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group mb-3">
      <label for="nama_mk">Nama Mata Kuliah</label>
      <input
        type="text" id="nama_mk" name="nama_mk"
        class="form-control @error('nama_mk') is-invalid @enderror"
        value="{{ $val('nama_mk') }}" required
      >
      @error('nama_mk') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group mb-3">
      <label for="sks">SKS</label>
      <input
        type="number" id="sks" name="sks" min="1" max="8"
        class="form-control @error('sks') is-invalid @enderror"
        value="{{ $val('sks', 2) }}" required
      >
      @error('sks') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group mb-3">
      <label for="semester">Semester</label>
      <input
        type="number" id="semester" name="semester" min="1" max="14"
        class="form-control @error('semester') is-invalid @enderror"
        value="{{ $val('semester', 1) }}" required
      >
      @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group mb-3">
      <label for="nama_dosen">Dosen Pengampu</label>
      <input
        type="text" list="dosen-list" id="nama_dosen" name="nama_dosen"
        class="form-control @error('nama_dosen') is-invalid @enderror"
        value="{{ old('nama_dosen', $val('nama_dosen', $matakuliah->dosen->name ?? '')) }}" required autocomplete="off"
      >
      <datalist id="dosen-list">
        @foreach ($dosens as $dosen)
          <option value="{{ $dosen->name }}">
        @endforeach
      </datalist>
      @error('nama_dosen') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>
</div>

<div class="d-flex gap-2">
  <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Simpan' }}</button>
  <a href="{{ route('admins.matakuliah.index') }}" class="btn btn-secondary">Batal</a>
</div>
