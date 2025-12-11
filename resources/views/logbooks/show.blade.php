{{-- resources/views/logbooks/show.blade.php --}}
@extends('layouts.mahasiswa')

@section('title', 'Detail Logbook')

@section('content')
<div class="container my-5">
  <h1 class="mb-4">Detail Logbook</h1>

  @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- CARD INFORMASI LOGBOOK --}}
  <div class="detail-card mb-4">
    <div class="detail-card__header">
      <span>Informasi Logbook</span>
    </div>

    <div class="detail-card__body">
      <div class="detail-row">
        <div class="detail-label">Tanggal</div>
        <div class="detail-value">
          {{ \Carbon\Carbon::parse($logbook->tanggal)->format('d-m-Y') }}
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Aktivitas</div>
        <div class="detail-value">{{ $logbook->aktivitas }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Keterangan</div>
        <div class="detail-value">{{ $logbook->keterangan }}</div>
      </div>

      @if($logbook->foto)
        <div class="detail-row mt-3">
          <div class="detail-label">Dokumentasi</div>
          <div class="detail-value">
            <img src="{{ asset('storage/' . $logbook->foto) }}" 
                 alt="Foto Logbook" 
                 class="logbook-foto">
          </div>
        </div>
      @endif
    </div>
  </div>

  {{-- CARD KOMENTAR DOSEN PEMBIMBING / MAHASISWA --}}
  <div class="detail-card">
    <div class="detail-card__header">
      <span>Komentar Dosen Pembimbing</span>
    </div>

    <div class="detail-card__body">
      {{-- LIST KOMENTAR --}}
      @forelse ($feedback as $fb)
        <div class="detail-row mb-3 pb-3" style="border-bottom:1px solid #f0f0f0;">
          <div class="detail-label">
            {{ \Carbon\Carbon::parse($fb->tanggal)->format('d-m-Y H:i') }}
            <div class="small text-muted text-uppercase">
              Status: {{ $fb->status }}
            </div>
          </div>
          <div class="detail-value">
            {{ $fb->isi }}
          </div>
        </div>
      @empty
        <p class="text-muted mb-3">Belum ada komentar.</p>
      @endforelse

      {{-- FORM KOMENTAR MAHASISWA --}}
      <form action="{{ route('logbooks.feedback.store', $logbook->id) }}" method="POST" class="mt-2">
        @csrf
        <div class="mb-2">
          <label for="isi" class="form-label fw-semibold">Tulis Komentar</label>
          <textarea
            name="isi"
            id="isi"
            rows="3"
            class="form-control @error('isi') is-invalid @enderror"
            placeholder="Tulis komentar kepada dosen pembimbing..."
          >{{ old('isi') }}</textarea>
          @error('isi')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-sm">
          Kirim Komentar
        </button>
      </form>
    </div>
  </div>

  <a href="{{ route('logbooks.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
