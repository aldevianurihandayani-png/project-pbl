<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LogbookSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $logbook;

    public function __construct($user, $logbook)
    {
        $this->user = $user;
        $this->logbook = $logbook;
    }

    public function build()
    {
        return $this->subject('Notifikasi Logbook Baru')
                    ->view('emails.logbook_submitted')
                    ->with([
                        'userName'  => $this->user->name,
                        'tanggal'   => $this->logbook->tanggal,
                        'minggu'    => $this->logbook->minggu,
                        'aktivitas' => $this->logbook->aktivitas,
                    ]);
    }
}
