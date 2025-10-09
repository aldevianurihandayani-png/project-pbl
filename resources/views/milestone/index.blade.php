<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Milestone — Dosen Pembimbing</title>
  <style>
    :root{--navy:#0b1d54;--bg:#eef3fa;--ring:rgba(13,23,84,.12);}
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);
         display:grid;grid-template-columns:240px 1fr;min-height:100vh}

    /* Sidebar */
    .sidebar{background:var(--navy);color:#e9edf7;padding:18px}
    .menu a{display:block;color:#e9edf7;text-decoration:none;padding:10px 12px;border-radius:10px;margin-bottom:8px}
    .menu a:hover{background:#12306d}.menu a.active{background:#1c3d86}

    /* Content */
    .content{padding:28px}
    h1{margin:0 0 14px;color:var(--navy);font-size:26px;font-weight:800}
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 26px var(--ring);padding:18px}
    .toolbar{display:flex;gap:10px;justify-content:flex-end;margin-bottom:10px}
    .search{display:flex;gap:8px}
    .search input{width:320px;padding:10px;border:1px solid #cbd5e1;border-radius:10px}
    .search button{border:0;background:var(--navy);color:#fff;border-radius:10px;padding:10px 14px}

    /* Table */
    .table-wrap{overflow:auto}
    table{width:100%;border-collapse:separate;border-spacing:0 10px;table-layout:fixed}
    thead th{font-size:13px;color:#334155;padding:10px 12px;text-align:center;background:#f0f4ff}
    tbody tr{background:#fff;box-shadow:0 2px 6px rgba(0,0,0,.06)}
    tbody td{padding:12px;text-align:center;border-top:1px solid #eef2f7;border-bottom:1px solid #eef2f7}
    thead th+th,tbody td+td{border-left:1px solid #e6edf6}
    tbody tr td:first-child{border-radius:10px 0 0 10px}
    tbody tr td:last-child{border-radius:0 10px 10px 0}

    /* Lebar kolom */
    th:nth-child(1),td:nth-child(1){width:6%}
    th:nth-child(2),td:nth-child(2){width:10%}
    th:nth-child(3),td:nth-child(3){width:32%;text-align:left}
    th:nth-child(4),td:nth-child(4){width:16%}
    th:nth-child(5),td:nth-child(5){width:22%}
    th:nth-child(6),td:nth-child(6){width:14%}

    /* Pills status */
    .status-group{display:flex;gap:8px;justify-content:center;flex-wrap:wrap}
    .status-group form{display:inline;margin:0}  /* <— bikin form tombol sejajar */
    .chip{border:1px solid #cbd5e1;background:#fff;color:#0b1220;padding:8px 14px;border-radius:999px;font-size:13px}
    .chip.btn{cursor:pointer;border:0}
    .chip.active{box-shadow:0 0 0 2px rgba(13,23,84,.08) inset;font-weight:600}
    .chip.belum.active{background:#fee2e2;color:#991b1b;border-color:#fecaca}
    .chip.pending.active{background:#fef3c7;color:#92400e;border-color:#fde68a}
    .chip.selesai.active{background:#dcfce7;color:#166534;border-color:#bbf7d0}

    /* Aksi info */
    .btnMuted{background:#94a3b8;color:#fff;border:0;border-radius:10px;padding:8px 12px}
    .note{color:#64748b;font-size:12px}
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <div style="font-weight:700">SIMAP POLITALA</div>
    <div style="font-size:12px;opacity:.8">Dosen Pembimbing</div>
    <nav class="menu" style="margin-top:12px">
      <a href="/dosen/dashboard">Dashboard</a>
      <a href="/dosen/kelompok">Kelompok</a>
      <a href="/dosen/logbook">Logbook</a>
      <a class="active" href="{{ route('dosen.milestone') }}">Milestone</a>
      <a href="#">Profil</a>
    </nav>
  </aside>

  <!-- Content -->
  <main class="content">
    <h1>Milestone</h1>

    <div class="card">
      <div class="toolbar">
        <form class="search" method="GET" action="{{ url('/dosen/milestone') }}">
          <input type="text" name="q" placeholder="Cari minggu/kegiatan/deadline/status" value="{{ request('q') }}">
          <button type="submit">Cari</button>
        </form>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Minggu</th>
              <th>Kegiatan</th>
              <th>Deadline</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $allStatuses = ['Belum','Pending','Selesai']; @endphp
            @forelse($milestones as $m)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $m->minggu }}</td>
                <td>{{ $m->kegiatan }}</td>
                <td>{{ $m->deadline }}</td>

                <td>
                  <div class="status-group">
                    @foreach ($allStatuses as $st)
                      @php $cls = strtolower($st); @endphp

                      @if ($m->status === $st)
                        <!-- status aktif → pill non-klik -->
                        <span class="chip {{ $cls }} active">
                          {{ $st === 'Selesai' ? '✓ ' : '' }}{{ $st }}
                        </span>
                      @else
                        <!-- status lain → tombol (PATCH) -->
                        <form method="POST" action="{{ route('dosen.milestone.setStatus', [$m->id, $st]) }}">
                          @csrf @method('PATCH')
                          <button class="chip {{ $cls }} btn" type="submit">{{ $st }}</button>
                        </form>
                      @endif
                    @endforeach
                  </div>
                </td>

                <td>
                  @if($m->status==='Selesai')
                    <span class="btnMuted">Sudah Selesai</span>
                    <div class="note">
                      Disetujui:
                      {{ $m->approved_at ? \Illuminate\Support\Carbon::parse($m->approved_at)->format('d M Y H:i') : '-' }}
                    </div>
                  @else
                    <span class="note">Klik salah satu status untuk mengubah</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="background:#fff;border-radius:10px;padding:18px;color:#64748b">
                  Belum ada data milestone.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body>
</html>
