<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PendingRegistrations extends Model
{
    use Notifiable;

    protected $table = 'pending_registrations';
    protected $fillable = [
        'name','email','password','role','nim','prodi',
        'verification_sent_at','verification_expires_at'
    ];

    // biar bisa kirim notifikasi email ke alamat ini
    public function routeNotificationForMail(): string
    {
        return $this->email;
    }
}
