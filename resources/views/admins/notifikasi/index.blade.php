@include('admins.partials.header', ['title' => 'Manajemen Notifikasi'])

<style>
    .notification-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 15px;
        padding: 15px;
        border: 1px solid #eef1f6;
        display: flex;
        align-items: center;
    }
    .notification-card.unread {
        border-left: 5px solid var(--navy-2);
    }
    .notification-icon {
        font-size: 1.8em;
        color: var(--navy-2);
        margin-right: 15px;
    }
    .notification-content {
        flex-grow: 1;
    }
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    .notification-title {
        font-weight: bold;
        color: #333;
        font-size: 1.1em;
    }
    .notification-timestamp {
        font-size: 0.8em;
        color: var(--muted);
    }
    .notification-message {
        color: #555;
        line-height: 1.4;
    }
    .notification-user {
        font-size: 0.9em;
        color: var(--muted);
        margin-top: 5px;
    }
</style>

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Notifikasi</h6>
    </div>
    <div class="card-body">
        @forelse ($notifikasis as $notifikasi)
            <div class="notification-card {{ !$notifikasi->is_read ? 'unread' : '' }}">
                <div class="notification-icon">
                    <i class="fa-solid {{ !$notifikasi->is_read ? 'fa-bell' : 'fa-bell-slash' }}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <div class="notification-title">{{ $notifikasi->title }}</div>
                        <div class="notification-timestamp">{{ $notifikasi->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="notification-message">{{ $notifikasi->message }}</div>
                    <div class="notification-user">Untuk: {{ $notifikasi->user->name ?? 'Pengguna Tidak Dikenal' }}</div>
                </div>
                {{-- Anda bisa menambahkan tombol aksi di sini jika diperlukan --}}
                {{-- <div class="ml-auto">
                    <a href="#" class="btn btn-sm btn-outline-primary">Tandai Dibaca</a>
                </div> --}}
            </div>
        @empty
            <div class="text-center text-muted">
                Tidak ada notifikasi.
            </div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $notifikasis->links() }}
        </div>
    </div>
</div>

@include('admins.partials.footer')
