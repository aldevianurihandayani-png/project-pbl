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

    // 🔥 RELASI: Dosen → Mahasiswa (1 dosen membimbing banyak mahasiswa)
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'id_dosen', 'id_dosen');
        // kolom 'id_dosen' harus ada di tabel mahasiswas
    }

    // relasi ke mata kuliah (opsional)
    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class, 'id_dosen', 'id_dosen');
    }
}
