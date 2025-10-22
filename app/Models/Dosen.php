<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    public $incrementing = true;
    protected $keyType = 'int';

    // 🔥 penting! daftar kolom yang boleh diisi massal:
    protected $fillable = [
        'nama_dosen',
        'jabatan',
        'nip',
        'no_telp',
    ];

    // (opsional) kalau pakai relasi ke mata kuliah:
    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class, 'id_dosen', 'id_dosen');
    }
}
