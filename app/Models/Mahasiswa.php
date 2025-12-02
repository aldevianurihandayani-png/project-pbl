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
    protected $primaryKey = 'nim';
    protected $keyType = 'string';
    public $incrementing = false;

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
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id');
    }

    // Logbook
    public function logbook()
    {
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
