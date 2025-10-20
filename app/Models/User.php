
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

     * Kolom yang boleh diisi secara mass-assignment

     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nidn',
        'prodi',
        'profile_photo_path',
        'role',
        'foto',
        'email_verified_at',
    ];



    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'nim');
    }

    /**
     * Kolom yang disembunyikan saat model diubah ke array / JSON
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

    protected $casts = [
        'email_verified_at' => 'datetime',
        // Laravel 10+ bisa pakai 'password' => 'hashed' agar auto hash
    ];


