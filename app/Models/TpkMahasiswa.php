<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpkMahasiswa extends Model
{
    use HasFactory;

    
    protected $table = 'data_tpks';

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'nama',
        'keaktifan',
        'nilai_kelompok',
        'nilai_dosen',
    ];
}
