@extends('layouts.koordinator')

@section('title', 'Edit Data TPK')

@section('content')

<style>
    .form-wrap{ max-width:760px; margin:24px auto; padding:0 14px; }
    .cardx{ background:#fff; border-radius:18px; box-shadow:0 14px 30px rgba(0,0,0,.10); border:1px solid #e8ecfb; overflow:hidden; }
    .cardx-hd{ display:flex; align-items:center; gap:12px; padding:16px 18px; border-bottom:1px solid #eef1fb; }
    .cardx-hd i{ width:34px;height:34px; border-radius:12px; background:#eef2ff; color:#0e257a; display:flex; align-items:center; justify-content:center; }
    .cardx-hd h3{ margin:0; font-size:20px; font-weight:800; color:#0e257a; letter-spacing:.2px; flex:1; text-align:center; }
    .cardx-bd{ padding:18px; }
    .rowx{ display:grid; grid-template-columns:220px 1fr; gap:12px; align-items:center; margin-bottom:14px; }
    .rowx label{ font-weight:800; color:#1a2440; }
    .inputx{ width:100%; padding:10px 12px; border-radius:12px; border:1px solid #dfe6fb; background:#fff; outline:none; font-size:14px; box-shadow:inset 0 1px 0 rgba(0,0,0,.02); }
    .inputx:focus{ border-color:#9fb3ff; box-shadow:0 0 0 4px rgba(80,110,255,.12); }
    .help{ margin-top:6px; color:#667085; font-size:12px; line-height:1.4; }
    .actions{ display:flex; gap:10px; flex-wrap:wrap; margin-top:8px; padding-top:10px; border-top:1px solid #eef1fb; }
    .btnx{ display:inline-flex; align-items:center; gap:10px; padding:10px 16px; border-radius:14px; font-weight:800; border:0; cursor:pointer; text-decoration:none !important; user-select:none; white-space:nowrap; transition:transform .08s ease, filter .12s ease; }
    .btnx:hover{ filter:brightness(1.03); transform:translateY(-1px); }
    .btnx:active{ transform:translateY(0); }
    .btnx-primary{ background:#0e257a; color:#fff; }
    .btnx-ghost{ background:#eef2ff; color:#0e257a; border:1px solid #d7def7; }
    .alertx{ padding:10px 12px; border-radius:12px; margin-bottom:12px; font-weight:700; }
    .alertx-danger{ background:#ffecec; color:#8a1f1f; border:1px solid #ffd2d2; }
    @media (max-width: 640px){ .rowx{ grid-template-columns:1fr; } .cardx-hd h3{ text-align:left; } }
</style>

<div class="form-wrap">

    @if ($errors->any())
        <div class="alertx alertx-danger">
            <div style="margin-bottom:6px;">Ada input yang belum valid:</div>
            <ul style="margin:0 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="cardx">
        <div class="cardx-hd">
            <i class="fa-solid fa-pen"></i>
            <h3>{{ $type === 'kelompok' ? 'Edit Nilai TPK Kelompok' : 'Edit Nilai TPK Mahasiswa' }}</h3>
        </div>

        <div class="cardx-bd">

            {{-- ✅ ROUTE UPDATE GENERIC --}}
            <form method="POST" action="{{ route('koordinator.peringkat.update', ['type' => $type, 'id' => $item->id]) }}">
                @csrf
                @method('PUT')

                <div class="rowx">
                    <label>{{ $type === 'kelompok' ? 'Nama Kelompok' : 'Nama Mahasiswa' }}</label>
                    <input type="text" name="nama" class="inputx" value="{{ old('nama', $item->nama) }}" required>
                </div>

                @if($type === 'kelompok')
                    <div class="rowx">
                        <label>Review UTS</label>
                        <div>
                            <input type="number" step="0.01" name="review_uts" class="inputx"
                                   value="{{ old('review_uts', $item->review_uts) }}" required>
                            <div class="help">Masukkan nilai numerik (contoh: 80, 90.5, dst).</div>
                        </div>
                    </div>

                    <div class="rowx">
                        <label>Review UAS</label>
                        <div>
                            <input type="number" step="0.01" name="review_uas" class="inputx"
                                   value="{{ old('review_uas', $item->review_uas) }}" required>
                            <div class="help">Masukkan nilai numerik (contoh: 80, 90.5, dst).</div>
                        </div>
                    </div>
                @else
                    <div class="rowx">
                        <label>Keaktifan</label>
                        <div>
                            <input type="number" step="0.01" name="keaktifan" class="inputx"
                                   value="{{ old('keaktifan', $item->keaktifan) }}" required>
                            <div class="help">Masukkan nilai numerik.</div>
                        </div>
                    </div>

                    <div class="rowx">
                        <label>Nilai Kelompok</label>
                        <div>
                            <input type="number" step="0.01" name="nilai_kelompok" class="inputx"
                                   value="{{ old('nilai_kelompok', $item->nilai_kelompok) }}" required>
                            <div class="help">Masukkan nilai numerik.</div>
                        </div>
                    </div>

                    <div class="rowx">
                        <label>Nilai Dosen</label>
                        <div>
                            <input type="number" step="0.01" name="nilai_dosen" class="inputx"
                                   value="{{ old('nilai_dosen', $item->nilai_dosen) }}" required>
                            <div class="help">Masukkan nilai numerik.</div>
                        </div>
                    </div>
                @endif

                <div class="actions">
                    <button type="submit" class="btnx btnx-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan & Hitung Ulang
                    </button>

                    <a href="{{ route('koordinator.peringkat.index') }}" class="btnx btnx-ghost">
                        <i class="fa-solid fa-arrow-left"></i> Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
