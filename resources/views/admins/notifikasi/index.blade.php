@extends('layouts.admin')

@section('page_title', 'Manajemen Notifikasi')

@section('content')

@php
    /**
     * ✅ ANTI ERROR / ANTI KOSONG:
     * Kadang controller ngirim $notifikasis, kadang $notifications.
     * Jadi kita samakan di sini.
     */
    $notifications = $notifications ?? ($notifikasis ?? collect());

    // ✅ FIX: kalau $notifications ternyata Collection (get()), ubah jadi paginator
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

    // ✅ aman sekarang karena pasti paginator (atau minimal punya total())
    $total = method_exists($notifications, 'total')
        ? $notifications->total()
        : (is_countable($notifications) ? count($notifications) : 0);

    $first = ($total > 0)
        ? ((method_exists($notifications,'currentPage') ? ($notifications->currentPage() - 1) : 0) * (method_exists($notifications,'perPage') ? $notifications->perPage() : 15) + 1)
        : 0;

    $last  = ($total > 0 && method_exists($notifications,'currentPage') && method_exists($notifications,'perPage'))
        ? min($notifications->currentPage() * $notifications->perPage(), $total)
        : $total;

    $rangeText = ($total > 0)
        ? "{$first}-{$last} dari {$total}"
        : "0 dari 0";

    $prevUrl = method_exists($notifications,'previousPageUrl') ? $notifications->previousPageUrl() : null;
    $nextUrl = method_exists($notifications,'nextPageUrl') ? $notifications->nextPageUrl() : null;

    // ✅ kalau controller belum ngirim $totalUsers, amanin biar ga error
    $totalUsers = $totalUsers ?? 0;
@endphp

<style>
    .gmail-wrap{background:#fff;border-radius:12px;box-shadow:0 1px 2px rgba(0,0,0,.08);overflow:hidden;border:1px solid #eef1f6;}
    .gmail-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:10px 12px;border-bottom:1px solid #e9ecef;background:#fff;}
    .gt-left{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
    .gt-right{display:flex;align-items:center;gap:10px;}
    .gt-icon{width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;border:1px solid #e9ecef;background:#fff;border-radius:8px;cursor:pointer;text-decoration:none;color:#111;}
    .gt-icon[aria-disabled="true"]{opacity:.4;pointer-events:none;}
    .gt-range{font-size:13px;color:#6b7280;white-space:nowrap;}
    .gt-nav{display:flex;gap:6px;}
    .gmail-search{padding:10px 12px;border-bottom:1px solid #e9ecef;background:#fff;}
    .gs-input{width:100%;height:40px;border:1px solid #e9ecef;border-radius:999px;padding:0 14px;outline:none;background:#f6f8fc;}
    .gs-input:focus{background:#fff;border-color:#c7d2fe;}
    .gmail-list{background:#fff;}
    .mail-row{display:flex;align-items:center;gap:10px;padding:10px 12px;border-bottom:1px solid #f1f5f9;transition:background .15s ease;}
    .mail-row:hover{background:#f8fafc;}
    .mail-row.unread{background:#f6f8fc;font-weight:600;}
    .mr-left{display:flex;align-items:center;gap:10px;min-width:36px;}
    .mr-star{border:0;background:transparent;cursor:pointer;font-size:18px;color:#94a3b8;line-height:1;}
    .mr-star:hover{color:#f59e0b;}
    .mr-main{flex:1;min-width:0;display:flex;align-items:center;gap:16px;text-decoration:none;color:#111;}
    .mr-sender{width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#111;}
    .mr-subject{flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:inherit;}
    .mr-title{margin-right:6px;}
    .mr-snippet{font-weight:400;color:#64748b;}
    .mr-meta{font-weight:400;color:#64748b;font-size:12px;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .mr-right{display:flex;align-items:center;gap:10px;}
    .mr-time{width:120px;text-align:right;font-size:12px;color:#64748b;white-space:nowrap;}
    .mr-actions{display:none;gap:8px;align-items:center;}
    .mail-row:hover .mr-actions{display:flex;}
    .mr-act{width:30px;height:30px;border:1px solid #e9ecef;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;background:#fff;cursor:pointer;text-decoration:none;color:#111;}
    .mr-act.danger{border-color:#ffd6d6;color:#b42318;}
    .gmail-empty{padding:16px 12px;color:#64748b;}
    .gmail-pagination{padding:12px;background:#fff;}
    .debug-pill{font-size:12px;color:#475569;background:#f1f5f9;border:1px solid #e2e8f0;padding:4px 8px;border-radius:999px;}
    @media (max-width: 768px){
        .mr-sender{width:120px;}
        .mr-time{width:80px;}
        .mr-actions{display:flex;}
    }
</style>

<div class="gmail-wrap">

    <div class="gmail-toolbar">
        <div class="gt-left">
            <a class="gt-icon" href="{{ route('admins.notifikasi.create') }}" title="Tambah Notifikasi">
                <i class="fa-solid fa-plus"></i>
            </a>

            <button class="gt-icon" type="button" title="Refresh" onclick="location.reload()">
                <i class="fa-solid fa-rotate-right"></i>
            </button>

            <form method="POST" action="{{ route('admins.notifikasi.markAll') }}">
                @csrf
                <button class="gt-icon" type="submit" title="Tandai semua dibaca">
                    <i class="fa-solid fa-check-double"></i>
                </button>
            </form>

            {{-- ✅ DEBUG (boleh hapus nanti) --}}
            <span class="debug-pill">Total data: {{ $total }}</span>
        </div>

        <div class="gt-right">
            <div class="gt-range">{{ $rangeText }}</div>
            <div class="gt-nav">
                <a class="gt-icon" href="{{ $prevUrl ?? '#' }}" aria-disabled="{{ empty($prevUrl) ? 'true':'false' }}">‹</a>
                <a class="gt-icon" href="{{ $nextUrl ?? '#' }}" aria-disabled="{{ empty($nextUrl) ? 'true':'false' }}">›</a>
            </div>
        </div>
    </div>

    <div class="gmail-search">
        <form method="GET" action="">
            <input class="gs-input" type="text" name="q" value="{{ request('q') }}" placeholder="Telusuri notifikasi">
        </form>
    </div>

    <div class="gmail-list">
        @forelse ($notifications as $notifikasi)
            @php
                $title   = $notifikasi->judul ?? '-';
                $snippet = $notifikasi->pesan ?? '-';
                $time    = optional($notifikasi->created_at)->format('d M Y H:i');
                $isUnread = !$notifikasi->is_read;

                if (empty($notifikasi->user_id)) {
                    $recipientText = $totalUsers > 0
                        ? "Kepada: Semua User ({$totalUsers})"
                        : "Kepada: Semua User";
                } else {
                    $recipientName = optional($notifikasi->user)->nama
                        ?? optional($notifikasi->user)->name
                        ?? 'User';
                    $recipientText = "Kepada: {$recipientName}";
                }

                $senderLabel = empty($notifikasi->user_id) ? 'Broadcast' : 'Personal';
            @endphp

            <div class="mail-row {{ $isUnread ? 'unread' : '' }}">
                <div class="mr-left">
                    <button class="mr-star" type="button" title="Bintang">☆</button>
                </div>

                <a class="mr-main" href="{{ route('admins.notifikasi.show', $notifikasi->id) }}">
                    <div class="mr-sender">{{ $senderLabel }}</div>
                    <div class="mr-subject">
                        <span class="mr-title">{{ $title }}</span>
                        <span class="mr-snippet">— {{ $snippet }}</span>
                        <div class="mr-meta">{{ $recipientText }}</div>
                    </div>
                </a>

                <div class="mr-right">
                    <div class="mr-time">{{ $time }}</div>

                    <div class="mr-actions">
                        @if($isUnread)
                            <a class="mr-act" href="{{ route('admins.notifikasi.read', $notifikasi->id) }}" title="Tandai dibaca">
                                <i class="fa-solid fa-check"></i>
                            </a>
                        @endif

                        <a class="mr-act" href="{{ route('admins.notifikasi.show', $notifikasi->id) }}" title="Detail">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a class="mr-act" href="{{ route('admins.notifikasi.edit', $notifikasi->id) }}" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form method="POST" action="{{ route('admins.notifikasi.destroy', $notifikasi->id) }}" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button class="mr-act danger" type="submit" title="Hapus" onclick="return confirm('Hapus notifikasi ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="gmail-empty text-center">
                Tidak ada notifikasi.
            </div>
        @endforelse
    </div>

    <div class="gmail-pagination d-flex justify-content-center">
        {{ method_exists($notifications,'links') ? $notifications->links() : '' }}
    </div>
</div>

@endsection
