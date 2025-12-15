@extends('layouts.dosen')
@section('content')
<div class="container">

    <h4 class="mb-3">Daftar Milestone</h4>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter --}}
    <form class="row g-2 mb-3" method="GET" action="{{ route('dosen.milestone.index') }}">
        <div class="col-md-6">
            <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Cari judul / deskripsi...">
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="menunggu"  @selected(($status ?? '')==='menunggu')>Menunggu</option>
                <option value="disetujui" @selected(($status ?? '')==='disetujui')>Disetujui</option>
                <option value="ditolak"   @selected(($status ?? '')==='ditolak')>Ditolak</option>
            </select>
        </div>

        <div class="col-md-3 d-grid">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    @include('dosen.milestone.table', ['milestones' => $milestones])

</div>
@endsection
