<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Feedback extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_notifikasi',
        'isi',
        'status',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    // 🔥 relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
