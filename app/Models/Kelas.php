<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';   // nama tabel yang sudah kamu buat
    protected $fillable = ['nama_kelas']; // tambah kolom lain kalau ada
    public $timestamps = false;   // kalau tabelnya nggak punya created_at / updated_at
}
