<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
     
    protected $fillable = ['nim','nama','kelas','id_kelompok'];

    public function user(){ return $this->belongsTo(User::class); }
    public function kelompok(){ return $this->belongsTo(Kelompok::class); }
    public function logbook(){ return $this->hasMany(Logbook::class); }
    public function laporan_penilaian(){ return $this->hasMany(laporan_penilaian::class); }
}
