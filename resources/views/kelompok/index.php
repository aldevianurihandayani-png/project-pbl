<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelompok — Dosen Pembimbing</title>
  <style>
    :root {
      --navy: #0b1d54;
      --light-bg: #eef3fa;
      --white: #fff;
      --shadow: rgba(13,23,84,.1);
    }

    * {
      box-sizing: border-box;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      margin: 0;
      background: var(--light-bg);
      display: grid;
      grid-template-columns: 240px 1fr;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      background: var(--navy);
      color: #e9edf7;
      padding: 20px;
    }

    .sidebar h2 {
      font-size: 18px;
      margin-bottom: 30px;
    }

    .menu a {
      display: block;
      color: #e9edf7;
      text-decoration: none;
      padding: 10px 12px;
      border-radius: 8px;
      margin-bottom: 8px;
    }

    .menu a:hover {
      background: #12306d;
    }

    .menu a.active {
      background: #1c3d86;
    }

    /* Content */
    .content {
      padding: 30px;
    }

    h1 {
      color: var(--navy);
      font-size: 24px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    h1 i {
      color: #3a4d96;
    }

    /* Table container */
    .card {
      background: var(--white);
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 10px var(--shadow);
      margin-top: 20px;
      max-width: 800px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid #d3d8e8;
      padding: 10px 15px;
      text-align: left;
    }

    th {
      background: #f4f6fb;
      color: var(--navy);
      font-weight: bold;
    }

    td {
      background: #fff;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <div style="font-weight:700">SIMAP POLITALA</div>
    <div style="font-size:12px;opacity:.8">Dosen Pembimbing</div>
    <nav class="menu" style="margin-top:12px">
      <a href="/dashboard/dosen">Dashboard</a>
      <a href="/dosen/kelompok">Kelompok</a>
      <a href="/dosen/logbook">Logbook</a>
      <a class="active" href="{{ route('dosen.milestone') }}">Milestone</a>
      <a href="#">Profil</a>
    </nav>
  </aside>

  <!-- Main content -->
  <div class="content">
    <h1>👥 Kelompok</h1>
    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Nama Kelompok</th>
            <th>Anggota</th>
            <th>Dosen Pembimbing</th>
          </tr>
        </thead>
       <tbody>
  @forelse($mahasiswa as $m)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $m->nim }}</td>
      <td>{{ $m->nama }}</td>
      <td>{{ $m->angkatan }}</td>
      <td>{{ $m->no_hp }}</td>
      <td class="text-right">
        <a href="{{ route('mahasiswa.edit', $m->nim) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('mahasiswa.destroy', $m->nim) }}" method="POST" style="display:inline-block">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
        </form>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="6" class="text-center">Belum ada data mahasiswa.</td>
    </tr>
  @endforelse
</tbody>
      </table>
    </div>
  </div>
</body>
</html>
