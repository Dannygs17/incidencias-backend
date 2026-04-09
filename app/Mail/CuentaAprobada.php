<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CuentaAprobada extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;

    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu cuenta ha sido aprobada! - Atención Ciudadana',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cuenta_aprobada', // Esta vista la crearemos en el paso 3
        );
    }
}