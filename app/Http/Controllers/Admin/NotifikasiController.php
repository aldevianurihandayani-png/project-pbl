<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        // ✅ jumlah user untuk label "Semua User (X)"
        $totalUsers = User::count();

        $notifications = Notification::with('user')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($x) use ($q) {
                    $x->where('judul', 'like', "%{$q}%")
                      ->orWhere('pesan', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admins.notifikasi.index', compact('notifications', 'totalUsers'));
    }

    public function create()
    {
        $users = User::orderBy('nama')->get();
        return view('admins.notifikasi.create', compact('users'));
    }

    /**
     * 🔥 SIMPAN KE DB + BUAT PENERIMA (pivot) + KIRIM EMAIL
     *
     * FIX UTAMA:
     * - Jika "kirim ke semua user" => user_id NULL dan role NULL (broadcast)
     * - Jika kirim ke user tertentu => user_id terisi, role NULL
     * - (Opsional) Jika kirim ke role tertentu => user_id NULL, role terisi
     *
     * NOTE:
     * Beberapa form mengirim user_id="all"/"semua"/"" saat broadcast.
     * Maka di sini kita NORMALISASI agar pasti menjadi NULL.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'    => 'required|string|max:255',
            'pesan'    => 'nullable|string',
            'user_id'  => 'nullable',              // jangan pakai exists dulu, karena bisa "all"
            'role'     => 'nullable|string|max:50',
            'type'     => 'nullable|string|max:30',
            'url'      => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:255',
            // kalau form kamu punya field penerima (optional):
            'penerima' => 'nullable|string',
        ]);

        $data['is_read'] = 0;

        // ================================
        // ✅ NORMALISASI PENERIMA (ANTI BUG)
        // ================================
        $userIdRaw   = $request->input('user_id');
        $roleRaw     = $request->input('role');
        $penerimaRaw = $request->input('penerima');

        $userIdIsBroadcastToken = is_string($userIdRaw) && in_array(strtolower(trim($userIdRaw)), ['', '0', 'all', 'semua', 'semua pengguna', 'semua_pengguna'], true);
        $penerimaIsBroadcast    = is_string($penerimaRaw) && in_array(strtolower(trim($penerimaRaw)), ['all', 'semua', 'semua pengguna', 'semua_pengguna'], true);

        // broadcast jika:
        // - penerima=semua/all
        // - atau user_id kosong / token all/semua
        // - atau user_id tidak ada dan role juga tidak ada
        $isBroadcast = $penerimaIsBroadcast || $userIdIsBroadcastToken || (empty($userIdRaw) && empty($roleRaw));

        if ($isBroadcast) {
            // ✅ BROADCAST KE SEMUA
            $data['user_id'] = null;
            $data['role']    = null;
        } else {
            // personal jika user_id numeric
            if (!empty($userIdRaw) && is_numeric($userIdRaw)) {
                // validasi exists untuk personal
                $request->validate([
                    'user_id' => 'exists:users,id',
                ]);

                $data['user_id'] = (int) $userIdRaw;
                $data['role']    = null;
            } else {
                // role-based (opsional)
                $data['user_id'] = null;
                $data['role']    = $roleRaw ?: null;
            }
        }

        // ✅ 1) Simpan notifikasi header
        $notif = Notification::create($data);

        // ✅ 2) Buat record penerima (notification_user)
        //    ini yang bikin lonceng tiap user nyala
        $notif->syncRecipients();

        // ✅ 3) Kirim email sesuai penerima
        $notif->sendEmail();

        return redirect()->route('admins.notifikasi.index')
            ->with('success', 'Notifikasi berhasil dibuat, penerima tersimpan, & email terkirim.');
    }

    public function show(Notification $notifikasi)
    {
        $totalUsers = User::count();

        // opsional: hitung jumlah penerima dari pivot (kalau tabel pivot ada)
        $recipientCount = 0;
        try {
            $recipientCount = $notifikasi->recipients()->count();
        } catch (\Throwable $e) {
            $recipientCount = 0;
        }

        return view('admins.notifikasi.show', compact('notifikasi', 'totalUsers', 'recipientCount'));
    }

    public function edit(Notification $notifikasi)
    {
        $users = User::orderBy('nama')->get();
        return view('admins.notifikasi.edit', compact('notifikasi', 'users'));
    }

    /**
     * Update konten notifikasi (judul/pesan/penerima)
     * NOTE: kalau kamu ubah penerima, kita sync ulang pivot penerima.
     */
    public function update(Request $request, Notification $notifikasi)
    {
        $data = $request->validate([
            'judul'    => 'required|string|max:255',
            'pesan'    => 'nullable|string',
            'user_id'  => 'nullable',              // jangan pakai exists dulu, karena bisa "all"
            'role'     => 'nullable|string|max:50',
            'type'     => 'nullable|string|max:30',
            'url'      => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:255',
            'penerima' => 'nullable|string',
        ]);

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
                $request->validate([
                    'user_id' => 'exists:users,id',
                ]);

                $data['user_id'] = (int) $userIdRaw;
                $data['role']    = null;
            } else {
                $data['user_id'] = null;
                $data['role']    = $roleRaw ?: null;
            }
        }

        $notifikasi->update($data);

        // ✅ sinkronkan penerima lagi
        $notifikasi->syncRecipients();

        return redirect()->route('admins.notifikasi.index')
            ->with('success', 'Notifikasi berhasil diupdate.');
    }

    public function destroy(Notification $notifikasi)
    {
        // pivot akan kehapus otomatis kalau FK cascade benar
        $notifikasi->delete();

        return redirect()->back()
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * ✅ TANDAI DIBACA UNTUK USER YANG SEDANG LOGIN (pivot)
     * route: notifikasi/{notification}/read
     */
    public function markRead(Notification $notification)
    {
        // mark read per-user (bukan global)
        $notification->markAsReadForCurrent();

        return redirect()->back();
    }

    /**
     * ✅ TANDAI SEMUA DIBACA UNTUK USER YANG SEDANG LOGIN (pivot)
     * route: notifikasi/markAll
     */
    public function markAllRead()
    {
        if (!Auth::check()) return redirect()->back();

        DB::table('notification_user')
            ->where('user_id', Auth::id())
            ->where('is_read', 0)
            ->update([
                'is_read' => 1,
                'read_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->back();
    }

    // ======================================================
    // ✅ TAMBAHAN (TANPA UBAH KODE LAMA)
    // ======================================================

    /**
     * 👁 Detail notifikasi khusus route:
     * admins/notifikasi/{notification}/detail
     * (dibuat supaya route detail tidak bentrok dengan resource show)
     */
    public function detail(Notification $notification)
    {
        // panggil show yang sudah ada (tanpa mengubah show)
        return $this->show($notification);
    }

    /**
     * ↩ Tandai BELUM dibaca untuk user yang sedang login (pivot)
     * route: notifikasi/{notification}/unread
     */
    public function markUnread(Notification $notification)
    {
        if (!Auth::check()) return redirect()->back();

        DB::table('notification_user')
            ->where('notification_id', $notification->id)
            ->where('user_id', Auth::id())
            ->update([
                'is_read' => 0,
                'read_at' => null,
                'updated_at' => now(),
            ]);

        return redirect()->back();
    }
}
