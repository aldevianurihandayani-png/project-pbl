<?php

namespace App\Mail;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Notification $notif) {}

    public function build()
    {
        return $this->subject('Notifikasi: ' . $this->notif->judul)
            ->view('emails.notifikasi');
    }
}
