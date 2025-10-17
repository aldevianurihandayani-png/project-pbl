<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $fillable = [

        'nama', 'kelas', 'judul', 'ketua_kelompok', 'dosen_pembimbing', 'judul_proyek', 'nama_klien', 'anggota'
    ];

    protected $table = 'kelompoks';   // <— jika tabel kamu singular

}