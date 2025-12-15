@extends('layouts.koordinator')

@section('title', 'Tambah Nilai Kelompok PBL')

@section('content')

<style>
    .form-wrap {
        max-width: 720px;
        margin: 0 auto;
    }
    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .form-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #dde2f0;
        border-radius: 10px;
        outline: none;
        font-size: 14px;
        background: #fff;
    }
    .form-input:focus {
        border-color: #0e257a;
        box-shadow: 0 0 0 3px rgba(14, 37, 122, 0.12);
    }
    .form-row {
        margin-bottom: 12px;
    }
    .btn-primaryx {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 10px;
        background: #0e257a;
        color: #fff;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .btn-successx {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 10px;
        background: #1b7a3a;
        color: #fff;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .btn-secondaryx {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 10px;
        background: #6b7280;
        color: #fff;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
</style>

<div class="page">

    <section class="card">
        <div class="card-hd">
            <i class="fa-solid fa-users"></i>
            Tambah Nilai Kelompok PBL
        </div>

        <div class="card-bd">
            <div class="form-wrap">

                {{-- error validate --}}
                @if($errors->any())
                    <div style="padding:10px 12px;border-radius:8px;background:#ffecec;color:#8a1f1f;margin-bottom:12px;">
                        <ul style="margin:0;padding-left:18px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('koordinator.peringkat.storeKelompok') }}" method="POST">
                    @csrf

                    <div class="form-row">
                        <label class="form-label">Nama Kelompok</label>
                        <input type="text" name="nama" class="form-input" required
                               value="{{ old('nama') }}" placeholder="Contoh: Kelompok 1">
                    </div>

                    <div class="form-row">
                        <label class="form-label">Review UTS</label>
                        <input type="number" step="0.01" name="review_uts" class="form-input" required
                               value="{{ old('review_uts') }}" placeholder="0 - 100">
                    </div>

                    <div class="form-row">
                        <label class="form-label">Review UAS</label>
                        <input type="number" step="0.01" name="review_uas" class="form-input" required
                               value="{{ old('review_uas') }}" placeholder="0 - 100">
                    </div>

                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:14px;">
                        <button type="submit" class="btn-successx">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan & Hitung Peringkat
                        </button>

                        <a href="{{ route('koordinator.peringkat.index') }}" class="btn-secondaryx">
                            Kembali
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </section>

</div>

@endsection
