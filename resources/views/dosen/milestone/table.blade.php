@extends('dosen.layout')

@section('title', 'Daftar Milestone')
@section('page_title', 'Daftar Milestone')

@push('styles')
    <style>
        .milestone-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .milestone-card {
            background-color: #fff;
            border-radius: var(--radius, 16px);
            box-shadow: var(--shadow, 0 6px 20px rgba(13,23,84,.08));
            border: 1px solid var(--ring, rgba(13,23,84,.10));
            display: flex;
            flex-direction: column;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .milestone-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(13,23,84,.12);
        }
        .milestone-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.25rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .milestone-card .project-title {
            font-weight: 600;
            color: var(--navy, #0b1d54);
        }
        .milestone-card .card-body {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .milestone-card .description {
            font-size: 1rem;
            color: #333;
            margin-bottom: 1rem;
            flex-grow: 1;
        }
        .milestone-card .meta {
            font-size: 0.875rem;
            color: var(--muted, #6c7a8a);
            margin-top: 1rem;
        }
        .milestone-card .meta-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .status-badge {
            padding: 0.4em 0.8em;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .status-berhasil { background-color: #d4edda; color: #155724; }
        .status-sedang { background-color: #fff3cd; color: #856404; }
        .status-belum { background-color: #e2e3e5; color: #383d41; }
        .approval-pending { color: #ffc107; }
        .approval-approved { color: #28a745; }
        .approval-rejected { color: #dc3545; }

        .form-inline-custom {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }
        .form-inline-custom .form-group {
            flex: 1 1 auto;
            min-width: 180px;
        }
        .form-inline-custom .btn-group {
            flex-shrink: 0;
            display: flex;
            gap: 0.5rem;
        }
        .form-inline-custom .btn {
            width: auto;
        }

    </style>
@endpush

@section('content')
<div class="card mb-4">
    <div class="card-hd">
        Filter Milestone
    </div>
    <div class="card-bd" style="padding: 1.25rem;">
        <form method="GET" action="{{ route('dosen.milestone.index') }}" class="form-inline-custom">
            <div class="form-group">
                <label for="search" class="form-label">Cari Deskripsi</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Cari deskripsi milestone...">
            </div>
            <div class="form-group">
                <label for="status" class="form-label">Status Pengerjaan</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Belum" {{ request('status') == 'Belum' ? 'selected' : '' }}>Belum</option>
                    <option value="Sedang" {{ request('status') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Berhasil" {{ request('status') == 'Berhasil' ? 'selected' : '' }}>Berhasil</option>
                </select>
            </div>
            <div class="form-group">
                <label for="id_proyek_pbl" class="form-label">Proyek</label>
                <select name="id_proyek_pbl" id="id_proyek_pbl" class="form-select">
                    <option value="">Semua Proyek</option>
                    @foreach($proyeks as $proyek)
                        <option value="{{ $proyek->id_proyek_pbl }}" {{ request('id_proyek_pbl') == $proyek->id_proyek_pbl ? 'selected' : '' }}>
                            {{ $proyek->judul }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="sort" class="form-label">Urutkan Berdasarkan</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="tanggal" {{ request('sort', 'tanggal') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                    <option value="deskripsi" {{ request('sort') == 'deskripsi' ? 'selected' : '' }}>Deskripsi</option>
                    <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status Pengerjaan</option>
                    <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID Milestone</option>
                </select>
            </div>
            <div class="form-group">
                <label for="direction" class="form-label">Arah Urutan</label>
                <select name="direction" id="direction" class="form-select">
                    <option value="desc" {{ request('direction', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru/Tertinggi</option>
                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Terlama/Terendah</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                <a href="{{ route('dosen.milestone.index') }}" class="btn btn-outline-secondary"><i class="fa fa-sync-alt"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

@if($milestones->isEmpty())
    <div class="alert alert-info text-center">
        Tidak ada data milestone yang cocok dengan filter.
    </div>
@else
    <div class="milestone-grid">
        @foreach($milestones as $milestone)
            <div class="milestone-card">
                <div class="card-header">
                    <span class="project-title">{{ $milestone->proyek->judul ?? 'Tanpa Proyek' }}</span>
                    <a href="{{ route('dosen.milestone.show', $milestone->id_milestone) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-eye"></i> Lihat Detail
                    </a>
                </div>
                <div class="card-body">
                    <p class="description">{{ $milestone->deskripsi }}</p>
                    <div class="meta">
                        <div class="meta-item">
                            <span><i class="fa fa-calendar-alt"></i> Tanggal</span>
                            <span>{{ \Carbon\Carbon::parse($milestone->tanggal)->format('d M Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <span><i class="fa fa-tasks"></i> Status Pengerjaan</span>
                            @php
                                $statusClass = 'status-belum';
                                if($milestone->status === 'Berhasil') $statusClass = 'status-berhasil';
                                if($milestone->status === 'Sedang') $statusClass = 'status-sedang';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $milestone->status }}</span>
                        </div>
                        <div class="meta-item">
                             <span><i class="fa fa-user-check"></i> Persetujuan Dosen</span>
                             @php
                                $approvalStatus = $milestone->dosen_approval_status ?? 'pending';
                                $approvalClass = 'approval-' . $approvalStatus;
                             @endphp
                             <span class="fw-bold {{ $approvalClass }}">{{ ucfirst($approvalStatus) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($milestones->hasPages())
        <div class="mt-4">
            {{ $milestones->appends(request()->query())->links() }}
        </div>
    @endif
@endif

@endsection
