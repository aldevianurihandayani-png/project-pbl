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