<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;
<<<<<<< HEAD
    protected $fillable = [
        'nama', 'kelas', 'anggota', 'dosen_pembimbing'];
    
=======

    protected $fillable = [

        'nama', 'kelas', 'judul', 'ketua_kelompok', 'dosen_pembimbing', 'judul_proyek', 'nama_klien', 'anggota'
    ];

    protected $table = 'kelompoks';   // <— jika tabel kamu singular
>>>>>>> a923d4ee3e9ef6fcc97abdd2b7e6643b04772962

}