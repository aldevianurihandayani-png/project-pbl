<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $primaryKey = 'kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'kelas',
        'id_dosen', // ✅ cukup simpan id_dosen untuk relasi dropdown
    ];

    protected $casts = [
        'sks'       => 'integer',
        'semester'  => 'integer',
        'id_dosen'  => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'kode_mk';
    }

    // ✅ RELASI: Mata Kuliah dimiliki 1 Dosen (Pengampu)
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    protected static function booted()
    {
        static::saving(function (self $mk) {
            if ($mk->kode_mk) {
                $mk->kode_mk = strtoupper($mk->kode_mk);
            }
        });
    }
}
