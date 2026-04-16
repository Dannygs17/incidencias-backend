<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarPasswordPin extends Mailable
{
    use Queueable, SerializesModels;

    public $pin;

    public function __construct($pin)
    {
        $this->pin = $pin;
    }

    public function build()
    {
        return $this->subject('Tu código de recuperación de contraseña')
                    ->view('emails.recuperar_pin');
    }
}