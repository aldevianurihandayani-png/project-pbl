<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);

use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'course',
        'link_url',
        'is_read',
    ];

    public static function getUnreadCount()
    {
        return self::query()
            ->where('is_read', false)
            ->where(function ($query) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', Auth::id());
            })
            ->count();
    }

    public static function getListForTopbar()
    {
        return self::query()
            ->where('is_read', false)
            ->where(function ($query) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', Auth::id());
            })
            ->latest()
            ->limit(10)
            ->get(['id', 'title', 'type', 'created_at', 'link_url']);

    }
}
    }