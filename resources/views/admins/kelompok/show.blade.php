@include('admins.partials.header', ['title' => 'Detail Kelompok'])

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Proyek</h6>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Nama Kelompok</dt>
                    <dd class="col-sm-8">{{ $kelompok->nama }}</dd>

                    <dt class="col-sm-4">Judul Proyek</dt>
                    <dd class="col-sm-8">{{ $kelompok->judul }}</dd>

                    <dt class="col-sm-4">Kelas</dt>
                    <dd class="col-sm-8">{{ $kelompok->kelas }}</dd>

                    <dt class="col-sm-4">Dosen Pembimbing</dt>
                    <dd class="col-sm-8">{{ $kelompok->dosen_pembimbing }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Anggota Kelompok</h6>
            </div>
            <div class="card-body">
                @if($kelompok->anggotas && $kelompok->anggotas->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach ($kelompok->anggotas as $anggota)
                            <li class="list-group-item">{{ $anggota->nama }} ({{ $anggota->nim }})</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center text-muted">Belum ada anggota.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<a href="{{ route('admins.kelompok.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>

@include('admins.partials.footer')
