<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';

    // Sesuaikan isian mass-assignment dengan kolom di tabelmu
    protected $fillable = [
        'nama',
        'kelas',
        'judul',
        'ketua_kelompok',      
        'dosen_pembimbing',    
        'judul_proyek',
        'nama_klien',
        'anggota',             
    ];

  
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id');
    }

    
    public function proyek()
    {
        return $this->hasOne(ProyekPbl::class, 'kelompok_id', 'id');
    }

   
    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing', 'id');
    }

   
    public function ketua()
    {
        return $this->belongsTo(Mahasiswa::class, 'ketua_kelompok', 'nim');
    
    }

    
    public function getAnggotaArrayAttribute(): array
    {
        if (!$this->anggota) return [];
        return array_values(array_filter(array_map('trim', explode(',', $this->anggota))));
    }
}
