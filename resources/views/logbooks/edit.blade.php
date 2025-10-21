@extends('layouts.mahasiswa')

@section('title', 'Edit Logbook')

@section('content')
<div class="container my-5 edit-logbook-box">
    <h1>Edit Logbook</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('logbooks.update', $logbook->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" 
                value="{{ old('tanggal', \Carbon\Carbon::parse($logbook->tanggal)->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label for="minggu" class="form-label">Minggu</label>
            <select class="form-select" id="minggu" name="minggu" required>
                @php
                    $mingguOptions = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
                @endphp
                <option value="" disabled>Pilih Minggu</option>
                @foreach($mingguOptions as $option)
                    <option value="{{ $option }}" {{ old('minggu', $logbook->minggu) == $option ? 'selected' : '' }}>
                        {{ $option }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="aktivitas" class="form-label">Aktivitas</label>
            <input type="text" class="form-control" id="aktivitas" name="aktivitas" 
                value="{{ old('aktivitas', $logbook->aktivitas) }}" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $logbook->keterangan) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input class="form-control" type="file" id="foto" name="foto">
            @if ($logbook->foto)
                <img src="{{ asset('storage/'.$logbook->foto) }}" alt="Foto Logbook">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('logbooks.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
