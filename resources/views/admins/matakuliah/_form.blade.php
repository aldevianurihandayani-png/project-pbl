<form action="{{ isset($matakuliah)
                  ? route('admins.matakuliah.update', $matakuliah)
                  : route('admins.matakuliah.store') }}"
      method="POST">
    @csrf
    @if(isset($matakuliah)) @method('PUT') @endif

    {{-- ========== DATA MATA KULIAH ========== --}}
    <div class="card">
      <div class="card-header">Data Mata Kuliah</div>
      <div class="card-body">
        <div class="mb-3">
          <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
          <input type="text" id="kode_mk" name="kode_mk"
                 class="form-control @error('kode_mk') is-invalid @enderror"
                 value="{{ old('kode_mk', $matakuliah->kode_mk ?? '') }}" required>
          @error('kode_mk') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
          <input type="text" id="nama_mk" name="nama_mk"
                 class="form-control @error('nama_mk') is-invalid @enderror"
                 value="{{ old('nama_mk', $matakuliah->nama_mk ?? '') }}" required>
          @error('nama_mk') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="sks" class="form-label">Jumlah SKS</label>
            <input type="number" id="sks" name="sks"
                   class="form-control @error('sks') is-invalid @enderror"
                   value="{{ old('sks', $matakuliah->sks ?? '') }}" required>
            @error('sks') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6">
            <label for="semester" class="form-label">Semester</label>
            <input type="number" id="semester" name="semester"
                   class="form-control @error('semester') is-invalid @enderror"
                   value="{{ old('semester', $matakuliah->semester ?? '') }}" required>
            @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    </div>

    {{-- ========== DATA DOSEN (INLINE) ========== --}}
    <div class="card mt-4">
      <div class="card-header">Data Dosen Pengampu (opsional, bisa tambah/ubah di sini)</div>
      <div class="card-body">
        {{-- kalau sedang edit & MK sudah punya dosen, kirim dosen_id tersembunyi agar update --}}
        <input type="hidden" name="dosen_id" value="{{ old('dosen_id', $matakuliah->dosen->id_dosen ?? '') }}">

        <div class="mb-3">
          <label for="nama_dosen" class="form-label">Nama Dosen</label>
          <input type="text" id="nama_dosen" name="nama_dosen"
                 class="form-control @error('nama_dosen') is-invalid @enderror"
                 value="{{ old('nama_dosen', $matakuliah->dosen?->nama_dosen ?? '') }}"
                 placeholder="Tulis nama dosen jika ingin membuat/mengubah dosen">
          @error('nama_dosen') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Jabatan</label>
            <input type="text" name="jabatan" class="form-control"
                   value="{{ old('jabatan', $matakuliah->dosen->jabatan ?? '') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">NIP</label>
            <input type="text" name="nip" class="form-control"
                   value="{{ old('nip', $matakuliah->dosen->nip ?? '') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">No. Telp</label>
            <input type="text" name="no_telp" class="form-control"
                   value="{{ old('no_telp', $matakuliah->dosen->no_telp ?? '') }}">
          </div>
        </div>

        <small class="text-muted d-block mt-2">
          Kosongkan semua kolom dosen jika tidak ingin menambah/mengubah dosen.
          Jika diisi, sistem akan <b>membuat atau memperbarui</b> dosen lalu mengaitkannya.
        </small>
      </div>
    </div>

    <div class="mt-4 d-flex gap-2">
      <button class="btn btn-primary" type="submit">{{ isset($matakuliah) ? 'Simpan Perubahan' : 'Simpan' }}</button>
      <a class="btn btn-secondary" href="{{ route('admins.matakuliah.index') }}">Batal</a>
    </div>
</form>
