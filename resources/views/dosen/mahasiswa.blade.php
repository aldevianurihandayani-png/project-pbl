<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Mahasiswa</title>
  <style>
    :root {
      --navy: #0b1d54;
      --blue: #2f73ff;
      --yellow: #ffcc00;
      --red: #e80000;
      --white: #fff;
      --gray-border: #ddd;
      --bg-light: #f9f9f9;
    }

    body {
      font-family: Arial, sans-serif;
      background: var(--bg-light);
      margin: 0;
      padding: 30px;
      color: #333;
    }

    h2 {
      color: var(--navy);
      font-size: 14px;
      margin-bottom: 20px;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .filter-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .filter-container label {
      font-size: 14px;
      color: var(--navy);
      font-weight: bold;
    }

    select {
      padding: 6px 10px;
      border: 1px solid var(--gray-border);
      border-radius: 5px;
    }

    .add-btn {
      background: var(--blue);
      color: var(--white);
      padding: 8px 18px;
      border-radius: 20px;
      text-decoration: none;
      font-weight: bold;
      font-size: 13px;
      border: none;
      cursor: pointer;
      transition: 0.3s;
    }

    .add-btn:hover {
      background: #0045c5;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: var(--white);
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      text-align: left;
      padding: 10px;
      border: 1px solid var(--gray-border);
      font-size: 14px;
    }

    th {
      background: #f0f3f9;
      color: var(--navy);
      font-weight: bold;
    }

    td {
      background: var(--white);
    }

    .action-buttons {
      display: flex;
      gap: 8px;
    }

    .btn {
      border: none;
      color: var(--white);
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 13px;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
    }

    .btn-view {
      background: var(--blue);
    }

    .btn-edit {
      background: var(--yellow);
      color: #000;
    }

    .btn-delete {
      background: var(--red);
    }

    .btn-view:hover { background: #0045c5; }
    .btn-edit:hover { background: #e0b000; }
    .btn-delete:hover { background: #c50000; }
  </style>
</head>
<body>

  <h2>DAFTAR MAHASISWA</h2>

  <div class="top-bar">
    <div class="filter-container">
      <label for="filter-kelas">Filter Kelas:</label>
      <select id="filter-kelas" onchange="filterKelas()">
        <option value="all">Semua</option>
        <option value="A">Kelas A</option>
        <option value="B">Kelas B</option>
        <option value="C">Kelas C</option>
        <option value="D">Kelas D</option>
        <option value="E">Kelas E</option>
      </select>
    </div>

    <button class="add-btn">TAMBAH MAHASISWA</button>
  </div>

  <table id="tabelMahasiswa">
    <thead>
      <tr>
        <th>NO</th>
        <th>NIM</th>
        <th>NAMA</th>
        <th>ANGKATAN</th>
        <th>NO HP</th>
        <th>KELAS</th>
        <th>ACTION</th>
      </tr>
    </thead>
    <tbody>
      <tr data-kelas="A">
        <td>1</td>
        <td>220101001</td>
        <td>Rina Saputri</td>
        <td>2022</td>
        <td>08123456789</td>
        <td>A</td>
        <td>
          <div class="action-buttons">
            <a href="#" class="btn btn-view">VIEW</a>
            <a href="#" class="btn btn-edit">EDIT</a>
            <a href="#" class="btn btn-delete">DELETE</a>
          </div>
        </td>
      </tr>
      <tr data-kelas="B">
        <td>2</td>
        <td>220101002</td>
        <td>Andi Pratama</td>
        <td>2022</td>
        <td>08129876543</td>
        <td>B</td>
        <td>
          <div class="action-buttons">
            <a href="#" class="btn btn-view">VIEW</a>
            <a href="#" class="btn btn-edit">EDIT</a>
            <a href="#" class="btn btn-delete">DELETE</a>
          </div>
        </td>
      </tr>
      <tr data-kelas="A">
        <td>3</td>
        <td>220101003</td>
        <td>Dewi Lestari</td>
        <td>2023</td>
        <td>08213344556</td>
        <td>A</td>
        <td>
          <div class="action-buttons">
            <a href="#" class="btn btn-view">VIEW</a>
            <a href="#" class="btn btn-edit">EDIT</a>
            <a href="#" class="btn btn-delete">DELETE</a>
          </div>
        </td>
      </tr>
    </tbody>
  </table>

  <script>
    function filterKelas() {
      const selected = document.getElementById('filter-kelas').value;
      const rows = document.querySelectorAll('#tabelMahasiswa tbody tr');
      rows.forEach(row => {
        if (selected === 'all' || row.dataset.kelas === selected) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }
  </script>

</body>
</html>
