<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AnggotaKelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompok_anggota';

    /**
     * Relasi ke tabel Mahasiswa
     * Menyesuaikan otomatis foreign key yang tersedia (mahasiswa_id atau nim)
     */
    public function mahasiswa()
    {
        // Deteksi otomatis nama kolom FK
        $fk = Schema::hasColumn($this->getTable(), 'mahasiswa_id') ? 'mahasiswa_id' : 'nim';
        $otherKey = $fk === 'nim' ? 'nim' : 'id';

        return $this->belongsTo(\App\Models\Mahasiswa::class, $fk, $otherKey);
    }
}
