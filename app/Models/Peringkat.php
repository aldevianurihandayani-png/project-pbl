<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peringkat extends Model
{
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
        'tpk_type',
        'tpk_id',
        'kelas', // ✅ TAMBAH INI
    ];

    protected $casts = [
        'nilai_total' => 'float',
        'peringkat'   => 'integer',
        'tpk_id'      => 'integer',
    ];

    public function tpk()
    {
        return $this->morphTo(__FUNCTION__, 'tpk_type', 'tpk_id');
    }
}
