@extends('layouts.dosen')

@section('title', 'Detail Logbook')

@section('content')
<div class="container-fluid px-4 py-4">

    <h4 class="mb-4 fw-bold text-black">Detail Logbook Mahasiswa</h4>

    @if (session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header" style="background-color:#002b5b; color:#ffffff;">
            <strong>Detail Logbook</strong>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <label class="fw-semibold">Tanggal</label>
                <input type="text" class="form-control"
                       value="{{ \Carbon\Carbon::parse($logbook->tanggal)->format('Y-m-d') }}" readonly>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Minggu</label>
                <input type="text" class="form-control" value="{{ $logbook->minggu }}" readonly>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Aktivitas</label>
                <textarea class="form-control" rows="3" readonly>{{ $logbook->aktivitas }}</textarea>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Keterangan</label>
                <textarea class="form-control" rows="3" readonly>{{ $logbook->keterangan }}</textarea>
            </div>

            <hr>

            {{-- FORM NILAI + KOMENTAR --}}
            <form action="{{ route('dosen.logbook.nilai.update', $logbook->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="fw-semibold">Nilai Pembimbing (1–100)</label>
                    <input
                        type="number"
                        name="nilai"
                        class="form-control @error('nilai') is-invalid @enderror"
                        min="1"
                        max="100"
                        value="{{ old('nilai', $logbook->nilai) }}"
                        required
                    >
                    @error('nilai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Komentar / Catatan Pembimbing</label>
                    <textarea
                        name="komentar"
                        class="form-control @error('komentar') is-invalid @enderror"
                        rows="3"
                        placeholder="Tuliskan komentar (opsional)..."
                    >{{ old('komentar') }}</textarea>
                    @error('komentar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('dosen.logbook.index') }}" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan Nilai
                    </button>
                </div>
            </form>

            <hr>

            {{-- LIST KOMENTAR SEBELUMNYA --}}
            <h5 class="fw-semibold mb-3">Komentar Sebelumnya</h5>
            @forelse ($feedback as $fb)
                <div class="border rounded p-3 mb-2 bg-light">
                    <div class="small text-muted mb-1">
                        {{ $fb->tanggal }} • Status: {{ $fb->status }}
                    </div>
                    <div>{{ $fb->isi }}</div>
                </div>
            @empty
                <p class="text-muted">Belum ada komentar untuk logbook ini.</p>
            @endforelse

        </div>
    </div>
</div>
@endsection
