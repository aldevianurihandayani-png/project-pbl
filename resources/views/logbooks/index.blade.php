@extends('layouts.mahasiswa')

@section('title', 'Logbook')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Logbook</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    
    @if(auth()->check() && (auth()->user()->role == 'mahasiswa' || auth()->user()->role == 'admin'))
    <div class="mb-4">
        <a href="{{ route('logbooks.create') }}" class="btn btn-primary tambah-logbook">
            <i class="bi bi-plus-lg"></i> Tambah Logbook
        </a>
    </div>
    @endif

    
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Minggu</th>
                        <th>Aktivitas</th>
                        <th>Keterangan</th>
                        <th style="width:130px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($logbooks as $logbook)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($logbook->tanggal)->format('Y-m-d') }}</td>
                        <td>{{ $logbook->minggu }}</td>
                        <td>{{ $logbook->aktivitas }}</td>
                        <td>{{ $logbook->keterangan }}</td>
                       <td>
  <div class="aksi">
    <a href="{{ route('logbooks.show', $logbook->id) }}" 
       class="btn-icon btn-info" title="Detail">
      <i class="bi bi-view-list"></i>
    </a>

    <a href="{{ route('logbooks.edit', $logbook->id) }}" 
       class="btn-icon btn-warning" title="Edit">
      <i class="bi bi-pencil-square"></i>
    </a>

    <form action="{{ route('logbooks.destroy', $logbook->id) }}" 
          method="POST" class="d-inline">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn-icon btn-danger" title="Hapus"
              onclick="return confirm('Yakin ingin menghapus logbook ini?')">
        <i class="bi bi-trash3-fill"></i>
      </button>
    </form>
  </div>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data logbook.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
