<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    
    protected $primaryKey = 'id_dosen';
    public $incrementing = true;     // set true jika kolom PK auto-increment
    protected $keyType = 'int';

    protected $fillable = [
        'nama_dosen',
        'jabatan',
        'nip',
        'no_telp',
    ];
}
