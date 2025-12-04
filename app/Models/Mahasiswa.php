<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // nama tabel
    protected $table = 'mahasiswas';

    // primary key = nim

    // NAMA TABEL SESUAI DB
    protected $table = 'mahasiswas';

    // Primary key pakai NIM (string, bukan auto increment)

    protected $primaryKey = 'nim';
    protected $keyType = 'string';
    public $incrementing = false;

    // KOLOM YANG BISA DI-ISI MASS ASSIGNMENT
    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'no_hp',
        'id_kelompok',
        'user_id',

        
        // tambahkan jika memang ada di tabel:
        'kelas',
        'semester',
        'dosen_pembimbing_id',
        'proyek_pbl_id'

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


    public function user()
    {
        // relasi ke tabel users via user_id -> id
        // withDefault() mencegah error ketika user_id NULL
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }


    // Route model binding pakai nim
    public function getRouteKeyName()
    {
        return 'nim';
    }
}
