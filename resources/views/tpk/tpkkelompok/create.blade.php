<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Kelompok PBL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2 class="mb-4">Tambah Data Penilaian Kelompok PBL</h2>

<form action="{{ route('tpk.kelompok.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama Kelompok</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Review UTS</label>
        <input type="number" step="0.01" name="review_uts" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Review UAS</label>
        <input type="number" step="0.01" name="review_uas" class="form-control" required>
    </div>

    <button class="btn btn-success" type="submit">Simpan</button>
    <a href="{{ route('tpk.kelompok.index') }}" class="btn btn-secondary">Kembali</a>
</form>

</body>
</html>
