@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-3">Data Mahasiswa</h1>

  <div class="mb-3 d-flex justify-content-between gap-2">
    <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah</a>

    <form method="GET" action="{{ route('mahasiswa.index') }}" class="d-flex gap-2">
      <input type="text" name="search" class="form-control"
             placeholder="Cari NIM/Nama/Angkatan/No HP"
             value="{{ request('search') }}">
      <button type="submit" class="btn btn-secondary">Cari</button>
    </form>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>#</th>
        <th>NIM</th>
        <th>Nama</th>
        <th>Angkatan</th>
        <th>No HP</th>
        <th class="text-end">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($mahasiswa as $m)
        <tr>
          {{-- pakai $loop->iteration; kalau pakai paginate dan mau nomor global: $mahasiswa->firstItem() + $loop->index --}}
          <td>{{ $loop->iteration }}</td>
          <td>{{ $m->nim }}</td>
          <td>{{ $m->nama }}</td>
          <td>{{ $m->angkatan }}</td>
          <td>{{ $m->no_hp }}</td>
          <td class="text-end">
            {{-- DEFAULT: resource pakai ID --}}
            <a href="{{ route('mahasiswa.edit', $m->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('mahasiswa.destroy', $m->id) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">Hapus</button>
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

  {{-- tampilkan pagination jika pakai paginate() --}}
  @if(method_exists($mahasiswa,'links'))
    {{ $mahasiswa->withQueryString()->links() }}
  @endif
</div>
@endsection
