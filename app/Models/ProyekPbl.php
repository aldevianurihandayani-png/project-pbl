<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyekPbl extends Model
{
    use HasFactory;

    protected $table = 'proyek_pbl';
    protected $primaryKey = 'id_proyek_pbl';
    public $incrementing = true;
    protected $keyType = 'int';

    // TABEL KAMU PUNYA created_at & updated_at
    public $timestamps = true;

    protected $fillable = [
        'judul',
    ];

    /* ================= RELATION ================= */

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id_kelompok');
    }
}
