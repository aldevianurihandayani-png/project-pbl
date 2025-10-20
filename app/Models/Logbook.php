<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $table = 'logbooks';

    protected $fillable = ['tanggal','minggu','aktivitas','keterangan','foto','user_id'];

    protected $casts = [
        'tanggal' => 'date',
    ];
    protected $hidden = ['foto'];
}
