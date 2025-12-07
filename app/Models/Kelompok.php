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
    /**
     * Kolom yang boleh diisi mass-assignment.
     * Sesuaikan dengan struktur tabel `kelompoks` di database kamu.
     */
    protected $fillable = [
        'nama',             // nama kelompok
        'kelas',            // TI-3A, TI-3B, dst
        'judul',            // kalau dipakai
        'judul_proyek',     // judul proyek (sering dipakai)
        'ketua_kelompok',   // NIM ketua
        'dosen_pembimbing', // user_id dosen pembimbing
        'nama_klien',       // nama klien (kalau di DB namanya 'klien', ganti jadi 'klien')
        'anggota',          // backup list anggota dalam bentuk string
        'nims',             // optional: list NIM anggota (string)
        'angkatan',         // angkatan ketua / kelompok
        'semester',         // semester PBL (1–6)
    ];

    /* =========================================================
     * RELASI YANG DIPAKAI BERSAMA (PEMBIMBING + PENGUJI)
     * =======================================================*/

    /**
     * Satu kelompok memiliki banyak mahasiswa (via FK `kelompok_id` di tabel `mahasiswas`)
     * Dipakai di beberapa tempat untuk ambil semua mahasiswa dalam kelompok.
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id');
    }

    // (2) Relasi ke proyek PBL
    /**
     * Satu kelompok memiliki satu proyek PBL (FK benar: id_kelompok di tabel proyek_pbl).
     * Nama 'proyek' dipakai di beberapa kode lama.
     */

    public function proyek()
    {
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
    }

    /**
     * Alias dengan nama yang lebih eksplisit.
     * Dipakai di controller sebagai with('proyekPbl').
     */
    public function proyekPbl()
    {
        return $this->hasOne(ProyekPbl::class, 'id_kelompok', 'id');
    }

    // (3) Relasi ke User sebagai dosen pembimbing
    /**
     * Dosen pembimbing (user) yang bertanggung jawab atas kelompok ini.
     * FK: dosen_pembimbing → users.id
     */
    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing', 'id');
    }
    // (4) Relasi ke mahasiswa sebagai ketua kelompok
    /**
     * Ketua kelompok (mahasiswa).
     * FK: ketua_kelompok (nim) → mahasiswas.nim
     */
    public function ketua()
    {
        return $this->belongsTo(Mahasiswa::class, 'ketua_kelompok', 'nim');
    }


    // (5) Relasi ke tabel anggota kelompok (jika ada tabel terpisah)
    public function anggotaRelasi()

    /**
     * Relasi ke tabel pivot anggota_kelompok.
     * Dipakai kalau kita mau with(['anggota.mahasiswa']).
     */
    public function anggota
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_id', 'id');
    }

    /**

     * ==========================
     *     ACCESSOR / MUTATOR
     * ==========================
     */

    // Convert kolom "anggota" (string) → array

    public function anggotaKelompok()
    {
        return $this->anggota();
    }

    /**
     * Many-to-many: kelompok ↔ dosen penguji.
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
