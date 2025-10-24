<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $table = 'milestone';
    protected $primaryKey = 'id_milestone';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'deskripsi',
        'tanggal',
        'status',
        'id_proyek_pbl',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status'  => 'boolean',
    ];

    public function proyek()
    {
        return $this->belongsTo(ProyekPbl::class, 'id_proyek_pbl', 'id_proyek_pbl');
    }
}
