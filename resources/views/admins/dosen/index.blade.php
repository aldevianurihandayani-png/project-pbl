@extends('layouts.admin') 

@section('page_title', 'Manajemen Dosen')

@section('content')
<div class="container-fluid">

    <style>
        body,
        .container-fluid{
            background:#f4f6ff !important;
        }

        .dsn-shell{
            max-width:1120px;
            margin:0 auto;
            padding:14px 0 32px;
        }

        /* ===== HEADER ATAS ===== */
        .dsn-header{
            display:flex;
            justify-content:space-between;
            align-items:flex-end;
            gap:12px;
            margin-bottom:16px;
        }
        .dsn-title-wrap{
            display:flex;
            flex-direction:column;
            gap:2px;
        }
        .dsn-page-title{
            font-size:18px;
            font-weight:700;
            color:#0b1f4d;
        }
        .dsn-page-sub{
            font-size:13px;
            color:#6b7280;
        }

        .dsn-add-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:9px 26px;
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
        .dsn-add-btn:hover{
            background:#0f3fc0;
            box-shadow:0 6px 18px rgba(21,84,209,.45);
            transform:translateY(-2px);
        }

        /* ===== CARD & TABEL ===== */
        .dsn-card{
            border-radius:18px;
            background:#ffffff;
            border:1px solid #e4ebff;
            box-shadow:0 18px 40px rgba(15,23,42,.08);
            padding:4px 0 4px;
        }

        .dsn-table{
            width:100%;
            border-collapse:collapse;
            table-layout:fixed; /* supaya lebar kolom konsisten */
        }
        .dsn-table thead{
            background:#e5edff;
        }
        .dsn-table thead th{
            font-size:12px;
            font-weight:800;
            text-transform:uppercase;
            letter-spacing:.08em;
            color:#0b1f4d;
            padding:10px 18px;
            border-bottom:1px solid #d4ddff;
        }

        .dsn-table tbody td{
            padding:10px 18px;
            border-bottom:1px solid #f3f4f6;
            font-size:14px;
            color:#111827;
        }
        .dsn-table tbody tr:last-child td{
            border-bottom:none;
        }

        /* lebar & alignment kolom */
        .col-no{      width:8%;  text-align:center; }
        .col-nama{    width:32%; text-align:left;   }
        .col-nip{     width:20%; text-align:left;   }
        .col-telp{    width:22%; text-align:left;   }
        .col-aksi{    width:18%; text-align:center; }

        .col-no-cell{     text-align:center; }
        .col-nama-cell,
        .col-nip-cell,
        .col-telp-cell{   text-align:left;   }
        .col-aksi-cell{   text-align:center; }

        /* ===== AKSI BUTTON ===== */
        .dsn-actions{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }
        .dsn-actions form{
            margin:0;
        }

        .dsn-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:6px 18px;
            border-radius:999px;
            font-size:12px;
            font-weight:600;
            border:1px solid transparent;
            text-decoration:none !important;
            cursor:pointer;
        }
        .dsn-btn-edit{
            background:#1554d1;
            border-color:#1554d1;
            color:#ffffff;
            box-shadow:0 3px 8px rgba(21,84,209,.35);
        }
        .dsn-btn-edit:hover{
            background:#0f3fc0;
            border-color:#0f3fc0;
        }
        .dsn-btn-delete{
            background:#ffe8e8;
            border-color:#dc2626;
            color:#b91c1c;
        }
        .dsn-btn-delete:hover{
            background:#ffd4d4;
            border-color:#b91c1c;
        }
    </style>

    <div class="dsn-shell">

        {{-- HEADER --}}
        <div class="dsn-header">
            <div class="dsn-title-wrap">
                <div class="dsn-page-title">Manajemen Dosen</div>
                <div class="dsn-page-sub">
                    Kelola data dosen di sistem akademik.
                </div>
            </div>

            <a href="{{ route('admins.dosen.create') }}" class="dsn-add-btn">
                Tambah Dosen
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- CARD TABEL --}}
        <div class="dsn-card">
            <div class="table-responsive">
                <table class="dsn-table">
                    <thead>
                        <tr>
                            <th class="col-no">ID</th>
                            <th class="col-nama">Nama</th>
                            <th class="col-nip">NIP</th>
                            <th class="col-telp">No. Telp</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosens as $dosen)
                            <tr>
                                <td class="col-no-cell">{{ $loop->iteration }}</td>
                                <td class="col-nama-cell">{{ $dosen->nama_dosen }}</td>
                                <td class="col-nip-cell">{{ $dosen->nip ?? '-' }}</td>
                                <td class="col-telp-cell">{{ $dosen->no_telp ?? '-' }}</td>
                                <td class="col-aksi-cell">
                                    <div class="dsn-actions">
                                        <a href="{{ route('admins.dosen.edit', $dosen->id_dosen) }}"
                                           class="dsn-btn dsn-btn-edit">
                                            Edit
                                        </a>

                                        <form action="{{ route('admins.dosen.destroy', $dosen->id_dosen) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dsn-btn dsn-btn-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Belum ada data dosen.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- /.dsn-shell --}}
</div>
@endsection
