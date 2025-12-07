<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Nama tabel di DB
    protected $table = 'mahasiswas';

    // Primary key = nim (string)
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'angkatan',
        'no_hp',
        'kelas',
        'id_dosen',     // tambahkan ini kalau memang ada di tabel
        'kelompok_id',  // opsional, kalau ada
    ];

    // === RELASI ===

    // Dosen pembimbing / penguji
    public function dosenPembimbing()
    {
        // FK: id_dosen di tabel mahasiswas → PK: id di tabel dosen
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id');
    }

    // (opsional) relasi ke Kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }
}
