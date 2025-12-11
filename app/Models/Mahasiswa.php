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

    // Kolom yang boleh diisi (pastikan sama dengan kolom di tabel mahasiswas)
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'angkatan',
        'no_hp',
        'kelas',

        // FK hubungan
        'id_kelompok',          // FK ke tabel kelompoks (id)
        'dosen_pembimbing_id',  // FK ke tabel dosens (id)
        'proyek_pbl_id',        // FK ke tabel proyek_pbls (id)
        'user_id',              // FK ke users (id)

        'semester',
    ];

    /* ======================================
       RELASI
    ====================================== */

    // Setiap mahasiswa hanya punya satu kelompok
    public function kelompok()
    {
        // FK: id_kelompok (di tabel mahasiswas) -> PK: id (di tabel kelompoks)
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
        // FK: dosen_pembimbing_id di tabel mahasiswas → id di tabel dosens
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id', 'id');
    }

    // Proyek PBL
    public function proyekPbl()
    {
        return $this->belongsTo(ProyekPbl::class, 'proyek_pbl_id', 'id');
    }

    /* ======================================
       HELPER UNTUK KELOMPOK / ANGGOTA
    ====================================== */

    // Cek apakah mahasiswa sudah punya kelompok
    public function hasKelompok(): bool
    {
        return ! is_null($this->id_kelompok);
    }

    // Scope: ambil hanya mahasiswa yang belum punya kelompok
    public function scopeBelumPunyaKelompok($query)
    {
        return $query->whereNull('id_kelompok');
    }

    // Set kelompok untuk beberapa NIM sekaligus (dipakai di controller)
    public static function setKelompokForAnggota(array $nims, int $kelompokId): int
    {
        return static::whereIn('nim', $nims)
            ->update(['id_kelompok' => $kelompokId]);
    }

    // Route model binding pakai nim
    public function getRouteKeyName()
    {
        return 'nim';
    }
}
