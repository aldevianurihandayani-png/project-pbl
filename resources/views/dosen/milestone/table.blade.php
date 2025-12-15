<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th width="60">No</th>
                <th>Judul</th>
                <th width="150">Tanggal</th>
                <th width="140">Status</th>
                <th width="320">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($milestones as $milestone)
                <tr>
                    <td>{{ ($milestones->currentPage() - 1) * $milestones->perPage() + $loop->iteration }}</td>

                    <td>
                        <div class="fw-semibold">{{ $milestone->judul }}</div>
                        @if(!empty($milestone->deskripsi))
                            <div class="text-muted small">
                                {{ \Illuminate\Support\Str::limit($milestone->deskripsi, 90) }}
                            </div>
                        @endif
                    </td>

                    <td>
                        {{ optional($milestone->tanggal)->format('d M Y') ?? $milestone->tanggal }}
                    </td>

                    <td>
                        @if ($milestone->status === 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif ($milestone->status === 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @endif
                    </td>

                    <td class="text-center">
                        {{-- DETAIL --}}
                        <a href="{{ route('dosen.milestone.show', $milestone) }}"
                           class="btn btn-outline-primary btn-sm">
                            Detail
                        </a>

                        @if ($milestone->status === 'menunggu')
                            {{-- SETUJUI --}}
                            <form action="{{ route('dosen.milestone.approve', $milestone) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="btn btn-success btn-sm"
                                        onclick="return confirm('Setujui milestone ini?')">
                                    Setujui
                                </button>
                            </form>

                            {{-- TOLAK --}}
                            <form action="{{ route('dosen.milestone.reject', $milestone) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Tolak milestone ini?')">
                                    Tolak
                                </button>
                            </form>
                        @else
                            <span class="text-muted ms-2">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Tidak ada data milestone.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $milestones->links() }}
</div>
