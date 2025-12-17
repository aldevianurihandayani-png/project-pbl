<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TpkMahasiswa extends Model
{
   

    protected $table = 'data_tpks';

    protected $fillable = [
        'kelas',          
        'mahasiswa_nim',
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

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }
}
