{{-- resources/views/koordinator/dashboard.blade.php --}}
@extends('layouts.koordinator')

@section('title', 'Dashboard — Koordinator')
@section('page_title', 'Dashboard Koordinator')

@section('content')
  <style>
    /* KPI cards */
    .kpi{ display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:16px }
    .kpi .card{
      background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); padding:16px 18px;
      display:flex; align-items:center; gap:12px; border:1px solid var(--ring);
    }
    .kpi .icon{ width:36px; height:36px; border-radius:10px; background:#eef3ff; display:grid; place-items:center; color:var(--navy-2) }
    .kpi .meta small{ color:var(--muted) }
    .kpi .meta b{ font-size:22px; color:var(--navy-2) }
    .kpi .meta span{ font-size:11px; color:var(--muted) }

    /* Section cards */
    .card{
      background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); border:1px solid var(--ring);
    }
    .card .card-hd{ padding:14px 18px; border-bottom:1px solid #eef1f6; display:flex; align-items:center; justify-content:space-between; gap:10px; color:var(--navy-2); font-weight:700 }
    .card .card-hd span.small{ font-size:11px; font-weight:400; color:var(--muted); }
    .card .card-bd{ padding:16px 18px; color:#233042 }
    .muted{ color:var(--muted) }

    .grid-2{ display:grid; grid-template-columns:2fr 1.1fr; gap:18px; }
    .grid-bottom{ display:grid; grid-template-columns:1.5fr 1.2fr; gap:18px; }

    /* table mini */
    .table-mini{ width:100%; border-collapse:collapse; font-size:12px; }
    .table-mini th, .table-mini td{ padding:6px 4px; text-align:left; }
    .table-mini th{
      font-size:11px; text-transform:uppercase; letter-spacing:.06em;
      color:#9ca3af; border-bottom:1px solid #e3e7f2;
    }
    .table-mini tr + tr td{ border-top:1px solid #f0f2f8; }
    .table-mini tbody tr:hover{ background:#f7f8fe; }

    .tag{ font-size:11px; padding:3px 7px; border-radius:999px; background:#e4ebff; color:#273b90; white-space:nowrap; }
    .tag-ok{ background:#dcfce7; color:#166534; }
    .tag-warn{ background:#fef9c3; color:#854d0e; }
    .tag-bad{ background:#fee2e2; color:#b91c1c; }

    .list{ list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:6px; }
    .list-item{
      font-size:13px; padding:6px 0; display:flex; justify-content:space-between; gap:10px; align-items:flex-start;
      border-bottom:1px dashed #eef1f6;
    }
    .list-text{ max-width:80%; }
    .list-sub{ font-size:11px; color:var(--muted); }
    .badge-pill{ font-size:11px; padding:3px 7px; border-radius:999px; background:#eef3ff; color:#273b90; white-space:nowrap; }

    .progress-wrap{ margin-top:6px; }
    .progress{ height:6px; border-radius:999px; background:#e5e7f3; overflow:hidden; }
    .progress > div{ height:100%; background:linear-gradient(90deg,#2563eb,#4f46e5); }

    @media (max-width: 1100px){
      .grid-2, .grid-bottom{ grid-template-columns:1fr; }
      .kpi{ grid-template-columns:1fr; }
    }
  </style>

  <!-- KPI -->
  <section class="kpi">
    <div class="card">
      <div class="icon"><i class="fa-solid fa-users"></i></div>
      <div class="meta">
        <small>Jumlah Kelompok Aktif</small><br>
        <b>{{ $jumlahKelompok ?? 8 }}</b><br>
        <span>{{ $jumlahMahasiswa ?? 100 }} Mahasiswa</span>
      </div>
    </div>

    <div class="card">
      <div class="icon"><i class="fa-solid fa-book"></i></div>
      <div class="meta">
        <small>Logbook Minggu Ini</small><br>
        <b>{{ $logbookMasuk ?? 76 }}</b><br>
        <span>{{ $logbookDisetujui ?? 58 }} disetujui, {{ $logbookTerlambat ?? 4 }} terlambat</span>
      </div>
    </div>

    <div class="card">
      <div class="icon"><i class="fa-solid fa-chart-line"></i></div>
      <div class="meta">
        <small>Rata-rata Nilai Kelompok</small><br>
        <b>{{ $rataNilai ?? 87 }}</b><br>
        <span>Kelompok terbaik: {{ $kelompokTerbaik ?? 'Kelompok B - Diskominfo' }}</span>
      </div>
    </div>
  </section>

  <!-- ROW 2 : Logbook & Milestone -->
  <section class="grid-2">
    <!-- Aktivitas Logbook -->
    <section class="card">
      <div class="card-hd">
        <div><i class="fa-solid fa-clipboard-check"></i>&nbsp; Aktivitas Logbook Terbaru</div>
        <span class="small">5 update terakhir mahasiswa</span>
      </div>
      <div class="card-bd" style="overflow-x:auto;">
        <table class="table-mini">
          <thead>
            <tr>
              <th>Mahasiswa</th>
              <th>Kelompok</th>
              <th>Minggu</th>
              <th>Status</th>
              <th>Update</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentLogbooks ?? [] as $item)
              <tr>
                <td>{{ $item->mahasiswa }}</td>
                <td>{{ $item->kelompok }}</td>
                <td>M{{ $item->minggu }}</td>
                <td>
                  @if($item->status === 'Disetujui')
                    <span class="tag tag-ok">Disetujui</span>
                  @elseif($item->status === 'Revisi')
                    <span class="tag tag-warn">Perlu Revisi</span>
                  @else
                    <span class="tag">Menunggu</span>
                  @endif
                </td>
                <td>{{ $item->updated_at->diffForHumans() }}</td>
              </tr>
            @empty
              <tr><td>Fikri Alamsyah</td><td>Kelompok A - PT Telkom</td><td>M5</td><td><span class="tag tag-ok">Disetujui</span></td><td>10 menit lalu</td></tr>
              <tr><td>Nadia Salsabila</td><td>Kelompok B - Diskominfo</td><td>M5</td><td><span class="tag">Menunggu</span></td><td>30 menit lalu</td></tr>
              <tr><td>Dika Pratama</td><td>Kelompok C - RSUD Kota</td><td>M4</td><td><span class="tag tag-warn">Perlu Revisi</span></td><td>1 jam lalu</td></tr>
              <tr><td>Intan Puspitasari</td><td>Kelompok D - Dinas Pendidikan</td><td>M5</td><td><span class="tag tag-ok">Disetujui</span></td><td>2 jam lalu</td></tr>
              <tr><td>Rama Abdillah</td><td>Kelompok E - Bank Syariah</td><td>M3</td><td><span class="tag">Menunggu</span></td><td>3 jam lalu</td></tr>
            @endforelse
          </tbody>
        </table>

        <p class="muted" style="margin-top:10px;font-size:12px;">
          Ringkasan: <strong>{{ $logbookDisetujui ?? 58 }}</strong> logbook disetujui,
          <strong>{{ $logbookMenunggu ?? 14 }}</strong> menunggu verifikasi,
          <strong>{{ $logbookTerlambat ?? 4 }}</strong> terlambat.
        </p>
      </div>
    </section>

    <!-- Milestone -->
    <section class="card">
      <div class="card-hd">
        <div><i class="fa-solid fa-flag"></i>&nbsp; Milestone Periode Ini</div>
        <span class="small">Pantau deadline penting setiap kelompok</span>
      </div>
      <div class="card-bd">
        @php
          $milestones = $milestones ?? [
            ['judul'=>'Kickoff & Kontrak Belajar','tgl'=>'10 September 2025','status'=>'Selesai'],
            ['judul'=>'Review Tengah Program','tgl'=>'10 Oktober 2025','status'=>'Sedang Berjalan','progress'=>0.78],
            ['judul'=>'Submit Laporan Akhir','tgl'=>'20 November 2025','status'=>'Belum Dimulai','progress'=>0.1],
            ['judul'=>'Presentasi Akhir PPL','tgl'=>'25 November 2025','status'=>'Belum Dimulai','progress'=>0],
          ];
        @endphp

        <ul class="list">
          @foreach($milestones as $m)
            <li class="list-item">
              <div class="list-text">
                <strong>{{ $m['judul'] }}</strong>
                <div class="list-sub">
                  Deadline: {{ $m['tgl'] }}
                  @if(isset($m['status']))
                    &middot;
                    @if($m['status']==='Selesai')
                      <span class="tag tag-ok">{{ $m['status'] }}</span>
                    @elseif($m['status']==='Sedang Berjalan')
                      <span class="tag tag-warn">{{ $m['status'] }}</span>
                    @else
                      <span class="tag">{{ $m['status'] }}</span>
                    @endif
                  @endif
                </div>

                @if(isset($m['progress']))
                  <div class="progress-wrap">
                    <div class="progress"><div style="width:{{ $m['progress']*100 }}%"></div></div>
                  </div>
                @endif
              </div>

              <span class="badge-pill">
                {{ isset($m['progress']) ? (int)($m['progress']*100).'%' : '' }}
              </span>
            </li>
          @endforeach
        </ul>

        <p class="muted" style="margin-top:10px;font-size:12px;">
          Terdapat <strong>{{ $milestoneMepet ?? 3 }}</strong> kelompok dengan deadline &lt; 7 hari lagi —
          disarankan melakukan follow-up.
        </p>
      </div>
    </section>
  </section>

  <!-- ROW 3 : Peringkat & Notifikasi -->
  <section class="grid-bottom">
    <!-- Peringkat Kelompok -->
    <section class="card">
      <div class="card-hd">
        <div><i class="fa-solid fa-star"></i>&nbsp; Peringkat Kelompok</div>
        <span class="small">Top 5 berdasarkan nilai & progres logbook</span>
      </div>
      <div class="card-bd">
        @php
          $peringkat = $peringkat ?? [
            ['nama'=>'Kelompok B - Diskominfo','nilai'=>93,'progress'=>0.94],
            ['nama'=>'Kelompok A - PT Telkom','nilai'=>90,'progress'=>0.91],
            ['nama'=>'Kelompok E - Bank Syariah','nilai'=>88,'progress'=>0.89],
            ['nama'=>'Kelompok D - Dinas Pendidikan','nilai'=>84,'progress'=>0.83],
            ['nama'=>'Kelompok C - RSUD Kota','nilai'=>81,'progress'=>0.78],
          ];
        @endphp

        <ul class="list">
          @foreach($peringkat as $i => $row)
            <li class="list-item">
              <div class="list-text">
                <strong>#{{ $i+1 }} {{ $row['nama'] }}</strong>
                <div class="list-sub">
                  Nilai akhir: <b>{{ $row['nilai'] }}</b> &middot;
                  Progres logbook: <b>{{ (int)($row['progress']*100) }}%</b>
                </div>
                <div class="progress-wrap">
                  <div class="progress"><div style="width:{{ $row['progress']*100 }}%"></div></div>
                </div>
              </div>
              <span class="badge-pill"><i class="fa-solid fa-trophy"></i> {{ $row['nilai'] }}</span>
            </li>
          @endforeach
        </ul>

        <p class="muted" style="margin-top:10px;font-size:12px;">
          Posisi rata-rata kelas: <strong>{{ $infoPeringkat ?? 'Top 25% dari seluruh kelas' }}</strong>.
        </p>
      </div>
    </section>

    <!-- Notifikasi -->
    <section class="card" style="margin-bottom:28px">
      <div class="card-hd">
        <div><i class="fa-regular fa-bell"></i>&nbsp; Notifikasi & Tindakan Koordinator</div>
        <span class="small">Hal yang perlu segera dicek</span>
      </div>
      <div class="card-bd">
        <ul class="list">
          <li class="list-item">
            <div class="list-text">
              <strong>2 Mahasiswa belum mengisi logbook minggu ini</strong>
              <div class="list-sub">Sistem sudah mengirimkan pengingat otomatis ke mahasiswa terkait.</div>
            </div>
            <span class="tag tag-bad">Perlu follow-up</span>
          </li>
          <li class="list-item">
            <div class="list-text">
              <strong>Dosen pembimbing menambahkan catatan baru</strong>
              <div class="list-sub">Kelompok C - RSUD Kota &middot; Revisi lingkup pekerjaan.</div>
            </div>
            <span class="badge-pill">Catatan logbook</span>
          </li>
          <li class="list-item">
            <div class="list-text">
              <strong>Pengajuan perubahan jadwal presentasi</strong>
              <div class="list-sub">Kelompok E - Bank Syariah mengajukan pindah ke 28 November 2025.</div>
            </div>
            <span class="tag tag-warn">Menunggu respon</span>
          </li>
          <li class="list-item">
            <div class="list-text">
              <strong>Rekap nilai otomatis berhasil disinkronkan</strong>
              <div class="list-sub">Total {{ $jumlahMahasiswa ?? 100 }} nilai mahasiswa diperbarui dari sistem.</div>
            </div>
            <span class="tag tag-ok">Sistem</span>
          </li>
        </ul>
      </div>
    </section>
  </section>
@endsection
