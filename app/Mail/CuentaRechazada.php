<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CuentaRechazada extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $motivo;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $motivo)
    {
        $this->user = $user;
        $this->motivo = $motivo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificación: Cuenta Rechazada - Incidencias Smart',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cuenta_rechazada', // Esta es la vista que crearemos ahora
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}