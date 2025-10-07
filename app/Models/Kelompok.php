<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $table = 'kelompok';
    protected $primaryKey = 'id_kelompok';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['judul','topik','nim','id_proyek_pbl','id_dosen'];

    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class, 'nim', 'nim'); }
    public function proyek()    { return $this->belongsTo(ProyekPbl::class, 'id_proyek_pbl', 'id_proyek_pbl'); }
    public function dosen()     { return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen'); }
}
