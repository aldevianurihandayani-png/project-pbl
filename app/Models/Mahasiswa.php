<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswas';

    /**
     * Karena tabel kamu TIDAK punya kolom `id`
     * dan PRIMARY KEY = `nim` (varchar)
     * maka WAJIB ditambahkan properti berikut.
     */
    protected $primaryKey = 'nim';   // Primary key adalah nim
    public $incrementing = false;    // nim bukan auto-increment
    protected $keyType = 'string';   // tipe nim adalah varchar

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'angkatan',
        'no_hp',
        'kelas',
        'id_dosen'
    ];
}
