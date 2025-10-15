<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $fillable = [
        'nama', 'kelas', 'anggota', 'dosen_pembimbing'];
    protected $table = 'kelompok';   // <— jika tabel kamu singular

}