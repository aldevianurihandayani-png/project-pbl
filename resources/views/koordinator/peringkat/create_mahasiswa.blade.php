@extends('layouts.koordinator')

@section('title', 'Tambah Nilai Mahasiswa PBL')

@section('content')

<style>
    .form-wrap { max-width: 720px; margin: 0 auto; }
    .form-label { display: block; font-weight: 600; margin-bottom: 6px; }
    .form-input{
        width:100%;
        padding:10px 12px;
        border:1px solid #dde2f0;
        border-radius:10px;
        outline:none;
        font-size:14px;
        background:#fff;
    }
    .form-input:focus{
        border-color:#0e257a;
        box-shadow:0 0 0 3px rgba(14, 37, 122, 0.12);
    }
    .form-row{ margin-bottom:12px; }

    .btn-successx{
        padding:8px 14px;
        border-radius:10px;
        background:#1b7a3a;
        color:#fff;
        border:none;
        cursor:pointer;
    }
    .btn-secondaryx{
        padding:8px 14px;
        border-radius:10px;
        background:#6b7280;
        color:#fff;
        text-decoration:none;
        display:inline-block;
    }
</style>

@php
    // ✅ supaya tetap kebaca setelah validation error
    $kelasValue = old('kelas', $kelasAktif);
@endphp

<div class="page">
    <section class="card">
        <div class="card-hd">
            <i class="fa-solid fa-user-graduate"></i>
            Tambah Nilai Mahasiswa PBL
        </div>

        <div class="card-bd">
            <div class="form-wrap">

                {{-- ERROR --}}
                @if($errors->any())
                    <div style="padding:10px 12px;border-radius:8px;background:#ffecec;color:#8a1f1f;margin-bottom:12px;">
                        <ul style="margin:0;padding-left:18px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FILTER KELAS (GET) --}}
                <form method="GET" action="{{ route('koordinator.peringkat.createMahasiswa') }}" style="margin-bottom:14px;">
                    <div class="form-row">
                        <label class="form-label">Pilih Kelas</label>
                        <select name="kelas" class="form-input" onchange="this.form.submit()">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k }}" {{ ($kelasValue === $k) ? 'selected' : '' }}>
                                    {{ $k }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                {{-- FORM SIMPAN NILAI (POST) --}}
                <form action="{{ route('koordinator.peringkat.storeMahasiswa') }}" method="POST">
                    @csrf

                    {{-- ✅ WAJIB: kirim kelas ke POST (pakai old fallback) --}}
                    <input type="hidden" name="kelas" value="{{ $kelasValue }}">

                    {{-- MAHASISWA --}}
                    <div class="form-row">
                        <label class="form-label">
                            Mahasiswa (Kelas {{ $kelasValue ?: '-' }})
                        </label>

                        <select name="mahasiswa_nim" id="mahasiswa_nim"
                                class="form-input"
                                required
                                {{ empty($kelasValue) ? 'disabled' : '' }}>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($mahasiswaList as $m)
                                <option value="{{ $m->nim }}"
                                        data-nama="{{ $m->nama }}"
                                        {{ old('mahasiswa_nim') == $m->nim ? 'selected' : '' }}>
                                    {{ $m->nim }} - {{ $m->nama }}
                                </option>
                            @endforeach
                        </select>

                        
                    </div>

                    {{-- NAMA disimpan hidden (tanpa input dobel) --}}
                    <input type="hidden" name="nama" id="nama_hidden" value="{{ old('nama') }}">

                    {{-- NILAI --}}
                    <div class="form-row">
                        <label class="form-label">Keaktifan</label>
                        <input type="number" step="0.01" name="keaktifan"
                               class="form-input" required
                               value="{{ old('keaktifan') }}" placeholder="0 - 100">
                    </div>

                    <div class="form-row">
                        <label class="form-label">Nilai Kelompok</label>
                        <input type="number" step="0.01" name="nilai_kelompok"
                               class="form-input" required
                               value="{{ old('nilai_kelompok') }}" placeholder="0 - 100">
                    </div>

                    <div class="form-row">
                        <label class="form-label">Nilai Dosen</label>
                        <input type="number" step="0.01" name="nilai_dosen"
                               class="form-input" required
                               value="{{ old('nilai_dosen') }}" placeholder="0 - 100">
                    </div>

                    <div style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap;">
                        <button type="submit" class="btn-successx"
                            {{ empty($kelasValue) ? 'disabled style=opacity:.6;cursor:not-allowed;' : '' }}>
                            <i class="fa-solid fa-floppy-disk"></i> Simpan & Hitung Peringkat
                        </button>

                        <a href="{{ route('koordinator.peringkat.index') }}" class="btn-secondaryx">
                            Kembali
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </section>
</div>

<script>
(function(){
    const sel = document.getElementById('mahasiswa_nim');
    const namaHidden = document.getElementById('nama_hidden');

    function syncNama(){
        if(!sel) return;
        const opt = sel.options[sel.selectedIndex];
        const nm = opt?.dataset?.nama || '';
        if(namaHidden) namaHidden.value = nm;
    }

    sel?.addEventListener('change', syncNama);
    syncNama();
})();
</script>

@endsection
