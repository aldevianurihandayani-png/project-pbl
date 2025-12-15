@extends('layouts.koordinator')

@section('title', 'Peringkat PBL')

@section('content')

<style>
    /* ===== Buttons ===== */
    .btnx{
        display:inline-flex;
        align-items:center;
        gap:10px;
        padding:10px 16px;
        border-radius:14px;
        color:#fff !important;
        text-decoration:none !important;
        font-weight:700;
        border:0;
        cursor:pointer;
        line-height:1;
        box-shadow: 0 6px 14px rgba(0,0,0,.08);
        transition: transform .08s ease, filter .12s ease;
        user-select:none;
        white-space:nowrap;
    }
    .btnx:hover{ filter:brightness(1.03); transform: translateY(-1px); }
    .btnx:active{ transform: translateY(0); }
    .btnx-primary{ background:#0e257a; }
    .btnx-success{ background:#1b7a3a; }
    .btnx-ghost{
        background:#eef2ff;
        color:#0e257a !important;
        box-shadow:none;
        border:1px solid #d7def7;
    }

    /* ===== Table ===== */
    .table-center{ width:100%; border-collapse:collapse; font-size:14px; }
    .table-center th, .table-center td{
        text-align:center !important;
        vertical-align:middle !important;
        padding:10px 10px;
        border-bottom:1px solid #eef1fb;
    }
    .table-center thead th{
        background:#f1f4ff;
        color:#1a2440;
        font-weight:800;
        border-bottom:1px solid #dde2f0;
    }
    .table-center .text-left{
        text-align:left !important;
        padding-left:16px !important;
        font-weight:600;
        color:#111a33;
    }

    /* ===== Actions (⋮ dropdown) ===== */
    .aksi-wrap{
        position:relative;
        display:inline-block;
    }
    .aksi-btn{
        width:38px;
        height:34px;
        border-radius:10px;
        border:1px solid #e1e6f8;
        background:#ffffff;
        color:#1a2440;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        font-size:18px;
        line-height:0;
        box-shadow: 0 4px 10px rgba(0,0,0,.06);
    }
    .aksi-btn:hover{ background:#f6f8ff; }

    /* menu muncul di samping kiri tombol */
    .aksi-menu{
        position:absolute;
        top:50%;
        right:44px;               /* geser ke samping kiri */
        transform:translateY(-50%);
        min-width:160px;
        background:#fff;
        border:1px solid #e6eaf8;
        border-radius:12px;
        box-shadow: 0 14px 30px rgba(0,0,0,.12);
        padding:6px;
        z-index:9999;
        display:none;
    }
    .aksi-menu.show{ display:block; }

    .aksi-item{
        width:100%;
        display:flex;
        align-items:center;
        gap:10px;
        padding:10px 10px;
        border-radius:10px;
        text-decoration:none !important;
        color:#1a2440 !important;
        font-weight:700;
        background:transparent;
        border:0;
        cursor:pointer;
        text-align:left;
    }
    .aksi-item:hover{ background:#f3f6ff; }

    .aksi-danger{ color:#b42318 !important; }
    .aksi-danger:hover{ background:#ffecec; }

    .aksi-muted{ color:#667085 !important; }
    .aksi-sep{ height:1px; background:#eef1fb; margin:6px 0; }

    /* supaya kolom aksi gak jadi sempit di layar kecil */
    .col-aksi{ width:90px; }

    /* Flash */
    .flash-success{
        padding:8px 12px;border-radius:8px;background:#e6ffed;color:#13653f;margin-bottom:10px;
    }
    .flash-error{
        padding:8px 12px;border-radius:8px;background:#ffecec;color:#8a1f1f;margin-bottom:10px;
    }
</style>

<div class="page">

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    {{-- ================= PERINGKAT KELOMPOK ================= --}}
    <section class="card" style="margin-bottom:16px;">
        <div class="card-hd">
            <i class="fa-solid fa-users"></i> Peringkat Kelompok
        </div>

        <div class="card-bd">
            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:12px;">
                <a class="btnx btnx-primary" href="{{ route('koordinator.peringkat.createKelompok') }}">
                    <i class="fa-solid fa-plus"></i> Tambah Nilai Kelompok
                </a>

                <a class="btnx btnx-success" href="{{ route('koordinator.peringkat.calculate', ['type' => 'kelompok']) }}">
                    <i class="fa-solid fa-calculator"></i> Hitung Ulang Kelompok
                </a>
            </div>

            <div style="overflow-x:auto;">
                <table class="table-center">
                    <thead>
                        <tr>
                            <th style="width:70px;">No</th>
                            <th>Nama Kelompok</th>
                            <th style="width:170px;">Nilai</th>
                            <th style="width:140px;">Peringkat</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($peringkatKelompok as $p)
                        <tr>
                            <td>{{ $loop->iteration + ($peringkatKelompok->firstItem() - 1) }}</td>
                            <td class="text-left">{{ $p->nama_tpk }}</td>
                            <td>{{ number_format($p->nilai_total * 100, 2) }}%</td>
                            <td><strong>{{ $p->peringkat }}</strong></td>
                            <td>
                                <div class="aksi-wrap">
                                    <button type="button" class="aksi-btn js-aksi-btn" aria-label="Aksi">
                                        ⋮
                                    </button>

                                    <div class="aksi-menu js-aksi-menu">
                                        <a class="aksi-item" href="{{ route('koordinator.peringkat.edit', $p->id) }}">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>

                                        <div class="aksi-sep"></div>

                                        <form method="POST" action="{{ route('koordinator.peringkat.destroy', $p->id) }}"
                                              onsubmit="return confirm('Hapus data peringkat ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="aksi-item aksi-danger">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>

                                        {{-- kalau kamu sudah pakai soft delete + restore --}}
                                        @if(method_exists($p, 'trashed') && $p->trashed())
                                            <div class="aksi-sep"></div>
                                            <form method="POST" action="{{ route('koordinator.peringkat.restore', $p->id) }}">
                                                @csrf
                                                <button type="submit" class="aksi-item aksi-muted">
                                                    <i class="fa-solid fa-rotate-left"></i> Undo (Restore)
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="color:#777;text-align:center;">
                                Belum ada data peringkat kelompok.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:10px;">
                {{ $peringkatKelompok->appends(request()->except('pk_page'))->links() }}
            </div>
        </div>
    </section>

    {{-- ================= PERINGKAT MAHASISWA ================= --}}
    <section class="card">
        <div class="card-hd">
            <i class="fa-solid fa-user-graduate"></i> Peringkat Mahasiswa
        </div>

        <div class="card-bd">
            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:12px;">
                <a class="btnx btnx-primary" href="{{ route('koordinator.peringkat.createMahasiswa') }}">
                    <i class="fa-solid fa-plus"></i> Tambah Nilai Mahasiswa
                </a>

                <a class="btnx btnx-success" href="{{ route('koordinator.peringkat.calculate', ['type' => 'mahasiswa']) }}">
                    <i class="fa-solid fa-calculator"></i> Hitung Ulang Mahasiswa
                </a>
            </div>

            <div style="overflow-x:auto;">
                <table class="table-center">
                    <thead>
                        <tr>
                            <th style="width:70px;">No</th>
                            <th>Nama Mahasiswa</th>
                            <th style="width:170px;">Nilai</th>
                            <th style="width:140px;">Peringkat</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($peringkatMahasiswa as $p)
                        <tr>
                            <td>{{ $loop->iteration + ($peringkatMahasiswa->firstItem() - 1) }}</td>
                            <td class="text-left">{{ $p->nama_display }}</td>
                            <td>{{ number_format($p->nilai_total * 100, 2) }}%</td>
                            <td><strong>{{ $p->peringkat }}</strong></td>
                            <td>
                                <div class="aksi-wrap">
                                    <button type="button" class="aksi-btn js-aksi-btn" aria-label="Aksi">
                                        ⋮
                                    </button>

                                    <div class="aksi-menu js-aksi-menu">
                                        <a class="aksi-item" href="{{ route('koordinator.peringkat.edit', $p->id) }}">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>

                                        <div class="aksi-sep"></div>

                                        <form method="POST" action="{{ route('koordinator.peringkat.destroy', $p->id) }}"
                                              onsubmit="return confirm('Hapus data peringkat ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="aksi-item aksi-danger">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>

                                        @if(method_exists($p, 'trashed') && $p->trashed())
                                            <div class="aksi-sep"></div>
                                            <form method="POST" action="{{ route('koordinator.peringkat.restore', $p->id) }}">
                                                @csrf
                                                <button type="submit" class="aksi-item aksi-muted">
                                                    <i class="fa-solid fa-rotate-left"></i> Undo (Restore)
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="color:#777;text-align:center;">
                                Belum ada data peringkat mahasiswa.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:10px;">
                {{ $peringkatMahasiswa->appends(request()->except('pm_page'))->links() }}
            </div>
        </div>
    </section>

</div>

<script>
    // Dropdown aksi: toggle + close when click outside
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-aksi-btn');
        const isMenuClick = e.target.closest('.js-aksi-menu');

        // kalau klik tombol: toggle menu milik row itu
        if (btn) {
            // tutup semua menu dulu
            document.querySelectorAll('.js-aksi-menu.show').forEach(m => m.classList.remove('show'));

            const wrap = btn.closest('.aksi-wrap');
            const menu = wrap.querySelector('.js-aksi-menu');
            menu.classList.toggle('show');
            return;
        }

        // kalau klik di dalam menu, biarin
        if (isMenuClick) return;

        // klik di luar => tutup semua
        document.querySelectorAll('.js-aksi-menu.show').forEach(m => m.classList.remove('show'));
    });
</script>

@endsection
