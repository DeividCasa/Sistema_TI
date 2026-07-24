<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EstadoPedidoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nombre,
        public string $codigo,
        public string $tipoPedido,
        public string $estadoLabel,
        public ?string $tiempoEstimado = null,
        public ?string $imagenPath = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Actualización de tu pedido {$this->codigo} — Leo José",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.estado-pedido',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
