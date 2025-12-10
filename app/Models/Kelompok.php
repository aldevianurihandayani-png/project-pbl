<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\ProyekPbl;
use App\Models\AnggotaKelompok;

class Kelompok extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kelompoks';

    /**
     * Kolom yang boleh diisi mass-assignment.
     * (digabung dari dua $fillable yang sebelumnya dobel)
     */
    protected $fillable = [
        'nama',             // nama kelompok
        'kelas',            // TI-3A, TI-3B, dst
        'judul',            // kalau dipakai
        'judul_proyek',     // judul proyek PBL
        'ketua_kelompok',   // NIM ketua
        'dosen_pembimbing', // user_id dosen pembimbing
        'nama_klien',       // nama klien
        'anggota',          // backup list anggota dalam bentuk string
        'nims',             // optional: list NIM anggota (string)
        'angkatan',         // angkatan ketua / kelompok
        'semester',         // semester PBL (1–6)
    ];

    /* =========================================================
     * RELASI
     * =======================================================*/

    /**
     * Satu kelompok punya banyak mahasiswa
     * FK di tabel mahasiswas: kelompok_id → kelompoks.id
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id');
    }

    /**
     * Satu kelompok punya satu proyek PBL
     * FK: id_kelompok di tabel proyek_pbl
     */
    public function proyek()
    {
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
    }

    /**
     * Alias proyekPbl (dipakai di beberapa kode)
     */
    public function proyekPbl()
    {
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
    }

    /**
     * Dosen pembimbing (User)
     * FK: dosen_pembimbing → users.id
     */
    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing', 'id');
    }

    /**
     * Ketua kelompok (Mahasiswa)
     * FK: ketua_kelompok (nim) → mahasiswas.nim
     */
    public function ketua()
    {
        return $this->belongsTo(Mahasiswa::class, 'ketua_kelompok', 'nim');
    }

    /**
     * Relasi ke tabel anggota_kelompok (jika ada tabel terpisah)
     * Dipakai misalnya with(['anggotaRelasi.mahasiswa'])
     */
    public function anggotaRelasi()
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_id', 'id');
    }

    /**
     * Alias anggota (nama pendek)
     */
    public function anggota()
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_id', 'id');
    }

    /**
     * Helper: kembalikan relasi anggota
     */
    public function anggotaKelompok()
    {
        return $this->anggota();
    }

    /**
     * Many-to-many: kelompok ↔ dosen penguji
     * Pivot: kelompok_penguji (kelompok_id, penguji_id)
     */
    public function penguji()
    {
        return $this->belongsToMany(User::class, 'kelompok_penguji', 'kelompok_id', 'penguji_id');
    }

    /* =========================================================
     * ACCESSOR / HELPER
     * =======================================================*/

    /**
     * Accessor: konversi kolom string "anggota" (dipisah koma)
     * menjadi array nama anggota.
     *
     * Contoh isi DB: "Budi, Siti, Andi"
     * -> $kelompok->anggota_array = ['Budi','Siti','Andi']
     */
    public function getAnggotaArrayAttribute(): array
    {
        if (!$this->anggota) {
            return [];
        }

        return array_values(
            array_filter(
                array_map('trim', explode(',', $this->anggota))
            )
        );
    }
}
