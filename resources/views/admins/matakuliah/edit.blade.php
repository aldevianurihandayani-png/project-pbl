{{-- resources/views/admins/matakuliah/edit.blade.php --}}
@include('admins.partials.header', ['title' => 'Edit Mata Kuliah'])

<div class="card shadow mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Form Edit Mata Kuliah</h6>
    <span class="text-muted">Kode: {{ $matakuliah->kode_mk }}</span>
  </div>

  <div class="card-body">
    {{-- ==================== VALIDATION ERRORS ==================== --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- ==================== FORM ==================== --}}
    <form action="{{ route('admins.matakuliah.update', $matakuliah->kode_mk) }}" method="POST">
      @csrf
      @method('PUT')

      {{-- ========== DATA MATA KULIAH ========== --}}
      <h6 class="text-primary font-weight-bold mb-3">Data Mata Kuliah</h6>

      <div class="form-group">
        <label for="kode_mk">Kode Mata Kuliah</label>
        <input
          type="text"
          class="form-control @error('kode_mk') is-invalid @enderror"
          id="kode_mk"
          name="kode_mk"
          value="{{ old('kode_mk', $matakuliah->kode_mk) }}"
          readonly
        >
        @error('kode_mk')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="nama_mk">Nama Mata Kuliah</label>
        <input
          type="text"
          class="form-control @error('nama_mk') is-invalid @enderror"
          id="nama_mk"
          name="nama_mk"
          value="{{ old('nama_mk', $matakuliah->nama_mk) }}"
          required
        >
        @error('nama_mk')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="sks">Jumlah SKS</label>
            <input
              type="number"
              class="form-control @error('sks') is-invalid @enderror"
              id="sks"
              name="sks"
              value="{{ old('sks', $matakuliah->sks) }}"
              min="1"
              required
            >
            @error('sks')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="semester">Semester</label>
            <input
              type="number"
              class="form-control @error('semester') is-invalid @enderror"
              id="semester"
              name="semester"
              value="{{ old('semester', $matakuliah->semester) }}"
              min="1"
              required
            >
            @error('semester')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      {{-- ========== DATA DOSEN PENGAMPU ========== --}}
      <hr class="my-4">
      <h6 class="text-primary font-weight-bold mb-3">Data Dosen Pengampu</h6>

      <div class="form-group">
        <label for="nama_dosen">Nama Dosen</label>
        <input
          type="text"
          class="form-control @error('nama_dosen') is-invalid @enderror"
          id="nama_dosen"
          name="nama_dosen"
          value="{{ old('nama_dosen', $matakuliah->dosen?->nama_dosen ?? '') }}"
          list="dosen-list"
          autocomplete="off"
          placeholder="cth: M. Najamudin Ridha, S.Kom."
        >
        <datalist id="dosen-list">
          @foreach ($dosens as $d)
            <option value="{{ $d->nama_dosen }}"></option>
          @endforeach
        </datalist>
        @error('nama_dosen')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Ketik nama dosen baru atau pilih dari daftar.</small>
      </div>

      <div class="form-group">
        <label for="jabatan">Jabatan</label>
        <input
          type="text"
          class="form-control @error('jabatan') is-invalid @enderror"
          id="jabatan"
          name="jabatan"
          value="{{ old('jabatan', $matakuliah->dosen?->jabatan ?? '') }}"
          placeholder="cth: Dosen Tetap / Koordinator PBL"
        >
        @error('jabatan')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="nip">NIP</label>
        <input
          type="text"
          class="form-control @error('nip') is-invalid @enderror"
          id="nip"
          name="nip"
          value="{{ old('nip', $matakuliah->dosen?->nip ?? '') }}"
          placeholder="cth: 19800521 201001 1 001"
        >
        @error('nip')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="no_telp">Nomor Telepon</label>
        <input
          type="text"
          class="form-control @error('no_telp') is-invalid @enderror"
          id="no_telp"
          name="no_telp"
          value="{{ old('no_telp', $matakuliah->dosen?->no_telp ?? '') }}"
          placeholder="cth: 081234567890"
        >
        @error('no_telp')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- ========== BUTTONS ========== --}}
      <div class="mt-4">
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-save"></i> Simpan Perubahan
        </button>
        <a href="{{ route('admins.matakuliah.index') }}" class="btn btn-secondary">
          <i class="fa fa-times"></i> Batal
        </a>
      </div>
    </form>
  </div>
</div>

@include('admins.partials.footer')
