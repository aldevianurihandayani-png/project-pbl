<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TpkMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'data_tpks';

    protected $fillable = [
        'nama',
        'keaktifan',
        'nilai_kelompok',
        'nilai_dosen',
    ];

    protected $casts = [
        'keaktifan'      => 'float',
        'nilai_kelompok' => 'float',
        'nilai_dosen'    => 'float',
    ];
}
