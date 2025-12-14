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
     * - user_id terisi => notifikasi personal + pivot ke user itu
     * - user_id kosong => broadcast + pivot ke semua user
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'   => 'required|string|max:255',
            'pesan'   => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id', // null = semua user (broadcast)
        ]);

        $data['is_read'] = 0;

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
            'judul'   => 'required|string|max:255',
            'pesan'   => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $notifikasi->update($data);

        // ✅ kalau pivot dipakai, sinkronkan penerima lagi
        // (biar penerima sesuai perubahan user_id / broadcast)
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
