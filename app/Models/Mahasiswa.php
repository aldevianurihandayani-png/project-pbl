<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'no_hp',
    ];

    // Pakai NIM sebagai primary key
    protected $primaryKey = 'nim';
    public $incrementing = false;   // karena nim bukan auto increment
    protected $keyType = 'string';  // nim bertipe string

    // (Opsional) Nonaktifkan timestamps jika tabelmu tidak punya created_at / updated_at
    // public $timestamps = false;
}
