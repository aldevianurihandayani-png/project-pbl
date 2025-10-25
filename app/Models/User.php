<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailCustom;

class User extends Authenticatable implements MustVerifyEmail 

{
    use HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * Kolom yang boleh diisi mass assignment.
     * Sertakan 'nama' (kolom DB) dan 'name' (alias) agar keduanya aman.
     */
    protected $fillable = [
        'nama', 'name',
        'email',
        'password',
        'role',
        'nim',
        'nim',                 
        'nidn',
        'prodi',
        'profile_photo_path',
        'foto',
        'email_verified_at',
        'avatar_url',
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

   
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed', // otomatis bcrypt (Laravel 10+)
    ];


    /**
     * Atribut tambahan saat model di-serialize
     */
    protected $appends = ['avatar_url_computed','avatar_url'];

    
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'nim');
    }


    /**
     * Accessor/Mutator untuk memetakan atribut 'name' ke kolom DB 'nama'.
     * - $user->name akan membaca kolom 'nama'
     * - set $user->name = '...' akan menulis ke kolom 'nama'
     */
    public function getNameAttribute()
    {
        return $this->attributes['nama'] ?? ($this->attributes['name'] ?? null);
    }


    public function getAvatarUrlAttribute()
    {
        return $this->attributes['nama'] ?? null;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nama'] = $value;
    }

    /**
     * Accessor: URL foto profil komputasi (fallback).
     * Tidak menimpa kolom 'avatar_url' di DB; disajikan sebagai 'avatar_url_computed'.
     */
    public function getAvatarUrlComputedAttribute()
    {
        // Prioritaskan kolom DB 'avatar_url' kalau ada
        if (!empty($this->attributes['avatar_url'])) {
            return $this->attributes['avatar_url'];
        }

        // Fallback ke file yang diunggah
        if (!empty($this->profile_photo_path)) {
            return asset('storage/' . ltrim($this->profile_photo_path, '/'));
        }

        return null;
    }
}
