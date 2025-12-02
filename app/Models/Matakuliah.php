<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;

    /** Nama tabel di database */
    protected $table = 'mata_kuliah';

    /** Gunakan kode_mk sebagai primary key */
    protected $primaryKey = 'kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';

    /** Kolom yang boleh diisi (mass assignable) */
    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'id_dosen',
    ];

    /** Tipe data otomatis dikonversi */
    protected $casts = [
        'sks'       => 'integer',
        'semester'  => 'integer',
        'id_dosen'  => 'integer',
    ];

    /** Gunakan kode_mk untuk route binding */
    public function getRouteKeyName()
    {
        return 'kode_mk';
    }

    /** Relasi ke tabel dosen (many-to-one) */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen', 'id');
    }

    /**
     * Event model: pastikan kode_mk selalu disimpan dalam huruf besar
     * (contoh: mk-001 => MK-001)
     */
    protected static function booted()
    {
        static::saving(function (self $mk) {
            if ($mk->kode_mk) {
                $mk->kode_mk = strtoupper($mk->kode_mk);
            }
        });
    }
}
