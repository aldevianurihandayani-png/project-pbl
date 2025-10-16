<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $fillable = ['name','email','password','role','email_verified_at'];


    // supaya di Blade bisa pakai ->format('Y-m-d')
    protected $casts = [
        'tanggal' => 'date',
    ];
}
