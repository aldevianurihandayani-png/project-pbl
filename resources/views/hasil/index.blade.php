{{-- resources/views/hasil/index.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? null; // sesuaikan kalau field role kamu beda

    $map = [
        'admin'            => 'admin',
        'koor_pbl'         => 'koordinator',
        'dosen_penguji'    => 'dosen',
        'dosen_pembimbing' => 'dosen',
        'mahasiswa'        => 'mahasiswa',
        'jaminan_mutu'     => 'jaminanmutu',
    ];

    $layout = 'layouts.' . ($map[$role] ?? 'mahasiswa');
@endphp

@extends($layout)

@section('title', 'Peringkat PBL')

@section('content')

<style>
/* ===== Layout/Card ===== */
.page{ max-width:1100px; margin:0 auto; padding:18px; }
.card{ background:#fff; border:1px solid #e6eaf8; border-radius:16px; overflow:hidden; box-shadow:0 10px 22px rgba(0,0,0,.06); }
.card + .card{ margin-top:18px; }
.card-hd{ padding:14px 16px; font-weight:900; color:#0e257a; background:#f6f8ff; display:flex; gap:10px; align-items:center; justify-content:space-between; }
.card-hd .left{ display:flex; gap:10px; align-items:center; }
.card-bd{ padding:14px 16px; }

/* ===== Buttons ===== */
.btnx{
    display:inline-flex; align-items:center; gap:10px;
    padding:10px 16px; border-radius:14px;
    color:#0e257a !important; text-decoration:none !important;
    font-weight:800; border:1px solid #d7def7;
    background:#eef2ff; cursor:pointer; line-height:1;
    box-shadow:none; transition:.12s;
}
.btnx:hover{ filter:brightness(1.03); transform:translateY(-1px); }

/* ===== Table ===== */
.table-center{ width:100%; border-collapse:collapse; font-size:14px; }
.table-center th,.table-center td{ text-align:center; padding:10px; border-bottom:1px solid #eef1fb; }
.table-center thead th{ background:#f1f4ff; font-weight:900; }
.text-left{ text-align:left!important; font-weight:700; }

/* ===== Filter ===== */
.filterbar{ display:flex; align-items:center; gap:10px; margin-bottom:12px; flex-wrap:wrap; }
.selectx{
    padding:10px 12px; border-radius:12px;
    border:1px solid #d7def7; background:#fff;
    font-weight:800; min-width:240px;
}

/* ===== Header title ===== */
.hd-right{ font-weight:900; color:#0e257a; font-size:20px; }

/* ===== Empty text ===== */
.empty{ padding:14px 0; color:#667085; font-weight:700; }
</style>

<div class="page">

    {{-- ===== FILTER KELAS ===== --}}
    <section class="card" style="margin-bottom:18px;">
        <div class="card-hd">
            <div class="left">
                <i class="fa-solid fa-filter"></i> Peringkat PBL
            </div>
        </div>

        <div class="card-bd">
            <form method="GET" action="{{ url()->current() }}">
                <div class="filterbar">
                    <div style="font-weight:900;color:#0e257a;">Kelas</div>

                    <select name="kelas" class="selectx" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>

                        @foreach($kelasList as $k)
                            @php
                                // support kalau $k object atau string
                                $namaKelas = is_object($k) ? ($k->nama ?? $k->nama_kelas ?? null) : $k;
                            @endphp

                            @if($namaKelas)
                                <option value="{{ $namaKelas }}" {{ request('kelas') == $namaKelas ? 'selected' : '' }}>
                                    {{ $namaKelas }}
                                </option>
                            @endif
                        @endforeach
                    </select>

                    @if(request('kelas'))
                        <a class="btnx" href="{{ url()->current() }}">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    {{-- ================= KELOMPOK ================= --}}
    <section class="card" style="margin-bottom:18px;">
        <div class="card-hd">
            <div class="left">
                <i class="fa-solid fa-users"></i> <span class="hd-right">Peringkat Kelompok</span>
            </div>
        </div>

        <div class="card-bd">
            <table class="table-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-left">Nama Kelompok</th>
                        <th>Kelas</th>
                        <th>Nilai</th>
                        <th>Peringkat</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($peringkatKelompok as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left">{{ $p->nama_tpk ?? '-' }}</td>
                        <td>{{ $p->kelas ?? '-' }}</td>
                        <td>{{ number_format(((float)($p->nilai_total ?? 0)) * 100, 2) }}%</td>
                        <td><b>{{ $p->peringkat ?? '-' }}</b></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Belum ada data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ================= MAHASISWA ================= --}}
    <section class="card">
        <div class="card-hd">
            <div class="left">
                <i class="fa-solid fa-user-graduate"></i> <span class="hd-right">Peringkat Mahasiswa</span>
            </div>
        </div>

        <div class="card-bd">
            <table class="table-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-left">Nama Mahasiswa</th>
                        <th>Kelas</th>
                        <th>Nilai</th>
                        <th>Peringkat</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($peringkatMahasiswa as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left">{{ $p->nama_tpk ?? '-' }}</td>
                        <td>{{ $p->kelas ?? '-' }}</td>
                        <td>{{ number_format(((float)($p->nilai_total ?? 0)) * 100, 2) }}%</td>
                        <td><b>{{ $p->peringkat ?? '-' }}</b></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Belum ada data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>

@endsection
