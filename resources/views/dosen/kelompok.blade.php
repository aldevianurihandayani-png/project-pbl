<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Kelompok — SIMAP</title>
  <!-- Font Awesome (ikon sidebar) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    :root{
      --navy: #0b1d54;         /* warna brand */
      --navy-2: #0a1a47;       /* sedikit lebih gelap untuk gradient */
      --link: #2f66f5;
      --bg: #eef3fa;
      --card: #ffffff;
      --border: #dfe3eb;
      --text: #0b1d54;
    }
    *{ box-sizing: border-box; }
    body{
      margin:0;
      font-family: Arial, Helvetica, sans-serif;
      background: var(--bg);
      color:#14233b;
      display:grid;
      grid-template-columns: 240px 1fr; /* Sidebar + konten */
      min-height:100vh;
    }

    /* =========================
       SIDEBAR
    ==========================*/
    .sidebar{
      background: linear-gradient(180deg, var(--navy), var(--navy-2));
      color:#e9edf7;
      padding:18px 16px;
      position:sticky;
      top:0;
      height:100vh;
      display:flex;
      flex-direction:column;
    }
    .brand{
      display:flex; align-items:center; gap:10px; margin-bottom:22px;
    }
    .brand .logo{
      width:34px; height:34px; border-radius:8px;
      display:grid; place-items:center;
      background:#1d2e6f; color:#e5edff; font-weight:700;
    }
    .brand .title{ line-height:1.1; }
    .brand .title .main{ font-weight:700; letter-spacing:.3px; }
    .brand .title .sub{ font-size:12px; opacity:.75; }

    .section-label{
      font-size:11px; letter-spacing:.8px; opacity:.65;
      margin:14px 10px 8px; text-transform:uppercase;
    }
    .menu a{
      display:flex; align-items:center; gap:10px;
      text-decoration:none; color:#e9edf7;
      padding:10px 12px; border-radius:10px; margin:4px 6px;
      transition:.15s ease;
      font-size:14px;
    }
    .menu a:hover{ background:rgba(255,255,255,.08); }
    .menu a.active{
      background:#1e3a8a; /* biru highlight */
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.08);
    }
    .spacer{ flex:1; }
    .logout{
      margin:10px 6px 6px;
    }
    .logout a{
      color:#ffb1b1; text-decoration:none; font-size:14px;
      display:flex; align-items:center; gap:8px;
    }
    .logout a:hover{ text-decoration:underline; }

    /* =========================
       KONTEN
    ==========================*/
    .content{
      padding:26px;
    }
    h1{
      margin:0 0 12px; color: var(--text); font-size:22px;
      display:flex; gap:8px; align-items:center;
    }
    .card{
      background:var(--card); border:1px solid var(--border);
      border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,.04);
      padding:18px;
    }

    .toolbar{
      display:flex; justify-content:space-between; align-items:center;
      margin:0 0 14px;
      gap:12px; flex-wrap:wrap;
    }
    .filters{
      display:flex; align-items:center; gap:10px;
      color:#263144;
    }
    .filters select{
      padding:8px 10px; border-radius:8px; border:1px solid #cfd6e3; background:#fff;
    }
    .btn{
      border:none; border-radius:8px; padding:9px 14px;
      cursor:pointer; font-weight:700; font-size:14px;
    }
    .btn-primary{ background: var(--navy); color:#fff; }
    .btn-primary:hover{ background:#142f85; }

    table{
      width:100%; border-collapse:collapse; background:#fff;
      border:1px solid var(--border); border-radius:10px; overflow:hidden;
    }
    thead th{
      background:#f5f7fa; color:var(--text); text-align:left;
      padding:10px 12px; border-bottom:1px solid var(--border);
    }
    tbody td{
      padding:10px 12px; border-top:1px solid var(--border);
      vertical-align:top;
    }

    .actions .btn-sm{
      border:none; border-radius:6px; padding:6px 10px; font-size:12px;
      color:#fff; cursor:pointer; margin-right:6px;
    }
    .btn-view{ background:#3b82f6; }
    .btn-edit{ background:#facc15; color:#0f172a; }
    .btn-del{ background:#ef4444; }
    .btn-view:hover{ background:#2563eb; }
    .btn-edit:hover{ background:#eab308; }
    .btn-del:hover{ background:#dc2626; }

    @media (max-width: 880px){
      body{ grid-template-columns: 200px 1fr; }
    }
    @media (max-width: 640px){
      body{ grid-template-columns: 1fr; }
      .sidebar{ position:relative; height:auto; border-bottom:1px solid rgba(255,255,255,.15); }
    }
  </style>
</head>
<body>

  <!-- =================== SIDEBAR =================== -->
  <aside class="sidebar">
    <div class="brand">
      <div class="logo">SI</div>
      <div class="title">
        <div class="main">SIMAP</div>
        <div class="sub">Politala</div>
      </div>
    </div>

    <div class="section-label">Menu</div>
    <nav class="menu">
      <a href="#" class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
      <a href="#"><i class="fa-solid fa-user-graduate"></i> Mahasiswa</a>
      <a href="#"><i class="fa-solid fa-users"></i> Kelompok</a>
      <a href="#"><i class="fa-solid fa-flag-checkered"></i> Milestone</a>
      <a href="#"><i class="fa-solid fa-book"></i> Logbook</a>
      <a href="#"><i class="fa-solid fa-list-check"></i> CPMK</a>
    </nav>

    <div class="section-label" style="margin-top:14px;">Akun</div>
    <nav class="menu">
      <a href="#"><i class="fa-solid fa-id-badge"></i> Profil</a>
    </nav>

    <div class="spacer"></div>

    <div class="logout">
      <a href="#"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  <!-- =================== KONTEN =================== -->
  <main class="content">
    <h1><i class="fa-solid fa-users"></i> Kelompok</h1>

    <div class="card">
      <div class="toolbar">
        <div class="filters">
          <label for="filterKelas"><strong>Filter Kelas:</strong></label>
          <select id="filterKelas">
            <option value="all">Semua Kelas</option>
            <option value="TI-3A">TI-3A</option>
            <option value="TI-3B">TI-3B</option>
            <option value="TI-3C">TI-3C</option>
          </select>
        </div>
        <button class="btn btn-primary" onclick="tambahKelompok()">+ Tambah Kelompok</button>
      </div>

      <div class="table-wrap">
        <table id="tabelKelompok">
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
            <tr data-kelas="TI-3A">
              <td>Kelompok 1</td>
              <td>Aldevianuri Handayani, Fara Apriliana, Noorma, Wike Widiya Wati</td>
              <td>TI-3A</td>
              <td>Pa Oky Rahmanto</td>
              <td class="actions">
                <button class="btn-sm btn-view">View</button>
                <button class="btn-sm btn-edit">Edit</button>
                <button class="btn-sm btn-del" onclick="hapusBaris(this)">Delete</button>
              </td>
            </tr>
            <tr data-kelas="TI-3B">
              <td>Kelompok 2</td>
              <td>Deni, Eka, Fajar</td>
              <td>TI-3B</td>
              <td>Ibu Sari</td>
              <td class="actions">
                <button class="btn-sm btn-view">View</button>
                <button class="btn-sm btn-edit">Edit</button>
                <button class="btn-sm btn-del" onclick="hapusBaris(this)">Delete</button>
              </td>
            </tr>
            <tr data-kelas="TI-3C">
              <td>Kelompok 3</td>
              <td>Gina, Hadi, Indra</td>
              <td>TI-3C</td>
              <td>Pak Rafi</td>
              <td class="actions">
                <button class="btn-sm btn-view">View</button>
                <button class="btn-sm btn-edit">Edit</button>
                <button class="btn-sm btn-del" onclick="hapusBaris(this)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script>
    const filterSelect = document.getElementById('filterKelas');
    const tbody = document.querySelector('#tabelKelompok tbody');

    filterSelect.addEventListener('change', () => {
      const filter = filterSelect.value;
      [...tbody.rows].forEach(row => {
        row.style.display = (filter === 'all' || row.dataset.kelas === filter) ? '' : 'none';
      });
    });

    function hapusBaris(btn){
      if(confirm('Yakin ingin menghapus kelompok ini?')){
        btn.closest('tr').remove();
      }
    }

    function tambahKelompok(){
      const nama = prompt('Masukkan nama kelompok:'); if(!nama) return;
      const kelas = prompt('Masukkan kelas (misal TI-3A):'); if(!kelas) return;
      const anggota = prompt('Masukkan anggota (pisahkan koma):') || '-';
      const dosen = prompt('Masukkan dosen pembimbing:') || '-';

      const tr = document.createElement('tr');
      tr.dataset.kelas = kelas.trim();
      tr.innerHTML = `
        <td>${nama}</td>
        <td>${anggota}</td>
        <td>${kelas}</td>
        <td>${dosen}</td>
        <td class="actions">
          <button class="btn-sm btn-view">View</button>
          <button class="btn-sm btn-edit">Edit</button>
          <button class="btn-sm btn-del" onclick="hapusBaris(this)">Delete</button>
        </td>
      `;
      tbody.appendChild(tr);
    }
  </script>
</body>
</html>
