<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RubrikPenilaian extends Model
{
    use HasFactory;

    protected $table = 'rubrik_penilaian';
    protected $primaryKey = 'kode_rubrik';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_rubrik',
        'kriteria',
        'bobot',
        'kode_mk',
    ];

    // Relasi ke tabel MataKuliah
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }
}
