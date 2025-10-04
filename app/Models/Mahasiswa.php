<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false; // karena primary key bukan auto increment
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'no_hp',
    ];
}
