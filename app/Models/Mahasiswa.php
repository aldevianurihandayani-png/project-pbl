<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // NAMA TABEL SESUAI DB
    protected $table = 'mahasiswas';

    // Primary key pakai NIM (string, bukan auto increment)
    protected $primaryKey = 'nim';
    protected $keyType = 'string';
    public $incrementing = false;

    // KOLOM YANG BISA DI-ISI MASS ASSIGNMENT
    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'no_hp',
        'id_kelompok',
        'user_id',
    ];

    /** RELASI **/
    public function kelompok()
    {
        // FK: id_kelompok -> PK: id di tabel kelompok
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id');
    }

    public function logbook()
    {
        // FK di tabel logbooks: nim -> nim di tabel mahasiswas
        return $this->hasMany(Logbook::class, 'nim', 'nim');
    }

    public function user()
    {
        // relasi ke tabel users via user_id -> id
        // withDefault() mencegah error ketika user_id NULL
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    // Route model binding pakai nim
    public function getRouteKeyName()
    {
        return 'nim';
    }
}
