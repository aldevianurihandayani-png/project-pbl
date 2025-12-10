<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';      // nama tabel
    protected $primaryKey = 'id';

    public $timestamps = false;      // karena created_at & updated_at NULL

    protected $fillable = [
        'nama_kelas',
    ];
}
