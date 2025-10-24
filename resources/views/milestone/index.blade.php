@extends('layouts.mahasiswa')

@section('title', 'Milestone — Mahasiswa')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-4">
    <h1 class="mb-3">Daftar Milestone</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <form class="d-flex" method="get" action="{{ route('mahasiswa.milestone.index') }}">
            <input class="form-control me-2" type="search" name="q" value="{{ $q ?? '' }}" placeholder="Cari deskripsi...">
            <button class="btn btn-outline-primary" type="submit">Cari</button>
        </form>
        <a href="{{ route('mahasiswa.milestone.create') }}" class="btn btn-primary">+ Tambah Milestone</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Proyek</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $row)
                        <tr>
                            <td>{{ $row->id_milestone }}</td>
                            <td>{{ $row->deskripsi }}</td>
                            <td>{{ optional($row->tanggal)->format('Y-m-d') }}</td>
                            <td>
                                @if($row->status)
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </td>
                            <td>{{ $row->proyek->judul ?? '—' }}</td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="{{ route('mahasiswa.milestone.edit', $row->id_milestone) }}">Edit</a>
                                <form action="{{ route('mahasiswa.milestone.destroy', $row->id_milestone) }}" method="post" class="d-inline" onsubmit="return confirm('Hapus milestone ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $data->links() }}
    </div>
</div>
@endsection
