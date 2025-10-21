@include('admins.partials.header', ['title' => 'Manajemen Mata Kuliah'])

<div class="card shadow mb-4">
        <div class="card-header">
        <a href="{{ route('admins.matakuliah.create') }}" class="btn btn-primary">Tambah Mata Kuliah</a>
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
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Dosen Pengampu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($matakuliah as $mk)
                        <tr>
                            <td>{{ $mk->kode_mk }}</td>
                            <td>{{ $mk->nama_mk }}</td>
                            <td>{{ $mk->sks }}</td>
                            <td>{{ $mk->semester }}</td>
                            <td>{{ $mk->dosen->name ?? 'N/A' }}</td>
                            <td class="d-flex">
                                <a href="{{ route('admins.matakuliah.edit', $mk) }}" class="btn btn-warning btn-sm mr-2">
                                    <i class="fa fa-pen"></i> Edit
                                </a>
                                <form action="{{ route('admins.matakuliah.destroy', $mk) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data mata kuliah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         <div class="d-flex justify-content-center">
            {{ dd(get_class($matakuliah)) }}
            {{ $matakuliah->links() }}
        </div>
    </div>
</div>

@include('admins.partials.footer')