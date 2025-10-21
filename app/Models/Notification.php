<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use HasFactory;

    // Jika kamu TIDAK pakai tabel 'notifications' bawaan Laravel, set table sendiri:
    // protected $table = 'app_notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'course',
        'link_url',
        'is_read',
    ];

    protected $casts = [
        'is_read'   => 'boolean',
        'created_at'=> 'datetime',
        'updated_at'=> 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ---------- Scopes ---------- */
    public function scopeForCurrent($q)
    {
        return $q->where(function ($x) {
            $x->whereNull('user_id')->orWhere('user_id', Auth::id());
        });
    }

    public function scopeUnread($q)
    {
        return $q->where('is_read', false);
    }

    /* ---------- Helpers ---------- */
    public static function getUnreadCount(): int
    {
        return static::query()->unread()->forCurrent()->count();
    }

    public static function getListForTopbar()
    {
        return static::query()
            ->unread()
            ->forCurrent()
            ->latest()
            ->limit(10)
            ->get(['id','title','type','created_at','link_url']);
    }

    public function markAsRead(): bool
    {
        return $this->update(['is_read' => true]);
    }
}
