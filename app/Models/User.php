<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Jika tabel users tidak memiliki created_at & updated_at
    public $timestamps = false;

    /**
     * Kolom yang bisa diisi mass assignment.
     */
    protected $fillable = [
        'nama',
        'email',
        'role',
        'password',
        'nidn',
        'prodi',
        'profile_photo_path',
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

    /**
     * Atribut tambahan saat model di-serialize.
     */
    protected $appends = ['avatar_url'];

    /**
     * Accessor: URL foto profil atau fallback.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        // fallback: null atau bisa diganti path default avatar
        return null;
    }
}
