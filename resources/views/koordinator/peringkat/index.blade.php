@extends('layouts.koordinator')

@section('title', 'Peringkat PBL')

@section('content')

<style>
/* ===== Top Action ===== */
.top-actions{
    display:flex;
    justify-content:flex-end;
    margin-bottom:14px;
}

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
    box-shadow:0 6px 14px rgba(0,0,0,.08);
    transition:.12s;
}
.btnx:hover{ filter:brightness(1.03); transform:translateY(-1px); }
.btnx-primary{ background:#0e257a; }
.btnx-success{ background:#1b7a3a; }
.btnx-ghost{
    background:#eef2ff;
    color:#0e257a !important;
    border:1px solid #d7def7;
    box-shadow:none;
}

/* ===== Table ===== */
.table-center{ width:100%; border-collapse:collapse; font-size:14px; }
.table-center th,.table-center td{
    text-align:center; padding:10px; border-bottom:1px solid #eef1fb;
}
.table-center thead th{
    background:#f1f4ff; font-weight:800;
}
.text-left{ text-align:left!important; font-weight:600; }

/* ===== Aksi ===== */
.aksi-wrap{ position:relative; display:inline-block; }
.aksi-btn{
    width:38px;height:34px;border-radius:10px;
    border:1px solid #e1e6f8;background:#fff;
    cursor:pointer;font-size:18px;
}
.aksi-menu{
    position:absolute; right:44px; top:50%;
    transform:translateY(-50%);
    min-width:180px;background:#fff;
    border-radius:12px;border:1px solid #e6eaf8;
    box-shadow:0 14px 30px rgba(0,0,0,.12);
    display:none; padding:6px; z-index:9999;
}
.aksi-menu.show{ display:block; }
.aksi-item{
    display:flex; gap:10px; padding:10px;
    border-radius:10px; font-weight:700;
    background:none; border:0; width:100%;
}
.aksi-item:hover{ background:#f3f6ff; }
.aksi-danger{ color:#b42318!important; }
.aksi-muted{ color:#667085!important; }
</style>

<div class="page">

    {{-- ===== MENU ATUR BOBOT (KANAN ATAS) ===== --}}
    <div class="top-actions">
        <a href="{{ route('koordinator.peringkat.bobot') }}" class="btnx btnx-ghost">
            <i class="fa-solid fa-sliders"></i> Atur Bobot
        </a>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif

    {{-- ================= KELOMPOK ================= --}}
    <section class="card" style="margin-bottom:18px;">
        <div class="card-hd">
            <i class="fa-solid fa-users"></i> Peringkat Kelompok
        </div>

        <div class="card-bd">
            <div style="display:flex;gap:12px;margin-bottom:12px;">
                <a class="btnx btnx-primary" href="{{ route('koordinator.peringkat.createKelompok') }}">
                    + Tambah Nilai Kelompok
                </a>
                <a class="btnx btnx-success"
                   href="{{ route('koordinator.peringkat.calculate',['type'=>'kelompok']) }}">
                    Hitung Ulang
                </a>
            </div>

            <table class="table-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelompok</th>
                        <th>Nilai</th>
                        <th>Peringkat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($peringkatKelompok as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left">{{ $p->nama_tpk }}</td>
                        <td>{{ number_format($p->nilai_total*100,2) }}%</td>
                        <td><b>{{ $p->peringkat }}</b></td>
                        <td>
                            <div class="aksi-wrap">
                                <button class="aksi-btn js-aksi-btn">⋮</button>
                                <div class="aksi-menu js-aksi-menu">
                                    <a class="aksi-item"
                                       href="{{ route('koordinator.peringkat.edit',['type'=>'kelompok','id'=>$p->tpk_id]) }}">
                                        ✏️ Edit
                                    </a>
                                    <form method="POST"
                                          action="{{ route('koordinator.peringkat.destroyTpk') }}">
                                        @csrf
                                        <input type="hidden" name="tpk_type" value="kelompok">
                                        <input type="hidden" name="tpk_id" value="{{ $p->tpk_id }}">
                                        <button class="aksi-item aksi-danger">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ================= MAHASISWA ================= --}}
    <section class="card">
        <div class="card-hd">
            <i class="fa-solid fa-user-graduate"></i> Peringkat Mahasiswa
        </div>

        <div class="card-bd">
            <div style="display:flex;gap:12px;margin-bottom:12px;">
                <a class="btnx btnx-primary" href="{{ route('koordinator.peringkat.createMahasiswa') }}">
                    + Tambah Nilai Mahasiswa
                </a>
                <a class="btnx btnx-success"
                   href="{{ route('koordinator.peringkat.calculate',['type'=>'mahasiswa']) }}">
                    Hitung Ulang
                </a>
            </div>

            <table class="table-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Nilai</th>
                        <th>Peringkat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($peringkatMahasiswa as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left">{{ $p->nama_display }}</td>
                        <td>{{ number_format($p->nilai_total*100,2) }}%</td>
                        <td><b>{{ $p->peringkat }}</b></td>
                        <td>
                            <div class="aksi-wrap">
                                <button class="aksi-btn js-aksi-btn">⋮</button>
                                <div class="aksi-menu js-aksi-menu">
                                    <a class="aksi-item"
                                       href="{{ route('koordinator.peringkat.edit',['type'=>'mahasiswa','id'=>$p->tpk_id]) }}">
                                        ✏️ Edit
                                    </a>
                                    <form method="POST"
                                          action="{{ route('koordinator.peringkat.destroyTpk') }}">
                                        @csrf
                                        <input type="hidden" name="tpk_type" value="mahasiswa">
                                        <input type="hidden" name="tpk_id" value="{{ $p->tpk_id }}">
                                        <button class="aksi-item aksi-danger">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
document.addEventListener('click',e=>{
    const btn=e.target.closest('.js-aksi-btn');
    if(btn){
        document.querySelectorAll('.js-aksi-menu').forEach(m=>m.classList.remove('show'));
        btn.nextElementSibling.classList.toggle('show');
        return;
    }
    if(!e.target.closest('.js-aksi-menu')){
        document.querySelectorAll('.js-aksi-menu').forEach(m=>m.classList.remove('show'));
    }
});
</script>

@endsection
