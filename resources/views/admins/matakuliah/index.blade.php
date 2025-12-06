@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- =============== STYLE GLOBAL =============== --}}
    <style>
        /* HEADER & TOMBOL TAMBAH (dipakai di overview) */
        .mk-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }
        .mk-subtitle {
            font-size: 13px;
            color: #6b7280;
        }
        .mk-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #2563eb;
            color: #ffffff !important;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            transition: background .18s ease, box-shadow .18s ease, transform .18s ease;
        }
        .mk-add-btn:hover {
            background: #1d4ed8;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }

        /* link kembali di halaman detail */
        .mk-back-link {
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
        .mk-back-link span:first-child {
            font-size: 14px;
        }
        .mk-back-link:hover {
            background: #e5edff;
            color: #111827;
            box-shadow: 0 2px 6px rgba(148, 163, 184, 0.4);
        }

        /* OVERVIEW: GRID KELAS */
        .kelas-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
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
            padding: 18px 20px 16px;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
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
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
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
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }
        .kelas-meta {
            font-size: 13px;
            color: #64748b;
        }
        .kelas-meta strong { color: #111827; }

        .kelas-footer {
            margin-top: auto;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .kelas-footer span { color: #64748b; }
        .kelas-footer .kelas-link {
            font-weight: 600;
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            border-radius: 999px;
            background: #eff6ff;
        }
        .kelas-footer .kelas-link::after {
            content: "→";
            font-size: 13px;
        }

        /* DETAIL PER KELAS */
        .mk-detail-wrap {
            max-width: 1120px;
            margin: 0 auto;
        }

        /* GRID & CARD MATA KULIAH (detail) */
        .mk-mk-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 14px;
        }
        @media (max-width: 992px) {
            .mk-mk-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 576px) {
            .mk-mk-grid { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        }

        .mk-mk-card {
            position: relative;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid #e5edff;
            box-shadow: 0 14px 32px rgba(15,23,42,.06);
            padding: 14px 18px 12px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .mk-mk-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            opacity: 0.05;
            pointer-events: none;
        }
        .mk-mk-inner {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .mk-mk-code {
            font-size: 11px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #9ca3af;
            font-weight: 700;
        }
        .mk-mk-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .mk-mk-meta {
            font-size: 13px;
            color: #64748b;
        }
        .mk-mk-meta span + span::before {
            content: "•";
            margin: 0 4px;
        }
        .mk-mk-footer {
            margin-top: auto;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding-top: 8px;
        }

        /* TOMBOL EDIT / HAPUS (tanpa garis bawah, warna SIMAP) */
        .mk-btn-simap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none !important;
            transition: background .15s ease, box-shadow .15s ease, transform .15s ease;
        }

        .mk-btn-edit {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(37,99,235,0.25);
        }
        .mk-btn-edit:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(37,99,235,0.35);
        }

        .mk-btn-delete {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .mk-btn-delete:hover {
            background: #fecaca;
            transform: translateY(-1px);
        }
    </style>

    @php
        // $kelasFilter dikirim dari controller: request('kelas')
    @endphp

    {{-- ================== MODE 1: OVERVIEW SEMUA KELAS ================== --}}
    @if (!$kelasFilter)

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mk-title mb-1">Manajemen Mata Kuliah</h2>
                <p class="mk-subtitle mb-0">
                </p>
            </div>

            <a href="{{ route('admins.matakuliah.create') }}" class="mk-add-btn">
                <span></span>
                <span>Tambah Mata Kuliah</span>
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h5 class="mb-3">Daftar Mata Kuliah per Kelas</h5>

        <div class="kelas-grid mb-4">
            @php $daftarKelas = ['A','B','C','D','E']; @endphp

            @foreach ($daftarKelas as $kls)
                @php
                    $stat = $kelasStats[$kls] ?? null;
                    $total = $stat->total ?? 0;
                    $rangeSemester = $stat
                        ? ($stat->min_semester == $stat->max_semester
                            ? 'Semester '.$stat->min_semester
                            : 'Semester '.$stat->min_semester.' – '.$stat->max_semester)
                        : '-';
                @endphp

                <a href="{{ route('admins.matakuliah.index', ['kelas' => $kls]) }}"
                   class="kelas-card-link">
                    <div class="kelas-card">
                        <div class="kelas-card-inner">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <div class="kelas-name">Kelas {{ $kls }}</div>
                                </div>
                            </div>

                            <div class="mt-2 kelas-meta">
                                <div class="mb-1">
                                    Jumlah mata kuliah:
                                    <strong>{{ $total }}</strong>
                                    <span>mata kuliah</span>
                                </div>
                                <div>
                                    @if ($total > 0)
                                        Rentang semester:
                                        <strong>{{ $rangeSemester }}</strong>
                                    @else
                                        <span>Belum ada data semester</span>
                                    @endif
                                </div>
                            </div>

                            <div class="kelas-footer">
                                <span>
                                    @if ($total > 0)
                                    @else
                                    @endif
                                </span>
                                <span class="kelas-link">Lihat detail</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    {{-- ================== MODE 2: DETAIL PER KELAS ================== --}}
    @else

        <div class="mk-detail-wrap">

            {{-- Back link --}}
            <a href="{{ route('admins.matakuliah.index') }}" class="mk-back-link mb-3 d-inline-flex">
                <span>←</span>
                <span>Kembali ke semua kelas</span>
            </a>

            {{-- Header detail kelas (tanpa tulisan "Kelas B" biru lagi) --}}
            <div class="mb-2">
                <h2 class="mk-title mb-1">Daftar Mata Kuliah — Kelas {{ $kelasFilter }}</h2>
                <p class="mk-subtitle mb-0">
                </p>
            </div>

            @if (session('success'))
                <div class="alert alert-success mt-1">{{ session('success') }}</div>
            @endif

            @if ($matakuliahs->count() == 0)
                <div class="alert alert-info mt-3">
                    Belum ada data mata kuliah untuk kelas {{ $kelasFilter }}.
                </div>
            @else
                <div class="mk-mk-grid">
                    @foreach ($matakuliahs as $mk)
                        <div class="mk-mk-card">
                            <div class="mk-mk-inner">
                                <div class="mk-mk-code">{{ $mk->kode_mk }}</div>
                                <div class="mk-mk-name">{{ $mk->nama_mk }}</div>

                                <div class="mk-mk-meta mb-1">
                                    <span>SKS: {{ $mk->sks }}</span>
                                    <span>Semester: {{ $mk->semester }}</span>
                                </div>

                                <div class="mk-mk-meta mb-1">
                                    Dosen pengampu:
                                    <strong> {{ $mk->nama_dosen ?? '-' }}</strong>
                                </div>

                                <div class="mk-mk-footer">
                                    <a href="{{ route('admins.matakuliah.edit', $mk->kode_mk) }}"
                                       class="mk-btn-simap mk-btn-edit">
                                        Edit
                                    </a>

                                    <form action="{{ route('admins.matakuliah.destroy', $mk->kode_mk) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mk-btn-simap mk-btn-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    {{ $matakuliahs->links() }}
                </div>
            @endif
        </div>

    @endif

</div>
@endsection