{{-- resources/views/dosenpenguji/rubrik/_form.blade.php --}}

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <label for="kode_mk">Mata Kuliah</label>
    <select name="kode_mk" id="kode_mk" class="form-control" required>
        <option value="">Pilih Mata Kuliah</option>
        @foreach($matakuliahOptions as $mk)
            <option value="{{ $mk->kode_mk }}" {{ old('kode_mk', $rubrik->kode_mk ?? '') == $mk->kode_mk ? 'selected' : '' }}>
                {{ $mk->nama_mk }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="nama_rubrik">Nama Rubrik</label>
    <input type="text" name="nama_rubrik" id="nama_rubrik" class="form-control" value="{{ old('nama_rubrik', $rubrik->nama_rubrik ?? '') }}" required>
</div>

<div class="form-group">
    <label for="bobot">Bobot (%)</label>
    <input type="number" name="bobot" id="bobot" class="form-control" value="{{ old('bobot', $rubrik->bobot ?? '0') }}" required min="0" max="100">
</div>

<div class="form-group">
    <label for="urutan">Urutan</label>
    <input type="number" name="urutan" id="urutan" class="form-control" value="{{ old('urutan', $rubrik->urutan ?? '0') }}" required min="0">
</div>

<div class="form-group">
    <label for="deskripsi">Deskripsi</label>
    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $rubrik->deskripsi ?? '') }}</textarea>
</div>
