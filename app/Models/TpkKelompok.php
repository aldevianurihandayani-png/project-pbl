<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpkKelompok extends Model
{
    use HasFactory;

    // Nama tabel, silakan sesuaikan dengan migration kamu
    protected $table = 'tpk_kelompoks';

    protected $fillable = [
        'nama',        // nama kelompok
        'review_uts',
        'review_uas',
    ];
}
