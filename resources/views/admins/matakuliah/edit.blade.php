@include('admins.partials.header', ['title' => 'Edit Mata Kuliah'])

<div class="card shadow mb-4">
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