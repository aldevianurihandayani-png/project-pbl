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

    protected $table = 'users';
    protected $primaryKey = 'id';

    // ✅ FIX MINIMAL: karena tabel users punya created_at & updated_at (migration timestamps())
    public $timestamps = true;

    // ✅ opsional tapi aman (tidak mengubah fitur lain)
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'nama', 'name',
        'email',
        'password',

        // ✔ role final setelah disetujui admin
        'role',

        // ✔ data tambahan
        'nim',
        'nidn',
        'prodi',
        'profile_photo_path',
        'foto',
        'email_verified_at',
        'avatar_url',

        // ============================
        // ✔ Tambahan baru untuk sistem approval
        // ============================
        'status',          // pending | active | rejected
        'requested_role',  // role yang diminta user saat register
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',

        // opsional, tetapi rapi — cast jadi string
        'status'            => 'string',
        'requested_role'    => 'string',
    ];

    protected $appends = ['avatar_url_computed', 'avatar_url'];

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'nim');
    }

    // name -> baca dari kolom 'nama'
    public function getNameAttribute()
    {
        return $this->attributes['nama'] ?? ($this->attributes['name'] ?? null);
    }

    // set name -> tulis ke kolom 'nama'
    public function setNameAttribute($value): void
    {
        $this->attributes['nama'] = $value;
    }

    public function getAvatarUrlAttribute()
    {
        return $this->attributes['avatar_url'] ?? null;
    }

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
