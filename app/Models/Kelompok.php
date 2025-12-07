<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';

    protected $fillable = [
        'nama',
        'kelas',
        'judul',
        'ketua_kelompok',
        'dosen_pembimbing',   // FK → users.id
        'judul_proyek',
        'nama_klien',
        'anggota',            // disimpan sebagai text (string berisi list nama)
    ];

    /**
     * ==========================
     *        RELASI MODEL
     * ==========================
     */

    // (1) Relasi ke mahasiswa anggota kelompok
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id');
    }

    // (2) Relasi ke proyek PBL
    public function proyek()
    {
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
    }

    public function proyekPbl()
    {
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
    }

    // (3) Relasi ke User sebagai dosen pembimbing
    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing', 'id');
    }

    // (4) Relasi ke mahasiswa sebagai ketua kelompok
    public function ketua()
    {
        return $this->belongsTo(Mahasiswa::class, 'ketua_kelompok', 'nim');
    }

    // (5) Relasi ke tabel anggota kelompok (jika ada tabel terpisah)
    public function anggotaRelasi()
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_id', 'id');
    }

    /**
     * ==========================
     *     ACCESSOR / MUTATOR
     * ==========================
     */

    // Convert kolom "anggota" (string) → array
    public function getAnggotaArrayAttribute(): array
    {
        if (!$this->anggota) return [];
        return array_values(array_filter(array_map('trim', explode(',', $this->anggota))));
    }
}
