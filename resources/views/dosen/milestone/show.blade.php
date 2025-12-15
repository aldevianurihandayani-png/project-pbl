@extends('dosen.layout')

@section('title', 'Detail Milestone')
@section('page_title', 'Detail Milestone')

@push('styles')
<style>
    .timeline-container {
        max-width: 800px;
        margin: auto;
    }
    .timeline-item {
        position: relative;
        padding-left: 40px;
        padding-bottom: 2rem;
        border-left: 2px solid #e9ecef;
    }
    .timeline-item:last-child {
        border-left: 2px solid transparent;
    }
    .timeline-icon {
        position: absolute;
        left: -16px;
        top: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--navy, #0b1d54);
        color: #fff;
        display: grid;
        place-items: center;
        font-size: 1.1rem;
    }
    .timeline-content {
        background-color: #fff;
        border-radius: var(--radius, 16px);
        box-shadow: var(--shadow, 0 6px 20px rgba(13,23,84,.08));
        padding: 1.5rem;
    }
    .milestone-title {
        font-weight: 600;
        font-size: 1.5rem;
        color: var(--navy, #0b1d54);
        margin-bottom: 0.5rem;
    }
    .milestone-meta {
        font-size: 0.9rem;
        color: var(--muted, #6c7a8a);
        margin-bottom: 1.5rem;
    }
    .milestone-meta span {
        margin-right: 1.5rem;
    }
    .milestone-description {
        line-height: 1.6;
    }
    .approval-section, .comment-section {
        margin-top: 1.5rem;
    }
    .approval-badge {
        display: inline-block;
        padding: 0.6em 1.2em;
        font-size: 1.1rem;
        border-radius: 50px;
        font-weight: bold;
    }
    .approval-pending { background-color: #ffc107; color: #333; }
    .approval-approved { background-color: #28a745; color: #fff; }
    .approval-rejected { background-color: #dc3545; color: #fff; }

    .comment-form textarea {
        border-radius: 12px;
        padding: 1rem;
    }
    .comment-form .btn {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        margin-top: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="timeline-container">
    {{-- Back Button --}}
    <div class="mb-3">
        <a href="{{ route('dosen.milestone.index') }}" class="btn btn-light">
           <i class="fa fa-arrow-left"></i> Kembali ke Daftar
       </a>
    </div>

    {{-- Milestone Details Item --}}
    <div class="timeline-item">
        <div class="timeline-icon"><i class="fa fa-flag-checkered"></i></div>
        <div class="timeline-content">
            <p class="milestone-meta">
                <span><i class="fa fa-folder-open"></i> Proyek: <strong>{{ $milestone->proyek->judul ?? 'Tanpa Proyek' }}</strong></span>
                <span><i class="fa fa-calendar-alt"></i> Tanggal: <strong>{{ \Carbon\Carbon::parse($milestone->tanggal)->format('d F Y') }}</strong></span>
            </p>
            <h2 class="milestone-title">Detail Milestone</h2>
            <p class="milestone-description">{{ $milestone->deskripsi }}</p>
        </div>
    </div>

    {{-- Approval Item --}}
    <div class="timeline-item">
        <div class="timeline-icon"><i class="fa fa-user-check"></i></div>
        <div class="timeline-content">
             <h3 class="h5">Persetujuan Dosen</h3>
             <hr class="my-3">
             @php
                $status = $milestone->dosen_approval_status ?? 'pending';
             @endphp

             @if($status == 'pending')
                <p>Silakan berikan persetujuan untuk milestone ini.</p>
                <div class="d-flex gap-2">
                    <form action="{{ route('dosen.milestone.approve', $milestone->id_milestone) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyetujui milestone ini?');">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check"></i> Setuju
                        </button>
                    </form>
                    <form action="{{ route('dosen.milestone.reject', $milestone->id_milestone) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menolak milestone ini?');">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times"></i> Tolak
                        </button>
                    </form>
                </div>
             @else
                <p>Status persetujuan saat ini:</p>
                @php
                    $statusText = ucfirst($status);
                    $statusClass = 'approval-' . $status;
                @endphp
                <div class="approval-badge {{ $statusClass }}">{{ $statusText }}</div>
             @endif
        </div>
    </div>

    {{-- Comment/Feedback Item --}}
    <div class="timeline-item">
        <div class="timeline-icon"><i class="fa fa-comments"></i></div>
        <div class="timeline-content comment-section">
            <h3 class="h5">Kirim Feedback ke Admin</h3>
            <p class="text-muted">Komentar yang Anda kirim akan diteruskan sebagai feedback ke Admin.</p>
            <form action="{{ route('dosen.milestone.comment', $milestone->id_milestone) }}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea name="comment" rows="4" class="form-control" placeholder="Tuliskan feedback Anda di sini..." required minlength="5"></textarea>
                    @error('comment')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
