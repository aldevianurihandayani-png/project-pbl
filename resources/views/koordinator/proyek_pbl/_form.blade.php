@csrf

<div class="mb-3">
  <label class="form-label">Judul Proyek</label>
  <input
    type="text"
    name="judul"
    class="form-control"
    value="{{ old('judul', $item->judul ?? '') }}"
    required
  >
</div>

<button type="submit" class="btn btn-primary">
  Simpan
</button>

<a href="{{ route('koordinator.proyek-pbl.index') }}" class="btn btn-secondary">
  Kembali
</a>
