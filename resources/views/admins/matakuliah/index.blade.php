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
                    <tr>
                        <td colspan="6" class="text-center">Hello World</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admins.partials.footer')