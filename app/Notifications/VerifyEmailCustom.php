<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailCustom extends BaseVerifyEmail
{
   
    public function toMail($notifiable)
    {
        // URL verifikasi
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Akun SIMAP Politala')
            ->greeting('Halo, ' . $notifiable->name . ' 👋')
            ->line('Terima kasih telah mendaftar di Sistem Informasi Manajemen PBL (SIMAP) Politala.')
            ->line('Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini untuk memverifikasi email:')
            ->action('Verifikasi Sekarang', $verificationUrl)
            ->line('Link ini akan kadaluarsa dalam 60 menit.')
            ->line('Jika kamu tidak mendaftar di SIMAP Politala, abaikan email ini.')
            ->salutation('Salam hangat, Tim SIMAP Politala ');
    }

    
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
