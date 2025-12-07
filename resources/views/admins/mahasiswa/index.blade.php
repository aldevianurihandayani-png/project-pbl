@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- =============== STYLE GLOBAL =============== --}}
    <style>
        /* HEADER & TOMBOL TAMBAH */
        .mhs-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }

        .mhs-subtitle {
            font-size: 13px;
            color: #6b7280;
        }

        .mhs-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #2563eb; /* biru SIMAP */
            color: #ffffff !important;
            padding: 10px 24px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            transition: background .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        .mhs-add-btn:hover {
            background: #1d4ed8;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }

        /* link Kembali (dipakai di detail) */
        .mhs-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            text-decoration: none;
            color: #4b5563;
            padding: 4px 12px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            transition: background .15s ease, color .15s ease, box-shadow .15s ease;
        }

        .mhs-back-link span:first-child {
            font-size: 14px;
        }

        .mhs-back-link:hover {
            background: #e5edff;
            color: #111827;
            box-shadow: 0 2px 6px rgba(148, 163, 184, 0.4);
        }

        /* GRID KELAS (overview) */
        .kelas-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        @media (max-width: 992px) {
            .kelas-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 576px) {
            .kelas-grid { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        }

        .kelas-card-link {
            text-decoration: none;
            color: inherit;
        }

        .kelas-card {
            position: relative;
            border-radius: 16px;
            background: #ffffff;
            padding: 16px 18px 14px; /* diperkecil */
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
            border: 1px solid #e5edff;
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .kelas-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #2563eb, #1fb6ff);
            opacity: 0;
            transition: opacity .2s ease;
            pointer-events: none;
        }

        .kelas-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.11);
            border-color: transparent;
        }

        .kelas-card:hover::before { opacity: 0.045; }

        .kelas-card-inner {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .kelas-title-small {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #94a3b8;
        }

        .kelas-name {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
        }

        .kelas-meta {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        .kelas-meta strong { color: #111827; }

        .kelas-footer {
            margin-top: 14px;
            padding-top: 4px;
            display: flex;
            justify-content: center;  /* tombol di tengah */
            align-items: center;
        }

        .kelas-footer .kelas-link {
            min-width: 140px;
            justify-content: center;
            text-align: center;
            font-weight: 600;
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 7px 18px;
            border-radius: 999px;
            background: #eff6ff;
        }

        .kelas-footer .kelas-link::after {
            content: "→";
            font-size: 13px;
        }

        /* DETAIL KELAS – wrapper */
        .mhs-detail-wrap {
            max-width: 900px;
            margin: 0 auto;
        }

        .mhs-class-pill {
            padding: 6px 14px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 600;
            font-size: 13px;
        }

        /* CARD MAHASISWA DI HALAMAN DETAIL */
        .mhs-grid {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 10px;
        }

        .mhs-card {
            position: relative;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid #e5edff;
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.06);
            padding: 14px 18px 12px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .mhs-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            opacity: 0.05;
            pointer-events: none;
        }

        .mhs-inner {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .mhs-nim {
            font-size: 11px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #9ca3af;
            font-weight: 700;
        }

        .mhs-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .mhs-meta {
            font-size: 13px;
            color: #64748b;
        }

        .mhs-meta span + span::before {
            content: "•";
            margin: 0 4px;
        }

        .mhs-footer {
            margin-top: auto;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding-top: 8px;
        }

        .mhs-btn-sm {
            border-radius: 999px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-outline-secondary.mhs-btn-sm {
            border-color: #cbd5f5;
            color: #374151;
            background: #f9fafb;
        }

        .btn-outline-secondary.mhs-btn-sm:hover {
            background: #e5edff;
            border-color: #1d4ed8;
            color: #1d4ed8;
        }

        .btn-outline-danger.mhs-btn-sm {
            border-color: #fecaca;
            color: #b91c1c;
            background: #fef2f2;
        }

        .btn-outline-danger.mhs-btn-sm:hover {
            background: #fee2e2;
            border-color: #dc2626;
            color: #b91c1c;
        }
    </style>

    @php
        // $kelasFilter dikirim dari controller: request('kelas')
    @endphp

    {{-- ================== MODE 1: OVERVIEW SEMUA KELAS ================== --}}
    @if (!$kelasFilter)

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mhs-title mb-1">Manajemen Mahasiswa</h2>
                <p class="mhs-subtitle mb-0"></p>
            </div>

            <a href="{{ route('admins.mahasiswa.create') }}" class="mhs-add-btn">
                Tambah Mahasiswa
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h5 class="mb-3">Data Mahasiswa per Kelas</h5>

        <div class="kelas-grid mb-4">
            @php $daftarKelas = ['A','B','C','D','E']; @endphp

            @foreach ($daftarKelas as $kls)
                @php
                    $stat = $kelasStats[$kls] ?? null;
                    $total = $stat->total ?? 0;
                    $rangeAngkatan = $stat
                        ? ($stat->min_angkatan == $stat->max_angkatan
                            ? 'Angkatan '.$stat->min_angkatan
                            : 'Angkatan '.$stat->min_angkatan.' – '.$stat->max_angkatan)
                        : null;
                @endphp

                <a href="{{ route('admins.mahasiswa.index', ['kelas' => $kls]) }}"
                   class="kelas-card-link">
                    <div class="kelas-card">
                        <div class="kelas-card-inner">
                            <div>
                                <div class="kelas-name">Kelas {{ $kls }}</div>
                            </div>

                            <div class="mt-2 kelas-meta">
                                <div class="mb-1">
                                    Jumlah mahasiswa:
                                    <strong>{{ $total }}</strong>
                                    <span>orang</span>
                                </div>
                                <div>
                                    @if ($total > 0 && $rangeAngkatan)
                                        Rentang angkatan:
                                        <strong>{{ $rangeAngkatan }}</strong>
                                    @endif
                                </div>
                            </div>

                            <div class="kelas-footer">
                                <span class="kelas-link">Lihat detail</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    {{-- ================== MODE 2: DETAIL PER KELAS ================== --}}
    @else

        <div class="mhs-detail-wrap">

            {{-- Back link --}}
            <a href="{{ route('admins.mahasiswa.index') }}" class="mhs-back-link mb-2 d-inline-flex">
                <span>←</span>
                <span>Kembali ke semua kelas</span>
            </a>

            <div class="d-flex justify-content-between align-items-end mb-3 mt-1">
                <div>
                    <h2 class="mhs-title mb-1">Data Mahasiswa — Kelas {{ $kelasFilter }}</h2>
                    <p class="mhs-subtitle mb-0">
                    </p>
                </div>
                <div class="mhs-class-pill">
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success mt-1">{{ session('success') }}</div>
            @endif

            @if ($mahasiswas->count() == 0)
                <div class="alert alert-info mt-3">
                    Belum ada data mahasiswa untuk kelas {{ $kelasFilter }}.
                </div>
            @else
                <div class="mhs-grid">
                    @foreach ($mahasiswas as $mhs)
                        <div class="mhs-card">
                            <div class="mhs-inner">
                                <div class="mhs-nim">{{ $mhs->nim }}</div>
                                <div class="mhs-name">{{ $mhs->nama }}</div>

                                <div class="mhs-meta mb-1">
                                    <span>Angkatan: {{ $mhs->angkatan ?? '-' }}</span>
                                    <span>Kelas: {{ $mhs->kelas }}</span>
                                </div>

                                <div class="mhs-meta mb-1">
                                    Email: <strong>{{ $mhs->email ?? '-' }}</strong>
                                </div>
                                <div class="mhs-meta mb-1">
                                    No. HP: <strong>{{ $mhs->no_hp ?? '-' }}</strong>
                                </div>

                                <div class="mhs-footer">
                                    <a href="{{ route('admins.mahasiswa.edit', $mhs->id) }}"
                                       class="btn btn-outline-secondary mhs-btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('admins.mahasiswa.destroy', $mhs->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger mhs-btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    {{ $mahasiswas->links() }}
                </div>
            @endif
        </div>

    @endif

</div>
@endsection
