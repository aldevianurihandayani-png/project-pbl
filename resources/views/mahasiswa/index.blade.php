@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Data Mahasiswa</h1>

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah</a>
        <form method="GET" action="{{ route('mahasiswa.index') }}">
            <input type="text" name="search" placeholder="Cari NIM/Nama/Angkatan/No HP" value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Angkatan</th>
                <th>No HP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa as $i => $m)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $m->nim }}</td>
                <td>{{ $m->nama }}</td>
                <td>{{ $m->angkatan }}</td>
                <td>{{ $m->no_hp }}</td>
                <td>
                    <a href="{{ route('mahasiswa.edit', $m->nim) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('mahasiswa.destroy', $m->nim) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
