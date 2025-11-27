<!DOCTYPE html>
<html>
<head>
    <title>Hasil Perhitungan TPK PBL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Hasil Perhitungan Peringkat Mahasiswa</h2>
<h4 class="text-success mt-3">Mahasiswa Terbaik: <b>{{ $best_student['nama'] }}</b></h4>

<hr>

<h5>Bobot Kriteria (AHP)</h5>
<table class="table table-bordered mb-4">
    <thead class="table-dark">
        <tr>
            <th>Kriteria</th>
            <th>Bobot</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Keaktifan</td>
            <td>{{ round($weights['keaktifan'], 4) }}</td>
        </tr>
        <tr>
            <td>Nilai Kelompok</td>
            <td>{{ round($weights['nilai_kelompok'], 4) }}</td>
        </tr>
        <tr>
            <td>Nilai Dosen</td>
            <td>{{ round($weights['nilai_dosen'], 4) }}</td>
        </tr>
    </tbody>
</table>

<h5>Peringkat Mahasiswa</h5>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Peringkat</th>
            <th>Nama</th>
            <th>Keaktifan</th>
            <th>Nilai Kelompok</th>
            <th>Nilai Dosen</th>
            <th>Skor SAW</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ranking as $i => $row)
        <tr @if($i == 0) class="table-success" @endif>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row['nama'] }}</td>
            <td>{{ $row['keaktifan'] }}</td>
            <td>{{ $row['nilai_kelompok'] }}</td>
            <td>{{ $row['nilai_dosen'] }}</td>
            <td>{{ $row['score'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('tpk.mahasiswa.index') }}" class="btn btn-primary mt-3">Kembali</a>

</body>
</html>
