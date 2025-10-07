<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    // ...
    public function getRouteKeyName()
    {
        return 'nim';
    }
}
