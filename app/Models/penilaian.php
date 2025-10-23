<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    // kolom id sudah auto-increment (sesuai create table kamu)
    protected $fillable = [
        'mahasiswa_nim',
        'rubrik_id',
        'nilai',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }

    public function rubrik()
    {
        return $this->belongsTo(Rubrik::class, 'rubrik_id');
    }
}
