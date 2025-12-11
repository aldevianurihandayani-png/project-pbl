@extends('layouts.admin') 

@section('title', 'Manajemen Mata Kuliah — Admin')
@section('page_title', 'Manajemen Mata Kuliah')

@section('content')
<div class="container-fluid">

    {{-- =============== STYLE KHUSUS HALAMAN MATA KULIAH =============== --}}
    <style>
        body,
        .container-fluid {
            background-color: #f4f6ff !important;
        }

        .mk-shell {
            max-width: 1120px;
            margin: 0 auto;
            padding: 10px 0 24px;
        }

        /* ===== CARD PUTIH UTAMA (mirip "Daftar Kelompok") ===== */
        .mk-page-header {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e4ebff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
            padding: 12px 18px 16px;
            margin-bottom: 18px;
        }
        .mk-page-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .mk-page-title {
            font-size: 14px;
            font-weight: 700;
            color: #2563eb;
        }

        .mk-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1554d1;
            color: #ffffff !important;
            padding: 7px 18px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none !important;
            box-shadow: 0 4px 10px rgba(21, 84, 209, 0.35);
            transition: background .18s ease, box-shadow .18s ease, transform .18s ease;
            white-space: nowrap;
        }
        .mk-add-btn:hover {
            background: #0f3fc0;
            box-shadow: 0 6px 16px rgba(21, 84, 209, 0.45);
            transform: translateY(-1px);
        }

        /* ===== FILTER BAR ===== */
        .mk-filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 18px;
            align-items: flex-end;
        }
        .mk-filter-group {
            display: flex;
            flex-direction: column;
            min-width: 160px;
        }
        .mk-filter-label {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .mk-filter-input,
        .mk-filter-select {
            font-size: 13px;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            padding: 8px 14px;
            background-color: #ffffff;
        }
        .mk-filter-input:focus,
        .mk-filter-select:focus {
            outline: none;
            border-color: #1554d1;
            box-shadow: 0 0 0 3px rgba(21, 84, 209, 0.18);
            background-color: #ffffff;
        }
        .mk-filter-search-btn {
            border-radius: 999px;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            background: #1554d1;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 3px 9px rgba(21, 84, 209, 0.4);
        }
        .mk-filter-search-btn:hover {
            background: #0f3fc0;
        }

        .mk-search-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== GRID RINGKASAN KELAS ===== */
        .kelas-section-title {
            font-size: 14px;
            font-weight: 600;
            color: #0b1f4d;
            margin-bottom: 8px;
        }
        .kelas-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 18px;
        }
        @media (max-width: 1200px) {
            .kelas-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
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
            border-radius: 18px;
            background: #ffffff;
            padding: 18px 18px 16px;
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 140px;
            box-shadow: 0 18px 30px rgba(15, 23, 42, 0.05);
            border: 1px solid #dde5ff;
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .kelas-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 22px 36px rgba(15, 23, 42, 0.10);
            border-color: #c8d5ff;
        }
        .kelas-card-inner {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .kelas-name {
            font-size: 18px;
            font-weight: 700;
            color: #0b1f4d;
        }
        .kelas-meta {
            font-size: 12px;
            color: #6b7280;
        }
        .kelas-meta strong {
            color: #1554d1;
            font-weight: 700;
        }
        .kelas-footer {
            margin-top: auto;
            padding-top: 12px;
            display: flex;
            justify-content: center;
        }
        .kelas-link {
            font-weight: 600;
            color: #1554d1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 8px 18px;
            border-radius: 999px;
            background: #ffffff;
            border: 1px solid #cfd7ff;
            font-size: 13px;
            width: 100%;
            max-width: 220px;
        }
        .kelas-link::after {
            content: "→";
            font-size: 12px;
        }
        .kelas-link:hover {
            background: #edf2ff;
            border-color: #1554d1;
        }

        /* ===== DETAIL PER KELAS ===== */
        .mk-detail-wrap {
            max-width: 1120px;
            margin: 0 auto;
        }
        .mk-class-pill {
            padding: 6px 14px;
            border-radius: 999px;
            background: #edf2ff;
            color: #1554d1;
            font-weight: 600;
            font-size: 13px;
        }
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
            background: #ffffff;
            transition: background .15s ease, color .15s ease, box-shadow .15s ease;
        }
        .mk-back-link span:first-child { font-size: 14px; }
        .mk-back-link:hover {
            background: #edf2ff;
            color: #0b1f4d;
            box-shadow: 0 2px 6px rgba(148, 163, 184, 0.4);
        }

        .mk-mk-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
            margin-top: 10px;
        }
        .mk-mk-card {
            position: relative;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid #dde5ff;
            box-shadow: 0 14px 32px rgba(15,23,42,.05);
            padding: 14px 18px 12px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .mk-mk-inner {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .mk-mk-code {
            font-size: 10px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #9ca3af;
            font-weight: 700;
        }
        .mk-mk-name {
            font-size: 16px;
            font-weight: 700;
            color: #0b1f4d;
            margin-bottom: 4px;
        }
        .mk-mk-meta {
            font-size: 13px;
            color: #6b7280;
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

        /* tombol kecil umum */
        .mk-btn-sm {
            border-radius: 999px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none !important;
        }

        /* tombol Edit / Hapus ala SIMAP */
        .btn-edit {
            background: #e9f0ff;
            border: 1px solid #1d4ed8;
            color: #1d4ed8;
        }
        .btn-edit:hover {
            background: #dbe8ff;
        }

        .btn-hapus {
            background: #ffe8e8;
            border: 1px solid #dc2626;
            color: #b91c1c;
        }
        .btn-hapus:hover {
            background: #ffd4d4;
        }
    </style>

    @php
        $kelasFilter = $kelasFilter ?? request('kelas');
    @endphp

    <div class="mk-shell">

        {{-- ================== MODE 1: OVERVIEW SEMUA KELAS ================== --}}
        @if (!$kelasFilter)

            <div class="mk-page-header">
                <div class="mk-page-header-top">
                    <div class="mk-page-title">
                        Daftar Mata Kuliah per Kelas
                    </div>
                    <a href="{{ route('admins.matakuliah.create') }}" class="mk-add-btn">
                        Tambah Mata Kuliah
                    </a>
                </div>

                <form method="GET" action="{{ route('admins.matakuliah.index') }}" class="mk-filter-bar">
                    <div class="mk-filter-group">
                        <label class="mk-filter-label">Kelas</label>
                        <select name="filter_kelas" class="mk-filter-select">
                            <option value="">Semua</option>
                            {{-- 🔹 kelas diambil dari tabel `kelas` --}}
                            @foreach ($daftarKelas as $row)
                                <option value="{{ $row->nama_kelas }}"
                                    {{ request('filter_kelas') == $row->nama_kelas ? 'selected' : '' }}>
                                    {{ $row->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mk-filter-group">
                        <label class="mk-filter-label">Semester</label>
                        <select name="filter_semester" class="mk-filter-select">
                            <option value="">Semua</option>
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ request('filter_semester') == $i ? 'selected' : '' }}>
                                    Semester {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="mk-filter-group" style="flex:1; min-width:220px;">
                        <label class="mk-filter-label">Cari (kode / nama)</label>
                        <input
                            type="text"
                            name="q"
                            class="mk-filter-input"
                            value="{{ request('q') }}"
                        >
                    </div>

                    <div class="mk-filter-group" style="min-width:auto;">
                        <button type="submit" class="mk-filter-search-btn">
                            <span class="mk-search-icon">
                                <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                                    <circle cx="11" cy="11" r="6" fill="none" stroke="white" stroke-width="2" />
                                    <line x1="16" y1="16" x2="20" y2="20" stroke="white" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </span>
                            <span>Cari</span>
                        </button>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="kelas-section-title">Ringkasan per Kelas</div>

            <div class="kelas-grid mb-4">
                {{-- 🔹 kartu kelas juga dari tabel `kelas` --}}
                @foreach ($daftarKelas as $row)
                    @php
                        $namaKelas    = $row->nama_kelas;
                        $stat         = $kelasStats[$namaKelas] ?? null;
                        $total        = $stat->total ?? 0;
                        $rangeSemester = $stat
                            ? ($stat->min_semester == $stat->max_semester
                                ? 'Semester '.$stat->min_semester
                                : 'Semester '.$stat->min_semester.' – '.$stat->max_semester)
                            : '-';
                    @endphp

                    <a href="{{ route('admins.matakuliah.index', ['kelas' => $namaKelas]) }}" class="kelas-card-link">
                        <div class="kelas-card">
                            <div class="kelas-card-inner">
                                <div class="kelas-name mb-1">{{ $namaKelas }}</div>

                                <div class="kelas-meta mb-1">
                                    Jumlah mata kuliah:
                                    <strong>{{ $total }}</strong> mata kuliah
                                </div>
                                <div class="kelas-meta">
                                    @if ($total > 0)
                                        Rentang semester:
                                        <strong>{{ $rangeSemester }}</strong>
                                    @else
                                        Belum ada data semester
                                    @endif
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

            <div class="mk-detail-wrap">

                <a href="{{ route('admins.matakuliah.index') }}" class="mk-back-link mb-2 d-inline-flex">
                    <span>←</span>
                    <span>Kembali ke semua kelas</span>
                </a>

                <div class="d-flex justify-content-between align-items-end mb-3 mt-1">
                    <div>
                        <h2 class="mk-page-title mb-1" style="font-size:18px; color:#0b1f4d;">
                            Daftar Mata Kuliah — {{ $kelasFilter }}
                        </h2>
                        <p class="mk-filter-label mb-0">
                            Data mata kuliah terdaftar di {{ $kelasFilter }}.
                        </p>
                    </div>
                    <div class="mk-class-pill">
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success mt-1">{{ session('success') }}</div>
                @endif

                @if ($matakuliahs->count() == 0)
                    <div class="alert alert-info mt-3">
                        Belum ada data mata kuliah untuk {{ $kelasFilter }}.
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
                                        <strong>{{ $mk->nama_dosen ?? '-' }}</strong>
                                    </div>

                                    <div class="mk-mk-footer">
                                        <a href="{{ route('admins.matakuliah.edit', $mk->kode_mk) }}"
                                           class="mk-btn-sm btn-edit">
                                            Edit
                                        </a>

                                        <form action="{{ route('admins.matakuliah.destroy', $mk->kode_mk) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mk-btn-sm btn-hapus">
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

    </div>{{-- /.mk-shell --}}

</div>
@endsection
