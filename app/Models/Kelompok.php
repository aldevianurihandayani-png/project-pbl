<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\ProyekPbl;
use App\Models\AnggotaKelompok;
use App\Models\TpkKelompok;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';

    protected $fillable = [
        'nama',
        'kelas',
        'judul',
        'judul_proyek',
        'ketua_kelompok',
        'dosen_pembimbing',
        'nama_klien',
        'anggota',
        'nims',
        'angkatan',
        'semester',
    ];

    /* ==========================
     * RELASI
     * ========================== */

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id');
    }

    public function proyek()
    {
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
    }

    public function proyekPbl()
    {
        return $this->proyek();
    }

    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing', 'id');
    }

    public function ketua()
    {
        return $this->belongsTo(Mahasiswa::class, 'ketua_kelompok', 'nim');
    }

    public function anggotaRelasi()
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_id', 'id');
    }

    // alias kalau masih ada kode lama pakai anggotaKelompok()
    public function anggotaKelompok()
    {
        return $this->anggotaRelasi();
    }

    public function penguji()
    {
        return $this->belongsToMany(User::class, 'kelompok_penguji', 'kelompok_id', 'penguji_id');
    }

    // ✅ penting buat TPK nilai & ranking
    public function tpkKelompoks()
    {
        return $this->hasMany(TpkKelompok::class, 'kelompok_id', 'id');
    }

    /* ==========================
     * ACCESSOR
     * ========================== */

    public function getAnggotaArrayAttribute(): array
    {
        if (!$this->anggota) return [];

        return array_values(array_filter(array_map('trim', explode(',', $this->anggota))));
    }
}
