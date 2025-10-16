<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'kelas', 'anggota', 'dosen_pembimbing'];
    protected $table = 'kelompoks';   // <— jika tabel kamu singular

    public function anggotas()
    {
        return $this->hasMany(AnggotaKelompok::class);
    }
}