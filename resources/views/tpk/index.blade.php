<!DOCTYPE html>
<html>
<head>
    <title>Data TPK PBL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2 class="mb-4">Data Nilai PBL (Kelompok & Mahasiswa)</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row g-4">
    {{-- ====== KOLOM KELOMPOK ====== --}}
    <div class="col-12 col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0">Data Nilai Kelompok</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('tpk.kelompok.create') }}" class="btn btn-primary">+ Tambah Kelompok</a>
                @if($dataKelompok->total() > 0)
                    <a href="{{ route('tpk.kelompok.calculate') }}" class="btn btn-success">Hitung Peringkat</a>
                @endif
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th style="width:70px;">No</th>
                    <th>Nama Kelompok</th>
                    <th>Review UTS</th>
                    <th>Review UAS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataKelompok as $i => $item)
                    <tr>
                        <td>{{ $dataKelompok->firstItem() + $i }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->review_uts }}</td>
                        <td>{{ $item->review_uas }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada data kelompok.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- pagination kelompok (bawa query lain biar page mahasiswa tidak hilang) --}}
        {{ $dataKelompok->appends(request()->except('kelompok_page'))->links('pagination::bootstrap-5') }}
    </div>

    {{-- ====== KOLOM MAHASISWA ====== --}}
    <div class="col-12 col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0">Data Nilai Mahasiswa</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('tpk.mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
                @if($dataMahasiswa->total() > 0)
                    <a href="{{ route('tpk.mahasiswa.calculate') }}" class="btn btn-success">Hitung Peringkat</a>
                @endif
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th style="width:70px;">No</th>
                    <th>Nama</th>
                    <th>Keaktifan</th>
                    <th>Nilai Kelompok</th>
                    <th>Nilai Dosen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataMahasiswa as $i => $item)
                    <tr>
                        <td>{{ $dataMahasiswa->firstItem() + $i }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->keaktifan }}</td>
                        <td>{{ $item->nilai_kelompok }}</td>
                        <td>{{ $item->nilai_dosen }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Belum ada data mahasiswa.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- pagination mahasiswa (bawa query lain biar page kelompok tidak hilang) --}}
        {{ $dataMahasiswa->appends(request()->except('mahasiswa_page'))->links('pagination::bootstrap-5') }}
    </div>
</div>

</body>
</html>
