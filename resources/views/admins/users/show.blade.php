@extends('layouts.admin')

@section('title', 'Detail Akun')
@section('page_title', 'Detail Akun')

@section('content')

<style>
    .account-card {
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 14px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid #e3e7f5;
        overflow: hidden;
    }

    .account-card-hd {
        padding: 14px 20px;
        border-bottom: 1px solid #e5e7f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #0b1d54, #172554);
        color: #ffffff;
    }

    .account-title {
        font-size: 16px;
        font-weight: 600;
    }

    .account-subtitle {
        font-size: 13px;
        opacity: .85;
        margin-top: 2px;
    }

    .account-card-bd {
        padding: 20px 24px 18px;
        background: #f5f7ff;
    }

    .detail-box {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px 18px;
        border: 1px solid #e3e7f5;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 150px auto;
        row-gap: 10px;
        column-gap: 12px;
        font-size: 14px;
    }

    .detail-label {
        font-weight: 600;
        color: #0b1d54;
    }

    .detail-value {
        color: #111827;
    }

    .detail-value-muted {
        color: #6b7280;
        font-style: italic;
    }

    .detail-actions {
        margin-top: 16px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 13px;
        border: 1px solid transparent;
        text-decoration: none;
        cursor: pointer;
        transition: 0.15s;
        white-space: nowrap;
    }

    .btn-pill-primary {
        background: #0b1d54;
        color: #fff;
        border-color: #0b1d54;
    }

    .btn-pill-primary:hover {
        background: #122872;
    }

    .btn-pill-ghost {
        background: #ffffff;
        color: #0b1d54;
        border-color: #cfd5f0;
    }

    .btn-pill-ghost:hover {
        background: #eef2ff;
        text-decoration: none;
    }

    .badge-role {
        display: inline-flex;
        padding: 3px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        background: #e0edff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .badge-status {
        display: inline-flex;
        padding: 3px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-status-active {
        background: #e7f6ec;
        color: #1b7841;
        border: 1px solid #b7e2c5;
    }
    .badge-status-pending {
        background: #fff7e6;
        color: #b36b00;
        border: 1px solid #ffd48a;
    }
    .badge-status-rejected {
        background: #ffe5e5;
        color: #b42020;
        border: 1px solid #f5bbbb;
    }
</style>

<div class="account-card">
    <div class="account-card-hd">
        <div>
            <div class="account-title">Detail Akun Pengguna</div>
            <div class="account-subtitle">
                Informasi singkat akun terdaftar di sistem.
            </div>
        </div>
    </div>

    <div class="account-card-bd">
        <div class="detail-box">
            <div class="detail-grid">
                <div class="detail-label">ID</div>
                <div class="detail-value">#{{ $user->id }}</div>

                <div class="detail-label">Nama</div>
                <div class="detail-value">
                    {{ $user->nama ?? $user->name ?? '-' }}
                </div>

                <div class="detail-label">Email</div>
                <div class="detail-value">
                    {{ $user->email }}
                </div>

                <div class="detail-label">Role</div>
                <div class="detail-value">
                    @if($user->role)
                        <span class="badge-role">{{ $user->role }}</span>
                    @else
                        <span class="detail-value-muted">Belum ditetapkan</span>
                    @endif
                </div>

                @if(isset($user->status))
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        @if($user->status === 'active')
                            <span class="badge-status badge-status-active">Active</span>
                        @elseif($user->status === 'pending')
                            <span class="badge-status badge-status-pending">Pending</span>
                        @elseif($user->status === 'rejected')
                            <span class="badge-status badge-status-rejected">Rejected</span>
                        @else
                            <span class="badge-status">{{ $user->status }}</span>
                        @endif
                    </div>
                @endif
            </div>

            <div class="detail-actions">
                <a href="{{ route('admins.users.edit', $user->id) }}"
                   class="btn-pill btn-pill-primary">
                    Edit Akun
                </a>

                <a href="{{ route('admins.users.index') }}"
                   class="btn-pill btn-pill-ghost">
                    Kembali ke Manajemen Akun
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
