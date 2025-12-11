<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',      // 'materi', 'tugas', 'info'
        'title',
        'course',
        'link_url',
        'is_read',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* RELASI */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /* SCOPES */
    public function scopeForCurrent($q)
    {
        return $q->where(function ($x) {
            $x->whereNull('user_id')
              ->orWhere('user_id', Auth::id());
        });
    }

    /**
     * Ambil list notifikasi terbaru untuk topbar.
     * Bisa dipanggil tanpa parameter.
     */
    public static function getListForTopbar($userId = null, $limit = 5)
    {
        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }

    /* HELPERS */
    public static function getUnreadCount(): int
    {
        if (!Auth::check()) return 0;

        return static::query()
            ->unread()
            ->forCurrent()
            ->count();
    }

    public static function getListForTopbar(int $limit = 10)
    {
        if (!Auth::check()) return collect();

        return static::query()
            ->forCurrent()
            ->latest()
            ->limit($limit)
            ->get([
                'id',
                'type',
                'title',
                'course',
                'link_url',
                'is_read',
                'created_at',
                'user_id',
            ]);
    }

    public function markAsRead(): bool
    {
        return $this->update(['is_read' => true]);
    }
}
