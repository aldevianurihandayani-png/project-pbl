<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara mass-assignment
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
    ];

    /**
     * Kolom yang disembunyikan saat model diubah ke array / JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data casting otomatis
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Laravel 10+ bisa pakai 'password' => 'hashed' agar auto hash
    ];
}
