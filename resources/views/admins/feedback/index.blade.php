@include('admins.partials.header', ['title' => 'Manajemen Feedback'])

<style>
    .feedback-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        padding: 20px;
        border: 1px solid #eef1f6;
    }
    .feedback-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .feedback-sender {
        font-weight: bold;
        color: var(--navy-2);
    }
    .feedback-email {
        font-size: 0.9em;
        color: var(--muted);
    }
    .feedback-timestamp {
        font-size: 0.8em;
        color: var(--muted);
    }
    .feedback-subject {
        font-size: 1.1em;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }
    .feedback-message {
        line-height: 1.6;
        color: #555;
    }
</style>

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Feedback</h6>
    </div>
    <div class="card-body">
        @forelse ($feedbacks as $feedback)
            <div class="feedback-card">
                <div class="feedback-header">
                    <div>
                        <div class="feedback-sender">{{ $feedback->name }}</div>
                        <div class="feedback-email">Surel Pengirim: {{ $feedback->email }}</div>
                    </div>
                    <div class="feedback-timestamp">{{ $feedback->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="feedback-subject">{{ $feedback->subject }}</div>
                <div class="feedback-message">{{ $feedback->message }}</div>
                {{-- Anda bisa menambahkan tombol aksi di sini jika diperlukan --}}
                {{-- <div class="mt-3 text-right">
                    <a href="#" class="btn btn-sm btn-primary">Balas</a>
                    <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                </div> --}}
            </div>
        @empty
            <div class="text-center text-muted">
                Tidak ada data feedback.
            </div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>

@include('admins.partials.footer')