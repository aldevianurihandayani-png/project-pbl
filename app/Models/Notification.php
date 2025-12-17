<?php

namespace App\Models;

use App\Mail\NotifikasiMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',   // null = broadcast/role-based, isi = personal
        'role',      // null = broadcast/personal, isi = role-based
        'judul',
        'pesan',
        'type',
        'url',
        'link_url',
        'is_read',   // legacy header (status read yang benar ada di pivot)
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Pivot penerima notifikasi (per-user read status)
     * table: notification_user
     * columns minimal: notification_id, user_id, is_read
     */
    public function recipients()
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id')
            ->withPivot(['is_read', 'read_at'])
            ->withTimestamps();
    }

    /**
     * Ambil notifikasi yang memang ditujukan untuk user yang login
     * (berdasarkan pivot - ini yang paling aman untuk lonceng)
     */
    public function scopeForCurrent($query)
    {
        if (!Auth::check()) {
            return $query->whereRaw('1=0');
        }

        $uid = Auth::id();

        return $query->whereHas('recipients', function ($q) use ($uid) {
            $q->where('users.id', $uid);
        });
    }

    /**
     * Notifikasi yang belum dibaca untuk user login (berdasarkan pivot)
     */
    public function scopeUnread($query)
    {
        if (!Auth::check()) {
            return $query->whereRaw('1=0');
        }

        $uid = Auth::id();

        return $query->whereHas('recipients', function ($q) use ($uid) {
            $q->where('users.id', $uid)
              ->where('notification_user.is_read', 0);
        });
    }

    public static function getUnreadCount(): int
    {
        if (!Auth::check()) {
            return 0;
        }

        return static::query()->unread()->count();
    }

    public static function getListForTopbar(int $limit = 10)
    {
        if (!Auth::check()) {
            return collect();
        }

        return static::query()
            ->forCurrent()
            ->latest()
            ->limit($limit)
            ->get(['id', 'judul', 'pesan', 'created_at', 'user_id', 'role']);
    }

    /**
     * Mark as read untuk user login (pivot)
     */
    public function markAsReadForCurrent(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $uid = Auth::id();

        $this->recipients()->updateExistingPivot($uid, [
            'is_read' => 1,
            'read_at' => now(),
        ]);

        return true;
    }

    /**
     * ✅ SYNC PENERIMA KE PIVOT
     *
     * Aturan:
     * 1) Personal      : user_id terisi  => pivot hanya user itu
     * 2) Role-based    : user_id null & role terisi => pivot semua user dengan role itu
     * 3) Broadcast all : user_id null & role null   => pivot semua user
     */
    public function syncRecipients(): void
    {
        // 1) PERSONAL
        if (!empty($this->user_id)) {
            $this->recipients()->syncWithPivotValues([$this->user_id], [
                'is_read' => 0,
                'read_at' => null,
            ]);
            return;
        }

        // 2) ROLE-BASED
        if (empty($this->user_id) && !empty($this->role)) {
            $ids = User::where('role', $this->role)->pluck('id')->all();

            if (!empty($ids)) {
                $this->recipients()->syncWithPivotValues($ids, [
                    'is_read' => 0,
                    'read_at' => null,
                ]);
            } else {
                // kalau role tidak punya user, kosongkan pivot supaya konsisten
                $this->recipients()->sync([]);
            }
            return;
        }

        // 3) BROADCAST (SEMUA USER)
        $ids = User::pluck('id')->all();
        if (!empty($ids)) {
            $this->recipients()->syncWithPivotValues($ids, [
                'is_read' => 0,
                'read_at' => null,
            ]);
        } else {
            $this->recipients()->sync([]);
        }
    }

    /**
     * ✅ KIRIM EMAIL
     * mengikuti aturan penerima yang sama dengan syncRecipients()
     */
    public function sendEmail(): void
    {
        // 1) PERSONAL
        if (!empty($this->user_id)) {
            $user = User::find($this->user_id);
            if ($user && !empty($user->email)) {
                Mail::to($user->email)->send(new NotifikasiMail($this));
            }
            return;
        }

        // 2) ROLE-BASED
        if (empty($this->user_id) && !empty($this->role)) {
            User::where('role', $this->role)
                ->whereNotNull('email')
                ->chunk(50, function ($users) {
                    foreach ($users as $user) {
                        Mail::to($user->email)->send(new NotifikasiMail($this));
                    }
                });
            return;
        }

        // 3) BROADCAST
        User::whereNotNull('email')->chunk(50, function ($users) {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new NotifikasiMail($this));
            }
        });
    }

    public static function createAndSend(array $data): self
    {
        $data['is_read'] = $data['is_read'] ?? 0;

        // Normalisasi: kalau broadcast, pastikan null-null
        if (empty($data['user_id']) && empty($data['role'])) {
            $data['user_id'] = null;
            $data['role'] = null;
        }

        // Kalau personal, role harus null
        if (!empty($data['user_id'])) {
            $data['role'] = null;
        }

        // Kalau role-based, user_id harus null
        if (empty($data['user_id']) && !empty($data['role'])) {
            $data['user_id'] = null;
        }

        $notif = static::create($data);
        $notif->syncRecipients();
        $notif->sendEmail();

        return $notif;
    }
}
