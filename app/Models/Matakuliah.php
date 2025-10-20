<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'kode_mk';

    public $incrementing = false; // karena primary key bukan auto increment
    protected $keyType = 'string';
    protected $fillable = ['kode_mk','nama_mk','sks','semester','id_dosen'];

    public function dosen() {
        return $this->belongsTo(User::class, 'id_dosen', 'id');
    }
}

    public $incrementing = false;   // PK bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = ['kode_mk','nama_mk','sks','semester','id_dosen'];

    // <-- Tambahkan ini agar route model binding pakai kode_mk
    public function getRouteKeyName()
    {
        return 'kode_mk';
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen', 'id');
    }


