<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    // Jika nama tabel kamu adalah "feedback"
    protected $table = 'feedback';

    // Jika tabel TIDAK punya created_at dan updated_at
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'category',
        'message',
        'status',
    ];
}
