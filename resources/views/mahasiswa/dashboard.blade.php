@extends('layouts.mahasiswa')

@section('title','Dashboard — Mahasiswa')
@section('page_title','Dashboard Mahasiswa')

@section('content')
      <!-- KPI -->
      <section class="kpi">
        <div class="card">
          <div class="icon"><i class="fa-solid fa-users"></i></div>
          <div class="meta"><small>Anggota Kelompok</small><br><b>{{ $anggotaKelompok ?? 5 }}</b></div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-regular fa-clipboard"></i></div>
          <div class="meta"><small>Logbook Terkumpul</small><br><b>{{ $jumlahLogbook ?? 12 }}</b></div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-solid fa-flag-checkered"></i></div>
          <div class="meta"><small>Milestone Selesai</small><br><b>{{ ($milestoneSelesai ?? 3).'/'.($totalMilestone ?? 5) }}</b></div>
        </div>
        <div class="card">
          <div class="icon"><i class="fa-solid fa-star-half-stroke"></i></div>
          <div class="meta"><small>Nilai Sementara</small><br><b>{{ $nilaiAkhir ?? 86 }}</b></div>
        </div>
      </section>

      <!-- Jadwal Milestone + Progress -->
      <section class="card">
        <div class="card-hd"><i class="fa-solid fa-flag"></i> Milestone & Progress</div>
        <div class="card-bd">
          @php
            if (!function_exists('getStatusClass')) {
              function getStatusClass($status) {
                  $map = [
                      'Selesai'   => 'ok',
                      'Pending'   => 'warn',
                      'Belum'     => 'danger',
                      'menunggu'  => 'warn',
                      'disetujui' => 'ok',
                      'ditolak'   => 'danger',
                  ];
                  return $map[$status] ?? '';
              }
            }
          @endphp

          <div style="display:grid;grid-template-columns:1.2fr .8fr;gap:16px">
            <div>
              <table>
                <thead>
                  <tr><th>Tanggal</th><th>Milestone</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                  @forelse ($milestones ?? [] as $milestone)
                    <tr>
                      <td>{{ $milestone->deadline }}</td>
                      <td>{{ $milestone->kegiatan }}</td>
                      <td><span class="pill {{ getStatusClass($milestone->status) }}">{{ $milestone->status }}</span></td>
                      <td><button class="pill" style="border-color:#cbd5e1;background:#fff">Detail</button></td>
                    </tr>
                  @empty
                    <tr><td colspan="4" class="muted" style="text-align:center">Belum ada data milestone.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div>
              <div class="muted" style="margin-bottom:6px">Progress Kelompok</div>
              <div class="progress">
                <span style="width: {{ isset($progress) && is_numeric($progress) ? $progress : 64 }}%;"></span>
              </div>
              <div class="muted" style="margin-top:6px">{{ $progress ?? 64 }}%</div>

              <div class="muted" style="margin:14px 0 6px">Status Logbook</div>
              <ul class="clean">
                <li>Minggu 5: <strong>Disetujui</strong></li>
                <li>Minggu 6: <strong>Menunggu Review</strong></li>
                <li>Minggu 7: <strong>Belum Submit</strong></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- Logbook Ringkas -->
      <section class="card">
        <div class="card-hd"><i class="fa-regular fa-clipboard"></i> Logbook Terakhir</div>
        <div class="card-bd">
          <table>
            <thead><tr><th>Minggu</th><th>Ringkasan</th><th>Status</th></tr></thead>
            <tbody>
              @forelse ($logbooks ?? [] as $logbook)
                <tr>
                  <td>{{ $logbook->minggu }}</td>
                  <td>{{ $logbook->aktivitas }}</td>
                  <td><span class="pill {{ getStatusClass($logbook->status) }}">{{ $logbook->status }}</span></td>
                </tr>
              @empty
                <tr><td colspan="3" class="muted" style="text-align:center">Belum ada data logbook.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>

      <!-- Laporan Penilaian -->
      <section class="card" style="margin-bottom:28px">
        <div class="card-hd"><i class="fa-solid fa-file-lines"></i> Laporan Penilaian</div>
        <div class="card-bd">
          <table>
            <thead><tr><th>Komponen</th><th>Bobot</th><th>Skor</th><th>Nilai Akhir</th></tr></thead>
            <tbody>
              <tr><td>Kedisiplinan</td><td>10%</td><td>85</td><td>8.5</td></tr>
              <tr><td>Logbook</td><td>25%</td><td>90</td><td>22.5</td></tr>
              <tr><td>Milestone</td><td>35%</td><td>88</td><td>30.8</td></tr>
              <tr><td>Presentasi</td><td>30%</td><td>84</td><td>25.2</td></tr>
              <tr><th colspan="3" style="text-align:right">Total</th><th>{{ $nilaiAkhir ?? 86 }}</th></tr>
            </tbody>
          </table>
          <p class="muted" style="margin-top:8px">
            Unduh versi PDF dari Laporan Penilaian tersedia pada menu <em>Laporan Penilaian</em>.
          </p>
        </div>
      </section>
@endsection