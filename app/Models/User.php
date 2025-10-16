<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'foto',
        'email_verified_at',
    ];

    /**
     * Kolom yang disembunyikan saat model diubah ke array / JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data casting otomatis.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Kalau pakai Laravel 10+ bisa pakai auto hash password:
        // 'password' => 'hashed',
    ];
}
