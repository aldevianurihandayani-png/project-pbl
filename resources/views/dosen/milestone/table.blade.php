{{-- resources/views/dosen/milestone/index.blade.php --}}
@extends('dosen.layout')

@section('title', 'Daftar Milestone')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Milestone</h1>

    {{-- Alert sukses (opsional, bisa dihapus kalau ga perlu) --}}
    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Card utama --}}
    <div class="card my-4 border-0 shadow-sm">
        <div class="card-body">

            {{-- Bar atas: form cari saja, tanpa tombol Tambah --}}
            <div class="d-flex justify-content-between align-items-center mb-3">

                {{-- Form cari --}}
                <form method="GET" action="{{ route('dosen.milestone.index') }}" class="d-flex" style="gap:.5rem;">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari deskripsi..."
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>

                {{-- Tidak ada tombol "+ Tambah Milestone" di sini --}}
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">DESKRIPSI</th>
                            <th scope="col">TANGGAL</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">PROYEK</th>
                            {{-- Tidak ada kolom AKSI --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($milestones as $milestone)
                            <tr>
                                <td>{{ $milestone->id }}</td>
                                <td>{{ $milestone->deskripsi }}</td>
                                <td>{{ $milestone->tanggal }}</td>
                                <td>
                                    @if($milestone->status === 'Selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $milestone->proyek ?? '—' }}
                                </td>
                                {{-- Tidak ada tombol Edit / Hapus --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Belum ada milestone.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination (opsional, kalau pakai paginate() di controller) --}}
            @if(method_exists($milestones, 'links'))
                <div class="mt-3">
                    {{ $milestones->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
