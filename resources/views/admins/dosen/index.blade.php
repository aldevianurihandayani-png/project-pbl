@extends('layouts.admin')

@section('page_title', 'Manajemen Dosen')

@section('content')
<div class="container-fluid">

    <style>
        :root{
            --bg:#f4f6ff;
            --card:#ffffff;
            --text:#0b1f4d;
            --muted:#6b7280;
            --line:#e6ecff;
            --head:#e5edff;
            --primary:#1554d1;
            --primary-hover:#0f3fc0;
            --danger:#dc2626;
            --danger-text:#b91c1c;
        }

        body, .container-fluid{
            background:var(--bg) !important;
        }

        .dsn-shell{
            max-width:1120px;
            margin:0 auto;
            padding:18px 0 36px;
        }

        /* ===== HEADER ATAS ===== */
        .dsn-header{
            display:flex;
            justify-content:space-between;
            align-items:flex-end;
            gap:14px;
            margin-bottom:18px;
        }
        .dsn-title-wrap{
            display:flex;
            flex-direction:column;
            gap:4px;
        }
        .dsn-page-title{
            font-size:20px;
            font-weight:800;
            color:var(--text);
            letter-spacing:.2px;
            line-height:1.2;
        }
        .dsn-page-sub{
            font-size:13px;
            color:var(--muted);
            line-height:1.4;
        }

        .dsn-add-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:10px 22px;
            border-radius:999px;
            font-size:14px;
            font-weight:700;
            text-decoration:none !important;
            background:var(--primary);
            color:#ffffff !important;
            border:1px solid rgba(255,255,255,.15);
            box-shadow:0 10px 20px rgba(21,84,209,.22);
            white-space:nowrap;
            transition:transform .18s ease, box-shadow .18s ease, background .18s ease;
        }
        .dsn-add-btn:hover{
            background:var(--primary-hover);
            box-shadow:0 14px 26px rgba(21,84,209,.28);
            transform:translateY(-1px);
        }

        /* ===== ALERT ===== */
        .dsn-alert{
            border-radius:14px;
            border:1px solid rgba(16,185,129,.25);
            background:rgba(16,185,129,.08);
            color:#065f46;
            padding:10px 14px;
            font-size:14px;
            margin-bottom:14px;
        }

        /* ===== CARD & TABEL ===== */
        .dsn-card{
            border-radius:18px;
            background:var(--card);
            border:1px solid var(--line);
            box-shadow:0 18px 40px rgba(15,23,42,.08);
            overflow:hidden; /* supaya tabel ikut rounded */
        }

        .table-responsive{
            margin:0;
        }

        .dsn-table{
            width:100%;
            border-collapse:separate;
            border-spacing:0;
            table-layout:fixed;
        }

        .dsn-table thead{
            background:var(--head);
        }
        .dsn-table thead th{
            font-size:12px;
            font-weight:900;
            text-transform:uppercase;
            letter-spacing:.10em;
            color:var(--text);
            padding:12px 18px;
            border-bottom:1px solid rgba(11,31,77,.10);
        }

        .dsn-table tbody td{
            padding:12px 18px;
            border-bottom:1px solid rgba(17,24,39,.06);
            font-size:14px;
            color:#111827;
            vertical-align:middle;
            background:#ffffff;
        }
        .dsn-table tbody tr:last-child td{
            border-bottom:none;
        }

        .dsn-table tbody tr{
            transition:background .15s ease;
        }
        .dsn-table tbody tr:hover td{
            background:#f8fbff;
        }

        /* lebar & alignment kolom */
        .col-no{      width:8%;  text-align:center; }
        .col-nama{    width:32%; text-align:left;   }
        .col-nip{     width:20%; text-align:left;   }
        .col-telp{    width:22%; text-align:left;   }
        .col-aksi{    width:18%; text-align:center; }

        .col-no-cell{
            text-align:center;
            font-weight:700;
            color:#0f172a;
        }
        .col-nama-cell{
            text-align:left;
            font-weight:600;
            color:#0f172a;
        }
        .col-nip-cell,
        .col-telp-cell{
            text-align:left;
            color:#111827;
        }
        .col-aksi-cell{
            text-align:center;
        }

        /* ===== AKSI BUTTON ===== */
        .dsn-actions{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:10px;
        }
        .dsn-actions form{
            margin:0;
        }

        .dsn-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:7px 16px;
            border-radius:999px;
            font-size:12px;
            font-weight:800;
            border:1px solid transparent;
            text-decoration:none !important;
            cursor:pointer;
            transition:transform .12s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease;
        }
        .dsn-btn:active{
            transform:translateY(1px);
        }

        .dsn-btn-edit{
            background:var(--primary);
            border-color:var(--primary);
            color:#ffffff;
            box-shadow:0 8px 16px rgba(21,84,209,.18);
        }
        .dsn-btn-edit:hover{
            background:var(--primary-hover);
            border-color:var(--primary-hover);
            box-shadow:0 10px 20px rgba(21,84,209,.22);
        }

        .dsn-btn-delete{
            background:#fff1f1;
            border-color:rgba(220,38,38,.55);
            color:var(--danger-text);
        }
        .dsn-btn-delete:hover{
            background:#ffe1e1;
            border-color:rgba(185,28,28,.75);
        }

        /* mobile */
        @media (max-width:576px){
            .dsn-header{ align-items:flex-start; }
            .dsn-add-btn{ padding:10px 16px; font-size:13px; }
            .dsn-page-title{ font-size:18px; }
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
            <div class="dsn-alert">
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
