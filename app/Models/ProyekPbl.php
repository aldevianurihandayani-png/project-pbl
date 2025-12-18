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
        // NOTE: pastikan PK dosen sesuai tabel kamu (id atau id_dosen)
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    public function kelompok()
    {
        // ✅ proyek_pbl.id_kelompok -> kelompoks.id
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id');
    }
}
