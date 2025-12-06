{{-- resources/views/dosen/logbook/table.blade.php --}}

<table class="table-auto w-full">
    <thead>
    <tr>
        <th>TANGGAL</th>
        <th>MINGGU</th>
        <th>AKTIVITAS</th>
        <th>KETERANGAN</th>

        {{-- Kolom aksi hanya muncul kalau tidak readonly --}}
        @if(empty($readonly))
            <th>AKSI</th>
        @endif
    </tr>
    </thead>

    <tbody>
    @foreach($logbooks as $logbook)
        <tr>
            <td>{{ $logbook->tanggal }}</td>
            <td>{{ $logbook->minggu }}</td>
            <td>{{ $logbook->aktivitas }}</td>
            <td>{{ $logbook->keterangan }}</td>

            @if(empty($readonly))
                <td>
                    <a class="btn btn-info">Detail</a>
                    <a class="btn btn-warning">Edit</a>
                    <a class="btn btn-danger">Hapus</a>
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
<table class="min-w-full text-sm text-left">
    <thead>
        <tr class="border-b text-slate-500 text-xs uppercase">
            <th class="py-3 pr-4">Tanggal</th>
            <th class="py-3 pr-4">Minggu</th>
            <th class="py-3 pr-4">Aktivitas</th>
            <th class="py-3 pr-4">Keterangan</th>
        </tr>
    </thead>
    <tbody class="text-slate-700">
    @forelse ($logbooks as $logbook)
        <tr class="border-b last:border-0">
            <td class="py-3 pr-4 whitespace-nowrap">
                {{ $logbook->tanggal }}
            </td>
            <td class="py-3 pr-4">
                {{ $logbook->minggu }}
            </td>
            <td class="py-3 pr-4">
                {{ $logbook->aktivitas }}
            </td>
            <td class="py-3 pr-4">
                {{ $logbook->keterangan }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="py-6 text-center text-slate-400">
                Belum ada logbook dari mahasiswa.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
