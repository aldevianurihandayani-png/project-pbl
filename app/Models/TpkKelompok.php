<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TpkKelompok extends Model
{

    protected $table = 'tpk_kelompoks';

    protected $fillable = [
        'kelompok_id',
        'nama',
        'kelas',
        'review_uts',
        'review_uas',
    ];

    protected $casts = [
        'review_uts' => 'float',
        'review_uas' => 'float',
    ];

    /* =====================================================
     * RELATIONS
     * ===================================================== */

    /**
     * Relasi ke tabel kelompoks
     * tpk_kelompoks.kelompok_id -> kelompoks.id
     */
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

    /**
     * Relasi ke peringkat (polymorphic)
     * peringkats.tpk_type & tpk_id
     */
    public function peringkats()
    {
        return $this->morphMany(Peringkat::class, 'tpk', 'tpk_type', 'tpk_id');
    }
}
