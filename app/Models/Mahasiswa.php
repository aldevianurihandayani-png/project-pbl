<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{


    use HasFactory;
    
    public function kelompok(){ return $this->belongsTo(Kelompok::class); }
    public function logbook(){ return $this->hasMany(Logbook::class); }
    public function laporan_penilaian(){ return $this->hasMany(laporan_penilaian::class); }

    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'no_hp',
    ];


    public function getRouteKeyName()
    {
        return 'nim';
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'nim', 'nim');
    }

}
