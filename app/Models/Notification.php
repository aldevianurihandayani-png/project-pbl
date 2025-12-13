<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'is_read',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* ======================================
     * RELASI
     * ====================================== */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /* ======================================
     * SCOPES
     * ====================================== */
    public function scopeForCurrent($q)
    {
        return $query->where(function ($x) {
            $x->whereNull('user_id')               // notif global
              ->orWhere('user_id', Auth::id());    // notif per-user
        });
    }

    public function scopeUnread($q)
    {
        return $q->where('is_read', false);
    }

    /* ======================================
     * HELPERS
     * ====================================== */
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
                'judul',
                'pesan',
                'is_read',
                'created_at',
                'user_id',
            ]);
    }

    /* ======================================
     * ACTIONS
     * ====================================== */
    public function markAsRead(): bool
    {
        return $this->update(['is_read' => true]);
    }
}