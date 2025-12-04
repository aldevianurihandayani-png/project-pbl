{{-- resources/views/dosen/logbook/table.blade.php --}}
@extends('layouts.dosen') {{-- ganti jika layout-mu beda --}}

@section('title', 'Logbook Mahasiswa')

@section('content')
    <div class="container-fluid px-4 py-4">

        {{-- judul di bagian atas konten --}}
        <h4 class="mb-4 fw-bold text-white">Dashboard Mahasiswa</h4>

        {{-- pesan sukses kalau ada --}}
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- card tabel logbook --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header rounded-top-4"
                 style="background-color: #002b5b; color: #ffffff;">
                <strong>Logbook Mahasiswa</strong>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead style="background-color:#002b5b; color:#ffffff;">
                            <tr>
                                <th class="text-center">TANGGAL</th>
                                <th class="text-center">MINGGU</th>
                                <th class="text-center">AKTIVITAS</th>
                                <th class="text-center">KETERANGAN</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logbooks as $logbook)
                                <tr>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($logbook->tanggal)->format('Y-m-d') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $logbook->minggu }}
                                    </td>
                                    <td>
                                        {{ $logbook->aktivitas }}
                                    </td>
                                    <td>
                                        {{ $logbook->keterangan }}
                                    </td>
                                    <td class="text-center">
                                        {{-- contoh tombol lihat / detail --}}
                                        <a href="{{ route('dosen.logbook.show', $logbook->id) }}"
                                           class="btn btn-sm"
                                           style="background-color:#0d6efd; color:#ffffff;">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- kalau dosen boleh edit / hapus, buka komen di bawah --}}
                                        {{-- 
                                        <a href="{{ route('dosen.logbook.edit', $logbook->id) }}"
                                           class="btn btn-sm"
                                           style="background-color:#ffc107; color:#000000;">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('dosen.logbook.destroy', $logbook->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus logbook ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm"
                                                    style="background-color:#dc3545; color:#ffffff;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        Belum ada data logbook.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- kalau pakai pagination --}}
                @if (method_exists($logbooks, 'links'))
                    <div class="p-3">
                        {{ $logbooks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
