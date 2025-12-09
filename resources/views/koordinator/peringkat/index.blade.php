@extends('layouts.koordinator')

@section('title', 'Kelola Peringkat')

@section('content')

{{-- CSS RATA TENGAH UNTUK TABEL --}}
<style>
    .table-center th,
    .table-center td {
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>

<div class="page">

    <section class="card" style="margin-bottom:16px;">
        <div class="card-hd">
            <i class="fa-solid fa-ranking-star"></i>
            Kelola Peringkat
        </div>

        <div class="card-bd">

            {{-- Flash Success --}}
            @if(session('success'))
                <div style="padding:8px 12px;border-radius:8px;background:#e6ffed;color:#13653f;margin-bottom:10px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tombol Tambah --}}
            <a href="{{ route('koordinator.peringkat.create') }}"
               style="display:inline-block;margin-bottom:10px;padding:8px 14px;border-radius:10px;background:#0e257a;color:#fff;text-decoration:none;">
                <i class="fa-solid fa-plus"></i> Tambah Peringkat
            </a>

            {{-- TABLE --}}
            <div style="overflow-x:auto;">
                <table class="table-center" style="width:100%;border-collapse:collapse;font-size:14px;">
                    <thead>
                    <tr style="background:#f1f4ff;">
                        <th style="padding:8px;border-bottom:1px solid #dde2f0;">No</th>
                        <th style="padding:8px;border-bottom:1px solid #dde2f0;">Mahasiswa</th>
                        <th style="padding:8px;border-bottom:1px solid #dde2f0;">Mata Kuliah</th>
                        <th style="padding:8px;border-bottom:1px solid #dde2f0;">Nilai</th>
                        <th style="padding:8px;border-bottom:1px solid #dde2f0;">Peringkat</th>
                        <th style="padding:8px;border-bottom:1px solid #dde2f0;">Semester</th>
                        <th style="padding:8px;border-bottom:1px solid #dde2f0;">Tahun Ajaran</th>
                        <th style="padding:8px;border-bottom:1px solid #dde2f0;">Aksi</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($peringkats as $idx => $p)
                        <tr>
                            <td style="padding:8px;border-bottom:1px solid #f0f0f5;">
                                {{ $loop->iteration + ($peringkats->firstItem() - 1) }}
                            </td>

                            <td style="padding:8px;border-bottom:1px solid #f0f0f5;">
                                {{ $p->mahasiswa->nama ?? '-' }}
                            </td>

                            <td style="padding:8px;border-bottom:1px solid #f0f0f5;">
                                {{ $p->mata_kuliah }}
                            </td>

                            <td style="padding:8px;border-bottom:1px solid #f0f0f5;">
                                {{ $p->nilai_total }}
                            </td>

                            <td style="padding:8px;border-bottom:1px solid #f0f0f5;">
                                {{ $p->peringkat }}
                            </td>

                            <td style="padding:8px;border-bottom:1px solid #f0f0f5;">
                                {{ $p->semester }}
                            </td>

                            <td style="padding:8px;border-bottom:1px solid #f0f0f5;">
                                {{ $p->tahun_ajaran }}
                            </td>

                            <td style="padding:8px;border-bottom:1px solid #f0f0f5;white-space:nowrap;">
                                <a href="{{ route('koordinator.peringkat.edit', $p->id) }}"
                                   style="margin-right:6px;text-decoration:none;color:#0e257a;">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>

                                <form action="{{ route('koordinator.peringkat.destroy', $p->id) }}"
                                      method="POST"
                                      style="display:inline-block"
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="border:none;background:none;color:#c62828;cursor:pointer;">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" style="padding:10px;text-align:center;color:#777;">
                                Belum ada data peringkat.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div style="margin-top:10px;">
                {{ $peringkats->links() }}
            </div>

        </div>
    </section>

</div>

@endsection
