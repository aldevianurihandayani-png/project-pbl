@extends('layouts.dosen')

@section('title', 'Daftar Mahasiswa')

@section('content')
<div class="container">
    <h1>Daftar Mahasiswa</h1>

    @forelse ($mahasiswas as $mhs)
        <p>{{ $mhs->nama }}</p>
    @empty
        <p>Tidak ada mahasiswa.</p>
    @endforelse
</div>
@endsection
