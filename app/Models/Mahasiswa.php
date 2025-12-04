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
    protected $keyType    = 'string';
    public $incrementing  = false;

    // KOLOM YANG BISA DI-ISI MASS ASSIGNMENT
    protected $fillable = [
        'nim',
        'nama',
        'email',       // kolom di DB
        'no_telp',     // di DB namanya no_telp, bukan no_hp
        'angkatan',
        'kelas',
        'id_dosen',
        'kelompok_id',

        // kalau nanti kolom2 ini benar-benar kamu tambah di DB,
        // fillable-nya sudah siap:
        'user_id',
        'semester',
        'dosen_pembimbing_id',
        'proyek_pbl_id',
    ];

    /* ======================================
        RELASI
    ====================================== */

    // Kelompok (FK: kelompok_id -> id di tabel kelompok)
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }

    // Logbook (FK di tabel logbooks: nim)
    public function logbook()
    {
        return $this->hasMany(Logbook::class, 'nim', 'nim');
    }

    // Laporan penilaian
    public function laporanPenilaian()
    {
        return $this->hasMany(LaporanPenilaian::class, 'nim', 'nim');
    }

    // Relasi ke user — CUKUP SATU KALI, TIDAK BOLEH DOBEL
    public function user()
    {
        // kalau relasinya via nim (kolom nim juga ada di tabel users)
        return $this->belongsTo(User::class, 'nim', 'nim')->withDefault();
    }

    // Dosen Pembimbing (kalau nanti kolomnya sudah ada)
    public function dosenPembimbing()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id', 'id');
    }

    // Proyek PBL (kalau nanti kolomnya sudah ada)
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
