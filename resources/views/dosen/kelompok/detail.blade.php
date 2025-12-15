@extends('partials.sidebar')  {{-- kalau kamu pakai layout, kalau tidak hapus saja --}}

@section('content')
<div class="page">

    <section class="card">
        <div class="card-hd">
            <div>
                <i class="fa-solid fa-users"></i> 
                Detail Kelas: <strong>{{ $kelas }}</strong>
            </div>

            <a href="{{ route('dosen.kelompok.create') }}?kelas={{ $kelas }}" class="btn">
                Tambah Kelompok
            </a>
        </div>

        <div class="card-bd">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Kelompok</th>
                        <th>Kelas</th>
                        <th>Ketua</th>
                        <th>Dosen Pembimbing</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelompoks as $k)
                    <tr>
                        <td>
                            <strong>{{ $k->nama }}</strong><br>
                            <small>{{ $k->judul_proyek }}</small><br>
                            <small><b>Anggota:</b> {{ $k->anggota }}</small>
                        </td>

                        <td>{{ $k->kelas }}</td>
                        <td>{{ $k->ketua_kelompok }}</td>
                        <td>{{ $k->dosen_pembimbing }}</td>

                        <td>
                            <a href="{{ route('dosen.kelompok.edit', $k->id) }}" 
                                class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('dosen.kelompok.destroy', $k->id) }}"
                                method="POST"
                                style="display:inline-block"
                                onsubmit="return confirm('Yakin ingin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 3rem;">
                            Belum ada kelompok di kelas ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection
