{{-- resources/views/kelompok/index.blade.php --}}
@extends('layouts.app') 

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelompok — SIMAP</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root{ --navy:#0b1d54; --navy-2:#0a1a47; --bg:#eef3fa; --card:#fff; --border:#dfe3eb; --text:#0b1d54; }
    *{box-sizing:border-box} body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);
      display:grid;grid-template-columns:240px 1fr;min-height:100vh;}

    /* Sidebar */
    .sidebar{background:linear-gradient(180deg,var(--navy),var(--navy-2));color:#e9edf7;padding:18px 16px;position:sticky;top:0;height:100vh;display:flex;flex-direction:column}
    .brand{display:flex;gap:10px;align-items:center;margin-bottom:22px}
    .logo{width:34px;height:34px;border-radius:8px;display:grid;place-items:center;background:#1d2e6f;color:#e5edff;font-weight:700}
    .title .main{font-weight:700}.title .sub{font-size:12px;opacity:.75}
    .section{font-size:11px;opacity:.65;margin:10px 10px 6px;text-transform:uppercase}
    .menu a{display:flex;gap:10px;align-items:center;color:#e9edf7;text-decoration:none;padding:10px 12px;border-radius:10px;margin:4px 6px;font-size:14px}
    .menu a:hover{background:rgba(255,255,255,.08)} .menu a.active{background:#1e3a8a}
    .spacer{flex:1}.logout{margin:10px 6px 6px}.logout a{color:#ffb1b1;text-decoration:none;display:flex;gap:8px}.logout a:hover{text-decoration:underline}

    /* Content */
    .content{padding:26px}
    h1{margin:0 0 12px;color:var(--text);font-size:22px;display:flex;gap:8px;align-items:center}
    .card{background:var(--card);border:1px solid var(--border);border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.04);padding:18px}
    .toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap}
    .filters{display:flex;align-items:center;gap:10px}
    select, input[type=text]{padding:8px 10px;border:1px solid #cfd6e3;border-radius:8px;background:#fff}
    .btn{border:none;border-radius:8px;padding:9px 14px;cursor:pointer;font-weight:700;font-size:14px}
    .btn-primary{background:var(--navy);color:#fff}.btn-primary:hover{background:#142f85}
    table{width:100%;border-collapse:collapse;background:#fff;border:1px solid var(--border);border-radius:10px;overflow:hidden}
    thead th{background:#f5f7fa;color:var(--text);text-align:left;padding:10px 12px;border-bottom:1px solid var(--border)}
    tbody td{padding:10px 12px;border-top:1px solid var(--border);vertical-align:top}
    .actions .btn-sm{border:none;border-radius:6px;padding:6px 10px;font-size:12px;color:#fff;cursor:pointer;margin-right:6px}
    .btn-view{background:#3b82f6}.btn-edit{background:#facc15;color:#0f172a}.btn-del{background:#ef4444}
    .btn-view:hover{background:#2563eb}.btn-edit:hover{background:#eab308}.btn-del:hover{background:#dc2626}

    /* Modal */
    .modal{position:fixed;inset:0;background:rgba(0,0,0,.35);display:none;align-items:center;justify-content:center;padding:16px}
    .modal .box{background:#fff;border-radius:12px;max-width:520px;width:100%;padding:18px;border:1px solid var(--border)}
    .modal h3{margin-top:0;color:var(--text)}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
    .grid .full{grid-column:1/-1}
    .modal .actions{display:flex;justify-content:flex-end;gap:8px;margin-top:10px}
    .btn-light{background:#e9efff}
    @media (max-width:640px){ body{grid-template-columns:1fr} .sidebar{position:relative;height:auto} .grid{grid-template-columns:1fr}}
  </style>
</head>
<body>

  {{-- Sidebar --}}
  <aside class="sidebar">
    <div class="brand">
      <div class="logo">SI</div>
      <div class="title">
        <div class="main">SIMAP</div>
        <div class="sub">Politala</div>
      </div>
    </div>

    <div class="section">Menu</div>
    <nav class="menu">
      <a href="dosen/dashboard" class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="#"><i class="fa-solid fa-user-graduate"></i> Mahasiswa</a>
      <a href="{{ route('kelompok.index') }}"><i class="fa-solid fa-users"></i> Kelompok</a>
      <a href="#"><i class="fa-solid fa-flag-checkered"></i> Milestone</a>
      <a href="#"><i class="fa-solid fa-book"></i> Logbook</a>
      <a href="#"><i class="fa-solid fa-list-check"></i> CPMK</a>
    </nav>

    <div class="section">Akun</div>
    <nav class="menu">
      <a href="#"><i class="fa-solid fa-id-badge"></i> Profil</a>
    </nav>

    <div class="spacer"></div>
    <div class="logout">
      <a href="#"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  {{-- Content --}}
  <main class="content">
    <h1><i class="fa-solid fa-users"></i> Kelompok</h1>

    @if(session('success'))
      <div style="background:#e7f7ec;border:1px solid #b9ebc8;color:#185b2a;padding:10px 12px;border-radius:8px;margin-bottom:12px">
        {{ session('success') }}
      </div>
    @endif

    <div class="card">
      <div class="toolbar">
        <form method="GET" action="{{ route('kelompok.index') }}" class="filters">
          <label for="kelas"><strong>Filter Kelas:</strong></label>
          <select name="kelas" id="kelas" onchange="this.form.submit()">
            <option value="all" {{ $kelasFilter==='all'?'selected':'' }}>Semua Kelas</option>
            @foreach($kelases as $k)
              <option value="{{ $k }}" {{ $kelasFilter===$k?'selected':'' }}>{{ $k }}</option>
            @endforeach
          </select>
        </form>

        <button class="btn btn-primary" onclick="openModal()">+ Tambah Kelompok</button>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Nama Kelompok</th>
              <th>Anggota</th>
              <th>Kelas</th>
              <th>Dosen Pembimbing</th>
              <th style="width:170px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($data as $row)
              <tr>
                <td>{{ $row->nama }}</td>
                <td>{{ $row->anggota ?? '-' }}</td>
                <td>{{ $row->kelas }}</td>
                <td>{{ $row->dosen_pembimbing ?? '-' }}</td>
                <td class="actions">
                  <a class="btn-sm btn-view" href="#">View</a>
                  <a class="btn-sm btn-edit" href="#">Edit</a>
                  <form action="{{ route('kelompok.destroy', $row->id) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-sm btn-del" onclick="return confirm('Hapus kelompok ini?')">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" style="text-align:center;color:#6b7280">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>

  {{-- Modal Tambah --}}
  <div class="modal" id="modal">
    <div class="box">
      <h3>Tambah Kelompok</h3>
      <form method="POST" action="{{ route('kelompok.store') }}">
        @csrf
        <div class="grid">
          <div class="full">
            <label>Nama Kelompok</label>
            <input type="text" name="nama" required placeholder="Kelompok 1" style="width:100%">
          </div>
          <div>
            <label>Kelas</label>
            <input type="text" name="kelas" required placeholder="TI-3A" style="width:100%">
          </div>
          <div>
            <label>Dosen Pembimbing</label>
            <input type="text" name="dosen_pembimbing" placeholder="Bpk/Ibu ..." style="width:100%">
          </div>
          <div class="full">
            <label>Anggota (pisahkan dengan koma)</label>
            <input type="text" name="anggota" placeholder="Nama1, Nama2, ..." style="width:100%">
          </div>
        </div>
        <div class="actions">
          <button type="button" class="btn btn-light" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const m = document.getElementById('modal');
    function openModal(){ m.style.display='flex'; }
    function closeModal(){ m.style.display='none'; }
    // close modal on background click
    m.addEventListener('click', (e)=>{ if(e.target===m) closeModal(); });
  </script>
</body>
</html>
