@csrf

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;">
    <div>
        <label>Mahasiswa</label><br>
        <select name="mahasiswa_id" required
                style="width:100%;padding:8px;border-radius:8px;border:1px solid #d0d7e6;">
            <option value="">-- Pilih Mahasiswa --</option>
            @foreach($mahasiswas as $m)
                <option value="{{ $m->nim }}"
                    {{ old('mahasiswa_id', $peringkat->mahasiswa_id ?? null) == $m->nim ? 'selected' : '' }}>
                    {{ $m->nama }} ({{ $m->nim }})
                </option>
            @endforeach
        </select>
        @error('mahasiswa_id') <small style="color:#c62828">{{ $message }}</small> @enderror
    </div>

    <div>
        <label>Mata Kuliah</label><br>
        <input type="text" name="mata_kuliah"
               value="{{ old('mata_kuliah', $peringkat->mata_kuliah ?? '') }}"
               required
               style="width:100%;padding:8px;border-radius:8px;border:1px solid #d0d7e6;">
    </div>

    <div>
        <label>Nilai Total</label><br>
        <input type="number" step="0.01" min="0" max="100" name="nilai_total"
               value="{{ old('nilai_total', $peringkat->nilai_total ?? '') }}"
               required
               style="width:100%;padding:8px;border-radius:8px;border:1px solid:#d0d7e6;">
    </div>

    <div>
        <label>Peringkat</label><br>
        <input type="number" min="1" name="peringkat"
               value="{{ old('peringkat', $peringkat->peringkat ?? '') }}"
               required
               style="width:100%;padding:8px;border-radius:8px;border:1px solid:#d0d7e6;">
    </div>

    <div>
        <label>Semester</label><br>
        <input type="text" name="semester"
               value="{{ old('semester', $peringkat->semester ?? '') }}"
               placeholder="Ganjil / Genap"
               style="width:100%;padding:8px;border-radius:8px;border:1px solid:#d0d7e6;">
    </div>

    <div>
        <label>Tahun Ajaran</label><br>
        <input type="text" name="tahun_ajaran"
               value="{{ old('tahun_ajaran', $peringkat->tahun_ajaran ?? '') }}"
               placeholder="2024/2025"
               style="width:100%;padding:8px;border-radius:8px;border:1px solid:#d0d7e6;">
    </div>
</div>

<div style="margin-top:16px;">
    <button type="submit"
            style="padding:8px 16px;border-radius:10px;border:none;background:#0e257a;color:#fff;cursor:pointer;">
        Simpan
    </button>

    <a href="{{ route('koordinator.peringkat.index') }}"
       style="margin-left:6px;text-decoration:none;color:#555;">Batal</a>
</div>
