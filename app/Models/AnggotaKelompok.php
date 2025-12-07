<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AnggotaKelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompok_anggota';

    // Sesuaikan dengan kolom yang ada di tabel `kelompok_anggota`
    protected $fillable = [
        'kelompok_id',
        'mahasiswa_id', // kalau memang ada
        'nim',          // atau ini, kalau di tabel pakai nim
        // tambahkan kolom lain kalau perlu (misal: 'role', 'created_at' kalau manual, dll)
    ];

    /**
     * Relasi ke tabel Mahasiswa.
     * Otomatis pilih FK: `mahasiswa_id` atau `nim`, tergantung mana yang ada di tabel.
     */
    public function mahasiswa()
    {
        // Deteksi otomatis nama kolom FK di tabel pivot
        $fk = Schema::hasColumn($this->getTable(), 'mahasiswa_id')
            ? 'mahasiswa_id'
            : 'nim';

        // Kolom di tabel mahasiswas yang menjadi primary key untuk FK tersebut
        $otherKey = $fk === 'nim' ? 'nim' : 'id';

        return $this->belongsTo(Mahasiswa::class, $fk, $otherKey);
    }

    /**
     * Relasi ke tabel Kelompok
     * (kelompok_id -> kelompoks.id)
     */
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }
}
