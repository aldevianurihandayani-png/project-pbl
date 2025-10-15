<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
    protected $fillable = [
        'user_id','tanggal','minggu','aktivitas','rincian',
        'lampiran_path','status','komentar_dosen'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
