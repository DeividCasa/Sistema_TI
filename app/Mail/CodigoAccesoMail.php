<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodigoAccesoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nombre,
        public string $codigo,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu código de acceso — Leo José',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.codigo-acceso',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
