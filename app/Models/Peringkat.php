<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;

class Peringkat extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah',
        'nilai_total',
        'peringkat',
        'semester',
        'tahun_ajaran',
    ];

    public function mahasiswa()
    {
        // foreign key di peringkats = mahasiswa_id
        // owner key di mahasiswas  = nim
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'nim');
    }
}
