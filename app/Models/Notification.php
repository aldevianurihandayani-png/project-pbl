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
        'user_id', // null = broadcast, isi = personal
        'judul',
        'pesan',
        'is_read', // legacy header
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

    /* ======================================
     * SCOPE
     * ====================================== */

    // WAJIB: notif global + notif user
    public function scopeForCurrent($query)
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id')
            ->withPivot(['is_read']);
    }

    public function scopeForCurrent($query)
    {
        if (!Auth::check()) return $query->whereRaw('1=0');

        $uid = Auth::id();

        return $query->whereHas('recipients', function ($q) use ($uid) {
            $q->where('users.id', $uid);
        });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /* ======================================
     * HELPERS
     * ====================================== */

    public static function getUnreadCount(): int
    {
        if (!Auth::check()) return 0;

        return static::query()->forCurrent()->unread()->count();
    }

    public static function getListForTopbar(int $limit = 10)
    {
        if (!Auth::check()) return collect();

        return static::query()
            ->forCurrent()
            ->latest()
            ->limit($limit)
            ->get(['id', 'judul', 'pesan', 'created_at', 'user_id']);
    }

    public function markAsRead(): bool
    {
        if (!Auth::check()) return false;

        $uid = Auth::id();

        $this->recipients()->updateExistingPivot($uid, [
            'is_read' => 1,
        ]);

        return true;
    }

    public function syncRecipients(): void
    {
        // personal
        if (!empty($this->user_id)) {
            $this->recipients()->syncWithPivotValues([$this->user_id], ['is_read' => 0]);
            return;
        }

        // broadcast
        $ids = User::pluck('id')->all();
        if (!empty($ids)) {
            $this->recipients()->syncWithPivotValues($ids, ['is_read' => 0]);
        }
    }

    public function sendEmail(): void
    {
        // personal
        if (!empty($this->user_id)) {
            $user = User::find($this->user_id);
            if ($user && !empty($user->email)) {
                Mail::to($user->email)->send(new NotifikasiMail($this));
            }
            return;
        }

        // broadcast
        User::whereNotNull('email')->chunk(50, function ($users) {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new NotifikasiMail($this));
            }
        });
    }

    public static function createAndSend(array $data): self
    {
        $data['is_read'] = $data['is_read'] ?? 0;

        $notif = static::create($data);
        $notif->syncRecipients();
        $notif->sendEmail();

        return $notif;
    }
}
