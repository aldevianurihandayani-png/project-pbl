<!DOCTYPE html>
<html>
<head>
    <title>Data TPK Mahasiswa PBL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2 class="mb-4">Data Nilai Mahasiswa PBL</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="mb-3">
    <a href="{{ route('tpk.mahasiswa.create') }}" class="btn btn-primary">+ Tambah Data</a>

    @if(count($data_tpk) > 0)
        <a href="{{ route('tpk.mahasiswa.calculate') }}" class="btn btn-success">Hitung Peringkat</a>
    @endif
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Keaktifan</th>
            <th>Nilai Kelompok</th>
            <th>Nilai Dosen</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data_tpk as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->keaktifan }}</td>
            <td>{{ $item->nilai_kelompok }}</td>
            <td>{{ $item->nilai_dosen }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
