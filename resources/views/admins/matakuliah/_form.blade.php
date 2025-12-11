@php
    // aman untuk create/edit
    $isEdit = isset($matakuliah);
@endphp

<form action="{{ $isEdit
                  ? route('admins.matakuliah.update', $matakuliah)
                  : route('admins.matakuliah.store') }}"
      method="POST">

    @csrf
    @if($isEdit) @method('PUT') @endif


{{-- ============================================================
    FORM DATA MATA KULIAH
============================================================ --}}
<div class="card">
    <div class="card-header">Data Mata Kuliah</div>
    <div class="card-body">

        {{-- KODE MK --}}
        <div class="mb-3">
            <label class="form-label">Kode Mata Kuliah</label>
            <input type="text" name="kode_mk"
                   class="form-control @error('kode_mk') is-invalid @enderror"
                   value="{{ old('kode_mk', data_get($matakuliah ?? null, 'kode_mk', '')) }}"
                   required>
            @error('kode_mk') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- NAMA MK --}}
        <div class="mb-3">
            <label class="form-label">Nama Mata Kuliah</label>
            <input type="text" name="nama_mk"
                   class="form-control @error('nama_mk') is-invalid @enderror"
                   value="{{ old('nama_mk', data_get($matakuliah ?? null, 'nama_mk', '')) }}"
                   required>
            @error('nama_mk') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Jumlah SKS</label>
                <input type="number" name="sks"
                       class="form-control @error('sks') is-invalid @enderror"
                       value="{{ old('sks', data_get($matakuliah ?? null, 'sks', '')) }}"
                       required>
                @error('sks') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Semester</label>
                <input type="number" name="semester"
                       class="form-control @error('semester') is-invalid @enderror"
                       value="{{ old('semester', data_get($matakuliah ?? null, 'semester', '')) }}"
                       required>
                @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

    </div>
</div>



{{-- ============================================================
    FORM DOSEN PENGAMPU (AMAN NULL-SAFE)
============================================================ --}}
<div class="card mt-4">
    <div class="card-header">Data Dosen Pengampu (Opsional)</div>
    <div class="card-body">

        @php
            $dosen = data_get($matakuliah ?? null, 'dosen');  // bisa null → aman
        @endphp

        <input type="hidden" name="dosen_id"
               value="{{ old('dosen_id', data_get($dosen, 'id_dosen', '')) }}">

        {{-- Nama Dosen --}}
        <div class="mb-3">
            <label class="form-label">Nama Dosen</label>
            <input type="text" name="nama_dosen"
                   class="form-control @error('nama_dosen') is-invalid @enderror"
                   value="{{ old('nama_dosen', data_get($dosen, 'nama_dosen', '')) }}"
                   placeholder="Tulis nama dosen jika ingin membuat/mengubah dosen">
            @error('nama_dosen') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan"
                       class="form-control"
                       value="{{ old('jabatan', data_get($dosen, 'jabatan', '')) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">NIP</label>
                <input type="text" name="nip"
                       class="form-control"
                       value="{{ old('nip', data_get($dosen, 'nip', '')) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">No. Telp</label>
                <input type="text" name="no_telp"
                       class="form-control"
                       value="{{ old('no_telp', data_get($dosen, 'no_telp', '')) }}">
            </div>
        </div>

        <small class="text-muted mt-2 d-block">
            Jika kolom dosen diisi, sistem akan membuat/memperbarui dosen lalu menghubungkannya.
        </small>

    </div>
</div>



{{-- ============================================================
    BUTTON
============================================================ --}}
<div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary" type="submit">
        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
    </button>

    <a class="btn btn-secondary" href="{{ route('admins.matakuliah.index') }}">
        Batal
    </a>
</div>

</form>
