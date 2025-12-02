<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama',
        'kelas',
        'judul',
        'ketua_kelompok',
        'dosen_pembimbing',
        'judul_proyek',
        'nama_klien',
        'anggota',
    ];

    // Relasi: satu kelompok memiliki banyak mahasiswa
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id');
    }

    // ✅ Relasi: satu kelompok memiliki satu proyek PBL (FK yang benar: id_kelompok)
    public function proyek()
    {
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
    }

    // ✅ Alias yang sama persis (dipakai di controller)
    public function proyekPbl()
    {
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
    }

    // Relasi: satu kelompok dibimbing oleh satu dosen (user)
    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing', 'id');
    }

    // Relasi: ketua kelompok adalah satu mahasiswa
    public function ketua()
    {
        return $this->belongsTo(Mahasiswa::class, 'ketua_kelompok', 'nim');
    }

    // Relasi tambahan untuk with(['anggota.mahasiswa'])
    public function anggota()
    {
        return $this->hasMany(\App\Models\AnggotaKelompok::class, 'kelompok_id', 'id');
    }

    // Accessor: konversi string "anggota" menjadi array
    public function getAnggotaArrayAttribute(): array
    {
        if (!$this->anggota) return [];
        return array_values(array_filter(array_map('trim', explode(',', $this->anggota))));
    }
}
