<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianAnggotaKelompok extends Model
{
    protected $table = 'penilaian_anggota_kelompok';

    protected $fillable = [
        'kelompok_id',
        'penilai_nim',
        'dinilai_nim',
        'nilai',
        'keterangan',
    ];
}
