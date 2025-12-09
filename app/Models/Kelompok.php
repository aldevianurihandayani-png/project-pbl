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

    protected $table = 'kelompoks';

    /**
     * Kolom yang boleh diisi mass-assignment.
     * Sesuaikan dengan struktur tabel `kelompoks` di database.
     */
    protected $fillable = [
        'nama',             // nama kelompok
        'kelas',            // TI-3A, TI-3B, dst
        'judul',            // kalau dipakai
        'judul_proyek',     // judul proyek
        'ketua_kelompok',   // NIM ketua
        'dosen_pembimbing', // user_id dosen pembimbing
        'nama_klien',       // nama klien
        'anggota',          // backup list anggota dalam bentuk string
        'nims',             // optional: list NIM anggota (string)
        'angkatan',         // angkatan ketua / kelompok
        'semester',         // semester PBL (1–6)
    ];

    /* ==========================
     *        RELASI MODEL
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
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
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
        // isi kalau nanti dipakai
    }

    public function anggota()
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_id', 'id');
    }

    public function anggotaKelompok()
    {
        return $this->anggota();
    }

    public function penguji()
    {
        return $this->belongsToMany(User::class, 'kelompok_penguji', 'kelompok_id', 'penguji_id');
    }

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
