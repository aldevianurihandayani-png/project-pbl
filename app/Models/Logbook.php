<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $table = 'logbooks';

    // Kolom yang boleh diisi
    protected $fillable = [
        'tanggal',
        'minggu',
        'aktivitas',
        'keterangan',
        'foto',
        'user_id',
    ];

    // Konversi otomatis tipe data
    protected $casts = [
        'tanggal' => 'date',
    ];

    // Kolom yang tidak ingin ditampilkan di JSON (opsional)
    protected $hidden = [
        'foto',
    ];
}
