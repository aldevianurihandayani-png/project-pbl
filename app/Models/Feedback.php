<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    // Nama tabel di database
    protected $table = 'feedback';

    // Primary key bukan "id" tapi "id_feedback"
    protected $primaryKey = 'id_feedback';

    // Karena di tabel TIDAK ada created_at / updated_at
    public $timestamps = false;

    // Kolom-kolom yang boleh di-isi massal
    protected $fillable = [
        'id_user',
        'id_notifikasi',
        'isi',
        'status',
        'tanggal',
    ];
}
