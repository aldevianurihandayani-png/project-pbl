<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peringkat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'peringkats';

    protected $fillable = [
        'jenis',
        'mahasiswa_id',
        'nama_tpk',
        'mata_kuliah',
        'nilai_total',
        'peringkat',
        'semester',
        'tahun_ajaran',

        // WAJIB biar nyambung ke tabel TPK:
        'tpk_type',
        'tpk_id',
    ];

    protected $casts = [
        'nilai_total' => 'float',
        'peringkat'   => 'integer',
        'tpk_id'      => 'integer',
    ];
}
