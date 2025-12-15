<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_dosen',
        'jabatan',
        'nip',
        'no_telp',
    ];

    /**
     * RELASI:
     * 1 DOSEN membimbing banyak MAHASISWA
     */
    public function mahasiswas()
    {
        return $this->hasMany(
            Mahasiswa::class,
            'id_dosen',
            'id_dosen'
        );
    }

    /**
     * RELASI:
     * 1 DOSEN mengampu banyak MATA KULIAH
     */
    public function mataKuliahs()
    {
        return $this->hasMany(
            MataKuliah::class,
            'id_dosen',
            'id_dosen'
        );
    }
}
