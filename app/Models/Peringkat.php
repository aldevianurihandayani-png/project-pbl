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
        'jenis',          // mahasiswa | kelompok
        'mahasiswa_id',   // nullable (kalau kelompok)
        'nama_tpk',       // nama mahasiswa / kelompok (hasil TPK)
        'mata_kuliah',
        'nilai_total',    // 0–1 (ditampilkan persen)
        'peringkat',
        'semester',
        'tahun_ajaran',
    ];

    protected $casts = [
        'nilai_total' => 'float',
        'peringkat'   => 'integer',
    ];

    /* ===================== RELATION ===================== */

    // optional, kalau suatu saat mau mapping ke mahasiswa asli
    public function mahasiswa()
    {
        return $this->belongsTo(
            Mahasiswa::class,
            'mahasiswa_id',
            'nim'
        );
    }

    /* ===================== SCOPES ===================== */

    public function scopeMahasiswa($query)
    {
        return $query->where('jenis', 'mahasiswa');
    }

    public function scopeKelompok($query)
    {
        return $query->where('jenis', 'kelompok');
    }
}
