@extends('layouts.koordinator')

@section('title', 'Tambah Nilai Kelompok PBL')
@section('content')

<style>
  .form-wrap { max-width: 720px; margin: 0 auto; }
  .form-label { display:block; font-weight:600; margin-bottom:6px; }
  .form-input{
      width:100%; padding:10px 12px; border:1px solid #dde2f0; border-radius:10px;
      outline:none; font-size:14px; background:#fff;
  }
  .form-input:focus{ border-color:#0e257a; box-shadow:0 0 0 3px rgba(14,37,122,.12); }
  .form-row{ margin-bottom:12px; }

  .btn-successx{ padding:8px 14px; border-radius:10px; background:#1b7a3a; color:#fff; border:none; cursor:pointer; }
  .btn-secondaryx{ padding:8px 14px; border-radius:10px; background:#6b7280; color:#fff; text-decoration:none; display:inline-block; }
</style>

<div class="page">
  <section class="card">
    <div class="card-hd">
      <i class="fa-solid fa-users"></i>
      Tambah Nilai Kelompok PBL
    </div>

    <div class="card-bd">
      <div class="form-wrap">

        {{-- error validate --}}
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
        <form method="GET" action="{{ route('koordinator.peringkat.createKelompok') }}" style="margin-bottom:14px;">
          <div class="form-row">
            <label class="form-label">Pilih Kelas</label>
            <select name="kelas" class="form-input" onchange="this.form.submit()">
              <option value="">-- Pilih Kelas --</option>
              @foreach($kelasList as $k)
                <option value="{{ $k }}" {{ ($kelasAktif === $k) ? 'selected' : '' }}>
                  {{ $k }}
                </option>
              @endforeach
            </select>
          </div>
        </form>

        {{-- FORM SIMPAN NILAI (POST) --}}
        <form action="{{ route('koordinator.peringkat.storeKelompok') }}" method="POST">
          @csrf

          {{-- wajib biar validasi kelas kepenuhi --}}
          <input type="hidden" name="kelas" value="{{ $kelasAktif }}">

          <div class="form-row">
            <label class="form-label">Kelompok (Kelas {{ $kelasAktif ?? '-' }})</label>

            <select name="kelompok_id" class="form-input" required {{ empty($kelasAktif) ? 'disabled' : '' }}>
              <option value="">-- Pilih Kelompok --</option>

              @foreach($kelompokList as $g)
                <option value="{{ $g->id }}" {{ old('kelompok_id') == $g->id ? 'selected' : '' }}>
                  {{ $g->nama }}
                </option>
              @endforeach
            </select>

            @if(empty($kelasAktif))
              <small style="display:block;margin-top:6px;color:#6b7280;">
                Pilih kelas dulu supaya list kelompok muncul.
              </small>
            @elseif($kelompokList->isEmpty())
              <small style="display:block;margin-top:6px;color:#6b7280;">
                Belum ada data kelompok untuk kelas ini.
              </small>
            @endif
          </div>

          <div class="form-row">
            <label class="form-label">Review UTS</label>
            <input type="number" step="0.01" name="review_uts" class="form-input" required
                   value="{{ old('review_uts') }}" placeholder="0 - 100">
          </div>

          <div class="form-row">
            <label class="form-label">Review UAS</label>
            <input type="number" step="0.01" name="review_uas" class="form-input" required
                   value="{{ old('review_uas') }}" placeholder="0 - 100">
          </div>

          <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:14px;">
            <button type="submit" class="btn-successx"
              {{ empty($kelasAktif) ? 'disabled style=opacity:.6;cursor:not-allowed;' : '' }}>
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

@endsection
