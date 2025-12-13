@extends('layouts.admin')

@section('page_title', 'Manajemen Notifikasi')

@section('content')

@php
    // ✅ FIX: kalau $notifications ternyata Collection (get()), kita ubah jadi paginator supaya links() aman
    if ($notifications instanceof \Illuminate\Support\Collection) {
        $perPage = 15;
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;

        $notifications = new \Illuminate\Pagination\LengthAwarePaginator(
            $notifications->forPage($page, $perPage)->values(),
            $notifications->count(),
            $perPage,
            $page,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => request()->query(),
            ]
        );
    }
@endphp

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

        @forelse ($notifications as $notifikasi)
            <div class="notification-card {{ !$notifikasi->is_read ? 'unread' : '' }}">
                <div class="notification-icon">
                    <i class="fa-solid {{ !$notifikasi->is_read ? 'fa-bell' : 'fa-bell-slash' }}"></i>
                </div>

                <div class="notification-content">
                    <div class="notification-header">
                        <div class="notification-title">{{ $notifikasi->title }}</div>
                        <div class="notification-timestamp">
                            {{ optional($notifikasi->created_at)->format('d M Y H:i') }}
                        </div>
                    </div>

                    <div class="notification-message">
                        {{ $notifikasi->course ?? '-' }}
                    </div>

                    <div class="notification-user">
                        Untuk: {{ optional($notifikasi->user)->name ?? 'Umum' }}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted">
                Tidak ada notifikasi.
            </div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

@endsection
