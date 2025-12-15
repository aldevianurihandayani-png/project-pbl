<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailCustom;

// ✅ TAMBAHAN (relasi notifikasi)
use App\Models\Notification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

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

        'status',
        'requested_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',

        'status'            => 'string',
        'requested_role'    => 'string',
    ];

    protected $appends = ['avatar_url_computed', 'avatar_url'];

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'nim');
    }

    // ✅ TAMBAHAN: Relasi notifikasi untuk lonceng (pivot notification_user)
    public function notifications()
    {
        return $this->belongsToMany(
            Notification::class,
            'notification_user',
            'user_id',
            'notification_id'
        )
        ->withPivot(['is_read', 'read_at'])
        ->withTimestamps();
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
