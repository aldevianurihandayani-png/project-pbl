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

    // ✔ Pakai default Laravel: tabel "users", PK "id"
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama', 'name',
        'email',
        'password',
        'role',
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
        'password'          => 'hashed',
    ];

    protected $appends = ['avatar_url_computed', 'avatar_url'];

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'nim');
    }

    // name -> baca dari kolom 'nama' (fallback ke 'name')
    public function getNameAttribute()
    {
        return $this->attributes['nama'] ?? ($this->attributes['name'] ?? null);
    }

    // set name -> tulis ke kolom 'nama'
    public function setNameAttribute($value): void
    {
        $this->attributes['nama'] = $value;
    }

    // avatar_url asli di DB
    public function getAvatarUrlAttribute()
    {
        return $this->attributes['avatar_url'] ?? null;
    }

    // avatar_url_computed: fallback ke file upload kalau perlu
    public function getAvatarUrlComputedAttribute()
    {
        if (!empty($this->attributes['avatar_url'])) {
            return $this->attributes['avatar_url'];
        }

        if (!empty($this->profile_photo_path)) {
            return asset('storage/' . ltrim($this->profile_photo_path, '/'));
        }

        return null;
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustom());
    }
}
