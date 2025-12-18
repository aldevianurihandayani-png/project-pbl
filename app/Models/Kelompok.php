<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';

    // PK default Laravel adalah "id", jadi tidak perlu set primaryKey sama sekali.
   

    protected $fillable = [
        'nama',
        'kelas',
        'judul',
        'judul_proyek',
        'ketua_kelompok',   // nim ketua
        'dosen_pembimbing', // users.id
        'nama_klien',
        'anggota',          // string backup
        'nims',             // optional string list nim
        'angkatan',
        'semester',
    ];

    /* ==========================
     * RELASI
     * ========================== */

    // kelompoks.id -> mahasiswas.kelompok_id
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id');
    }

    // kelompoks.id -> proyek_pbl.id_kelompok
    public function proyek()
    {
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
    }

    // dosen_pembimbing -> users.id
    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing', 'id');
    }

    // ketua_kelompok(nim) -> mahasiswas.nim
    public function ketua()
    {
        return $this->belongsTo(Mahasiswa::class, 'ketua_kelompok', 'nim');
    }

    // kelompoks.id -> anggota_kelompoks.kelompok_id
    public function anggota()
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_id', 'id');
    }

    // pivot: kelompok_penguji(kelompok_id, penguji_id)
    public function penguji()
    {
        return $this->belongsToMany(User::class, 'kelompok_penguji', 'kelompok_id', 'penguji_id');
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
