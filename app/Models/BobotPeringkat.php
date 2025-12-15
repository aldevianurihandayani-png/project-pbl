<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BobotPeringkat extends Model
{
    protected $table = 'bobot_peringkats';

    protected $fillable = [
        'jenis',
        'mhs_keaktifan', 'mhs_nilai_kelompok', 'mhs_nilai_dosen',
        'klp_review_uts', 'klp_review_uas',
    ];
}
