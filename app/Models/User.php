<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // tabel users tidak punya created_at & updated_at
    public $timestamps = false;

    /**
     * Kolom yang bisa diisi mass assignment.
     */
    protected $fillable = [
        'nama',
        'email',
        'role',
        'password',
    ];

    /**
     * Kolom yang harus disembunyikan ketika serialize.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // otomatis bcrypt
        ];
    }
}
