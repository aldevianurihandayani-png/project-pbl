@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- ========= STYLE FORM & BUTTON (selaras dengan Tambah) ========= --}}
    <style>
        .mk-page {
            max-width: 960px;
            margin: 0 auto 16px auto;
        }

        .mk-form-card {
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
            border: 1px solid #e5edff;
            padding: 24px 26px 22px;
        }
        .mk-section-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 4px;
        }
        .mk-section-sub {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 14px;
        }
        .mk-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px 24px;
        }
        @media (max-width: 768px) {
            .mk-form-grid {
                grid-template-columns: 1fr;
            }
        }
        .mk-field label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }
        .mk-field small {
            display: block;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }
        .mk-field input,
        .mk-field select {
            width: 100%;
            font-size: 14px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            padding: 9px 11px;
            transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
        }
        .mk-field input:focus,
        .mk-field select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.20);
            background-color: #f9fbff;
        }
        .mk-field input.is-invalid,
        .mk-field select.is-invalid {
            border-color: #dc2626;
        }
        .mk-invalid {
            font-size: 12px;
            color: #b91c1c;
            margin-top: 2px;
        }

        /* tombol bawah */
        .mk-actions {
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .mk-btn {
            padding-inline: 22px;
            padding-block: 10px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none !important;
            transition: background .18s ease, border-color .18s ease, color .18s ease, box-shadow .18s ease;
        }

        /* Batal – sama gaya dengan Tambah Mata Kuliah */
        .btn-outline-secondary.mk-btn {
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #475569;
        }
        .btn-outline-secondary.mk-btn:hover {
            background: #e2e8f0;
            border-color: #94a3b8;
            color: #1e293b;
        }

        /* Simpan Perubahan – biru SIMAP, sama seperti tombol Simpan */
        .btn-primary.mk-btn {
            border: 1px solid #2563eb;
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .btn-primary.mk-btn:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #ffffff;
        }

        /* link kembali di atas */
        .mk-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            text-decoration: none;
            color: #4b5563;
            padding: 4px 10px;
            border-radius: 999px;
            transition: background-color .15s ease, color .15s ease;
        }
        .mk-back-icon {
            font-size: 14px;
        }
        .mk-back-link:hover {
            background-color: #e5edff;
            color: #111827;
        }
    </style>

    <div class="mk-page">

        {{-- HEADER: KEMBALI + JUDUL --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                {{-- kembali ke daftar mata kuliah per kelas --}}
                <a href="{{ route('admins.matakuliah.index', ['kelas' => $matakuliah->kelas]) }}"
                   class="mk-back-link">
                    <span class="mk-back-icon">←</span>
                    <span>Kembali</span>
                </a>
                <h4 class="mb-0">
                    Edit Mata Kuliah
                    <span class="text-muted" style="font-size: 14px;">
                        ({{ $matakuliah->kode_mk }})
                    </span>
                </h4>
            </div>
        </div>

        {{-- ERROR VALIDATION --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <strong>Terjadi kesalahan.</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mk-form-card">
            <form action="{{ route('admins.matakuliah.update', $matakuliah->kode_mk) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ====== SECTION 1: MATA KULIAH ====== --}}
                <div class="mb-3">
                    <div class="mk-section-title">Informasi Mata Kuliah</div>
                    <div class="mk-section-sub">
                        Perbarui kode, nama mata kuliah, SKS, semester, dan kelas.
                    </div>

                    <div class="mk-form-grid">
                        <div class="mk-field">
                            <label for="kode_mk">Kode Mata Kuliah</label>
                            <input type="text" id="kode_mk" name="kode_mk"
                                   class="@error('kode_mk') is-invalid @enderror"
                                   value="{{ old('kode_mk', $matakuliah->kode_mk) }}"
                                   readonly>
                            <small>Kode tidak dapat diubah.</small>
                            @error('kode_mk')
                                <div class="mk-invalid">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mk-field">
                            <label for="nama_mk">Nama Mata Kuliah</label>
                            <input type="text" id="nama_mk" name="nama_mk"
                                   class="@error('nama_mk') is-invalid @enderror"
                                   value="{{ old('nama_mk', $matakuliah->nama_mk) }}">
                            @error('nama_mk')
                                <div class="mk-invalid">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mk-field">
                            <label for="sks">SKS</label>
                            <input type="number" id="sks" name="sks" min="1"
                                   class="@error('sks') is-invalid @enderror"
                                   value="{{ old('sks', $matakuliah->sks) }}">
                            @error('sks')
                                <div class="mk-invalid">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mk-field">
                            <label for="semester">Semester</label>
                            <input type="number" id="semester" name="semester" min="1"
                                   class="@error('semester') is-invalid @enderror"
                                   value="{{ old('semester', $matakuliah->semester) }}">
                            @error('semester')
                                <div class="mk-invalid">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mk-field">
                            <label for="kelas">Kelas</label>
                            @php
                                $kelasSelected = old('kelas', $matakuliah->kelas);
                            @endphp
                            <select id="kelas" name="kelas"
                                    class="@error('kelas') is-invalid @enderror">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach (['A','B','C','D','E'] as $kls)
                                    <option value="{{ $kls }}"
                                        {{ $kelasSelected == $kls ? 'selected' : '' }}>
                                        {{ $kls }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas')
                                <div class="mk-invalid">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- ====== SECTION 2: DOSEN PENGAMPU ====== --}}
                <div class="mb-2">
                    <div class="mk-section-title">Informasi Dosen Pengampu</div>
                    <div class="mk-section-sub">
                        Perbarui data dosen pengampu untuk mata kuliah ini.
                    </div>

                    <div class="mk-form-grid">
                        <div class="mk-field">
                            <label for="nama_dosen">Nama Dosen Pengampu</label>
                            <input type="text" id="nama_dosen" name="nama_dosen"
                                   class="@error('nama_dosen') is-invalid @enderror"
                                   value="{{ old('nama_dosen', $matakuliah->nama_dosen) }}">
                            @error('nama_dosen')
                                <div class="mk-invalid">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mk-field">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" id="jabatan" name="jabatan"
                                   class="@error('jabatan') is-invalid @enderror"
                                   value="{{ old('jabatan', $matakuliah->jabatan) }}">
                            @error('jabatan')
                                <div class="mk-invalid">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mk-field">
                            <label for="nip">NIP</label>
                            <input type="text" id="nip" name="nip"
                                   class="@error('nip') is-invalid @enderror"
                                   value="{{ old('nip', $matakuliah->nip) }}">
                            @error('nip')
                                <div class="mk-invalid">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mk-field">
                            <label for="no_telp">No. Telepon</label>
                            <input type="text" id="no_telp" name="no_telp"
                                   class="@error('no_telp') is-invalid @enderror"
                                   value="{{ old('no_telp', $matakuliah->no_telp) }}">
                            @error('no_telp')
                                <div class="mk-invalid">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="mk-actions">
                    <a href="{{ route('admins.matakuliah.index', ['kelas' => $matakuliah->kelas]) }}"
                       class="btn btn-outline-secondary mk-btn">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary mk-btn">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>
@endsection
