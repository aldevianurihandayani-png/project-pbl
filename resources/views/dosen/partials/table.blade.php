@props([
  // Paginasi/Collection logbook
  'data',

  // 'mahasiswa' | 'dosen'
  'role' => 'mahasiswa',

  // Tampilkan tombol tambah (khusus mahasiswa)
  'showAddButton' => false,
])

@php
  // helper route name sesuai role
  $editRoute = $role === 'dosen' ? 'dosen.logbook.edit' : 'logbooks.edit';
  $destroyRoute = $role === 'dosen' ? 'dosen.logbook.destroy' : 'logbooks.destroy';
  $toggleRoute = 'dosen.logbook.toggleStatus';
@endphp

<div class="card shadow mb-4" style="border-radius:16px">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Logbook</h4>

      @if($showAddButton)
        <a href="{{ route('logbooks.create') }}" class="btn btn-primary">
          <i class="fa fa-plus me-1"></i> Tambah Logbook
        </a>
      @endif
    </div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead style="background:#0b1d54;color:#fff">
          <tr>
            <th>TANGGAL</th>
            <th>MINGGU</th>
            <th>AKTIVITAS</th>
            <th>KETERANGAN</th>

            {{-- Kolom khusus dosen --}}
            @if($role === 'dosen')
              <th width="120">STATUS</th>
            @endif

            <th class="text-end" width="180">AKSI</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $row)
            <tr>
              <td>{{ \Illuminate\Support\Carbon::parse($row->tanggal)->format('Y-m-d') }}</td>
              <td>{{ $row->minggu }}</td>
              <td>{{ $row->aktivitas }}</td>
              <td>{{ $row->keterangan }}</td>

              @if($role === 'dosen')
                <td>
                  @php
                    $badge = [
                      'approved' => 'success',
                      'pending'  => 'secondary',
                      'rejected' => 'danger',
                    ][$row->status ?? 'pending'] ?? 'secondary';
                  @endphp
                  <span class="badge bg-{{ $badge }}">{{ ucfirst($row->status ?? 'pending') }}</span>
                </td>
              @endif

              <td class="text-end">
                {{-- Detail/lihat (opsional) --}}
                <a href="#" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>

                {{-- Edit --}}
                <a href="{{ route($editRoute, $row) }}" class="btn btn-sm btn-warning">
                  <i class="fa fa-pen"></i>
                </a>

                {{-- Tombol toggle status khusus dosen --}}
                @if($role === 'dosen')
                  <form action="{{ route($toggleRoute, $row) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Ubah status logbook ini?')">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm btn-primary"><i class="fa fa-rotate"></i></button>
                  </form>
                @endif

                {{-- Delete --}}
                <form action="{{ route($destroyRoute, $row) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Hapus logbook ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ $role==='dosen' ? 6 : 5 }}" class="text-center py-4">
                Belum ada data.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $data->links() }}
    </div>
  </div>
</div>
