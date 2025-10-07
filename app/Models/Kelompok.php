<?php

namespace App\Models;              // <- harus persis begini

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $table = 'kelompoks';                       // <- WAJIB
    protected $fillable = ['judul','topik','id_dosen'];

    public function anggota()
    {
        return $this->hasMany(\App\Models\AnggotaKelompok::class, 'kelompok_id');
    }
}
