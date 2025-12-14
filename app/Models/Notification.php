<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

// ✅ TAMBAHAN UNTUK EMAIL
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifikasiMail;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id', // boleh dipakai untuk single-recipient legacy / info "personal vs broadcast"
        'judul',
        'pesan',
        'is_read', // legacy (untuk record header). status per-user ada di pivot
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Legacy: relasi single user (kalau user_id terisi)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * ✅ BARU: relasi penerima banyak user (pivot notification_user)
     * Pivot kamu saat ini hanya ada: notification_id, user_id, is_read, created_at
     * ❗ jadi JANGAN pakai withTimestamps() (karena pivot tidak punya updated_at)
     */
    public function recipients()
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id')
            ->withPivot(['is_read']); // read_at belum ada di DB kamu
    }

    /* ======================================
     * SCOPE (untuk lonceng user)
     * ====================================== */

    /**
     * ✅ notif untuk user yang login berdasarkan pivot
     */
    public function scopeForCurrent($query)
    {
        $uid = Auth::id();

        return $query->whereHas('recipients', function ($q) use ($uid) {
            $q->where('users.id', $uid);
        });
    }

    /**
     * ✅ unread untuk user login berdasarkan pivot
     */
    public function scopeUnread($query)
    {
        $uid = Auth::id();

        return $query->whereHas('recipients', function ($q) use ($uid) {
            $q->where('users.id', $uid)
              ->where('notification_user.is_read', 0);
        });
    }

    /* ======================================
     * HELPERS (untuk lonceng user)
     * ====================================== */

    public static function getUnreadCount(): int
    {
        if (!Auth::check()) return 0;

        return static::query()
            ->forCurrent()
            ->unread()
            ->count();
    }

    public static function getListForTopbar(int $limit = 10)
    {
        if (!Auth::check()) return collect();

        $uid = Auth::id();

        return static::query()
            ->whereHas('recipients', function ($q) use ($uid) {
                $q->where('users.id', $uid);
            })
            ->latest()
            ->limit($limit)
            ->get(['id', 'judul', 'pesan', 'created_at']);
    }

    /**
     * ✅ tandai dibaca untuk user yang login (pivot)
     * Pivot kamu belum ada read_at, jadi update is_read saja
     */
    public function markAsReadForCurrent(): bool
    {
        if (!Auth::check()) return false;

        $uid = Auth::id();

        $this->recipients()->updateExistingPivot($uid, [
            'is_read' => 1,
        ]);

        return true;
    }

    /**
     * Legacy: tandai dibaca global (jangan dipakai untuk lonceng user lagi)
     * Dipertahankan agar kode lama tidak error.
     */
    public function markAsRead(): bool
    {
        return $this->update(['is_read' => true]);
    }

    /* ======================================
     * PENERIMA (pivot) + EMAIL
     * ====================================== */

    /**
     * ✅ generate pivot recipients.
     * - kalau user_id terisi => hanya user itu
     * - kalau user_id null   => semua user
     *
     * Pivot default is_read = 0
     */
    public function syncRecipients(): void
    {
        // personal
        if (!empty($this->user_id)) {
            $this->recipients()->syncWithPivotValues([$this->user_id], ['is_read' => 0]);
            return;
        }

        // broadcast
        $ids = User::pluck('id')->toArray();
        if (count($ids) > 0) {
            $this->recipients()->syncWithPivotValues($ids, ['is_read' => 0]);
        }
    }

    /**
     * Kirim email notifikasi:
     * - kalau user_id terisi  => kirim ke user itu saja
     * - kalau user_id null    => kirim ke semua user yang punya email
     */
    public function sendEmail(): void
    {
        // ✅ kirim ke 1 user (notif personal)
        if (!empty($this->user_id)) {
            $user = User::find($this->user_id);

            if ($user && !empty($user->email)) {
                Mail::to($user->email)->send(new NotifikasiMail($this));
            }

            return;
        }

        // ✅ kirim ke semua user (notif global)
        User::whereNotNull('email')->chunk(50, function ($users) {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new NotifikasiMail($this));
            }
        });
    }

    /**
     * Helper cepat: simpan notifikasi + sync pivot + kirim email
     */
    public static function createAndSend(array $data): self
    {
        $data['is_read'] = $data['is_read'] ?? 0;

        $notif = static::create($data);

        // ✅ penting: bikin row pivot untuk setiap penerima
        $notif->syncRecipients();

        // ✅ kirim email
        $notif->sendEmail();

        return $notif;
    }
}
