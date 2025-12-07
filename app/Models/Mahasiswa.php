<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // NAMA TABEL SESUAI DB
    protected $table = 'mahasiswas';

    // Primary key pakai NIM (string, bukan auto increment)

    protected $primaryKey = 'nim';
    protected $keyType = 'string';
    public $incrementing = false;

    // KOLOM YANG BISA DI-ISI MASS ASSIGNME
    protected $table = 'mahasiswas';

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'angkatan',
        'no_hp',
        'kelas',
    ];


    /* ======================================
        RELASI
    ====================================== */

    // Kelompok
    public function kelompok()
    {

        // FK: id_kelompok -> PK: id di tabel kelompok

        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id');
    }

    // Logbook
    public function logbook()
    {
        // FK di tabel logbooks: nim -> nim di tabel mahasiswas
        return $this->hasMany(Logbook::class, 'nim', 'nim');
    }


    // Laporan penilaian
    public function laporanPenilaian()
    {
        return $this->hasMany(LaporanPenilaian::class, 'nim', 'nim');
    }

    // Relasi ke user
    public function user()
    {
        // jika relasi via nim:
        return $this->belongsTo(User::class, 'nim', 'nim');
    }

    // Dosen Pembimbing (yang kamu minta)
    public function dosenPembimbing()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id', 'id');
    }

    // Proyek PBL (yang kamu minta)
    public function proyekPbl()
    {
        return $this->belongsTo(ProyekPbl::class, 'proyek_pbl_id', 'id');
    }


    // Route model binding pakai nim
    public function getRouteKeyName()
    {
        return 'nim';
    }
}
