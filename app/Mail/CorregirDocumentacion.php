<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorregirDocumentacion extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $motivo;
    public $camposHumanos;

    public function __construct($user, $motivo, $camposTecnicos)
    {
        $this->user = $user;
        $this->motivo = $motivo;

        // Diccionario para traducir de la BD a palabras para el ciudadano
        $diccionario = [
            'curp' => 'Clave CURP',
            'ine_frente' => 'Fotografía de INE (Frente)',
            'ine_reverso' => 'Fotografía de INE (Reverso)'
        ];

        // Traducimos el arreglo que nos llega del controlador
        $this->camposHumanos = array_map(function($campo) use ($diccionario) {
            return $diccionario[$campo] ?? $campo;
        }, $camposTecnicos);
    }

    public function build()
    {
        return $this->subject('Acción Requerida: Actualiza tus documentos')
                    ->view('emails.corregir_documentacion');
    }
}