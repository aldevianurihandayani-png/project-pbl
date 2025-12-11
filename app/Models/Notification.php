<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'notifications';

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'is_read',
    ];

    // Casting tipe data
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

    // Notifikasi untuk user yang sedang login
    public function scopeForCurrent($query)
    {
        return $query->where('user_id', Auth::id());
    }

    // Hanya yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /* ======================================
     * HELPERS
     * ====================================== */

    // Jumlah notif belum dibaca
    public static function getUnreadCount(): int
    {
        if (!Auth::check()) {
            return 0;
        }

        return static::query()
            ->unread()
            ->forCurrent()
            ->count();
    }

    // List notif terbaru untuk topbar
    public static function getListForTopbar(int $limit = 10)
    {
        if (!Auth::check()) {
            return collect();
        }

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