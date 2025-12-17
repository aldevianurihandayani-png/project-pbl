@csrf

<div class="mb-3">
  <label class="form-label">Judul</label>
  <input type="text"
         name="judul"
         class="form-control"
         value="{{ old('judul', $item->judul ?? '') }}"
         required>
</div>

<div class="mb-3">
  <label class="form-label">Tanggal</label>
  <input type="date"
         name="tanggal"
         class="form-control"
         value="{{ old(
            'tanggal',
            !empty($item->tanggal ?? null)
              ? \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d')
              : ''
         ) }}">
</div>

<div class="mb-3">
  <label class="form-label">Kode MK</label>
  <input type="number"
         name="kode_mk"
         class="form-control"
         value="{{ old('kode_mk', $item->kode_mk ?? '') }}"
         required>
</div>

<div class="mb-3">
  <label class="form-label">ID Dosen</label>
  <input type="number"
         name="id_dosen"
         class="form-control"
         value="{{ old('id_dosen', $item->id_dosen ?? '') }}"
         required>
</div>

<div class="mb-3">
  <label class="form-label">ID Kelompok</label>
  <input type="number"
         name="id_kelompok"
         class="form-control"
         value="{{ old('id_kelompok', $item->id_kelompok ?? '') }}"
         required>
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('koordinator.proyek-pbl.index') }}" class="btn btn-secondary">
  Kembali
</a>
