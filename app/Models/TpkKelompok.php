<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TpkKelompok extends Model
{
    use HasFactory;

    protected $table = 'tpk_kelompoks';

    protected $fillable = [
        'nama',
        'review_uts',
        'review_uas',
    ];

    protected $casts = [
        'review_uts' => 'float',
        'review_uas' => 'float',
    ];
}
