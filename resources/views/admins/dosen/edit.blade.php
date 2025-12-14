@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <style>
        :root{
            --bg:#f4f6ff;
            --card:#ffffff;
            --text:#0b1f4d;
            --muted:#64748b;
            --line:#e6ecff;
            --head:#eef2ff;

            --primary:#1d4ed8;
            --primary-hover:#1e40af;

            --input:#f8fafc;
            --input-border:#dbeafe;
            --focus: rgba(29,78,216,.18);
        }

        body, .container-fluid{
            background:var(--bg) !important;
        }

        .dsn-shell{
            max-width:980px;
            margin:0 auto;
            padding:20px 0 40px;
        }

        .dsn-pagehead{
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            gap:14px;
            margin-bottom:16px;
        }
        .dsn-title{
            font-size:26px;
            font-weight:900;
            color:var(--text);
            line-height:1.15;
            margin:0;
        }
        .dsn-sub{
            margin-top:6px;
            font-size:13px;
            color:var(--muted);
        }

        .dsn-card{
            background:var(--card);
            border:1px solid var(--line);
            border-radius:18px;
            box-shadow:0 18px 40px rgba(15,23,42,.08);
            overflow:hidden;
        }
        .dsn-card-body{
            padding:20px;
        }

        .dsn-grid{
            display:grid;
            grid-template-columns: 1fr 1fr;
            gap:14px 16px;
        }

        .dsn-field label{
            display:block;
            font-size:12px;
            font-weight:800;
            color:#1e3a8a;
            letter-spacing:.08em;
            text-transform:uppercase;
            margin-bottom:6px;
        }

        .dsn-field .form-control{
            width:100%;
            height:44px;
            border-radius:12px;
            border:1px solid var(--input-border);
            background:var(--input);
            padding:10px 12px;
            font-size:14px;
            color:#0f172a;
            box-shadow:none !important;
            transition:border-color .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .dsn-field .form-control:focus{
            border-color:var(--primary);
            background:#fff;
            box-shadow:0 0 0 4px var(--focus) !important;
            outline:none;
        }

        .dsn-span-2{ grid-column: span 2; }

        .dsn-actions{
            display:flex;
            gap:10px;
            justify-content:flex-end;
            margin-top:16px;
        }

        .dsn-btn{
            border:none;
            border-radius:999px;
            padding:10px 18px;
            font-size:13px;
            font-weight:800;
            cursor:pointer;
            transition:transform .12s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease;
            text-decoration:none !important;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            white-space:nowrap;
        }
        .dsn-btn:active{ transform:translateY(1px); }

        .dsn-btn-primary{
            background:var(--primary);
            color:#fff;
            box-shadow:0 10px 20px rgba(29,78,216,.20);
        }
        .dsn-btn-primary:hover{
            background:var(--primary-hover);
            box-shadow:0 14px 26px rgba(29,78,216,.24);
            transform:translateY(-1px);
        }

        .dsn-btn-secondary{
            background:#eef2ff;
            color:#1e3a8a;
            border:1px solid #c7d2fe;
        }
        .dsn-btn-secondary:hover{
            background:#e0e7ff;
        }

        @media (max-width: 768px){
            .dsn-title{ font-size:22px; }
            .dsn-card-body{ padding:16px; }
            .dsn-grid{ grid-template-columns: 1fr; }
            .dsn-span-2{ grid-column: span 1; }
            .dsn-actions{ justify-content:stretch; }
            .dsn-btn{ width:100%; }
        }
    </style>

    <div class="dsn-shell">

        <div class="dsn-pagehead">
            <div>
                <h1 class="dsn-title">Edit Dosen</h1>
                <div class="dsn-sub">Perbarui data dosen, lalu klik simpan.</div>
            </div>
        </div>

        <div class="dsn-card">
            <div class="dsn-card-body">
                <form action="{{ route('admins.dosen.update', $dosen->id_dosen) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="dsn-grid">
                        <div class="form-group dsn-field dsn-span-2">
                            <label for="nama_dosen">Nama Dosen</label>
                            <input type="text"
                                   class="form-control"
                                   id="nama_dosen"
                                   name="nama_dosen"
                                   value="{{ $dosen->nama_dosen }}"
                                   required>
                        </div>

                        <div class="form-group dsn-field">
                            <label for="nip">NIP</label>
                            <input type="text"
                                   class="form-control"
                                   id="nip"
                                   name="nip"
                                   value="{{ $dosen->nip }}">
                        </div>

                        <div class="form-group dsn-field">
                            <label for="jabatan">Jabatan</label>
                            <input type="text"
                                   class="form-control"
                                   id="jabatan"
                                   name="jabatan"
                                   value="{{ $dosen->jabatan }}">
                        </div>

                        <div class="form-group dsn-field dsn-span-2">
                            <label for="no_telp">No. Telp</label>
                            <input type="text"
                                   class="form-control"
                                   id="no_telp"
                                   name="no_telp"
                                   value="{{ $dosen->no_telp }}">
                        </div>
                    </div>

                    <div class="dsn-actions">
                        <a href="{{ route('admins.dosen.index') }}" class="dsn-btn dsn-btn-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="dsn-btn dsn-btn-primary">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
