<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubrik extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * Kalau tabelmu memang bernama "rubrik" (bukan "rubriks"),
     * ini WAJIB ada.
     */
    protected $table = 'rubrik';

    /**
     * Kolom-kolom yang boleh di-mass assign (create/update).
     */
    protected $fillable = [
        'kode_mk',      // kode mata kuliah (relasi ke MK)
        'nama_rubrik',  // nama komponen rubrik
        'deskripsi',    // penjelasan rubrik
        'bobot',        // bobot nilai
        'urutan',       // urutan tampil
    ];

    /**
     * Relasi ke MataKuliah (optional tapi bagus kalau ada).
     * Asumsi:
     * - tabel mata kuliah: "mata_kuliah"
     * - model: App\Models\MataKuliah
     * - kunci: kode_mk di kedua tabel
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }
}
