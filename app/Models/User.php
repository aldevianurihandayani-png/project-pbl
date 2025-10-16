<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
<<<<<<< HEAD
     * Kolom yang boleh diisi secara mass-assignment.
=======
     * Kolom yang boleh diisi secara mass-assignment
>>>>>>> 7e4c8f16039fdbc37547803aea3ddc7c4610d0c4
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
<<<<<<< HEAD
        'foto',
=======
>>>>>>> 7e4c8f16039fdbc37547803aea3ddc7c4610d0c4
        'email_verified_at',
    ];

    /**
<<<<<<< HEAD
     * Kolom yang disembunyikan saat model diubah ke array / JSON.
=======
     * Kolom yang disembunyikan saat model diubah ke array / JSON
>>>>>>> 7e4c8f16039fdbc37547803aea3ddc7c4610d0c4
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
<<<<<<< HEAD
     * Tipe data casting otomatis.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Kalau pakai Laravel 10+ bisa pakai auto hash password:
        // 'password' => 'hashed',
=======
     * Tipe data casting otomatis
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Laravel 10+ bisa pakai 'password' => 'hashed' agar auto hash
>>>>>>> 7e4c8f16039fdbc37547803aea3ddc7c4610d0c4
    ];
}
