<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $table = 'milestone';   // penting jika tabel bukan 'milestones'
    protected $fillable = ['minggu','kegiatan','deadline','status','approved','approved_at'];
    public $timestamps = true;        // atau false jika tabelmu tidak pakai created_at/updated_at
}

