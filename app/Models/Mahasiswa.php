<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Nama tabel di DB
    protected $table = 'mahasiswas';

    // Primary key = nim (string, bukan auto increment)
    protected $primaryKey = 'nim';
    protected $keyType    = 'string';
    public $incrementing  = false;

    // Kolom yang boleh diisi (sesuaikan dengan kolom yang benar-benar ada di tabel)
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'angkatan',
        'no_hp',
        'kelas',

        // tambahkan kalau memang ada di tabel:
        'id_dosen',           // FK ke tabel dosen (opsional)
        'id_kelompok',        // FK ke tabel kelompok (kalau nama kolomnya ini)
        'user_id',            // kalau relasi ke users pakai user_id
        'dosen_pembimbing_id',
        'proyek_pbl_id',
        'semester',
    ];

    /* ======================================
       RELASI
    ====================================== */

    // Kelompok
    public function kelompok()
    {
        // FK: id_kelompok -> PK: id di tabel kelompok
        // kalau di DB kamu nama kolomnya `kelompok_id`, ganti saja:
        // return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
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

    // Relasi ke user (via user_id → id)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    // Dosen Pembimbing / Penguji
    public function dosenPembimbing()
    {
        // sesuaikan FK-nya dengan kolom yang kamu pakai:
        // kalau pakai `id_dosen` → ganti 'dosen_pembimbing_id' jadi 'id_dosen'
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id', 'id');
        // return $this->belongsTo(Dosen::class, 'id_dosen', 'id');
    }

    // Proyek PBL
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
