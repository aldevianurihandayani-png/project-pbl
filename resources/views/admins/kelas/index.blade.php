@extends('layouts.admin')

@section('page_title', 'Manajemen Akademik')

@section('content')
<div class="container-fluid">

    <style>
        body,
        .container-fluid{
            background:#f4f6ff !important;
        }

        .kelas-shell{
            max-width:1120px;
            margin:0 auto;
            padding:10px 0 24px;
        }

        .kelas-card{
            border-radius:18px;
            background:#ffffff;
            box-shadow:0 18px 40px rgba(15,23,42,0.08);
            border:1px solid #e5edff;
            padding:18px 22px 20px;
        }

        .kelas-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            margin-bottom:14px;
        }
        .kelas-title{
            font-size:14px;
            font-weight:700;
            color:#0b1f4d;
            text-transform:uppercase;
            letter-spacing:.10em;
        }
        .kelas-subtitle{
            font-size:12px;
            color:#6b7280;
            margin-top:2px;
        }

        .btn-tambah-kelas{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:9px 24px;
            border-radius:999px;
            font-size:14px;
            font-weight:600;
            text-decoration:none !important;
            background:#1554d1;
            color:#ffffff !important;
            border:none;
            box-shadow:0 4px 12px rgba(21,84,209,.35);
            white-space:nowrap;
            transition:background .18s ease, box-shadow .18s ease, transform .18s ease;
        }
        .btn-tambah-kelas:hover{
            background:#0f3fc0;
            box-shadow:0 6px 18px rgba(21,84,209,.45);
            transform:translateY(-1px);
        }

        .kelas-table{
            width:100%;
            border-collapse:collapse;
        }
        .kelas-table thead{
            background:#e5edff;
        }
        .kelas-table thead th{
            font-size:12px;
            font-weight:800;
            text-transform:uppercase;
            letter-spacing:.08em;
            color:#0b1f4d;
            padding:9px 12px;
            border-bottom:1px solid #d4ddff;
        }

        /* ====================== PERBAIKAN NAMA KELAS ====================== */

        th.col-nama-kelas{
            text-align:left !important;
            padding-left:24px !important;
            width:220px !important;
        }

        td.col-nama-kelas-cell{
            padding-left:24px !important;
            font-size:14px;
            color:#111827;   /* normal text */
            font-weight:400; /* sudah TIDAK bold */
        }

        /* ================================================================= */

        .kelas-table tbody td{
            padding:9px 12px;
            border-bottom:1px solid #f3f4f6;
            font-size:14px;
            color:#111827;
        }
        .kelas-table tbody tr:last-child td{
            border-bottom:none;
        }

        .col-no, .col-no-cell,
        .col-semester, .col-semester-cell,
        .col-periode, .col-periode-cell,
        .col-aksi, .col-aksi-cell{
            text-align:center;
        }
        .col-no{width:60px;}
        .col-aksi{width:170px;}

        .kelas-badge-status{
            font-size:13px;
            color:#6b7280;
        }

        .aksi-wrapper{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }
        .aksi-wrapper form{
            margin:0;
        }

        .btn-kelas-edit,
        .btn-kelas-delete{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:5px 18px;
            border-radius:999px;
            font-size:12px;
            font-weight:600;
            border:1px solid transparent;
            text-decoration:none !important;
        }

        .btn-kelas-edit{
            background:#1554d1;
            border-color:#1554d1;
            color:#ffffff;
        }
        .btn-kelas-edit:hover{
            background:#0f3fc0;
            border-color:#0f3fc0;
        }

        .btn-kelas-delete{
            background:#ffe8e8;
            border-color:#dc2626;
            color:#b91c1c;
        }
        .btn-kelas-delete:hover{
            background:#ffd4d4;
            border-color:#b91c1c;
        }
    </style>

    <div class="kelas-shell">

        @if (session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="kelas-card">
            <div class="kelas-header">
                <div>
                    <div class="kelas-title">Daftar Kelas</div>
                    <div class="kelas-subtitle">
                        Kelola nama kelas, semester, dan periode akademik.
                    </div>
                </div>

                <a href="{{ route('admins.kelas.create') }}" class="btn-tambah-kelas">
                    Tambah Kelas
                </a>
            </div>

            <div class="table-responsive">
                <table class="kelas-table">
                    <thead>
                        <tr>
                            <th class="col-no">ID</th>
                            <th class="col-nama-kelas">Nama Kelas</th>
                            <th class="col-semester">Semester</th>
                            <th class="col-periode">Periode</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($daftarKelas as $kelas)
                        <tr>
                            <td class="col-no-cell">{{ $loop->iteration }}</td>

                            <td class="col-nama-kelas-cell">
                                {{ $kelas->nama_kelas }}
                            </td>

                            <td class="col-semester-cell">
                                @if($kelas->semester)
                                    <span class="kelas-badge-status">Semester {{ $kelas->semester }}</span>
                                @else
                                    <span class="kelas-badge-status">Belum diatur</span>
                                @endif
                            </td>

                            <td class="col-periode-cell">
                                @if($kelas->periode)
                                    <span class="kelas-badge-status">{{ $kelas->periode }}</span>
                                @else
                                    <span class="kelas-badge-status">Belum diatur</span>
                                @endif
                            </td>

                            <td class="col-aksi-cell">
                                <div class="aksi-wrapper">
                                    <a href="{{ route('admins.kelas.edit', $kelas->id) }}"
                                       class="btn-kelas-edit">Edit</a>

                                    <form action="{{ route('admins.kelas.destroy', $kelas->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus kelas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-kelas-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data kelas.</td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>

</div>
@endsection
