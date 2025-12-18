<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Penilaian PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { text-align: left; }
    </style>
</head>
<body>
    <h3>Rekap Penilaian</h3>

    <p>
        Matakuliah: {{ $matakuliahKode ?? '-' }} <br>
        Kelas: {{ $kelas ?? '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Kode MK</th>
                <th>Kelas</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penilaian as $p)
                <tr>
                    <td>{{ $p->mahasiswa->nim ?? $p->mahasiswa->npm ?? '-' }}</td>
                    <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                    <td>{{ $p->matakuliah_kode ?? '-' }}</td>
                    <td>{{ $p->kelas ?? '-' }}</td>
                    <td>{{ $p->nilai_akhir ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
