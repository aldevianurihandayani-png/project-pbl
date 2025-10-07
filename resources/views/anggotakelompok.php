<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaKelompok extends Model
{
    protected $table = 'kelompok_anggota'; // penting: nama tabel custom

    protected $fillable = ['kelompok_id','nim','nama'];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }
}
