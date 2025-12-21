@extends('layouts.mahasiswa')

@section('title','Kelompok — Mahasiswa')
@section('page_title','Kelompok')

@section('content')
  <div class="page">

    {{-- CARD UTAMA --}}
    <section class="card">
      <div class="card-hd" style="display:flex;align-items:center;justify-content:space-between;gap:12px">
        <div style="display:flex;align-items:center;gap:10px">
          <i class="fa-solid fa-users"></i>
          <span>Data Kelompok</span>
        </div>
      </div>

      <div class="card-bd">

        {{-- TABEL --}}
        <div class="table-container">
          <table class="table">
            <thead>
              <tr>
                <th>Nama Kelompok</th>
                <th>Kelas</th>
                <th>Semester</th>
                <th>Dosen Pembimbing</th>
                <th>Status</th>
                <th style="text-align:right">Aksi</th>
              </tr>
            </thead>

            <tbody>
              @forelse(($kelompoks ?? []) as $k)
                <tr>
                  <td>{{ $k->nama ?? '-' }}</td>
                  <td>{{ $k->kelas ?? '-' }}</td>
                  <td>{{ $k->semester ?? '-' }}</td>
                  <td>{{ $k->dosen_pembimbing ?? '-' }}</td>
                  <td>
                    @php
                      $status = strtolower($k->status ?? 'menunggu');
                      $pill = in_array($status, ['disetujui','aktif','selesai'])
                        ? 'ok'
                        : (in_array($status, ['menunggu','pending']) ? 'warn' : 'danger');
                    @endphp
                    <span class="pill {{ $pill }}">{{ $k->status ?? 'Menunggu' }}</span>
                  </td>

                  <td style="text-align:right">
                    <div class="aksi">

                      {{-- ✅ PENILAIAN ANGGOTA KELOMPOK --}}
                      <a href="{{ route('mahasiswa.kelompok.penilaian_anggota', ['kelompok_id' => $k->id]) }}"
                         class="btn-icon btn-primary"
                         title="Penilaian Anggota Kelompok">
                        <i class="fa-solid fa-star"></i>
                      </a>

                      {{-- (opsional) tombol lain kalau masih mau dipakai --}}
                      <a href="#" class="btn-icon btn-info" title="Detail">
                        <i class="fa-regular fa-eye"></i>
                      </a>

                      <a href="#" class="btn-icon btn-warning" title="Edit">
                        <i class="fa-regular fa-pen-to-square"></i>
                      </a>

                      <form action="#" method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus kelompok?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-danger" title="Hapus">
                          <i class="fa-regular fa-trash-can"></i>
                        </button>
                      </form>

                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="muted" style="text-align:center;padding:18px 12px">
                    Tidak ada data kelompok.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </section>

  </div>
@endsection
