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




    protected $fillable = ['name','email','password','role','email_verified_at'];


    // supaya di Blade bisa pakai ->format('Y-m-d')
    protected $casts = [
        'tanggal' => 'date',
    ];

}
