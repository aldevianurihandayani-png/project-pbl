@include('admins.partials.header', ['title' => 'Manajemen Logbook'])

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Logbook</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mahasiswa</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logbooks as $logbook)
                        <tr>
                            <td>{{ $logbook->id }}</td>
                            <td>{{ $logbook->user->name ?? 'N/A' }}</td>
                            <td>{{ $logbook->tanggal->format('d M Y') }}</td>
                            <td>{{ Str::limit($logbook->deskripsi, 50) }}</td>
                            <td>
                                <span class="badge badge-{{ $logbook->status == 'Disetujui' ? 'success' : 'warning' }}">{{ $logbook->status }}</span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data logbook.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         <div class="d-flex justify-content-center">
            {{ $logbooks->links() }}
        </div>
    </div>
</div>

@include('admins.partials.footer')
