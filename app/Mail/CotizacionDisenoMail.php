<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CotizacionDisenoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nombre,
        public string $nombreDiseno,
        public float $precio,
        public ?string $mensajeAdmin = null,
        public ?string $imagenPath = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Tu diseño '{$this->nombreDiseno}' ya tiene precio — Leo José",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cotizacion-disenio',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
