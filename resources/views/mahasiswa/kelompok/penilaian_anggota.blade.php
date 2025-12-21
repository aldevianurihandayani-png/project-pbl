@extends('layouts.mahasiswa')

@section('title','Penilaian Anggota Kelompok')
@section('page_title','Penilaian Anggota Kelompok')

@section('content')
<div class="page">
  <section class="card">
    <div class="card-hd" style="display:flex;align-items:center;justify-content:space-between;gap:12px">
      <div style="display:flex;align-items:center;gap:10px">
        <i class="fa-solid fa-users"></i>
        <span>Penilaian Anggota Kelompok</span>
      </div>

      @if(isset($sudahIsi) && $sudahIsi)
        <span class="pill ok">Sudah Diisi</span>
      @else
        <span class="pill warn">Belum Diisi</span>
      @endif
    </div>

    <div class="card-bd">
      {{-- ALERT --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      {{-- JIKA TIDAK PUNYA KELOMPOK --}}
      @if(!$kelompok)
        <p class="muted">Kamu belum memiliki kelompok.</p>
      @else

        {{-- INFO --}}
        <div class="muted" style="margin-bottom:12px;">
          <b>Kelompok:</b> {{ $kelompok->nama_kelompok ?? ($kelompok->nama ?? ($kelompok->judul ?? ('Kelompok #'.$kelompok->id))) }} <br>

          {{-- NAMA + NIM LOGIN --}}
          <b>Nama:</b> {{ $namaPenilai ?? '-' }} <br>
          <b>NIM:</b> {{ $nim }}
        </div>

        {{-- JIKA TIDAK ADA ANGGOTA --}}
        @if(count($anggota) == 0)
          <p class="muted">Tidak ada anggota lain untuk dinilai.</p>
        @else

          <form method="POST" action="{{ route('mahasiswa.kelompok.penilaian_anggota.store', ['kelompok_id' => $kelompok->id]) }}">
            @csrf

            <div class="table-container" style="margin-top:12px;">
              <table class="table">
                <thead>
                  <tr>
                    <th style="width:60px;">No</th>
                    <th>Anggota</th>
                    <th style="width:160px;">Nilai</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($anggota as $idx => $a)
                    @php
                      $existing = $nilaiSaya[$a->nim] ?? null;
                    @endphp

                    <tr>
                      <td>{{ $idx + 1 }}</td>
                      <td>
                        <div style="font-weight:700">{{ $a->nama ?? '-' }}</div>
                        <div class="muted">{{ $a->nim }}</div>
                        <input type="hidden" name="dinilai_nim[]" value="{{ $a->nim }}">
                      </td>

                      <td>
                        <input
                          type="number"
                          name="nilai[]"
                          min="80"
                          max="100"
                          class="form-control"
                          value="{{ old('nilai.'.$idx, $existing->nilai ?? '') }}"
                          {{ $sudahIsi ? 'readonly' : '' }}
                          required
                        >
                        <small class="muted">80–100</small>
                      </td>

                      <td>
                        <textarea
                          name="keterangan[]"
                          class="form-control"
                          rows="2"
                          {{ $sudahIsi ? 'readonly' : '' }}
                        >{{ old('keterangan.'.$idx, $existing->keterangan ?? '') }}</textarea>
                        <small class="muted">Contoh: aktif / cukup aktif / kurang berkontribusi</small>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            @if(!$sudahIsi)
              <button class="tambah-logbook" type="submit" style="margin-top:12px;">
                Simpan Penilaian
              </button>
            @endif
          </form>

        @endif
      @endif
    </div>
  </section>
</div>
@endsection
