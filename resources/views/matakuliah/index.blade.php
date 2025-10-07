@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h1 class="mb-3">Data Matakuliah</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="d-flex gap-2 mb-3">
    <a href="{{ route('matakuliah.create') }}" class="btn btn-primary">+ Tambah</a>

    <form class="ms-auto d-flex gap-2" method="GET" action="{{ route('matakuliah.index') }}">
      <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari kode/nama">
      <button class="btn btn-secondary">Cari</button>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Kode</th>
          <th>Nama</th>
          <th>SKS</th>
          <th>Semester</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($matakuliah as $mk)
          <tr>
            <td>{{ ($matakuliah->currentPage()-1)*$matakuliah->perPage() + $loop->iteration }}</td>
            <td>{{ $mk->kode }}</td>
            <td><a href="{{ route('matakuliah.show', $mk) }}">{{ $mk->nama }}</a></td>
            <td>{{ $mk->sks }}</td>
            <td>{{ $mk->semester ?? '-' }}</td>
            <td class="text-end">
              <a href="{{ route('matakuliah.edit', $mk) }}" class="btn btn-sm btn-warning">Edit</a>
              <form action="{{ route('matakuliah.destroy', $mk) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus matakuliah ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $matakuliah->links() }}
</div>
@endsection
