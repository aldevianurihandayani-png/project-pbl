@extends('layouts.koordinator')

@section('title', 'Edit Peringkat')

@section('content')

<div class="page">
    <div class="card" style="max-width:520px;margin:auto;">
        <div class="card-hd">
            <i class="fa-solid fa-pen"></i>
            Edit Peringkat {{ $peringkat->jenis === 'kelompok' ? 'Kelompok' : 'Mahasiswa' }}
        </div>

        <div class="card-bd">

            <form method="POST" action="{{ route('koordinator.peringkat.update', $peringkat->id) }}">
                @csrf
                @method('PUT')

                {{-- NAMA --}}
                <div style="margin-bottom:12px;">
                    <label>Nama</label>
                    <input type="text" class="form-control" disabled
                        value="{{ $peringkat->nama_tpk ?? $peringkat->mahasiswa->nama ?? '-' }}">
                </div>

                {{-- NILAI --}}
                <div style="margin-bottom:12px;">
                    <label>Nilai Total (0 – 1)</label>
                    <input type="number" step="0.0001" name="nilai_total"
                        class="form-control" required
                        value="{{ $peringkat->nilai_total }}">
                </div>

                {{-- PERINGKAT --}}
                <div style="margin-bottom:16px;">
                    <label>Peringkat</label>
                    <input type="number" name="peringkat"
                        class="form-control" required
                        value="{{ $peringkat->peringkat }}">
                </div>

                <div style="display:flex;gap:10px;">
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Simpan
                    </button>

                    <a href="{{ route('koordinator.peringkat.index') }}"
                       class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
