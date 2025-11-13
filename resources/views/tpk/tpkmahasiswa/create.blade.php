<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data TPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2 class="mb-4">Tambah Data TPK Mahasiswa</h2>

<form action="{{ route('tpk.mahasiswa.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama Mahasiswa</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Keaktifan</label>
        <input type="number" name="keaktifan" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nilai Kelompok</label>
        <input type="number" name="nilai_kelompok" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nilai Dosen</label>
        <input type="number" step="0.01" name="nilai_dosen" class="form-control" required>
    </div>

    <button class="btn btn-success" type="submit">Simpan</button>
    <a href="{{ route('tpk.mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
</form>

</body>
</html>
