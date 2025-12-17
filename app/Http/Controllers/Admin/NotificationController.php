<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * ✅ Query dasar: ambil notifikasi yang memang ditujukan untuk user login
     * melalui pivot notification_user.
     */
    private function baseQuery()
    {
        if (!Auth::check()) {
            // tidak login -> kosong
            return Notification::query()->whereRaw('1=0');
        }

        $uid = Auth::id();

        // Ambil notifikasi yang punya pivot untuk user ini
        return Notification::query()->whereHas('recipients', function ($q) use ($uid) {
            $q->where('users.id', $uid);
        });
    }

    public function index(Request $request)
    {
        $query = $this->baseQuery()->latest();

        // Filter status read/unread pakai pivot (bukan kolom notifications.is_read)
        if ($request->filled('status') && $request->status !== 'all') {
            $isRead = ($request->status === 'read');

            $uid = Auth::id();
            $query->whereHas('recipients', function ($q) use ($uid, $isRead) {
                $q->where('users.id', $uid)
                  ->where('notification_user.is_read', $isRead ? 1 : 0);
            });
        }

        // Filter type (kalau kolomnya ada di tabel notifications)
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Search: sesuaikan dengan kolom yang benar (judul/pesan)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('pesan', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(15)->withQueryString();

        // ⚠️ pastikan view yang benar untuk halaman list notifikasi user/admin
        // kalau view ini memang dipakai admin, silakan biarkan.
        return view('admins.notifikasi.index', compact('notifications', 'request'));
    }

    public function create()
    {
        $users = User::orderBy('nama')->get();
        return view('admins.notifikasi.create', compact('users'));
    }

    /**
     * ⚠️ Store di controller ini SEBAIKNYA tidak dipakai lagi untuk sistem kamu,
     * karena sistem kamu sudah pakai NotifikasiController (Admin) + syncRecipients().
     * Tapi kalau route kamu masih mengarah ke sini, kita buat kompatibel:
     */
    public function store(Request $request)
    {
        // Sesuaikan dengan skema tabel kamu (judul/pesan/type/url/link_url/user_id/role)
        $data = $request->validate([
            'judul'    => 'required|string|max:255',
            'pesan'    => 'nullable|string',
            'user_id'  => 'nullable',
            'role'     => 'nullable|string|max:50',
            'type'     => 'nullable|string|max:30',
            'url'      => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:255',
            'penerima' => 'nullable|string',
        ]);

        $data['is_read'] = 0;

        // Normalisasi broadcast/personal/role-based seperti di controller admin
        $userIdRaw   = $request->input('user_id');
        $roleRaw     = $request->input('role');
        $penerimaRaw = $request->input('penerima');

        $userIdIsBroadcastToken = is_string($userIdRaw) && in_array(strtolower(trim($userIdRaw)), ['', '0', 'all', 'semua', 'semua pengguna', 'semua_pengguna'], true);
        $penerimaIsBroadcast    = is_string($penerimaRaw) && in_array(strtolower(trim($penerimaRaw)), ['all', 'semua', 'semua pengguna', 'semua_pengguna'], true);

        $isBroadcast = $penerimaIsBroadcast || $userIdIsBroadcastToken || (empty($userIdRaw) && empty($roleRaw));

        if ($isBroadcast) {
            $data['user_id'] = null;
            $data['role']    = null;
        } else {
            if (!empty($userIdRaw) && is_numeric($userIdRaw)) {
                $request->validate(['user_id' => 'exists:users,id']);
                $data['user_id'] = (int) $userIdRaw;
                $data['role'] = null;
            } else {
                $data['user_id'] = null;
                $data['role'] = $roleRaw ?: null;
            }
        }

        $notif = Notification::create($data);

        // ✅ pivot penerima
        $notif->syncRecipients();

        // ✅ kirim email (kalau kamu pakai)
        $notif->sendEmail();

        return redirect()->route('admins.notifikasi.index')->with('success', 'Notifikasi berhasil dibuat.');
    }

    /**
     * ✅ Mark ALL read untuk user login di pivot.
     */
    public function markAllRead()
    {
        if (!Auth::check()) return back();

        DB::table('notification_user')
            ->where('user_id', Auth::id())
            ->where('is_read', 0)
            ->update([
                'is_read' => 1,
                'read_at' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * ✅ Mark read untuk 1 notifikasi di pivot.
     */
    public function markRead(Notification $notification)
    {
        if (!Auth::check()) abort(403);

        $uid = Auth::id();

        // Pastikan notifikasi ini memang milik user tersebut di pivot
        $exists = DB::table('notification_user')
            ->where('notification_id', $notification->id)
            ->where('user_id', $uid)
            ->exists();

        if (!$exists) {
            abort(403, 'Unauthorized action.');
        }

        // Update pivot read
        DB::table('notification_user')
            ->where('notification_id', $notification->id)
            ->where('user_id', $uid)
            ->update([
                'is_read' => 1,
                'read_at' => now(),
                'updated_at' => now(),
            ]);

        // Redirect kalau ada link
        $target = $notification->link_url ?: ($notification->url ?? null);

        return $target
            ? redirect($target)
            : back()->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }
}
