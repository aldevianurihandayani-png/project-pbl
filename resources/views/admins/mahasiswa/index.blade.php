@include('admins.partials.header', ['title' => 'Manajemen Mahasiswa'])

<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Mahasiswa</h6>
        <a href="{{ route('admins.mahasiswa.create') }}" class="btn btn-primary btn-sm">Tambah Mahasiswa</a>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
<<<<<<< HEAD
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tanggal Registrasi</th>
=======
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Angkatan</th>
                        <th>No. HP</th>
>>>>>>> bbcfba2 (commit noorma)
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswas as $mahasiswa)
                        <tr>
<<<<<<< HEAD
                            <td>{{ $mahasiswa->id }}</td>
                            <td>{{ $mahasiswa->name }}</td>
                            <td>{{ $mahasiswa->email }}</td>
                            <td>{{ $mahasiswa->created_at->format('d M Y') }}</td>
=======
                            <td>{{ $mahasiswa->nim }}</td>
                            <td>{{ $mahasiswa->nama }}</td>
                            <td>{{ $mahasiswa->user->email }}</td>
                            <td>{{ $mahasiswa->angkatan }}</td>
                            <td>{{ $mahasiswa->no_hp }}</td>
>>>>>>> bbcfba2 (commit noorma)
                            <td>
                                <a href="{{ route('admins.mahasiswa.edit', $mahasiswa) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admins.mahasiswa.destroy', $mahasiswa) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
<<<<<<< HEAD
                            <td colspan="5" class="text-center">Tidak ada data mahasiswa.</td>
=======
                            <td colspan="6" class="text-center">Tidak ada data mahasiswa.</td>
>>>>>>> bbcfba2 (commit noorma)
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         <div class="d-flex justify-content-center">
            {{ $mahasiswas->links() }}
        </div>
    </div>
</div>

@include('admins.partials.footer')
