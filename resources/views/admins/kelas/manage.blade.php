@extends('layouts.admin')

@section('page_title', 'Manajemen Kelas')

@section('content')
<div class="container-fluid">

    <h1 style="font-size: 32px; margin-bottom: 16px;">Manajemen Kelas (BARU)</h1>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 12px;">
            {{ session('success') }}
        </div>
    @endif

    <p>
        Daftar Kelas
        <a href="{{ route('admins.kelas.create') }}">Tambah Kelas</a>
    </p>

    <table border="0" cellpadding="4" cellspacing="0">
        <thead>
        <tr>
            <th style="padding-right: 20px;">ID</th>
            <th style="padding-right: 40px;">Nama Kelas</th>
            <th style="padding-right: 40px;">Semester</th>
            <th style="padding-right: 60px;">Periode</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($daftarKelas as $kelas)
            <tr>
                <td>{{ $kelas->id }}</td>
                <td>{{ $kelas->nama_kelas }}</td>
                <td>{{ $kelas->semester }}</td>
                <td>{{ $kelas->periode }}</td>
                <td>
                    {{-- link Edit ke route resource --}}
                    <a href="{{ route('admins.kelas.edit', $kelas->id) }}">Edit</a>

                    {{-- tombol Hapus pakai method DELETE --}}
                    <form action="{{ route('admins.kelas.destroy', $kelas->id) }}"
                          method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('Yakin ingin menghapus kelas ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach

        @if(empty($daftarKelas) || count($daftarKelas) === 0)
            <tr>
                <td colspan="5">Belum ada data kelas.</td>
            </tr>
        @endif
        </tbody>
    </table>

</div>
@endsection
