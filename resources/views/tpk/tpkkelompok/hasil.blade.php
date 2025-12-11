<!DOCTYPE html>
<html>
<head>
    <title>Hasil Perhitungan Kelompok PBL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Hasil Perhitungan Peringkat Kelompok PBL</h2>
<h4 class="text-success mt-3">
    Kelompok Terbaik: <b>{{ $best_group['nama'] }}</b>
</h4>

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
            <td>Review UTS</td>
            <td>{{ round($weights['review_uts'], 4) }}</td>
        </tr>
        <tr>
            <td>Review UAS</td>
            <td>{{ round($weights['review_uas'], 4) }}</td>
        </tr>
    </tbody>
</table>

<h5>Peringkat Kelompok</h5>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Peringkat</th>
            <th>Nama Kelompok</th>
            <th>Review UTS</th>
            <th>Review UAS</th>
            <th>Skor SAW</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ranking as $i => $row)
        <tr @if($i == 0) class="table-success" @endif>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row['nama'] }}</td>
            <td>{{ $row['review_uts'] }}</td>
            <td>{{ $row['review_uas'] }}</td>
            <td>{{ $row['score'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('tpk.kelompok.index') }}" class="btn btn-primary mt-3">Kembali</a>

</body>
</html>
