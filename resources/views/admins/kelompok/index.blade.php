@include('admins.partials.header', ['title' => 'Manajemen Kelompok'])

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Kelompok</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kelompok</th>
                        <th>Judul Proyek</th>
                        <th>Kelas</th>
                        <th>Dosen Pembimbing</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelompoks as $kelompok)
                        <tr>
                            <td>{{ $kelompok->id }}</td>
                            <td>{{ $kelompok->nama }}</td>
                            <td>{{ $kelompok->judul }}</td>
                            <td>{{ $kelompok->kelas }}</td>
                            <td>{{ $kelompok->dosen_pembimbing }}</td>
                            <td>
<<<<<<< HEAD
<<<<<<< HEAD
                                <a href="{{ route('admins.kelompok.show', $kelompok) }}" class="btn btn-info btn-sm">Detail</a>
=======
                                <a href="#" class="btn btn-info btn-sm">Detail</a>
>>>>>>> 7e4c8f16039fdbc37547803aea3ddc7c4610d0c4
=======
                                <a href="#" class="btn btn-info btn-sm">Detail</a>
>>>>>>> bbcfba2 (commit noorma)
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data kelompok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         <div class="d-flex justify-content-center">
            {{ $kelompoks->links() }}
        </div>
    </div>
</div>

@include('admins.partials.footer')
