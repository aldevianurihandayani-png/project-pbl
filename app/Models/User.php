<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Jika tabel users tidak punya created_at & updated_at
    public $timestamps = false;

    /**
     * Kolom yang boleh diisi mass assignment
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',                 // tambahkan jika dipakai pada relasi
        'nidn',
        'prodi',
        'profile_photo_path',
        'foto',
        'email_verified_at',
    ];

    /**
     * Kolom yang disembunyikan saat diserialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed', // otomatis bcrypt
    ];

    /**
     * Atribut tambahan saat model di-serialize
     */
    protected $appends = ['avatar_url'];

    /**
     * Relasi ke Mahasiswa (1:1 via kolom nim)
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'nim');
    }

    /**
     * Accessor: URL foto profil atau null (fallback)
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        return null;
    }
}
