<?php

/**
 * Email para enviar factura de cuota en PDF
 * 
 * @package SiempreColgados
 * @subpackage Mail
 */

namespace App\Mail;

use App\Models\Cuota;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class FacturaCuotaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Cuota $cuota) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Factura de cuota #' . str_pad($this->cuota->id, 6, '0', STR_PAD_LEFT) . ' - SiempreColgados',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.factura_cuota',
            with: [
                'cuota' => $this->cuota,
                'cliente' => $this->cuota->cliente,
            ],
        );
    }

    public function attachments(): array
    {
        // Adjuntar el PDF de la factura
        if ($this->cuota->fichero_factura && Storage::disk('public')->exists($this->cuota->fichero_factura)) {
            return [
                Attachment::fromStorageDisk('public', $this->cuota->fichero_factura)
                    ->as('factura-' . $this->cuota->id . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }
        return [];
    }
}
