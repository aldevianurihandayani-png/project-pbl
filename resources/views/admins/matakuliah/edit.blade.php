@include('admins.partials.header', ['title' => 'Edit Mata Kuliah'])

<div class="card shadow mb-4">
<<<<<<< HEAD
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Mata Kuliah</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admins.matakuliah.update', $matakuliah->kode_mk) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="kode_mk">Kode Mata Kuliah</label>
                <input type="text" class="form-control @error('kode_mk') is-invalid @enderror" id="kode_mk" name="kode_mk" value="{{ old('kode_mk', $matakuliah->kode_mk) }}" required>
            </div>
            <div class="form-group">
                <label for="nama_mk">Nama Mata Kuliah</label>
                <input type="text" class="form-control @error('nama_mk') is-invalid @enderror" id="nama_mk" name="nama_mk" value="{{ old('nama_mk', $matakuliah->nama_mk) }}" required>
                @error('nama_mk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sks">SKS</label>
                        <input type="number" class="form-control @error('sks') is-invalid @enderror" id="sks" name="sks" value="{{ old('sks', $matakuliah->sks) }}" required>
                        @error('sks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <input type="number" class="form-control @error('semester') is-invalid @enderror" id="semester" name="semester" value="{{ old('semester', $matakuliah->semester) }}" required>
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="nama_dosen">Dosen Pengampu</label>
<<<<<<< HEAD
                <input type="text" class="form-control @error('nama_dosen') is-invalid @enderror" id="nama_dosen" name="nama_dosen" value="{{ old('nama_dosen', $matakuliah->dosen->name ?? '') }}" required autocomplete="off">
=======
                <input type="text" list="dosen-list" class="form-control @error('nama_dosen') is-invalid @enderror" id="nama_dosen" name="nama_dosen" value="{{ old('nama_dosen', $matakuliah->dosen->name ?? '') }}" required autocomplete="off">
                <datalist id="dosen-list">
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->name }}">
                    @endforeach
                </datalist>
>>>>>>> 7e4c8f16039fdbc37547803aea3ddc7c4610d0c4
                @error('nama_dosen')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admins.matakuliah.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@include('admins.partials.footer')
=======
  <div class="card-header d-flex justify-content-between align-items-center">
    <strong>Edit Mata Kuliah</strong>
    <span class="text-muted">Kode: {{ $matakuliah->kode_mk }}</span>
  </div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admins.matakuliah.update', $matakuliah) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admins.matakuliah._form', ['mode' => 'edit', 'submitText' => 'Update'])
    </form>
  </div>
</div>

@include('admins.partials.footer')
<form action="{{ route('admins.notifikasi.update', $notification) }}" method="POST">
  @csrf
  @method('PUT')
  <!-- field2 edit kamu -->
  <button type="submit">Update</button>
</form>
<form action="{{ route('admins.notifikasi.update', $notification) }}" method="POST">
  @csrf
  @method('PUT')  {{-- ini yang bikin request jadi PUT --}}
  <!-- field edit: title, type, link_url, is_read, dst -->
  <button type="submit">Update</button>
</form>
>>>>>>> bbcfba2 (commit noorma)
