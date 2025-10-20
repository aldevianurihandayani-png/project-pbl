<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $nama;

    public function __construct(string $nama)
    {
        $this->nama = $nama;
    }

    public function build()
    {
        return $this->subject('Registrasi Berhasil - SIMAP Politala')
                    ->view('emails.register-success');
    }
}
