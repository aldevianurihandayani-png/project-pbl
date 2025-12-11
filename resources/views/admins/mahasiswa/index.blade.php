@extends('layouts.admin')

@section('title', 'Manajemen Mahasiswa — Admin')
@section('page_title', 'Manajemen Mahasiswa')

@section('content')
<div class="container-fluid">

    {{-- =============== STYLE KHUSUS HALAMAN MAHASISWA =============== --}}
    <style>
        body,
        .container-fluid {
            background-color: #f4f6ff !important;
        }

        .mhs-shell {
            max-width: 1120px;
            margin: 0 auto;
            padding: 10px 0 24px;
        }

        /* ===== CARD PUTIH UTAMA (overview) ===== */
        .mhs-page-header {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e4ebff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
            padding: 12px 18px 16px;
            margin-bottom: 18px;
        }
        .mhs-page-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .mhs-page-title {
            font-size: 18px;
            font-weight: 700;
            color: #0b1f4d;
        }
        .mhs-page-sub {
            font-size: 12px;
            font-weight: 600;
            color: #0b1f4d;
            text-transform: uppercase;
            letter-spacing: .12em;
            margin: 2px 0 0;
        }

        .mhs-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1554d1;
            color: #ffffff !important;
            padding: 9px 22px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none !important;
            box-shadow: 0 4px 10px rgba(21, 84, 209, 0.35);
            transition: background .18s ease, box-shadow .18s ease, transform .18s ease;
            white-space: nowrap;
        }
        .mhs-add-btn:hover {
            background: #0f3fc0;
            box-shadow: 0 6px 16px rgba(21, 84, 209, 0.45);
            transform: translateY(-1px);
        }

        /* ===== FILTER BAR ===== */
        .mhs-filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 18px;
            align-items: flex-end;
        }
        .mhs-filter-group {
            display: flex;
            flex-direction: column;
            min-width: 160px;
        }
        .mhs-filter-label {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .mhs-filter-input,
        .mhs-filter-select {
            font-size: 13px;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            padding: 8px 14px;
            background-color: #ffffff;
        }
        .mhs-filter-input:focus,
        .mhs-filter-select:focus {
            outline: none;
            border-color: #1554d1;
            box-shadow: 0 0 0 3px rgba(21, 84, 209, 0.18);
            background-color: #ffffff;
        }
        .mhs-filter-search-btn {
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
        .mhs-filter-search-btn:hover {
            background: #0f3fc0;
        }
        .mhs-search-icon {
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

        .kelas-card-link { text-decoration: none; color: inherit; }
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
            background: #eef3ff;
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
            background: #dde8ff;
            border-color: #1554d1;
        }

        /* ===== DETAIL PER KELAS MAHASISWA ===== */
        .mhs-detail-wrap {
            max-width: 1120px;
            margin: 0 auto;
        }
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
            background: #ffffff;
            transition: background .15s ease, color .15s ease, box-shadow .15s ease;
        }
        .mhs-back-link span:first-child { font-size: 14px; }
        .mhs-back-link:hover {
            background: #edf2ff;
            color: #0b1f4d;
            box-shadow: 0 2px 6px rgba(148, 163, 184, 0.4);
        }

        .mhs-detail-card {
            border-radius: 18px;
            border: 1px solid #e4ebff;
            box-shadow: 0 16px 40px rgba(15,23,42,.08);
            background: #ffffff;
            overflow: hidden;
        }

        /* ===== TABEL (model garis klasik) ===== */
        .mhs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .mhs-table thead {
            background: #e5edff;
        }
        .mhs-table thead th {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #0b1f4d;
            padding: 10px 14px;
            border-bottom: 1px solid #d4ddff;
        }
        .mhs-table tbody td {
            padding: 10px 14px;
            border-bottom: 1px solid #f3f4f6;
            background: #ffffff;
        }
        .mhs-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* alignment & lebar kolom */
        .col-no, .col-no-cell,
        .col-nim, .col-nim-cell,
        .col-kelas, .col-kelas-cell,
        .col-dosen, .col-dosen-cell,
        .col-aksi, .col-aksi-cell {
            text-align: center;
        }
        .col-no      { width: 60px; }
        .col-nim     { width: 150px; }
        .col-kelas   { width: 80px; }
        .col-dosen   { width: 180px; }
        .col-aksi    { width: 160px; }

        .col-nama,
        .col-nama-cell {
            text-align: left;
        }

        /* tombol */
        .mhs-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 16px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid transparent;
            text-decoration: none !important;
        }
        .mhs-btn-edit {
            background: #1554d1;
            border-color: #1554d1;
            color: #ffffff;
        }
        .mhs-btn-edit:hover {
            background: #0f3fc0;
            border-color: #0f3fc0;
            color: #ffffff;
        }
        .mhs-btn-delete {
            background: #fef2f2;
            border-color: #fecaca;
            color: #b91c1c;
        }
        .mhs-btn-delete:hover {
            background: #fee2e2;
            border-color: #dc2626;
            color: #b91c1c;
        }

        .aksi-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .aksi-wrapper form {
            margin: 0;
        }
    </style>

    @php
        // backup kalau controller belum ngirim $kelasFilter
        $kelasFilter = $kelasFilter ?? request('kelas');
    @endphp

    <div class="mhs-shell">

        {{-- ================== MODE 1: OVERVIEW SEMUA KELAS ================== --}}
        @if (!$kelasFilter)

            <div class="mhs-page-header">
                <div class="mhs-page-header-top">
                    <div>
                        {{-- hilangkan "Manajemen Mahasiswa" di kartu filter --}}
                        <h1 class="mhs-page-title">Data Mahasiswa per Kelas</h1>
                    </div>

                    <a href="{{ route('admins.mahasiswa.create') }}" class="mhs-add-btn">
                        Tambah Mahasiswa
                    </a>
                </div>

                {{-- FILTER --}}
                <form method="GET" action="{{ route('admins.mahasiswa.index') }}" class="mhs-filter-bar">
                    <div class="mhs-filter-group">
                        <label class="mhs-filter-label">Kelas</label>
                        <select name="filter_kelas" class="mhs-filter-select">
                            <option value="">Semua</option>
                            {{-- 🔹 pakai kelas dari tabel `kelas` --}}
                            @foreach ($daftarKelas as $row)
                                <option value="{{ $row->nama_kelas }}"
                                    {{ request('filter_kelas') == $row->nama_kelas ? 'selected' : '' }}>
                                    {{ $row->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mhs-filter-group">
                        <label class="mhs-filter-label">Semester</label>
                        <select name="filter_semester" class="mhs-filter-select">
                            <option value="">Semua</option>
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ request('filter_semester') == $i ? 'selected' : '' }}>
                                    Semester {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="mhs-filter-group" style="flex:1; min-width:220px;">
                        <label class="mhs-filter-label">Cari (nama / NIM)</label>
                        <input
                            type="text"
                            name="q"
                            class="mhs-filter-input"
                            value="{{ request('q') }}"
                        >
                    </div>

                    <div class="mhs-filter-group" style="min-width:auto;">
                        <button type="submit" class="mhs-filter-search-btn">
                            <span class="mhs-search-icon">
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

            <div class="kelas-section-title">Data Mahasiswa per Kelas</div>

            <div class="kelas-grid mb-4">
                {{-- 🔹 loop kartu berdasarkan tabel `kelas` --}}
                @foreach ($daftarKelas as $row)
                    @php
                        $namaKelas = $row->nama_kelas;              // misal: "Kelas A" / "A" tergantung datanya
                        $stat      = $kelasStats[$namaKelas] ?? null;
                        $total     = $stat->total
                            ?? $stat->jumlah_mahasiswa
                            ?? $stat->count
                            ?? 0;
                    @endphp

                    <a href="{{ route('admins.mahasiswa.index', ['kelas' => $namaKelas]) }}" class="kelas-card-link">
                        <div class="kelas-card">
                            <div class="kelas-card-inner">
                                <div class="kelas-name mb-1">{{ $namaKelas }}</div>
                                <div class="kelas-meta">
                                    Jumlah mahasiswa:
                                    <strong>{{ $total }}</strong> orang
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

                <a href="{{ route('admins.mahasiswa.index') }}" class="mhs-back-link mb-3 d-inline-flex">
                    <span>←</span>
                    <span>Kembali ke semua kelas</span>
                </a>

                <div class="mb-2">
                    <h2 class="mhs-page-title mb-1" style="font-size:18px;">
                        Data Mahasiswa — Kelas {{ $kelasFilter }}
                    </h2>
                </div>

                @if ($mahasiswas->count() == 0)
                    <div class="alert alert-info mt-3">
                        Belum ada data mahasiswa untuk kelas {{ $kelasFilter }}.
                    </div>
                @else
                    <div class="mhs-detail-card mt-3">
                        <div class="card-body p-3">
                            <div class="table-responsive mb-0">
                                <table class="mhs-table">
                                    <thead>
                                        <tr>
                                            <th class="col-no">No</th>
                                            <th class="col-nim">NIM</th>
                                            <th class="col-nama">Nama</th>
                                            <th class="col-kelas">Kelas</th>
                                            <th class="col-dosen">No HP</th>
                                            <th class="col-aksi">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mahasiswas as $mhs)
                                            <tr>
                                                <td class="col-no-cell">{{ $loop->iteration }}</td>
                                                <td class="col-nim-cell">{{ $mhs->nim }}</td>
                                                <td class="col-nama-cell">{{ $mhs->nama }}</td>
                                                <td class="col-kelas-cell">{{ $mhs->kelas }}</td>
                                                <td class="col-dosen-cell">{{ $mhs->no_hp ?? '-' }}</td>
                                                <td class="col-aksi-cell">
                                                    <div class="aksi-wrapper">
                                                        <a href="{{ route('admins.mahasiswa.edit', ['mahasiswa' => $mhs->nim]) }}"
                                                           class="mhs-btn mhs-btn-edit">
                                                            Edit
                                                        </a>

                                                        <form action="{{ route('admins.mahasiswa.destroy', ['mahasiswa' => $mhs->nim]) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="mhs-btn mhs-btn-delete">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $mahasiswas->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

    </div>{{-- /.mhs-shell --}}

</div>
@endsection
