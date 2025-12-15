@extends('layouts.dosen')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Milestone</h4>
        <a href="{{ route('dosen.milestone.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <p><b>Judul:</b> {{ $milestone->judul }}</p>
            <p><b>Tanggal:</b> {{ optional($milestone->tanggal)->format('d M Y') ?? $milestone->tanggal }}</p>
            <p><b>Status:</b> {{ ucfirst($milestone->status) }}</p>
            <p><b>Deskripsi:</b></p>
            <div class="border rounded p-2 bg-light">
                {{ $milestone->deskripsi ?? '-' }}
            </div>
        </div>
    </div>
</div>
@endsection
