<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $primaryKey = 'kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'kelas',
        'id_dosen',
    ];

    protected $casts = [
        'sks'       => 'integer',
        'semester'  => 'integer',
        'id_dosen'  => 'integer',
    ];

    // Route model binding pakai kode_mk
    public function getRouteKeyName()
    {
        return 'kode_mk';
    }

    /* ======================================
       RELASI
    ====================================== */

    // ✅ Mata Kuliah dimiliki 1 Dosen (Pengampu)
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    // ======================================================
    // ✅ RELASI BARU (DITAMBAHKAN, TIDAK MENGHAPUS YANG LAIN)
    // ======================================================
    // Mata Kuliah punya banyak Mahasiswa (via pivot mk_mahasiswa)
    public function mahasiswa()
    {
        return $this->belongsToMany(
            Mahasiswa::class,
            'mk_mahasiswa', // tabel pivot
            'kode_mk',      // FK di mk_mahasiswa ke mata_kuliah.kode_mk
            'nim'           // FK di mk_mahasiswa ke mahasiswas.nim
        );
    }

    /* ======================================
       EVENT
    ====================================== */

    protected static function booted()
    {
        static::saving(function (self $mk) {
            if ($mk->kode_mk) {
                $mk->kode_mk = strtoupper($mk->kode_mk);
            }
        });
    }
}
