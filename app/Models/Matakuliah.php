<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
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
        'nama_dosen',
        'jabatan',
        'nip',
        'no_telp',
        'id_dosen',
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

    public function dosen()
    {
        // kalau pakai model User bawaan Laravel
        return $this->belongsTo(User::class, 'id_dosen', 'id');
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
