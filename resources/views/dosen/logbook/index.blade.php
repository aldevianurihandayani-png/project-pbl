@extends('layouts.dosen')

@section('title', 'Logbook Mahasiswa')

@section('content')
    <div class="container-fluid px-4 py-4">
        <h4 class="mb-4 fw-bold text-black">Dashboard Mahasiswa</h4>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header rounded-top-4"
                 style="background-color:#002b5b; color:#ffffff;">
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
                                <th class="text-center">NILAI</th>
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
                                    <td>{{ \Illuminate\Support\Str::limit($logbook->aktivitas, 60) }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($logbook->keterangan, 80) }}</td>

                                    <td class="text-center">
                                        @if($logbook->nilai)
                                            <span class="badge bg-success">{{ $logbook->nilai }}</span>
                                        @else
                                            <span class="badge bg-secondary">Belum dinilai</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('dosen.logbook.show', $logbook->id) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        Belum ada data logbook.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($logbooks, 'links'))
                    <div class="p-3">
                        {{ $logbooks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
