<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';          // nama tabel
    protected $primaryKey = 'id_notifikasi';    // primary key
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Hitung jumlah notifikasi yang belum dibaca
     * Bisa dipanggil tanpa parameter.
     */
    public static function getUnreadCount($userId = null)
    {
        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }

        return self::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
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

        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
