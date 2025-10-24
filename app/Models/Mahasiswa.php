<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // ⬇ WAJIB: sesuaikan nama tabelnya
    protected $table = 'mahasiswas';

    // PK kamu pakai NIM (string)
    protected $primaryKey = 'nim';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nim','nama','angkatan','no_hp','id_kelompok','user_id'
    ];

    /** RELASI **/
    public function kelompok()
    {
     
         return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id');
    }

    public function logbook()
    {
        return $this->hasMany(Logbook::class, 'nim', 'nim');
    }

    // Gunakan nama kelas model dengan PascalCase, bukan nama tabel
    public function laporanPenilaian()
    {
        return $this->hasMany(LaporanPenilaian::class, 'nim', 'nim');
    }

    public function user()
    {
        // kalau relasi ke users via user_id: return $this->belongsTo(User::class, 'user_id', 'id');
        // kalau relasi via nim sesuai kodenya:
        return $this->belongsTo(User::class, 'nim', 'nim');
    }

    public function getRouteKeyName()
    {
        return 'nim';
    }
}
