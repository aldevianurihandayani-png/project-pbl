<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Matakuliah;

class Mahasiswa extends Model
{
    use HasFactory;

    // Nama tabel di DB
    protected $table = 'mahasiswas';

    // Primary key = nim (string, bukan auto increment)
    protected $primaryKey = 'nim';
    protected $keyType    = 'string';
    public $incrementing  = false;

    // Kolom yang boleh diisi
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'angkatan',
        'no_hp',
        'kelas',

        // FK hubungan
        'id_kelompok',
        'dosen_pembimbing_id',
        'proyek_pbl_id',
        'user_id',

        'semester',
    ];

    /* ======================================
       RELASI
    ====================================== */

    // Setiap mahasiswa hanya punya satu kelompok
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
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    // Dosen Pembimbing / Penguji
    public function dosenPembimbing()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id', 'id');
    }

    // Proyek PBL
    public function proyekPbl()
    {
        return $this->belongsTo(ProyekPbl::class, 'proyek_pbl_id', 'id');
    }

    // ======================================================
    // ✅ RELASI BARU (DITAMBAHKAN, TIDAK MENGHAPUS YANG LAIN)
    // ======================================================
    // Mahasiswa bisa mengambil banyak Mata Kuliah
    public function matakuliah()
    {
        return $this->belongsToMany(
            Matakuliah::class,
            'mk_mahasiswa', // tabel pivot
            'nim',          // FK di mk_mahasiswa ke mahasiswas
            'kode_mk'       // FK di mk_mahasiswa ke mata_kuliah
        );
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

    // Set kelompok untuk beberapa NIM sekaligus
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
