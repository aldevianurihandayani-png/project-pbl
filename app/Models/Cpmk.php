<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpmk extends Model
{
    // karena nama tabel bukan 'cpmks'
    protected $table = 'cpmk';

    protected $fillable = [
        'kode_mk',
        'kode',
        'deskripsi',
        'bobot',
        'urutan',
    ];

    // relasi ke mata kuliah (asumsi tabel 'mata_kuliah' punya kolom 'kode_mk')
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }
}